<!DOCTYPE HTML>
<?php

$gPHPscript = __FILE__;
$dir = dirname(__FILE__);
require_once($dir . '/../include/boilerplate.php');
require_once($dir . '/../include/util.php');

if (mustlogon()) {
  return;
}

htmlHeader('Update Folder');

require_once($dir . '/../include/db.php');
require_once($dir . '/../include/tags.php');
srcStylesheet(
  '../css/style.css',
  '../css/iframe.css',
  '../css/alert.css'
);
srcJavascript(
  '../js/util.js',
  '../js/fullwidth.js',
  '../js/enterkey.js',
  '../js/alert.js',
  '../js/edit.js',
  '../js/folder.js'
);

$parent_id = getparameter('parent_id');
$target_id = getparameter('target_id');

enterJavascript();
?>

var old = null;

function isDirty()
{
  var data = [];
  getFormData('form', data);

  var length = data.length;

  if (old.length != length) {
    alert('Unexpected form length change');
    return true;
  }
  for (var name in data) {
	if (data[name] != old[name]) {
	  return true;
  } }
  return false;
}

function updated_link(parent_id)
{
  if (self != top) {
    // TODO make safer - can't in general assume frames[0] is right
    top.frames[0].done_update_link(<?php echo $parent_id; ?>);
} }

function checkForm()
{
  if (!isDirty()) {
    customAlert( {title:'Information unchanged', icon:'warn.png', body:'The link has not been changed. Therefore there is no point in attempting to update it' } );
    return false;
  }
  return true;
}

function loaded()
{
  old = [];
  getFormData('form', old);
  label_frame_button();
}

<?php exitJavascript(); ?>
</head>
<body onload="loaded()" >
<?php

// var_dump($_POST);

if (!isset($parent_id)) {
  echo 'Parent directory id not specified';
  goto done;
}
if (!isset($target_id)) {
  echo 'Target directory id not specified';
  goto done;
}

require_once($dir . '/../include/db.php');
require_once($dir . '/../include/folders.php');
require_once($dir . '/../include/folders/valid.php');

if (!DBconnect()) {
  goto done;
}

$mode = getpost('mode');
if (isset($mode)) {
  $name = getpost('name');
  $path = getpost('path');
  $orig_name = getpost('orig_name');
  $orig_path = getpost('orig_path');
  $orig_target_id = getpost('target_id');
  $link_owner = getpost('link_owner');
  $target_owner = getpost('target_owner');

  if (!isset($name)) {
    $name = '';
  } else {
    $name = trim($name);
  }

  if (!isset($path)) {
    $path = '';
  } else {
    $path = trim($path);
  }

  if ($path == $orig_path) {
    $target_id = $orig_target_id;
  } else {
    $target_id = find_folder_id($parent_id, $path, true);
    if (!$target_id) {
      goto show;
  } }
  if (!valid_folder_name($name, $parent_id, $orig_target_id, true)) {
    goto show;
  }
  if ($target_id == $orig_target_id) {
    if ($parent_id == 1) {
      $query = 
'update favouritefolders
   set name             = ' . DBstring($name) . '
 where user_id          = ' . DBstring($gUserid) . '
   and target_folder_id = ' . DBnumber($orig_target_id);
    } else {
      $query = 
'update symlinks
   set name             = ' . DBstring($name) . ',
       creator_user_id  = ' . DBstring($gUserid) . ',
       created          = utc_timestamp()
 where parent_folder_id = ' . DBnumber($parent_id) . '
   and target_folder_id = ' . DBnumber($orig_target_id);
    }
  } else {
    if ($parent_id == 1) {
      $query = 
'update favouritefolders
   set name             = ' . DBstring($name) . ',
       target_folder_id = ' . DBnumber($target_id) . '
 where user_id          = ' . DBstring($gUserid) . '
   and target_folder_id = ' . DBnumber($orig_target_id);
    } else {
      $query = 
'update symlinks
   set name             = ' . DBstring($name) . ',
       target_folder_id = ' . DBnumber($target_id) . ',
       creator_user_id  = ' . DBstring($gUserid) . ',
       created          = utc_timestamp()
 where parent_folder_id = ' . DBnumber($parent_id) . '
   and target_folder_id = ' . DBnumber($orig_target_id);
  } }
  $ret = DBquery($query);
  if (!$ret) {
    goto close;
  }

  echo '
<h3>Updated link ', htmlspecialchars($name), '</h3>';
  
  enterJavascript();
  echo '
  updated_link();
';
  exitJavascript();
  goto close;
}

if ($parent_id == 1) {
  $query =
'select 1 as parent_folder_id, target_folder_id, name, user_id as creator_user_id
  from favouritefolders
 where user_id          = ' . DBstring($gUserid) . '
   and target_folder_id = ' . DBnumber($target_id);
} else {
  $query =
'select parent_folder_id, target_folder_id, name, creator_user_id
  from symlinks
 where parent_folder_id = ' . DBnumber($parent_id) . '
   and target_folder_id = ' . DBnumber($target_id);
}

$ret = DBquery($query);
if (!$ret) {
  goto close;
}
$row = DBfetch($ret);
if (!$row) {
  echo '
<br>The link was not found';
  goto close;
}
$name  = $row['name'];
$orig_name = $name;
$link_owner = $row['creator_user_id'];
$array = getpath($target_id);
$path  = $array['path'];
$orig_path = $path;
$target_owner = $array['owner'];

show:

?>
<form id=form name=form action="update_link.php" onsubmit="return checkForm();" method="post">
<input type=hidden id=mode name=mode value=y />
<?php
hidden('parent_id');
hidden('target_id');
hidden('link_owner');
hidden('target_owner');
hidden('orig_name');
hidden('orig_path');
?>
<table class=iframetable width='99%'>

<tr>
<td align=right>Name:</td>
<td>
<input type="text" id=name name="name" size="50" maxlength="255" value="<?php echo htmlspecialchars($name); ?>" />
</td>
</tr>

<tr>
<td align=right>Path:</td>
<td>
<input type="text" name="path" size="50" maxlength="255" value="<?php echo htmlspecialchars($path); ?>" />
</td>
</tr>

<tr>
<td align=right>Owner:</td>
<td>
<?php echo htmlspecialchars($link_owner); ?>
</td>
</td>

<tr>
<td align=right>Target:</td>
<td>
<?php echo htmlspecialchars($target_owner); ?>
</td>
</td>
</tr>

<tr><td></td><td></td></tr>

<tr>
<td></td>
<td>
<input type="submit" value="Update" />
<input type="button" id="fullWidthButton" style="display:none" value="Full Frame" onclick="toggle_frames()" />
</td>
</tr>

</table>
</form>
<?php
close:
DBclose();
done:
bodyFooterFilename();
?>
</body>
</html>
