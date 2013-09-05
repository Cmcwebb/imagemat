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

$query =
'select creator_user_id
  from foldersannotations
 where folder_id     = ' . DBnumber($parent) . '
   and annotation_id = ' . DBnumber($id);

$ret = DBquery($query);
if (!$ret) {
  goto close;
}
$row = DBfetch($ret);
if (!$row) {
  echo 'Annotation not found';
  goto close;
}
if ($row['creator_user_id'] != $gUserid) {
  echo 'Can\'t delete annotation created by ', htmlspecialchars($row['creator_user_id']);
  goto close;
}

$query = 
'delete from foldersannotations
 where folder_id     = ' . DBnumber($parent) . '
   and annotation_id = ' . DBnumber($id);

$ret = DBquery($query);
if (!$ret) {
  goto close;
}
close:
DBclose();
?>
