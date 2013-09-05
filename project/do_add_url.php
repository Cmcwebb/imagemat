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

$url = getpost('url');
if (!isset($url)) {
  echo 'Missing URL';
  return;
}
$url = urldecode($url);

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
  echo 'Can\'t add URL under folder created by ', htmlspecialchars($row['creator_user_id']);
  goto close;
}

require_once($dir . '/../include/folders.php');

for ($cnt = 0; ; ++$cnt) {
  $query =
'select url_id
  from urls
 where url = ' . DBstring($url);

  $ret = DBQuery($query);
  if (!$ret) {
    goto close;
  }
  $row = DBfetch($ret);
  if ($row) {
    $url_id = $row['url_id'];
    break;
  }
  if ($cnt > 0) {
    echo 'Can\'t add URL';
    goto close;
  }
  $query =
'insert ignore into urls(url)
values (' . DBstring($url) . ')';

  $ret = DBQuery($query);
  if (!$ret) {
    goto close;
} }

$query =
'insert ignore into foldersurls(folder_id, url_id, creator_user_id, created)
values (' . DBnumberC($folder_id) . DBnumberC($url_id) . DBstringC($gUserid) . 'utc_timestamp())';

$ret = DBQuery($query);
if (!$ret) {
  goto close;
}
close:
DBclose();
?>
