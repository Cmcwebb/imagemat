<!DOCTYPE HTML>
<?php

$gPHPscript = __FILE__;
$dir = dirname(__FILE__);
require_once($dir . '/../include/boilerplate.php');
require_once($dir . '/../include/util.php');

if (mustlogon()) {
  return;
}

$annotation_id = getparameter('annotation_id');
if (!isset($annotation_id)) {
  header('Location: ' . dirname($_SERVER['REQUEST_URI']) . '/search.php');
  return;
}
$onRight = getparameter('onRight');
if (!isset($onRight)) {
  $onRight = 1;
}
$next_id   = getparameter('next_id');
if ($next_id == -3) {
  header('Location: ' . dirname($_SERVER['REQUEST_URI']) . '/listsetAnnotations.php?annotation_id=' . $annotation_id . '&href=view1.php' . urlencode('?onRight=' . $onRight . '&') );
  return;
}

$version = getparameter('version');
if (!isset($version)) {
  $archive = 'N';
} else {
  $archive = getparameter('archive');
  if (!isset($archive)) {
	$archive = 'Y';
} }

htmlHeader('View annotation');

require_once($dir . '/../include/alert.php');
require_once($dir . '/../include/db.php');
require_once($dir . '/../include/urls.php');
require_once($dir . '/../include/simple_view.php');
require_once($dir . '/../include/annotations.php');

srcStylesheet(
  '../css/style.css',
  '../css/tabs.css'
);
srcJavascript(
  '../js/util.js',
  '../js/enterkey.js',
  '../js/fullwidth.js',
  '../js/edit.js',
  '../js/annotate.js',
  '../js/images.js'
);
enterJavascript();
?>

function add_next(next_id)
{
  var form   = document.getElementById("form");

  addhidden(form, 'next_id', '' + next_id);
  form.submit();
}

function do_next()
{
  add_next(-1);
}

function do_prev()
{
  add_next(-2);
}

function do_edit()
{
  add_next(-3);
}

function clickEdit()
{
  var form = document.getElementById('form');
  
  form.action = 'annotate.php';
  form.target = '_top';
  form.submit();
}

function loaded()
{
<?php
  if ($onRight == 1) {
	echo '
  showImages();';
  }
?>
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

<?php
exitJavascript();
echo '
</head>
<body class="frame" onload="loaded()">
<div>
';

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
  $archive       = 'N';
}

if (!DBconnect()) {
  goto done;
}

showSimpleView($annotation_id, $version, $archive);
echo '
<p>
<form id=form method="post" action="view1.php">';
    hidden('annotation_id');
    hidden('version');
    hidden('archive');
    hidden('onRight');

echo '
<input type="button" value="Edit" onclick="clickEdit();"/>';
$setAnnotations = getSetAnnotations();
if ($setAnnotations != null) {
  $size = count($setAnnotations);
  if ($size > 0) {
    if ($annotation_id != $setAnnotations[$size-1]) {
      echo '
<input type="button" value="Next" onclick="add_next(-1)" />';
    }
    if ($annotation_id != $setAnnotations[0]) {
      echo '
<input type="button" value="Prev" onclick="add_next(-2)" />';
    }
    echo '
<input type="button" value="Goto" onclick="add_next(-3)" />';
  }
}
/*
echo '
<input type="button" id="fullWidthButton" style="display:none" value="Full Frame" onclick="toggle_frames()" />
</form>';
*/
if ($onRight != 0) {
  if (!build_image_data($annotation_id, $version, $archive, false, null, null, null)) {
    goto close;
  }
  enterJavascript();
  echo '
top.image_data.readonly = true;';
  exitJavascript();
  echo '
<form id="load_left" name="load_left" method="post" target="imageFrame" action="images.php">
<input type=hidden name=loaded value=y />
<input type=hidden name=readonly value=y />
</form>
<br class=clearfloat />';
}
close:
DBclose();
done:
bodyFooter();
?>
</div>
</body>
</html>
