<!DOCTYPE HTML>
<?php

/* http://www.elated.com/articles/javascript-tabs
 * http://www.free-pictures-photos.com/landscapes/landscape-10.jpg
 */

$gPHPscript = __FILE__;
$dir = dirname(__FILE__);
require_once($dir . '/../include/boilerplate.php');
require_once($dir . '/../include/urls.php');

if (mustlogon()) {
  /* This can happen if the frame is left idle a long time */
  return;
}

htmlHeader('Current Images');

srcStylesheet(
  '../css/style.css',
  '../css/iframe.css',
  '../css/alert.css',
  '../css/tabs.css',
  '../css/tooltip.css',
  '../css/zoom.css' 
/*  '../markup/spinbtn/JQuerySpinBtn.css' */
);
srcJavascript(
  '../js/util.js',
  '../js/base64.js',  
  '../js/tooltip.js',
  '../js/tabs.js',
  '../js/edit.js',
  '../js/alert.js',
  '../js/images.js',
  '../js/fullwidth.js',
  '../../tools/ckeditor/ckeditor.js',
  '../js/zoom.js',
  '../js/ajax.js'
  
  /* '../../tools/jquery-1.7.2.js', For spin button */
  /* '../markup/spinbtn/JQuerySpinBtn.min.js' */
);
enterJavascript();

$loaded = getparameter('loaded');
$readonly= getparameter('readonly');
$tab    = getparameter('tab');
if (!isset($loaded)) {
  // Load from provided values given by bookmarklet
  $urls = getpost('urls');
  if (!isset($urls)) {
    $urls = array();
  }
  $htmls = getpost('htmls');
  if (!isset($htmls)) {
    $htmls = array();
  }
  
  $url  = getparameter('url');
  $html = getparameter('html');

  if (isset($url) || isset($html)) {
    $urls[]  = $url;
    $htmls[] = $html;
  }
  echo 'top.image_data = 
{ images:
  [';
  $cnt = 0;
  $connector = '';
  foreach ($urls as $index => $value) {
    echo $connector, '
    { tab:', $cnt;
    if (isset($value)) {
	  echo ',
      image_url:',json_encode($value);
    }
    if (isset($htmls[$index])) {
	  echo ',
      html_url:',json_encode($htmls[$index]);
    }
	echo '
    }';
	$connector = ',
';
  }
  echo '
  ]
};
';
}

if (isset($tab)) {
  $tab = (int) $tab;
  if ($tab > 1) {
    echo '
top.image_data.start_tab = ', $tab, ';
';
  }
}
exitJavascript();
?>
</head>
<body class="frame" onload="image_page_loaded()">
<?php

//print_r($_POST);

$markup = getparameter('markup');
if (isset($markup)) {
  // Return from svg-editor requested update to database
require_once($dir . '/../include/db.php');
require_once($dir . '/../include/alert.php');
require_once($dir . '/../include/insert_markup.php');
require_once($dir . '/../include/archive.php');
  $minor = getparameter('minor');
  $image = json_decode($markup);
  if (!isset($image)) {
	echo htmlspecialchars($markup);
    javascriptAlert('Can\'t decode markup', 'error.png', null, null);
  } else {
	if (!DBconnect()) {
	  goto done;
    }
	$ret = update_image($image, $minor);
    DBclose();
	if ($ret != 0) {
	  goto done;
} } }

?>
<div id="rootdiv" width="100%" height="100%">
<div id="imageControls">
<form>
<table class="iframetable">
<tbody class="iframetable">
<tr>
<td>
<?php
if (!isset($readonly)) {
?>
<!-- this table adds back in fields to add URL's directly to image pane -->
<table>
<tr>
<td colspan=2>Paste URLs into space below</td>
</tr>
<tr>
<td>Image URL:</td>
<td><input id=urlbox type="text" name="url" size="60" value="" /></td>
</tr>
<tr>
<td>HTML URL:</td>
<td><input id=htmlbox type="text" name="html" size="60" value="" /></td>
</tr>
</table>
<!--end table -->
<input type="button" value="Add Image" onclick="return doClickAddUrl()"/>
<input id=markup type="button" value="Markup" onclick="return markup_url()" style="display:none;" />
<input id=delete type="button" value="Delete" onclick="return clickDeleteUrl()" style="display:none;" />
<?php 
}
?>
<input id=show type="button" value="Info" onclick="clickInfo()" style="display:none;" />
<input id=tabLeftBtn type="button" value="<" onclick="tabLeft()" style="display:none;" />
<input id=tabRightBtn type="button" value=">" onclick="tabRight()" style="display:none;" />
<!--<input type="button" id="fullWidthButton" style="display:none" value="Full Frame" onclick="clickResizeImage()" />-->
</td>
</tr>
</table>
</form>
<table id="imageControls2" class="iframetable" style="display:none">
<tr><td align="right">Version:</td><td><select id='imageVersions' onchange="clickVersion(this);"></select> &nbsp; <?php emit_zoom(); ?> </td></tr>
</table>
</tbody>
</div>
<div class="imageArea" width="100%" height="100%">
<ul id="tabs">
</ul>
<!--
<table>
<tr>
<td>imageMAT allows you to pull virtually any image available to you online into the annotation interface, mark it up and add commentary about it. You can add an image to this space in one of two ways: install the bookmarklet in your browser and use it to identify an image on a webpage - the image is automatically added to this space; you also have the ability to manually add the image URL to the space above (imageMAT accepts .jpg, .gif, .png and .tif file formats). Once you click "Add image", the image will appear in this space.</td>
</tr>
</table> -->
<br class=clearfloat />
</div>
<div>

</div>
<?php
done:
bodyFooterFilename();
?>
</div>
<br class=clearfloat />
</body>
</html>
