<!DOCTYPE HTML>
<?php
$gPHPscript = __FILE__;
$dir = dirname(__FILE__);
require_once($dir . '/../include/boilerplate.php');
require_once($dir . '/../include/util.php');

$zoom = getparameter('zoom');
if (!isset($zoom)) {
  $zoom = 1.0;
}
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Test Zooming of iframe content</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<style type="text/css" media="screen">
#iframe{
zoom: <?php echo $zoom; ?>;
-moz-transform: scale(<?php echo $zoom; ?>);
-moz-transform-origin: 0 0;
-o-transform: scale(<?php echo $zoom; ?>);
-o-transform-origin: 0 0;
-webkit-transform: scale(<?php echo $zoom; ?>);
-webkit-transform-origin: 0 0;
-ms-transform: scale(<?php echo $zoom; ?>);
-ms-transform-origin:: 0 0;
}
.clear { clear: both; }
</style>
<?php
enterJavascript();
?>
function loaded()
{
	iframe = document.getElementById('iframe');
	alert('iframe fullScreen=' + iframe.mozFullScreenEnabled + ' Enabled=' +
			document.fullScreenEnabled);
}
<?php
exitJavascript();
?>
</head>
<body onload="loaded()" >
<div>
<form>
Zoom: <input type=text name=zoom value="<?php echo $zoom; ?>" />
</form>
</div>
<div>
<iframe id=iframe src="http://www.uwaterloo.ca" height=700 width="100%" mozallowfullscreen=true></iframe>
<br class="clear" />
</div>
<br class="clear" />
<div>
<br class="clear" />
<h1>footer</h1></div>
</body>
</html> 
