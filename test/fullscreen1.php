<!DOCTYPE HTML>
<?php
$gPHPscript = __FILE__;
$dir = dirname(__FILE__);
require_once($dir . '/../include/boilerplate.php');

?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Test fullScreen of iframe content</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<style type="text/css" media="screen">

/* make the image stretch to fill the screen in WebKit */
:-webkit-full-screen #me {
      width: 100%;
      height: 100%;
}
</style>
</head>
<body >
<h3>Press Enter to Toggle Full Screen</h3>
<iframe src="fullscreen.php"
        mozallowfullscreen
        webkitallowfullscreen
></iframe>
</body>
</html> 
