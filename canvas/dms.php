<!DOCTYPE HTML>
<?php

$gPHPscript = __FILE__;
$dir = dirname(__FILE__);
require_once($dir . '/../include/boilerplate.php');

htmlHeader('Visualization of Shared Canvas');

srcStylesheet(
  '../css/style.css'
);
echo '
</head>
<body>
';

bodyHeader();

$target = getpost('target');
if (!isset($target)) {
  $target = 1;
}
switch ($target) {
case 2:
  $manifest = "http://rosetest.library.jhu.edu/m3";
  $site     = 'John Hopkins';
  break;
case 3:
  $manifest = "http://www.shared-canvas.org/impl/demo1/res/Manifest.xml";
  $site     = 'Shared Canvas Demo';
  break;
default:
  $manifest = "http://mat.uwaterloo.ca/m3/index.php";
  $site     = 'Margot';
  break;
}

$size  = getpost('size');
if (!isset($size)) {
  $size = 1;
}
switch ($size) {
case 2:
  $width  = 1024;
  $height = 768;
  break;
case 3:
  $width  = 1280;
  $height = 1024;
  break;
case 4:
  $width  = 1600;
  $height = 1200;
  break;
default:
  $width  = 800;
  $height = 600;
  break;
}
?>
<body>
<h3>Shared Canvas Visualisation of <?php echo $site; ?> Repository</h3>
<p>
This repository is located at <?php echo htmlspecialchars($manifest); ?>
<p>
<APPLET 
  CODE="dms/DmsViewer.class" 
  CODEBASE="current"
  ARCHIVE="dms.jar"
  ALIGN=center 
  WIDTH=<?php echo $width; ?>
  HEIGHT=<?php echo $height; ?> 
  NAME="lsedit"
>
<param name="lsfile" value="dms:<?php echo htmlspecialchars($manifest); ?>">
<param name="layout" value="simplex">

Your browser is ignoring the <b>&lt;APPLET&gt;</b> tag.
<P>
You must enable use of Java within your browser in order to execute a Java
applet. 
<p>
This requires potentially downloading the latest version
of Java.
<br/>The latest version of java can be obtained from
<a href="http://www.java.com/en/download/inc/windows_upgrade_xpi.jsp">
http://www.java.com/en/download/inc/windows_upgrade_xpi.jsp</a>.
<br/>
Manual download of a specific version of java for a specific machine
can be achieved via
<a href="http://www.java.com/en/download/manual.jsp">
http://www.java.com/en/download/manual.jsp</a>
<p>
Once java is installed on the machine on which you wish to browse
instructions for enabling java applets within your browser
vary.
<p>
<ul>
<li>
Within <b>Internet Explorer</b> this can be done by navigating via:
<ol>
<li>"tools" frame menu option
<li>"internet options"
<li>"security"
<li>"custom level"
<li>"java permissions"
<li>enabling java.
</ol>
</li>
<p>
<li>
Within <b>Netscape</b> this can be done by navigating via:
<ol>
<li>"edit" frame menu option
<li>"preferences"
<li>"advanced"
<li>checking "enable java"
</ol>
</li>
<p>
<li>
Within <b>Firefox</b> this can be done by navigating via:
<ol>
<li>Tools (Menu bar)</li>
<li>Add ons</li>
<li>Plug ins</li>
<li>Enabling all Java plugins</li>
</ol>
</li>
</ul>
<p>
Internally the shared canvas visualiser uses sockets in order to retrieve the
most current shared canvas information from the selected repository. 
<br/>This
may result in you being asked to approve the use of the java visualisation
applet before first being able to use it.

</APPLET>

<?php
done:
bodyFooter();
?>

</body>
</html>
