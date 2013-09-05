<!DOCTYPE HTML>
<?php

$dir = dirname(__FILE__);
require_once($dir . '/../include/boilerplate.php');
require_once($dir . '/../include/util.php');
$gPHPscript = __FILE__;

$width = getparameter('width');
if (!isset($width)) {
  $width = '98';
}
?>
<html>
<head>
<title>Image Scaling</title>
</head>
<body>
<form action='image.php?width=<?php echo $width; ?>' >
Width: <input name=width value='<?php echo $width; ?>' />
</form>
<div id=imageArea'>
<div id='tab0'>
<div id='image0wrap' width='50%'>
<img id='image0img' src='http://train-photos.com.s3.amazonaws.com/8920.jpg' width='<?php echo $width; ?>%'></img>
</div>
</div>
</div>
