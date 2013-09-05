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

echo HtmlHeader('Place symlink'), '
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
<br>', $path, '$ ln';

$name       = getpost('name');
$path       = getpost('path');
$mode        = getpost('mode');

if (isset($mode)) {
  $ok = true;
  if (!isset($name)) {
    echo '
<br><font color=red>A symlink needs a name</font>';
    $ok = false;
  } else {
require_once($dir . '/../include/folders/valid.php');
    if (!valid_folder_name($name, null, null, null)) {
      $ok = false;
  } }
  if (!isset($path)) {
    echo '
<br><font color=red>A symlink needs a path</font>';
    $ok = false;
  } else {
    $target_id = find_folder_id(null, $path, true);
    if (!$target_id) {
      $ok = false;
  } }
  if ($folder_id == 1) {
    echo '
<br><font color=red>You may not add items to the root directory</font>';
    $ok = false;
  }
  if ($ok) {
    $query =
'insert ignore into symlinks
(source_folder_id, name, target_folder_id, creator_user_id, created)
values ('
. DBnumberC($folder_id)
. DBstringC($name)
. DBnumberC($target_id)
. DBstringC($gUserid)
. 'utc_timestamp())';
    $ret = DBquery($query);
    if ($ret) {
      ls($parent_folder_id, $folder_id);
    }
    goto close;
} }

echo '
<form action="ln.php" method="post">
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

