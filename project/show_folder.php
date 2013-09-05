<!DOCTYPE HTML>
<?php

$gPHPscript = __FILE__;
$dir = dirname(__FILE__);
require_once($dir . '/../include/boilerplate.php');

if (mustlogon()) {
  return;
}

$folder_id = getparameter('folder_id');

$update = false;
$delete = false;

if (!isset($folder_id)) {
  header('Location: ' . dirname($_SERVER['REQUEST_URI']) . '/searchFolders.php');
  return;
}

htmlHeader('Show Folder');

require_once($dir . '/../include/db.php');
srcStylesheet(
  '../css/style.css'
);
srcJavascript(
  '../js/util.js',
  '../js/fullwidth.js',
  '../js/enterkey.js',
  '../js/edit.js',
  '../js/folder.js'
);
enterJavascript();

echo '
var folder_id = ', $folder_id, ';
var myScript  = \'', scriptName(), '\';
';
?>

function loaded()
{
  showSetButtons();
  label_frame_button();
}

<?php exitJavascript(); ?>
</head>
<body onload="loaded()" id="background">
<?php

// var_dump($_POST);

require_once($dir . '/../include/tables.php');

if (!DBconnect()) {
  goto done;
}

$query =
'select parent_folder_id, folders.folder_id, name, description, fdescription,
       creator_user_id, created, modified
  from folders left join folderfulltexts
    on folders.folder_id = folderfulltexts.folder_id
 where folders.folder_id = ' . DBnumber($folder_id);
$ret = DBquery($query);
if (!$ret) {
  goto close;
}
$row = DBfetch($ret);
if (!$row) {
  echo 'Folder ', htmlspecialchars($folder_id), ' not found';
} else {
  foreach ($row as $colname => $value) {
    $$colname = $value;
    if (isset($value)) {
      echo '
<h3>', htmlspecialchars($colname), '</h3>
<p>';
      if ($colname == 'fdescription') {
		echo $value;
	  } else {
		echo htmlspecialchars($value);
  } } }
  $update = ($folder_id > 1) && ($gUserid == $creator_user_id);
  $delete = ($folder_id > 1) && ($parent_folder_id > 1);
}

$query =
'select name,value
  from foldertags
 where folder_id = ' . DBnumber($folder_id);

echo '
<h3>Tags</h3>
<p>';
echo_table($query);

echo '
<p>
<form>';
if ($update) {
  echo '
<input type="button" value="Update" onclick="update_folder()" />';
}
if ($delete) {
  echo '
<input type="button" value="Delete" onclick="delete_folder()" />';
}
echo '
<input id=prev type="button" value="<" style="display:none" onclick="prev_folder()" />
<input id=list type="button" value="List" style="display:none" onclick="list_folders()" />
<input id=next type="button" value=">" style="display:none" onclick="next_folder()" />
<input type="button" id="fullWidthButton" style="display:none" value="Full Frame" onclick="toggle_frames()" />
</form>';
close:
DBclose();
done:
bodyFooterFilename();
?>
</body>
</html>
