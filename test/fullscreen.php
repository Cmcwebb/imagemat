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

/* make the image stretch to fill the screen in WebKit */
:-webkit-full-screen #me {
      width: 100%;
      height: 100%;
}
</style>
<?php
enterJavascript();
?>
var myImage = null;
    
function toggleFullScreen()
{
	if (!document.mozFullScreen && !document.webkitFullScreen) {
		if (myImage.mozRequestFullScreen) {
        	myImage.mozRequestFullScreen();
      	} else {
        	myImage.webkitRequestFullScreen(Element.ALLOW_KEYBOARD_INPUT);
      	}
    } else {
		if (document.mozCancelFullScreen) {
        	document.mozCancelFullScreen();
      	} else {
        	document.webkitCancelFullScreen();
      	}
    }
}
  
document.addEventListener(
	"keydown",
	function(e)
	{
    	if (e.keyCode == 13) {
      		toggleFullScreen();
    	}
  	},
	false);

function loaded()
{
	myImage = document.getElementById("myImage");
	alert('iframe fullScreen=' + myImage.mozFullScreenEnabled + ' Enabled=' +
			document.fullScreenEnabled);
}
<?php
exitJavascript();
?>
</head>
<body onload="loaded()" >
<h3>Press Enter to Toggle Full Screen</h3>
<img id="myImage" src="http://mat.uwaterloo.ca/ijdavis/jpg/landscape-10.jpg" width="100">
<form>
<input type=button value="FullScreen" onclick="toggleFullScreen()"></input>
<form>
</body>
</html> 
