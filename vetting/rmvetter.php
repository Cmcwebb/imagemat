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

echo HtmlHeader('Remove vetter'), '
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

$user = getpost('user');
$mode = getpost('mode');
if (isset($mode)) {
  if (!isset($user)) {
    echo '
<br><font color=red>Please specify the vetter you wish to delete.</font>';
    goto repeat;
  }
  $query = '
delete from vetters
 where folder_id = ' . DBnumber($folder_id) . '
   and user_id = ' . DBstring($user);
  $ret = DBquery($query);
  if (!$ret) {
    goto close;
  }
  if (DBupdated() == 1) {
    echo $path, '$ ';
    ls($parent_folder_id, $folder_id);
    goto close;
  }
  echo '
<br><font color=red>Vetter with user id "',
htmlspecialchars($user), '" not found.</font>';
}
repeat:
echo '
<br>', $path, '$ rmuser';
ls($parent_folder_id, $folder_id, false);

echo '
<form action="rmvetter.php" method="post">
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

