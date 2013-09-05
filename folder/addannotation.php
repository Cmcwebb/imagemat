<!DOCTYPE HTML>
<?php

$gPHPscript = __FILE__;
$dir = dirname(__FILE__);
require_once($dir . '/../include/boilerplate.php');
require_once($dir . '/../include/util.php');

if (mustlogon()) {
  return;
}

require_once($dir . '/../include/db.php');
require_once($dir . '/../include/folders.php');

echo HtmlHeader('Add annotation'), '
</head>
<body>';

if (!DBconnect()) {
  goto done;
}

$folder_id = get_folder_id();
if (!$folder_id) {
  goto close;
}
if ($folder_id == 1) {
  echo '
<br><font color=red>You may not add items to the root directory</font>';
  goto do_ls;
}
$getpath   = getpath($folder_id);
if (!$getpath) {
  goto close;
}
$path = htmlspecialchars($getpath['path']);
$parent_folder_id = $getpath['parent_id'];
$folder_owner_id  = $getpath['owner'];

require_once($dir . '/../include/permissions.php');
require_once($dir . '/../include/groups.php');

$member = resolve_member($gUserid, $folder_id, $folder_owner_id);
if (!isset($member)) {
  goto close;
} 
if (!$member) {
  if ($gUserid == $folder_owner_id) {
    echo '
<br>You are the owner of this folder but have not assigned yourself membership
in this group.  We will presume that you want the can_annotate permission.';
  } else {
    echo '
<br>You are not a group member of ', $path, '
<br>Only members and the folder creator ', htmlspecialchars($folder_owner_id),
' may add annotations to this folder.';
    goto do_ls;
  }
} else if (!$member['folder_permissions'] & CAN_ANNOTATE) {
  echo '
<br>Your group membership does not permit you to place annotations in ', $path;
  goto do_ls;
}

$annotation_id = getpost('annotation_id');
$mode = getpost('mode');
if (isset($mode)) {
  if (!isset($annotation_id)) {
    echo '
<br><font color=red>No annotation id provided</font>';
    goto retry;
  }

  $query =
'insert into foldersannotations
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
  }
do_ls:
  echo '
<br>', $path;
  ls($parent_folder_id, $folder_id);
  goto close;
} 

retry:

echo '
<br>', $path;
echo '$ add annotation';
ls($parent_folder_id, $folder_id, false);
echo '
<p>
<form action="addannotation.php" method="post">
<input type=hidden name=mode value=y />';
hidden('folder_id');
echo '
<table>
<tr>
<td align=right>Annotation: </td>
<td><input type=text name=annotation_id size=10 value="',
 htmlspecialchars($annotation_id), '" /></td>
</tr>
<tr>
<td></td>
<td><input type=submit name=send value=Send /><input type=reset /></td>
</tr>
</table>
</form>';

close:
DBclose();
done:
?>
</body>
</html>

