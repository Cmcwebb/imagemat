<!DOCTYPE HTML>
<?php
$gPHPscript = __FILE__;
$dir = dirname(__FILE__);
require_once($dir . '/../include/boilerplate.php');
require_once($dir . '/../include/util.php');

$lines = getparameter('lines');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Test Full window content with fixed with headed/footer</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<style type="text/css" media="screen">

body { 
  margin: 0; 
}
  
.row {
  overflow: hidden;
  position: absolute;
  left: 0;
  right: 0;
}

.col {
  overflow: hidden;
  position: absolute;
  top: 0;
  bottom: 0;
}

.scroll-x {
  overflow-x: auto;
}

.scroll-y {
  overflow-y: auto;
}

html { 
  background-color:yellow;
}

body { 
  background-color:green;
}

#wrapper { 
  background-color: blue;
}

#header { 
  background-color: red;
  height: 86px;
}      

#content {
  background-color: lightblue;
  top: 86px;
  bottom:44px;
  margin: 0;
}

#footer { 
  height: 44px;
  bottom: 0;
  background-color: orange;
}
</style>
</head>
<body>
<div id="wrapper">
  <div id="header" class=row><h1>header</h1></div>
  <div id="content" class="row scroll-y"><h1>content</h1>
This solution is from
<a href="http://blog.stevensanderson.com/2011/10/05/full-height-app-layouts-a-css-trick-to-make-it-easier">http://blog.stevensanderson.com/2011/10/05/full-height-app-layouts-a-css-trick-to-make-it-easier</a>
<p>
Works, beautifully on firefox
<p>
To increase the number of lines in the content to force scrolling indicated the number of lines you want
<form>
<input type=text name=lines value=40 />
</form>
<?
  if (!isset($lines)) {
    $lines = 0;
  }
  for ($i = 1; $i <= $lines; ++$i) {
    echo '
<p>
Stuff ', $i;
  }
?>
</div>
<div id="footer" class=row><h1>footer</h1></div>
</body>
</html> 
