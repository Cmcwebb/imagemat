<?php

$gPHPscript = __FILE__;
$dir = dirname(__FILE__);
require_once($dir . '/../include/boilerplate.php');

if ($gUserid == null) {
  /* This can happen if the frame is left idle a long time */
  return 'You are not logged on';
}

$parent = getpost('parent');
if (!isset($parent)) {
  echo 'Missing parent';
  return;
}

$id     = getpost('id');
if (!isset($id)) {
  echo 'Mising id';
  return;
}

require_once($dir . '/../include/db.php');

if (!DBconnect()) {
  return;
}
$id = DBnumber($id);

if ($parent == 1) {
  $query =
'select user_id
  from favouritefolders
 where user_id          = ' . DBstring($gUserid) . '
   and target_folder_id = ' . DBnumber($id);
} else {
  $query =
'select creator_user_id
  from symlinks
 where parent_folder_id = ' . DBnumber($parent) . '
   and target_folder_id = ' . DBnumber($id);

$ret = DBquery($query);
if (!$ret) {
  goto close;
}
$row = DBfetch($ret);
if (!$row) {
  echo 'Link not found';
  goto close;
}
if ($parent != 1 && $row['creator_user_id'] != $gUserid) {
  echo 'Can\'t delete symlink created by ', htmlspecialchars($row['creator_user_id']);
  goto close;
}

if ($parent == 1) {
  $query = 
'delete from favouritefolders
 where user_id          = ' . DBstring($gUserid) . '
   and target_folder_id = ' . DBnumber($id);
} else {
  $query = 
'delete from symlinks
 where parent_folder_id = ' . DBnumber($parent) . '
   and target_folder_id = ' . DBnumber($id);
}

$ret = DBquery($query);
if (!$ret) {
  goto close;
}
close:
DBclose();
?>
