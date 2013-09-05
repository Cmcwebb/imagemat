<!DOCTYPE HTML>
<?php

$gPHPscript = __FILE__;
$dir = dirname(__FILE__);
require_once($dir . '/../include/boilerplate.php');
require_once($dir . '/../include/db.php');
require_once($dir . '/../include/folders.php');
require_once($dir . '/../include/folders/insert.php');
require_once($dir . '/../include/date.php');
require_once($dir . '/../include/urls.php');
require_once($dir . '/../include/alert.php');

$gSeen      = null;
$gFileCnt   = 0;
$gFolderCnt = 0;
$gImageCnt  = 0;
$gVerbose   = false;

function displayXMLerror($source, $error, $lines)
{
  echo '
<tr><td>',
	htmlspecialchars($source),
	'</td><td><font color=red>' ,
	htmlspecialchars($lines[$error->line - 1]),'
</font></td><td>';

  switch ($error->level) {
  case LIBXML_ERR_WARNING:
    echo 'Warning ';
    break;
  case LIBXML_ERR_ERROR:
    echo 'Error ';
	break;
  case LIBXML_ERR_FATAL:
    echo 'Fatal ';
    break;
  }
  echo '</td><td>', $error->code, ':', htmlspecialchars(trim($error->message)),'</td><td>', $error->line, ':', $error->column, '</td></tr>';

}

function import_xml($root, $source, $folder_id)
{
  global $gSeen, $gUserid, $gFileCnt, $gFolderCnt, $gImageCnt, $gVerbose;


  if (!isset($gSeen)) {
	$gSeen = Array();
  } else {
	if (isset($gSeen[$source])) {
		return 2;
  } }
  ++$gFileCnt;
  $gSeen[$source] = true;
  

  $ch = curl_init($source);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $xmlstr = curl_exec($ch);
  curl_close($ch);
  if (!$xmlstr) {
	echo '<tr></td>', htmlspecialchars($source), '</td><td><font color=red>Failed to read</font></td></tr>';
	return;
  }
  if ($gVerbose) {
    echo '<br/>&gt;&gt;', $source, '
<pre>
', htmlspecialchars($xmlstr),'
</pre>';
  }
  $xmlReader = new XMLReader;
  //libxml_use_internal_errors(true);
  $xmlReader->xml($xmlstr);

  $lines = null;
  $seen  = false;
  while ($xmlReader->read()) {

/*
    $errors = libxml_get_errors();

    foreach ($errors as $error) {
	  $seen = true;
      if (!isset($lines)) {
        $lines = explode("\n", $xmlstr);
      }
      displayXMLerror($source, $error, $lines);
    }
	if ($seen) {
	  libxml_clear_errors();
	  $seen = false;
	}
*/
	$warn = '';

    $about = '';
    if ($xmlReader->nodeType != XMLReader::ELEMENT) {
	  continue;
	}
	if ($xmlReader->attributeCount < 1) {
	  continue;
    }
	$about = $xmlReader->getAttributeNs('about','http://www.w3.org/1999/02/22-rdf-syntax-ns#');
	if ($about == '' || $about[0] == 'u' /* urn: */) {
	  continue;
	}
    $namespace = $xmlReader->namespaceURI;
	$localName = $xmlReader->localName;

	if (($namespace == 'http://dms.stanford.edu/ns/' && $localName == 'Canvas')) {
	  continue;
    }
	if (($namespace == 'http://www.w3.org/1999/02/22-rdf-syntax-ns#' && $localName    == 'Description') ||
        ($namespace == 'http://dms.stanford.edu/ns/'                 && $localName    == 'ImageAnnotationList')
       ) {
      if (substr($about, 0, strlen($root)) != $root) {
		$warn = 'Outside root';
      } else {
	    $path = explode('/', $about);
		for ($i = count($path); ; ) {
		  if (--$i < 0) {
			$last = '';
			break;
		  }
		  $last = trim($path[$i]);
		  if ($last != '') {
			break;
		} }
        $subfolder_id = insert_folder($folder_id, $last, 'Shared canvas import of ' . $about);
	    if ($subfolder_id < 0) {
		  return -1;
	    }
	    if (!$subfolder_id) {
		  $warn = 'Can\'t create folder';
	    } else {
	      ++$gFolderCnt;
	      $ret = import_xml($root, $about, $subfolder_id);
	      if ($ret <= 0) {
		    return $ret;
	      }
	      continue;
    } } }
    if (($namespace == 'http://purl.org/dc/dcmitype/' && $localName == 'Image') ||
        ($namespace == 'http://dms.stanford.edu/ns/' && $localName == 'ImageAnnotation')
       ) {
      $url_id =  get_url_id($about, true);
	  if (!$url_id) {
		$warn = 'Can\'t create url';
	  } else {
        $query =
'insert ignore into foldersimages(folder_id, url_id, creator_user_id, created)
values (' . DBnumberC($folder_id) . DbnumberC($url_id) . DBstringC($gUserid) . 'utc_timestamp())';

        $ret = DBquery($query);
	    ++$gImageCnt;
	    continue;
    } }

	echo '<tr><td>', 
		htmlspecialchars($source), '</td><td>',
		htmlspecialchars($xmlReader->namespaceURI), '</td><td>',
 		htmlspecialchars($xmlReader->localName), '</td><td>',
        htmlspecialchars($about), '</td><td>',
	    htmlspecialchars($warn), '</td></tr>';
  } 

  $xmlReader->close();
  if ($gVerbose) {
    echo '<br/>&lt;&lt;', $source;
  }
  return 1;
}

if (mustlogon()) {
  /* This can happen if the frame is left idle a long time */
  return;
}

htmlHeader('Import Data from Shared Canvas');

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
			     body:'A folder is required'
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
  if (!$folder_id) {
    javascriptAlert('Folder not found', 'warn.png',
      'Folder ' . htmlspecialchars($folder) . ' does not exist', null);
    goto start;
  }
  $target = getpost('target');
  if (!isset($target)) {
    javascriptAlert('Target not specified', 'warn.png',
      'Choice the repository you wish to import', null);
    goto start;
  }
  switch ($target) {
  case 1:
    $manifest = "http://mat.uwaterloo.ca/m3/index.php";
    $root     = "http://mat.uwaterloo.ca/m3/";
    break;
  case 2:
	$manifest = "http://rosetest.library.jhu.edu/m3";
	$root     = "http://rosetest.library.jhu.edu/m3/";
	break;
  case 3:
	$manifest = "http://www.shared-canvas.org/impl/demo1/res/Manifest.xml";
	$root     = "http://www.shared-canvas.org/impl/demo1/";
	break;
  }
  $verbose = getpost('verbose');
  if (isset($verbose)) {
	$gVerbose = true;
  }
  $timenow =  clientstimenow();
  $subfolder_id = insert_folder($folder_id, 'Import ' . $timenow, 'Import of shared canvas from ' . $manifest . ' at ' . $timenow);
  if ($subfolder_id < 0) {
	goto close;
  }
  if (!$subfolder_id) {
	echo '<br>Can\'t create container for import';
    goto close;
  }
  ++$gFolderCnt;
  echo '
<h3>Importing from ', htmlspecialchars($manifest), '</h3>
<p>
<h4>Ignored references</h4>
<p>
<div style="background:white">
<table rules=all>
<thead>
<tr><th>Source</th><th>Namespace</th><th>LocalName</th><th>About</th><th>Warning</th></tr>
</thead>
<tbody>';

  import_xml($root, $manifest, $subfolder_id);

  echo '
</tbody>
</table>
<p>
Imported ', $gImageCnt, ' images from ', $gFileCnt, ' xml files into ', $gFolderCnt, ' created folders
<div>';

  goto close;
}

start:
?>

<h3>Import images and annotations from Shared Canvas Manifest</h3>

<p>
If the repository you wish to import from is not in this list please contact
<?php echo  $gAdminEmail; ?>
<p>
<form id=form action=import.php method=post onsubmit="return check();" >
<table>
<tr>
<td align=right>Repository:</td>
<td>
<select name=target >
<option value=1>Margot Repository</option>
<option value=2>John Hopkins Repository</option>
<option value=3>Shared Canvas demonstration site</option>
</select>
</td>
</tr>
<tr>
<td align=right>Folder:</td>
<td><input id=folder name=folder type=text cols=80 value='~/import'></input></td>
</tr>
<tr>
<td align=right>Verbose:</td>
<td><input type=checkbox name="verbose"/></td>
</tr>
<tr>
<td align=right>
<input type=submit value="Import">
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
