<?php

$gPHPscript = __FILE__;
$dir = dirname(__FILE__);
require_once($dir . '/../include/boilerplate.php');

if ($gUserid == null) {
  return 'You are not logged on';
}

$item  = getpost('item');
if (!isset($item)) {
  echo 'Missing item';
  return;
}
$value = getpost('value');

require_once($dir . '/../include/db.php');

if (!DBconnect()) {
  return;
}

switch ($item) {
case 1:
  $field = 'visible';
  break;
case 2:
  $field = 'visible_email';
  break;
case 3:
  $field = 'global';
  break;
case 4:
  $field = 'global_email';
  break;
default:
  echo 'invalid item ', htmlspecialchars($item);
  goto close;
}

$query =
'update users
   set ' . $field . ' = ' . DBnumber($value) . '
 where user_id = ' . DBstring($gUserid);

$ret = DBquery($query);
if (!$ret) {
  goto close;
}
if (DBupdated() == 0) {
  echo 'User not found';
}

close:
DBclose();
?>
