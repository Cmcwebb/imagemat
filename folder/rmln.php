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

echo HtmlHeader('Remove symbolic link'), '
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

$name = getpost('name');
$mode = getpost('mode');
if (isset($mode)) {
  if ($name[0] == '@') {
    $name = substr($name, 1);
  }
  $query = '
delete from symlinks
 where source_folder_id = ' . DBnumber($folder_id) . '
   and name = ' . DBstring($name);
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
<br><font color=red>Symbolic link "',
htmlspecialchars($name), '" not found.</font>';
}

echo '
<br>', $path, '$ rmln';
ls($parent_folder_id, $folder_id, false);

echo '
<form action="rmln.php" method="post">
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

