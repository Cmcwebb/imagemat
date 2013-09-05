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

$url_id = getpost('url_id');
$mode = getpost('mode');
if (isset($mode)) {
  $ok = true;
  if (!isset($url_id)) {
    echo '
<br><font color=red>Please specify the url id you wish to delete.</font>';
    $ok = false;
  }
  if ($ok) {
    $query = '
delete from foldersurls
 where folder_id = ' . DBnumber($folder_id) . '
   and url_id = ' . DBnumber($url_id);
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
<br><font color=red>URL ',
htmlspecialchars($url_id), ' not found.</font>';
} }

echo '
<br>', $path, '$ rmurl';
ls($parent_folder_id, $folder_id, false);

echo '
<form action="rmurl.php" method="post">
<input type=hidden name=mode value=y />';
hidden('folder_id');
echo '
<table>
<tr>
<td align=right>URL id:</td>
<td><input type=text name=url_id size=90 value="',
 htmlspecialchars($url_id), '" /></td>
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

