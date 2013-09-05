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

echo HtmlHeader('Add url'), '
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

$url        = getpost('url');
$path      = getpost('path');
if (!isset($path)) {
  $path = '.';
}
$mode        = getpost('mode');

if (isset($mode)) {
  $ok = true;
  if (!isset($url)) {
    echo '
<br><font color=red>A url needs a name</font>';
    $ok = false;
  }
  $target_id = find_folder_id(null, $path, true);
  if (!$target_id) {
    $ok = false;
  } else {
    if ($target_id == 1) {
      echo '
<br><font color=red>You may not add items to the root directory</font>';
      $ok = false;
  } }
  if ($ok) {
    $query =
'insert ignore into urls(url)
values (' . DBstring($url) . ')';
    $ret = DBquery($query);
    if (!$ret) {
      goto close;
    }
    $query =
'select url_id
  from urls
 where url = ' . DBstring($url);

    $ret = DBquery($query);
    if (!$ret) {
      goto close;
    }
    $row = DBfetch($ret);
    if (!$row) {
      echo '
<br>Unable to find url "', htmlspecialchars($url) , '" in mat.urls';
      goto close;
    }
    $url_id = $row['url_id'];

    $query =
'select url_id
  from foldersurls
 where folder_id = ' . DBnumber($target_id) . '
   and url_id    = ' . DBnumber($url_id);
    $ret = DBquery($query);
    if (!$ret) {
      goto close;
    }
    $row = DBfetch($ret);
    if ($row) {
      echo '
<br><font color=red>The URL ', htmlspecialchars($url), ' already exists in the folder';
      goto repeat;
    }

    $query =
'insert ignore into foldersurls(folder_id, url_id, creator_user_id, created)
values ('
 . DBnumberC($target_id) 
 . DBnumberC($url_id) 
 . DBstringC($gUserid) 
 . 'utc_timestamp())';

    $ret = DBquery($query);
    if (!$ret) {
      goto close;
    }
    $folder_id = $target_id;
    $_SESSION['imageMAT_cwd'] = $folder_id;
    echo '
<br>', $path;
    ls($parent_folder_id, $folder_id);
    goto close;
} }

repeat:
echo '
<form action="addurl.php" method="post">
<input type=hidden name=mode value=y />';
hidden('folder_id');
echo '
<table>
<tr>
<td align=right>URL:</td>
<td><input type=text name=url size=90 value="',
 htmlspecialchars($url), '" /></td>
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

