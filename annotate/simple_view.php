<!DOCTYPE HTML>
<?php

/* The one frame annotation view */

$gPHPscript = __FILE__;
$dir = dirname(__FILE__);
require_once($dir . '/../include/boilerplate.php');
require_once($dir . '/../include/util.php');

if (mustlogon()) {
  return;
}

require_once($dir . '/../include/db.php');
require_once($dir . '/../include/annotations.php');
require_once($dir . '/../include/simple_view.php');

htmlHeader('Simple View');
srcStylesheet(
  '../css/style.css'
);
enterJavascript();
?>

function clickView()
{
  var form = document.getElementById('form');
  if (form) {
	form.action = '../annotate/view.php';
	form.submit();
} }

<?php

exitJavascript();
?>
</head>
<body id="imageFrame">
<div overflow="hidden">
<?php

$annotation_id = getparameter('annotation_id');
if (!isset($annotation_id)) {
  echo '<h3 class=error>No annotation id</h3>';
  goto done;
}
if (!DBconnect()) {
  goto done;
}
showSimpleView($annotation_id, null, 'N');
echo '
<p>
<form id=form method="post" action=../annotate/annotate.php target=_top >';
hidden('annotation_id');
echo '
<input type="button" value="View" onclick="clickView()" />
<input type="submit" value="Edit" />
</form>';

close:
DBClose();
done:
?>
<br class=clearfloat />
</div>
</body>
</html>
