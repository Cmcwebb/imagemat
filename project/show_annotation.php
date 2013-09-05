<!DOCTYPE HTML>
<?php

/* 
 * http://www.elated.com/articles/javascript-tabs
 */

$gPHPscript = __FILE__;
$dir = dirname(__FILE__);
require_once($dir . '/../include/boilerplate.php');
require_once($dir . '/../include/db.php');
require_once($dir . '/../include/annotations.php');
require_once($dir . '/../include/urls.php');

if (mustlogon()) {
  /* This can happen if the frame is left idle a long time */
  return;
}

htmlHeader('Annotation');

$annotation_id = getparameter('annotation_id');

srcStylesheet(
  '../css/style.css',
  '../css/alert.css',
  '../css/tooltip.css',
  '../css/tabs.css',
  '../css/zoom.css'
);
srcJavascript(
  '../js/util.js',
  '../js/base64.js',
  '../js/tooltip.js',
  '../js/tabs.js',
  '../js/alert.js',
  '../js/images.js',
  '../js/fullwidth.js',
  '../js/tabs.js',
  '../js/zoom.js'
);
?>
</head>
<body onload="show_page_loaded()" id="background">
<?php

if (!DBconnect()) {
  goto done;
}
$row = read_annotation($annotation_id, null,'N');
if (!isset($row) || !$row) {
  goto close;
}

$url0    = '../annotate/simple_view.php?annotation_id=' . $annotation_id;
$version = null;

$archive = $row['archive'];
if ($archive == 'Y') {
  $version = $row['version'];
  $url0   .= '&version=' . $version;
} 

if (!build_image_data($annotation_id, $version, $archive, false, $url0, null, null)) {
  goto close;
}
  
enterJavascript();
?>

function show_page_loaded()
{
  label_frame_button();
  setupZoomButton();
  add_tabs();
  showFirstTab();
}
<?php

exitJavascript();
?>
<div id='rootdiv'>
<div id='imageControls'>
<form>
<input id=show type="button" value="URL" onclick="show_url()" style="display:none;" />
<input type="button" id="fullWidthButton" style="display:none" value="Full Frame" onclick="clickResizeImage()" />
</form>
<?php emit_zoom(); ?>
</div>
<div class='imageArea'>
<ul id="tabs">
</ul>
</div>
</div>
<?php
close:
DBclose();
done:
bodyFooterFilename();
?>
</body>
</html>
