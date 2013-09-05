<!DOCTYPE HTML>
<?php
$gPHPscript = __FILE__;
$dir = dirname(__FILE__);
require_once($dir . '/../include/boilerplate.php');
require_once($dir . '/../include/util.php');

if (mustlogon()) {
  return;
}

$group_id = getparameter('group_id');
if (!isset($group_id)) {
  $group_id = getparameter('id');
}
if (!isset($group_id)) {
  $setGroups = getSetGroups();
  if ($setGroups == null) {
    header('Window-Target: _top');
    header('Location: ' . dirname($_SERVER['REQUEST_URI']) . '/../group/search.php?updates=update' );
    return;
  }
  $group_id = $setGroups[0];
}

htmlHeader('Update Groups');
srcStylesheet(
  '../css/style.css',
  '../css/annotation.css',
  '../css/alert.css'
);
srcJavascript('../js/alert.js'); 
?>

</head>
<body>
<?php bodyHeader(); ?>

<div>
<iframe id="groupFrame" name="groupFrame" class="fullFrame" src="update1.php?group_id=<?php echo $group_id; ?> mozallowfullscreen webkitallowfullscreen></iframe>
</div>

<?php
bodyFooter();
?>

</body>
</html>
