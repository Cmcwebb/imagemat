<?php

$gPHPscript = __FILE__;
$dir = dirname(__FILE__);
require_once($dir . '/../include/boilerplate.php');

if ($gUserid == null) {
  /* This can happen if the frame is left idle a long time */
  return 'You are not logged on';
}

$user_id = getpost('user_id');
if (!isset($user_id)) {
  echo 'Please provide a user id';
  return;
}

require_once($dir . '/../include/db.php');

if (!DBconnect()) {
  return;
}
$query =
'select null
  from users
 where user_id = ' . DBstring($user_id);

$ret = DBquery($query);
if (!$ret) {
  goto close;
}
$row = DBfetch($ret);
if (!$row) {
  echo 'User id "', htmlspecialchars($user_id), '" not found';
  goto close;
}
close:
DBclose();
?>
