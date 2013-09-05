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

echo HtmlHeader('Change directory'), '
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
$parent_folder_id = $getpath['parent_id'];
$path = htmlspecialchars($getpath['path']);

$path       = getpost('path');
$mode        = getpost('mode');
if (isset($mode)) {
  $ok = true;
  if (!isset($path)) {
    echo '
<br><font color=red>No path provided</font>';
    $ok = false;
  } else {
    $target_id = find_folder_id(null, $path, true);
    if (!$target_id) {
      $ok = false;
  } }
  if ($ok) {
    $folder_id = $target_id;
    $_SESSION['imageMAT_cwd'] = $folder_id;
    echo '
<br>', $path;
    ls($parent_folder_id, $folder_id);
    goto close;
} }

echo '
<br>', $path, '$ cd';
ls($parent_folder_id, $folder_id, false);
echo '
<p>
<form action="cd.php" method="post">
<input type=hidden name=mode value=y />';
hidden('folder_id');
echo '
<table>
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

