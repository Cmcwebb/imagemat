<!DOCTYPE HTML>
<?php
$gPHPscript = __FILE__;
$dir = dirname(__FILE__);
require_once($dir . '/../include/boilerplate.php');
require_once($dir . '/../include/util.php');
require_once($dir . '/../include/db.php');
require_once($dir . '/../include/folders.php');
require_once($dir . '/../include/alert.php');
require_once($dir . '/../include/urls.php');

function emit_image($url, $b)
{
  return
$b . '<ore:aggregates>' .
$b . '  <foaf:image>'.htmlspecialchars($url).'</foaf:image>' .
$b . '</ore:aggregates>';
}

function emit_document($url, $b)
{
  return
$b . '<ore:aggregates>' .
$b . '  <foaf:document>'.htmlspecialchars($url).'</foaf:document>' .
$b . '</ore:aggregates>';
}

function emit_citation($citation_id, $b)
{
  return
$b . '<sc:ContentAnnotation>' .
$b . '  <dcterms:identifier rdf:parseType="Resource">' .
$b . '    <foaf:name>' .  $citation_id . '</foaf:name>' .
$b . '  </dcterms:identifier>' .
$b . '</sc:ContentAnnotation>';
}

function emit_annotation($row, $b)
{
  $annotation_id = $row['annotation_id'];
  $b1 = $b  . '  ';
  $b2 = $b1 . '  ';
  $msg = 
$b . '<sc:ContentAnnotation>' .
$b1 . '<dcterms:identifier rdf:parseType="Resource">' .
$b1 . '  <foaf:name>' . $annotation_id . '</foaf:name>' .
$b1 . '</dcterms:identifier>';

  $title = $row['title'];
  if (isset($title)) {
    $msg .=
$b1 . '<dcterms:title rdf:parseType="Resource">' .
$b1 . '  <foaf:name>'.htmlspecialchars($title).'</foaf:name>' .
$b1 . '</dcterms:title>';
  }

  $description = $row['content'];
  if (isset($description)) {
    $msg .= 
$b1 . '<dcterms:description rdf:parseType="Resource">' . 
$b1 . '  <foaf:name>'.htmlspecialchars($description).'</foaf:name>' .
$b1 . '</dcterms:description>';
  }

  $query =
'select markup_id, image_url_id, html_url_id, citation_id, title, description
 from annotationsofurls
where annotation_id = ' . DBnumber($annotation_id);

  $ret = DBquery($query);
  if (!$ret) {
    return null;
  }
  while ($row = DBfetch($ret)) {
    $msg .=
$b1 . '<oac:hasTarget>';

    $title = $row['title'];
    if (isset($title)) {
      $msg .=
$b2 . '<dcterms:title rdf:parseType="Resource">' .
$b2 . '  <foaf:name>'.htmlspecialchars($title).'</foaf:name>' .
$b2 . '</dcterms:title>';
    }

    $description = $row['description'];
    if (isset($description)) {
      $msg .= 
$b2 . '<dcterms:description rdf:parseType="Resource">' . 
$b2 . '  <foaf:name>'.htmlspecialchars($description).'</foaf:name>' .
$b2 . '</dcterms:description>';
    }

    $url_id = $row['image_url_id'];
    if (isset($url_id)) {
      $url = get_url_string($url_id);
	  if ($url) {
	    $msg .= emit_image($url, $b2);
	  }
    }
    $url_id = $row['html_url_id'];
    if (isset($url_id)) {
      $url = get_url_string($url_id);
	  if ($url) {
	    $msg .= emit_document($url, $b2);
	  }
    }
    $citation_id = $row['citation_id'];
	if ($citation_id) {
	  $msg .= emit_citation($citation_id, $b2);
    }
    $msg .=
$b1 . '</oac:hasTarget>';
  }
  $msg .=
$b . '/<sc:ContentAnnotation>';
  return $msg;
}

function emit_folder($row, $b)
{
  $folder_id = $row['folder_id'];
  $b1  = $b . '  ';
  $msg =
$b  . '<re:Aggregation>' .
$b1 . '<dcterms:identifier rdf:parseType="Resource">' .
$b1 . '  <foaf:name>' .  $folder_id . '</foaf:name>' .
$b1 . '</dcterms:identifier>';

  $title = $row['name'];
  if (isset($title)) {
    $msg .=
$b1 . '<dcterms:title rdf:parseType="Resource">' .
$b1 . '  <foaf:name>'. htmlspecialchars($title).'</foaf:name>' .
$b1 . '</dcterms:title>';
  }

  $description = $row['description'];
  if (isset($description)) {
    $msg .= 
$b1 . '<dcterms:description rdf:parseType="Resource">' . 
$b1 . '  <foaf:name>'. htmlspecialchars($description).'</foaf:name>' .
$b1 . '</dcterms:description>';
  }

  $folder_id = DBnumber($folder_id);
  $query =
'select folder_id, name, description
  from folders
 where parent_folder_id = ' . $folder_id;

  $ret = DBquery($query);
  if (!$ret) {
	return null;
  }
  while ($row = DBfetch($ret)) {
    $ret1 = emit_folder($row, $b1);
	if (!$ret1) {
	  return $ret1;
    }
    $msg .= $ret1;
  }

  $query =
'select f.annotation_id, a.title, a.content
  from foldersannotations f, annotations a
 where f.folder_id = ' . $folder_id . '
   and f.annotation_id = a.annotation_id';

  $ret = DBquery($query);
  if (!$ret) {
	return null;
  }
  while ($row = DBfetch($ret)) {
    $ret1 = emit_annotation($row, $b1);
	if (!$ret1) {
	  return $ret1;
    }
    $msg .= $ret1;
  }

  $query =
'select u.url
  from foldersurls f, urls u
 where f.folder_id = ' . $folder_id . '
   and f.url_id    = u.url_id';

  $ret = DBquery($query);
  if (!$ret) {
	return null;
  }
  while ($row = DBfetch($ret)) {
    $ret1 = emit_document($row['url'], $b1);
	if (!$ret1) {
	  return $ret1;
    }
    $msg .= $ret1;
  }

  $query =
'select u.url 
  from foldersimages f, urls u
 where f.folder_id = ' . $folder_id . '
   and f.url_id    = u.url_id';

  $ret = DBquery($query);
  if (!$ret) {
	return null;
  }
  while ($row = DBfetch($ret)) {
    $ret1 = emit_image($row['url'], $b1);
	if (!$ret1) {
	  return $ret1;
    }
    $msg .= $ret1;
  }

  $msg .=
$b . '</re:Aggregation>';

  return $msg;
}

if (mustlogon()) {
  return;
}

htmlHeader('Export Data in Shared Canvas format');

srcStylesheet(
  '../css/style.css',
  '../css/alert.css'
);
srcJavascript(
  '../js/alert.js'
);
enterJavascript();
?>
function check()
{
  var folder = document.getElementById('folder');
  var value;

  value = trim(folder.value);
  if (value == '') {
    customAlert({title:'Missing folder',
				 icon:'error.png',
			     body:'The folder you wish to export must be specified'
				});
	return false;
  }
  return true;
}

<?php
exitJavascript();
echo '
</head>
<body>
';

bodyHeader();

$folder = getpost('folder');
if (isset($folder)) {
  if (!DBconnect()) {
    goto done;
  }
  $folder_id = find_folder_id(null, $folder, false);
  if ($folder_id) {
    $query = 
'select folder_id, name, description
  from folders
 where folder_id = ' . DBnumber($folder_id);

    $ret = DBquery($query);
    if (!$ret) {
	  goto close;
    }
    $row = DBfetch($ret);
    
	if (!$row) {
	  echo '<font color=red>Folder deleted</font>';
	  goto close;
	}

    $export = 
'<rdf:RDF
    xmlns:sc="http://www.shared-canvas.org/ns/"
    xmlns:cnt="http://wwww.w3.org/2011/content#"
    xmlns:dc="http://purl.org/dc/elements/1.1/"
    xmlns:dcterms="http://purl.org/dc/terms/"
    xmlns:dctypes="http://purl.org/dc/dcmitype/"
    xmlns:exif="http://www.w3.org/2003/12/exif/ns/">
    xmlns:oa="http://www.w3.org/ns/openannotation/core/"
    xmlns:oax="http://www.w3.org/ns/openannotation/extensions/"
    xmlns:ore="http://www.openarchives.org/ns/"
    xmlns:ore="http://www.openarchives.org/ore/terms/"
    xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
    xmlns:rdfs="http://www.w3.org/2001/01/01/rdf-schema#"
    xmlns:foaf="http://xmlns.com/foaf/0.1/"
    xmlns:dms="http://dms.stanford.edu/ns/"
    xmlns:dcmitypes="http://purl.org/dc/dcmitype/"
  <ore:ResourceMap rdf:about="http://mat.uwaterloo.ca/imagemat/canvas/export">
    <ore:describes>
      <sc:Manifest" rdf:about="' . htmlspecialchars($folder) . '>
        <ore:aggregates>';

    $export .= emit_folder($row,'
          ');

	if (!$export) {
	  echo '
<font color=red>Export failed</font>';
	  goto done;
    }
    $export .=
'        </ore:aggregates>
      </sc:Manifest>
    </ore:describes>
    <dcterms:creator rdf:parseType="Resource">
      <foaf:name>ImageMAT</foaf:name>
    </dcterms:creator>
  </ore:ResourceMap>
</rdf:RDF>
';
    echo '
<h3>Export Created</h3>
<p>
<form action=export1.php method=post>';
    hidden('export');
    echo '
<input type=submit value="Download" />
</form>';
    goto close;
 }
  javascriptAlert('Folder not found', 'warn.gif',
  'Folder ' . htmlspecialchars($folder) . ' does not exist', null);
}
?>

<h3>Export images and annotations as a Shared Canvas</h3>
<p>
<form id=form action=export.php method=post onsubmit="return check();" >
<table>
<tr>
<td align=right>Folder:</a>
<td><input id=folder name=folder type=text cols=80></text></td>
</tr>
<tr>
<td align=right>
<input type=submit value="Export">
</td>
</tr>
</table>
</form>
<?php
close:
DBclose();
done:
bodyFooter();
?>

</body>
</html>
