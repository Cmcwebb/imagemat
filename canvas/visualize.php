<!DOCTYPE HTML>
<?php

$gPHPscript = __FILE__;
$dir = dirname(__FILE__);
require_once($dir . '/../include/boilerplate.php');

htmlHeader('Visualize Shared Canvas graphically');

srcStylesheet(
  '../css/style.css'
);

echo '
</head>
<body>
';

bodyHeader();

echo '
<h3>Shared Canvas Graphical Visualization</h3>
';

$target = getpost('target');
if (!isset($target)) {
  goto start;
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
<h3><?php echo $site; ?> Repository</h3>
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
goto showform;

start:
?>

Please select the 
<a href="http://www.shared-canvas.org">shared canvas</a> graphical visualisation that you wish to view,
and navigate within.
<p>
If the shared canvas you wish to view is not in this list please contact
<?php echo  $gAdminEmail; ?>
<?php
showform:
?>
<p>
<form id=form action="visualize.php" method="post">
<table>
<tr><td align=right>Window Size:</td>
<td>
<select name=size>
<option value=''></option>
<option value=1>800x600</option>
<option value=2>1024x768</option>
<option value=3>1280x1024</option>
<option value=4>1600x1200</option>
</select>
</td>
</tr>
<tr><td align=right>Repository:</td>
<td>
<select name=target>
<option value=1>Margot Repository</option>
<option value=2>John Hopkins Repository</option>
<option value=3>Shared Canvas demonstration site</option>
</select>
</td>
</tr>
<tr>
<td align=right>
<input type=submit value="View">
</td>
</tr>
</table>
</form>
<?php
done:
bodyFooter();
?>

</body>
</html>
