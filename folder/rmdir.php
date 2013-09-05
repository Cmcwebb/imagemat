<!DOCTYPE HTML>
<?php

$gPHPscript = __FILE__;
$dir = dirname(__FILE__);
require_once($dir . '/../include/boilerplate.php');

if (mustlogon()) {
  return;
}

require_once($dir . '/../include/db.php');
require_once($dir . '/../include/folders.php');

echo HtmlHeader('Remove directory'), '
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
<br><font color=red>You may not delete the root directory</font>';
  goto close;
}

$getpath = getpath($folder_id);
if (!$getpath) {
  goto close;
}
$parent_folder_id = $getpath['parent_id'];
$path = htmlspecialchars($getpath['path']);

echo '
<br>', $path;

if ($parent_folder_id == 1) {
  echo '
<br><font color=red>You may not delete your home directory</font>';
  goto close;
}
$mode = getpost('mode');
if (isset($mode)) {
  $reallysure = getpost('reallysure');
  if ($reallysure == 'Yes') {
    $delete_id = $folder_id;
    $folder_id = $parent_folder_id;
    $_SESSION['imageMAT_cwd'] = $folder_id;

    $query = '
delete from folders
 where folder_id = ' . DBnumber($delete_id);
    $ret = DBquery($query);
    if (!$ret) {
      goto close;
  } }
  echo $path, '$ ';
  ls($parent_folder_id, $folder_id);
  goto close;
}

echo '$ rm';
ls($parent_folder_id, $folder_id, false);

?>
<form action="rmdir.php" method="post">
<table>
<tr>
<td align=right>Delete current directory:</td>
<td>
<select name=reallysure>
<option size=10></option>
<option >No</option>
<option >Yes</option>
</select>
</td>
</tr>

<tr>
<td></td>
<td><input type=submit name=mode value=Send /></td>
</tr>
</table>
</form>

<?php

close:
DBclose();
done:
?>
</body>
</html>

