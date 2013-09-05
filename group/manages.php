<!DOCTYPE HTML>
<?php
$gPHPscript = __FILE__;
$dir = dirname(__FILE__);
require_once($dir . '/../include/boilerplate.php');

if (mustlogon()) {
  return;
}

$group_id = getparameter('id');

htmlHeader('Review Groups');
srcStylesheet(
  '../css/style.css',
  '../css/annotation.css');
?>
</head>
<body>
<?php
bodyHeader();
?>

<iframe id="searchFrame" name="searchFrame" class="fullFrame" src="manages1.php">
</iframe>

<?php
bodyFooter();
?>

</body>
</html>
