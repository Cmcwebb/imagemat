<?php

$gPHPscript = __FILE__;
$dir = dirname(__FILE__);
require_once($dir . '/../include/boilerplate.php');

if ($gUserid == null) {
  /* This can happen if the frame is left idle a long time */
  return 'You are not logged on';
}

$id = getpost('id');
if (!isset($id)) {
  echo 'Missing id';
  return;
}

if ($id == 1) {
  echo 'You may not add items to the root directory';
  return;
}

$name = getpost('name');
if (!isset($name)) {
  echo 'Missing name';
  return;
}
$name = urldecode($name);

$path = getpost('path');
if (!isset($path)) {
  echo 'Missing path';
  return;
}
$path = urldecode($path);

require_once($dir . '/../include/db.php');

if (!DBconnect()) {
  return;
}
$query =
'select creator_user_id
  from folders
 where folder_id = ' . DBnumber($id);

$ret = DBquery($query);
if (!$ret) {
  goto close;
}
$row = DBfetch($ret);
if (!$row) {
  echo 'Folder not found';
  goto close;
}
if ($row['creator_user_id'] != $gUserid) {
  echo 'Can\'t add link under folder created by ', htmlspecialchars($row['creator_user_id']);
  goto close;
}

require_once($dir . '/../include/folders/valid.php');

if (!valid_folder_name($name, $id, null, true)) {
  return;
}
require_once($dir . '/../include/folders.php');

$target = find_folder_id($id, $path, true);
if (!$target) {
  goto close;
}

$query =
'select name
   from symlinks
  where parent_folder_id = ' . DBnumber($id) . '
    and target_folder_id = ' . DBnumber($target_folder_id);

$ret = DBquery($query);
if (!$ret) {
  goto close;
}
$row = DBfetch($ret);
if ($row) {
  echo 'Link duplicates link named ', $row['name'];
  goto close;
}

$query = 
'insert ignore into symlinks
       (parent_folder_id, target_folder_id, name, creator_user_id, created)
values (' . DBnumberC($id) . DBnumberC($target) . DBstringC($name) . DBstringC($gUserid) . 'utc_timestamp())';
       
$ret = DBquery($query);
if (!$ret) {
  goto close;
}
close:
DBclose();
?>
