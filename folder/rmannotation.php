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

echo HtmlHeader('Remove annotation'), '
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
$path = htmlspecialchars($getpath['path']);
$parent_folder_id = $getpath['parent_id'];
$folder_owner_id  = $getpath['owner'];

$annotation_id = getpost('annotation_id');
$mode = getpost('mode');
if (isset($mode)) {
  $ok = true;
  if (!isset($annotation_id)) {
    echo
'<br><font color=red>Need an annotation_id to remove it.</font>';
    $ok = false;
  }
  if (!isset($folder_id)) {
    echo
'<br><font color=red>Need a folder the annotation in in.</font>';
    $ok = false;
  }
  if ($ok) {
    $query = '
delete from foldersannotations
 where folder_id = ' . DBnumber($folder_id) . '
   and annotation_id = ' . DBnumber($annotation_id);
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
<br><font color=red>Annotation ',
htmlspecialchars($annotation_id), ' not found.</font>';
} }

echo '
<br>', $path, '$ rm annotation';
ls($parent_folder_id, $folder_id, false);

echo '
<form action="rmannotation.php" method="post">
<input type=hidden name=mode value=y />';
hidden('folder_id');
echo '
<table>
<tr>
<td align=right>Annotation id:</td>
<td><input type=text name=annotation_id size=90 value="',
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

