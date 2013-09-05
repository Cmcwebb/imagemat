<?php

$gPHPscript = __FILE__;
$dir = dirname(__FILE__);
require_once($dir . '/../include/boilerplate.php');

if ($gUserid == null) {
  /* This can happen if the frame is left idle a long time */
  return 'You are not logged on';
}

$folder_id = getpost('id');
if (!isset($folder_id)) {
  echo 'Missing id';
  return;
}

if ($folder_id == 1) {
  echo 'You may not add items to the root directory';
  return;
}

if (!isset($_SESSION['imageMAT_setAnnotations'])) {
  echo 'No selected annotations';
  return;
}

$setAnnotations = $_SESSION['imageMAT_setAnnotations'];
if (!count($setAnnotations)) {
  echo 'No selected annotations';
  return;
}
  
require_once($dir . '/../include/db.php');

if (!DBconnect()) {
  return;
}
$query =
'select creator_user_id
  from folders
 where folder_id = ' . DBnumber($folder_id);

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
  echo 'Can\'t add annotations under folder created by ', htmlspecialchars($row['creator_user_id']);
  goto close;
}

$member = false;

foreach ($setAnnotations as $annotation_id) {
  $query =
'insert ignore into foldersannotations
(folder_id, annotation_id,
 owns, may_see, may_read, may_copy, may_post, may_update, may_delete,
 may_comment, may_read_comments, manage_comments, may_x_post,
 creator_user_id, created)
select ' 
. DBnumberC($folder_id)
. DBnumberC($annotation_id)
. 'default_owns, default_may_see, default_may_read, default_may_post,
default_may_copy, default_may_update, default_may_delete,
default_may_comment, default_may_read_comments, default_manage_comments,
default_may_x_post,'
. DBstringC($gUserid)
. 'utc_timestamp()';

  if ($member) {
    $query .= '
 from groups
where user_id   = ' . DBstring($gUserid) . '
  and folder_id = ' . DBnumber($folder_id);
  } else {
    $query .= '
 from users
where user_id = ' . DBstring($gUserid);
  }

  $ret = DBquery($query);
  if (!$ret) {
    goto close;
} }
close:
DBclose();
?>
