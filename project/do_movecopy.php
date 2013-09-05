<?php

$gPHPscript = __FILE__;
$dir = dirname(__FILE__);
require_once($dir . '/../include/boilerplate.php');

if ($gUserid == null) {
  return 'You are not logged on';
}

$target_folder_id = getpost('id');
if (!isset($target_folder_id)) {
  echo 'Missing target folder id';
  return;
}
$move = getpost('move');
if (isset($move)) {
  $move = true;
} else {
  $move = false;
}

$json  = getpost('json');
if (!isset($json)) {
  echo 'Missing json';
  return;
}
$actions = json_decode(urldecode($json));
if (!isset($actions)) {
  echo 'Can\'t decode json input';
  return;
}

if ($target_folder_id == 1) {
  echo 'You may not add items to the root directory';
  return;
}

require_once($dir . '/../include/db.php');

if (!DBconnect()) {
  return;
}

$query =
'select creator_user_id
  from folders
 where folder_id = ' . DBnumber($target_folder_id);

$ret = DBquery($query);
if (!$ret) {
  goto close;
}
$row = DBfetch($ret);
if (!$row) {
  echo 'Target folder not found';
  goto close;
}

if ($row['creator_user_id'] != $gUserid) {
  foreach ($actions as $action) {
    if ($action->type == 'f') {
      echo 'Can\'t move folders to a folder owned by ', $row['creator_user_id'];
      return;
} } }

$length = count($actions);
for($i = 0; $i < $length; ++$i) {
  $action        = $actions[$i];
  $type          = $action->t;
  $old_parent_id = $action->p;
  $id            = $action->i;
  if (isset($action->x)) {
    $target_folder_id = $action->x;
  }
  
  switch ($type) {
  case 'a':
    $query =
'insert ignore into foldersannotations
(folder_id, annotation_id,
 owns, may_see, may_read, may_copy, may_post, may_update, may_delete,
 may_comment, may_read_comments, manage_comments, may_x_post,
 creator_user_id, created)
select ' . DBnumberC($target_folder_id) . '
       annotation_id,
       default_owns, default_may_see, default_may_read, default_may_post,
       default_may_copy, default_may_update, default_may_delete,
       default_may_comment, default_may_read_comments, default_manage_comments,
       default_may_x_post,' . DBstringC($gUserid) . 'utc_timestamp()
  from foldersannotations, users
 where folder_id     = ' . DBnumber($old_parent_id) . '
   and annotation_id = ' . DBnumber($id) . '
   and user_id       = ' . DBstring($gUserid);

    if (!DBquery($query)) {
      goto close;
    }
    if (!$move || DBupdated() != 1) {
	  break;
    }

    $query = 
'delete ignore from foldersannotations
 where folder_id     = ' . DBnumber($old_parent_id) . '
   and annotation_id = ' . DBnumber($id);

    if (!DBquery($query)) {
	  goto close;
    }
    break;
  case 'u':
    $query =
'insert ignore into foldersurls
(folder_id, url_id, creator_user_id, created)
 values (' . DBnumberC($target_folder_id) . DBnumberC($id) . DBstringC($gUserid) . 'utc_timestamp())';

    if (!DBquery($query)) {
      goto close;
    }
    if (!$move || DBupdated() != 1) {
      break;
    }
    $query =
'delete ignore from foldersurls
 where folder_id = ' . DBnumber($old_parent_id) . '
   and url_id    = ' . DBnumber($id);

    if (!DBquery($query)) {
      goto close;
    }
    break;

  case 's':
    $query =
'insert ignore into symlinks
       (parent_folder_id, target_folder_id, name, creator_user_id, created)
select ' . DBnumberC($target_folder_id) . 'target_folder_id, name,' . DBstringC($gUserid) . 'utc_timestamp()
  from symlinks
 where parent_folder_id = ' . DBnumber($old_parent_id) . '
   and target_folder_id = ' . DBnumber($id);

    if (!DBquery($query)) {
      goto close;
    }
    if (!$move || DBupdated() != 1) {
      break;
    }
    $query =
'delete ignore from symlinks
 where parent_folder_id = ' . DBnumber($old_parent_id) . '
   and target_folder_id = ' . DBnumber($id);

    if (!DBquery($query)) {
      goto close;
    }
    break;

  case 'f':
    if ($move) {
      $query = 
'update folders
   set parent_folder_id = ' . DBnumber($target_folder_id) . '
 where folder_id        = ' . DBnumber($id);

      if (!DBquery($query)) {
        goto close;
      }
      break;
    }
    $query = 
'insert into folders
       (parent_folder_id, name, new_users, inherits, 
        default_folder_permissions, default_mask_owns, default_mask_may_see,
        default_mask_may_read, default_mask_may_post, default_mask_may_copy,
        default_mask_may_update, default_mask_may_delete,
        default_mask_may_comment,
        default_mask_may_read_comments, default_mask_manage_comments,
        default_mask_may_x_post, creator_user_id, created)
 select p.folder_id, old.name, 0, 127,
        p.default_folder_permissions, p.default_mask_owns, 
        p.default_mask_may_see, p.default_mask_may_read,
        p.default_mask_may_post, p.default_mask_may_copy,
        p.default_mask_may_update, p.default_mask_may_delete,
        p.default_mask_may_comment, p.default_mask_may_read_comments,
        p.default_mask_manage_comments, 
        p.default_mask_may_x_post,' . DBstringC($gUserid) . 'utc_timestamp()
   from folders old, folders p
  where old.folder_id = ' . DBnumber($id) . '
    and p.folder_id   = ' . DBnumber($target_folder_id);

    if (!DBquery($query)) {
      goto close;
    }

    $new_id = DBid();
    if ($new_id == 0) {
	  echo 'Failed to copy folder';
      goto close;
    }

    $query =
'select annotation_id
  from foldersannotations
 where folder_id     = ' . DBnumber($id);

    $ret = DBquery($query);
    if (!$ret) {
      goto close;
    }
    while ($row = DBfetch($ret)) {
      $action = new stdClass;
      $action->t = 'a';
      $action->p = $id;
      $action->i = $row['annotation_id'];
      $action->x = $new_id;
      $actions[] = $action;
    }

    $query =
'select url_id
  from foldersurls
 where folder_id = ' . DBnumber($id);

    $ret = DBquery($query);
    if (!$ret) {
      goto close;
    }
    while ($row = DBfetch($ret)) {
      $action = new stdClass;
      $action->t = 'u';
      $action->p = $id;
      $action->i = $row['url_id'];
      $action->x = $new_id;
      $actions[] = $action;
    }

    $query =
'select target_folder_id
  from symlinks
 where parent_folder_id = ' . DBnumber($id);

    $ret = DBquery($query);
    if (!$ret) {
      goto close;
    }
    while ($row = DBfetch($ret)) {
      $action = new stdClass;
      $action->t = 's';
      $action->p = $id;
      $action->i = $row['target_folder_id'];
      $action->x = $new_id;
      $actions[] = $action;
    }

    $query =
'select folder_id
  from folders
 where parent_folder_id = ' . DBnumber($id);

    $ret = DBquery($query);
    if (!$ret) {
      goto close;
    }
    while ($row = DBfetch($ret)) {
      $action = new stdClass;
      $action->t = 'f';
      $action->p = $id;
      $action->i = $row['folder_id'];
      $action->x = $new_id;
      $actions[] = $action;
    }

    $length = count($actions);
	break;

  default:
    echo 'Unknown type of item to move/copy of ' + $type;
    return;
} }
close:
DBclose();
?>
