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

echo HtmlHeader('Make directory'), '
</head>
<body>';

if (!DBconnect()) {
  goto done;
}

$folder_id = get_folder_id();
if (!$folder_id) {
  goto close;
}
$getpath = getpath($folder_id);
if (!$getpath) {
  goto close;
}
$parent_folder_id = $getpath['parent_id'];
$path = htmlspecialchars($getpath['path']);

echo '
<br>', $path, '$ mkdir';

$name        = getpost('name');
$description = getpost('description');
$mode        = getpost('mode');

if (isset($mode)) {
  if (!$name) {
    echo '
<br><font color=red>A folder needs a name</font>';
    goto repeat;
  }
  if ($folder_id == 1) {
    echo '
<br><font color=red>You may not add items to the root directory</font>';
    goto repeat;
  }
  $query =
'insert into folders
       (parent_folder_id, name, description, new_users, inherits, 
        default_folder_permissions, default_mask_owns, default_mask_may_see,
        default_mask_may_read, default_mask_may_post, default_mask_may_copy,
        default_mask_may_update, default_mask_may_delete,
        default_mask_may_comment,
        default_mask_may_read_comments, default_mask_manage_comments,
        default_mask_may_x_post, creator_user_id, created)
 select folder_id, ' . DBstringC($name) . DBstringC($description) .  '0, 127,
        default_folder_permissions, default_mask_owns, default_mask_may_see,
        default_mask_may_read, default_mask_may_post, default_mask_may_copy,
        default_mask_may_update, default_mask_may_delete,
        default_mask_may_comment,
        default_mask_may_read_comments, default_mask_manage_comments,
        default_mask_may_x_post,' . DBstringC($gUserid) . 'utc_timestamp()
   from folders
  where folder_id = ' . DBnumber($folder_id);

  $ret = DBquery($query);
  if (!$ret) {
    goto close;
  }
  ls($parent_folder_id, $folder_id);
  goto close;
} 
repeat:
echo '
<form action="mkdir.php" method="post">
<input type=hidden name=mode value=y />';
hidden('folder_id');
echo '
<table>
<tr>
<td align=right>Name:</td>
<td><input type=text name=name size=90 value="',
 htmlspecialchars($name), '" /></td>
</tr>
<tr>
<td align=right>Description:</td>
<td><input type=text name=description size=90 value="',
 htmlspecialchars($description), '" /></td>
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

