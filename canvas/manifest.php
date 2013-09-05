<!DOCTYPE HTML>
<?php

$gPHPscript = __FILE__;
$dir = dirname(__FILE__);
require_once($dir . '/../include/boilerplate.php');

$manifest = null;
$target   = getpost('target');
if (isset($target)) {
  switch ($target) {
  case 1:
    $manifest = "http://mat.uwaterloo.ca/m3/index.php";
    break;
  case 2:
	$manifest = "http://rosetest.library.jhu.edu/m3";
	break;
  case 3:
	$manifest = "http://www.shared-canvas.org/impl/demo1/res/Manifest.xml";
	break;
  }
  $raw = getpost('raw');
  if (isset($raw)) {
    header('Window-Target: _top');
    header('Location: ' . $manifest );
} }

htmlHeader('View Shared Canvas Manifest');

srcStylesheet(
  '../css/style.css'
);
echo '
</head>
<body>
';

bodyHeader();

if (isset($manifest)) {
  $ch = curl_init($manifest);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $ret = curl_exec($ch);
  if ($ret) {
	echo '<h3>Contents of ', htmlspecialchars($manifest), '</h3><p>';
    echo '<pre style="background:white">',htmlspecialchars($ret),'</pre>';
  } else {
	echo '<font color=red>Web transfer failed</font>';
  }
  curl_close($ch);
  goto done;
}
?>

<h3>View Shared Canvas Manifest</h3>

Please select the 
<a href="http://www.shared-canvas.org">shared canvas</a> manifest that you wish to view in its raw
<a href="http://www.w3.org/TR/REC-rdf-syntax">XML/RDF</a> format.
<p>
If the shared canvas you wish to view is not in this list please contact
<?php echo  $gAdminEmail; ?>
<p>
<form id=form action=manifest.php method=post>
<select name=target>
<option value=1>Margot Repository</option>
<option value=2>John Hopkins Repository</option>
<option value=3>Shared Canvas demonstration site</option>
</select>
<input type=checkbox name="raw"/>Raw
<br>
<input type=submit value="View">
</form>
<?php
done:
bodyFooter();
?>

</body>
</html>
