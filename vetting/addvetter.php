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

echo HtmlHeader('Add vetter'), '
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
<br>', $path, '$ addvetter';

$user  = getpost('user');
$path  = getpost('path');
if (!isset($path)) {
  $path = '.';
}
$mode  = getpost('mode');
if (isset($mode)) {
  if (!isset($user)) {
    echo '
<br><font color=red>A vetter needs a name</font>';
    goto repeat;
  } 
  $target_id = find_folder_id(null, $path, true);
  if (!$target_id) {
    goto repeat;
  }
  if ($target_id == 1) {
    echo '
<br><font color=red>You may not add items to the root directory</font>';
    goto repeat;
  }
  $query =
'select user_id
  from users
 where user_id = ' . DBstring($user);
  $ret = DBquery($query);
  if (!$ret) {
    goto close;
  }
  $row = DBfetch($ret);
  if (!$row) {
    echo '
<br><font color=red>Unknown user "',
 htmlspecialchars($user), '"</font>';
    goto repeat;
  }
      
  $query =
'select user_id
  from vetters
 where folder_id = ' . DBnumber($target_id) . '
   and user_id   = ' . DBstring($user);
  $ret = DBquery($query);
  if (!$ret) {
    goto close;
  }
  $row = DBfetch($ret);
  if ($row) {
    echo '
<br><font color=red>The vetter ', htmlspecialchars($user), ' already exists in the folder ', htmlspecialchars($path);
    goto repeat;
  }

  $query =
'insert ignore into vetters
(user_id, folder_id, creator_user_id, created)
values ('
. DBstringC($user) 
. DBnumberC($target_id)
. DBstringC($gUserid)
. 'utc_timestamp())';
  $ret = DBquery($query);
  if (!$ret) {
    goto close;
  }
  $folder_id = $target_id;
  $getpath   = getpath($target_id);
  if (!$getpath) {
    goto close;
  }
  $_SESSION['imageMAT_cwd'] = $folder_id;
  echo '
<br>', htmlspecialchars($getpath['path']);
  $parent_folder_id = $getpath['parent_id'];
  ls($parent_folder_id, $folder_id);
  goto close;
} 

repeat:
echo '
<form action="addvetter.php" method="post">
<input type=hidden name=mode value=y />';
hidden('folder_id');
echo '
<table>
<tr>
<td align=right>Vetter user id:</td>
<td><input type=text name=user size=90 value="',
 htmlspecialchars($user), '" /></td>
</tr>
<tr>
<td align=right>Path:</td>
<td><input type=text name=path size=90 value="',
 htmlspecialchars($path), '" /></td>
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

