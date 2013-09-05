<!DOCTYPE HTML>
<?php
$gPHPscript = __FILE__;
$dir = dirname(__FILE__);
require_once($dir . '/../include/boilerplate.php');

if (mustlogon()) {
  return;
}

$group_id = getparameter('id');

htmlHeader('Create new group');
srcStylesheet(
  '../css/style.css',
  '../css/annotation.css');
?>
</head>
<body>
<?php
bodyHeader();
?>

<iframe id="searchFrame" name="searchFrame" class="fullFrame" src="<?php
if (isset($group_id)) {
  echo 'update1.php?group_id=', htmlspecialchars($group_id);
} else {
  echo 'create1.php'; 
}
?>"></iframe>

<?php
bodyFooter();
?>

</body>
</html>
