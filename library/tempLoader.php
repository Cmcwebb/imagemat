<!DOCTYPE HTML>
<?php

$gPHPscript = __FILE__;
$dir = dirname(__FILE__);
require_once($dir . '/../include/boilerplate.php');
require_once($dir . '/../include/util.php');

if (mustlogon()) {
  /* Should never see this page if not logged on but still */
  return;
}

function hasContent($title, $content)
{
  if (!isset($title) || !isset($content)) {
	if (isset($content)) {
      javascriptAlert(null, null, 'An annotation needs a title', 'Error');
    } else if (isset($title)) {
      javascriptAlert(null, null, 'An annotation needs content', 'Error');
    } else {
      javascriptAlert(null, null, 'An annotation needs a title and content', 'Error');
    }
	return false;
  }
  return true;
}

$similar    = getpost('similar');
if (isset($similar)) {
  // Show selected similar annotation
  $annotation_id = $similar;
  $version       = null;
  $archive       = 'N';
  $mode          = 'v';
} else {
  $annotation_id = getparameter('annotation_id');
  $version       = getparameter('version');
  $maxversion    = getpost('maxversion');
  $annotation_deleted = getpost('annotation_deleted');
  $archive       = getpost('archive');
  if (!isset($archive)) {
	$archive = 'N';
  }
  $mode          = getpost('mode');
  if (!isset($mode)) {
	$mode = '';
} }
if (!isset($annotation_id)) {
  header('Location: ' . dirname($_SERVER['REQUEST_URI']) . '/search.php');
  return;
}
$next_id   = getparameter('next_id');
if ($next_id == -3) {
  header('Location: ' . dirname($_SERVER['REQUEST_URI']) . '/listsetAnnotations.php?annotation_id=' . $annotation_id . '&href=update_annotation.php' . urlencode('?'));

  return;
}

if (isset($version)) {
  if ($version == '') {
    $version = null;
  }
} else {
  $version = null;
}
$language_code = getpost('language_code');

htmlHeader('Update annotation');

require_once($dir . '/../include/alert.php');
require_once($dir . '/../include/db.php');
require_once($dir . '/../include/urls.php');
require_once($dir . '/../include/simple_view.php');
require_once($dir . '/../include/annotations.php');
require_once($dir . '/../include/insert_markup.php');
require_once($dir . '/../include/template.php');
require_once($dir . '/../include/tags.php');
require_once($dir . '/../include/archive.php');

srcStylesheet(
  '../css/style.css',
  '../css/iframe.css'
);
srcJavascript(
  '../js/util.js',
  '../js/enterkey.js',
  '../js/fullwidth.js',
  '../js/edit.js',
  '../js/annotate.js',
  '../js/images.js',
  '../../tools/ckeditor/ckeditor.js'
);
enterJavascript();
?>

function isDirtyAnnotation()
{
  if (old_draft != new_draft) {
	return true;
  }
  var data = [];
  getFormData('form', data);

  var length = data.length;

  if (old.length != length) {
    alert('Unexpected form length change');
    return true;
  }
  for (var name in data) {
    switch (name) {
	case 'minor':
	case 'languages':
    case 'editor1':
		continue;
	}
	if (data[name] != old[name]) {
	  return true;
  } }
	
  var editor = CKEDITOR.instances['editor1'];

  if (editor) {
    // Bug in CKEditor adds \n at end of data
    var newdata = trim(editor.getData());
    var olddata = old['editor1'];
    if (newdata != olddata) {
      return true;
    }
  }
  return false;
}

function update(force)
{
  if (!checkEdit()) {
    return false;
  }
  var dirtyAnnotation = isDirtyAnnotation();
  var image_data1     = sendImageData();
  if (!dirtyAnnotation && !image_data1) {
    if (force) {
      customAlert( {title:'Information unchanged', icon:'warn.png', body:'The annotation has not been changed. Therefore there is no point in attempting to update it' } );
      return false;
  } }
  var form = document.getElementById('form');
  if (image_data1) {
	addhidden(form, 'dirtyImages', JSON.stringify(image_data1));
  }
  if (dirtyAnnotation) {
    addhidden(form, 'dirtyAnnotation','Y');
  }
  return true;
}

function reset_form()
{
  CKEDITOR.instances['editor1'].setData(old['editor1']);
}

function form_submit()
{
  var form   = document.getElementById("form");
  form.submit();
}

function add_next(next_id)
{
  var form   = document.getElementById("form");

  addhidden(form, 'next_id', '' + next_id);
  form.submit();
}

function clickNext()
{
  if (!update(false)) {
    return false;
  }
  add_next(-1);
}

function clickPrev()
{
  if (!update(false)) {
    return false;
  }
  add_next(-2);
}

function clickGoto()
{
  if (!update(false)) {
    return false;
  }
  add_next(-3);
}

function loaded()
{
  disableEnterKey('form');
  showImages();
  old = [];
  getFormData('form', old);
  label_frame_button();
}

function version_change(select)
{
  if (select.selectedIndex < 0) {
    alert('No version selected');
  } else {
    var form   = document.getElementById('form');
    var option = select.options[select.selectedIndex];
    var mode   = document.getElementById("mode");
    addhidden(form, 'archive', ((option.value == '') ? 'N' : 'Y'));
    if (mode) {
	  mode.value = 'v';
    }
    /* We will start re-entry */
    form.submit();
} }

// Stupid function used to avoid two commit buttons

<?php
exitJavascript();
echo '
</head>
<body onload="loaded()" >
';

if (!DBconnect()) {
  goto done;
}

$sql_annotation_id = DBnumber($annotation_id);
/*
if ($mode == 'x') {
    $query = 
'update annotations
   set annotation_deleted = 1
 where annotation_id      = ' . $sql_annotation_id;

    $ret = DBquery($query);
    if (!$ret) {
	  goto close;
    }
    echo '<h3>Annotation ', htmlspecialchars($annotation_id), ' deleted</h3>';
    $mode = '';
	goto proceed;
}
*/

$template_code  = getpost('template_code');
$template       = getpost('template');
if (!isset($template)) {
  $template = array();
}
$title         = getpost('title');
$minor          = getpost('minor');
$tags           = getpost('tags');
$content       = getpost('editor1');
$language_codes = getpost('language_codes');
$modifier_user_id = getpost('modifier_user_id');
$creator_user_id  = getpost('creator_user_id');
$refresh        = getpost('refresh');
$draft          = getpost('draft');

$ok             = true;

$dirtyAnnotation = getpost('dirtyAnnotation');
$dirtyImages     = getpost('dirtyImages');
if (isset($dirtyAnnotation) || isset($dirtyImages)) {
  // Do update
  if (!hasContent($title, $content)) {
	goto retry;
  }
  if (!DBatomic()) {
    goto close;
  }
  if (isset($minor) && $minor != 'Y') {
    $minor = null;
  }
/*  
*/ 
} 

if (isset($next_id)) {
  switch ($next_id) {
  case -1:	// Goto next
    $setAnnotations = getSetAnnotations();
    if (!isset($setAnnotations)) {
      $next_id = null;
    } else {
      $setAnnotations_lth = count($setAnnotations);
      for ($i = $setAnnotations_lth-1; 0 <= --$i; ) {
        if ($setAnnotations[$i] == $annotation_id) {
          break;
      } }
      $next_id = $setAnnotations[$i+1];
    }
    break;
  case -2:	// Goto prev
    $setAnnotations = getSetAnnotations();
    if (!isset($setAnnotations)) {
      $next_id = null;
    } else {
      $setAnnotations_lth = count($setAnnotations);
      for ($i = 1; $i < $setAnnotations_lth; ++$i) {
        if ($setAnnotations[$i] == $annotation_id) {
          break;
      } }
      $next_id = $setAnnotations[$i-1];
    }
    break;
  }
  $annotation_id = $next_id;
  $version       = null;
  $refresh       = null;
}

retry:
$sql_annotation_id = DBnumber($annotation_id);

switch ($mode) {
case 'v':
case 's':
  $refresh = null;
}

if (!isset($refresh)) {
  $row = read_extended_annotation($annotation_id, $version, $archive);
  if (!isset($row)) {
    goto close;
  }
  foreach ($row as $colname => $value) {
    $$colname = $value;
  }  

  // Passed in by bookmarklet
  $urls  = getpost('urls');
  $htmls = getpost('htmls');
  /*
  if (isset($urls)) {
	if (!isset($htmls)) {
    	$htmls = array();
  }	}
 */
  /* Avoiding needing to connect in images.php saves one connection */
  if (!build_image_data($annotation_id, $version, $archive, false, null, $urls, $htmls)) {    goto close;   }

} else if ($template_code != '' && $refresh != $template_code) {
  $template = read_template($annotation_id, $version, $archive, $template_code);
  if (!isset($template)) {
    goto close;
} }

enterJavascript();
echo '
var annotation_id = ', htmlspecialchars($annotation_id), ';
var version       = ', htmlspecialchars($version), ';
var archive       = "', htmlspecialchars($archive), '";
var language      = \'', htmlspecialchars($language_code), '\';
var old           = null;
var old_draft     = \'',(isset($annotation_deleted) ? 'X' : (isset($draft) ? $draft : 'N')), '\';
var new_draft     = old_draft;';

?>
function getAnnotationId()
{
  return annotation_id;
}
<?php

exitJavascript();

require_once($dir . '/../include/language.php');

if ($template_code != '') {
  $refresh = $template_code;
} else {
  $refresh = 'yes';
}


if (isset($_SESSION['imageMAT_language_code2'])) {
  $mylanguage = $_SESSION['imageMAT_language_code2'];
} else {
  $mylanguage = '';
}
?>
<form id=form name=form action="update_annotation.php" method="post">
<input id=mode type=hidden name=mode value=y />
<?php
hidden('annotation_id');
hidden('modifier_user_id');
hidden('creator_user_id');
hidden('archive');
hidden('version');
hidden('maxversion');
hidden('annotation_deleted');
hidden('refresh');
?>

</td>
</tr>
</table>
</form>

<?php
$tab = getparameter('tab');
echo '
<form id="load_left" name="load_left" method="post" target="imageFrame" action="../annotate/images.php">
<input type=hidden name=loaded value=y />';
hidden('tab');
echo '
</form>';

close:
DBcommit();
DBclose();
done:
bodyFooterFilename();
?>
</body>
</html>
