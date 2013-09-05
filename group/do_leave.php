<?php

$gPHPscript = __FILE__;
$dir = dirname(__FILE__);
require_once($dir . '/../include/boilerplate.php');

if ($gUserid == null) {
  echo 'You are not logged on';
  goto done;
}

$group_id = getparameter('group_id');

if (!$group_id) {
  echo 'Missing group id';
  goto done;
}

require_once($dir . '/../include/db.php');

if (!DBconnect()) {
  return;
}

require_once($dir . '/../include/usersgroups.php');

$sql_group_id = DBnumber($group_id);

$query = 
'select exclude
  from usersgroups
 where group_id = ' . $sql_group_id;

$ret = DBquery($query);
if (!$ret) {
  goto close;
}

$row = DBfetch($ret);
if (!$row) {
  echo 'Group does not exist';
  goto close;
}
if (isset($row['exclude'])) {
  echo 'Can\'t leave an exclusion group';
  goto close;
}

$query =
'delete from groupsmembers
 where group_id = ' . $sql_group_id . '
   and user_id  = ' . DBstring($gUserid);

$ret = DBquery($query);

close:
DBclose();
done:

?>
