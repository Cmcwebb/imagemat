<!DOCTYPE HTML>
<?php

$gPHPscript = __FILE__;
$dir = dirname(__FILE__);
require_once($dir . '/../include/boilerplate.php');
require_once($dir . '/../include/util.php');

if (mustlogon()) {
  return;
}

$folder_id = getparameter('folder_id');
if (!isset($folder_id)) {
  header('Location: ' . dirname($_SERVER['REQUEST_URI']) . '/searchFolders.php');
  return;
}
$update = false;
$delete = false;

htmlHeader('Update Folder');

require_once($dir . '/../include/db.php');
require_once($dir . '/../include/tags.php');
srcStylesheet(
  '../css/style.css',
  '../css/iframe.css'
);
srcJavascript(
  '../js/util.js',
  '../js/fullwidth.js',
  '../js/enterkey.js',
  '../js/edit.js',
  '../../tools/ckeditor/ckeditor.js',
  '../js/folder.js'
);
enterJavascript();

echo '
var folder_id = ', $folder_id, ';
var myScript  = \'', scriptName(), '\';
';
?>

var old = null;

function reset_form()
{
  CKEDITOR.instances['folderdesc'].setData(old['folderdesc']);
}

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
    switch (name) {
	case 'languages':
    case 'folderdesc':
		continue;
	}
	if (data[name] != old[name]) {
	  return true;
  } }
	
  var editor = CKEDITOR.instances['folderdesc'];

  if (editor) {
    // Bug in CKEditor adds \n at end of data
    var newdata = trim(editor.getData());
    var olddata = old['folderdesc'];
    if (newdata != olddata) {
      return true;
    }
  }
  return false;
}

function updated_folder(folder_id, name)
{
  if (self != top) {
    // TODO make safer - can't in general assume frames[0] is right
    top.frames[0].done_update_folder(folder_id, name);
} }

function update()
{
  if (!isDirty()) {
    customAlert( {title:'Information unchanged', icon:'warn.png', body:'The folder has not been changed. Therefore there is no point in attempting to update it' } );
    return false;
  }
  return true;
}

function loaded()
{
  disableEnterKey('form');
  old = [];
  getFormData('form', old);
  showSetButtons();
  label_frame_button();
}

<?php exitJavascript(); ?>
</head>
<body onload="loaded()" id="background">
<?php

// var_dump($_POST);

require_once($dir . '/../include/db.php');
require_once($dir . '/../include/folders/valid.php');

if (!DBconnect()) {
  goto done;
}

if ($folder_id < 2) {
  echo 'You may not alter the root folder';
  goto preview;
}

$query =
'select parent_folder_id, folder_id, name,description,creator_user_id, created, modified
  from folders
 where folder_id = ' . DBnumber($folder_id);

$ret = DBquery($query);
if (!$ret) {
  goto close;
}
$row = DBfetch($ret);
if (!$row) {
  echo '
<br>Folder_id ', htmlspecialchars($folder_id), ' not found in database';
  goto close;
}

$parent_folder_id = $row['parent_folder_id'];
$creator_user_id  = $row['creator_user_id'];
$created          = $row['created'];
$modified         = $row['modified'];

if (1 < $parent_folder_id) {
  $delete = true;
}

$mode = getpost('mode');
if (!isset($mode) || $creator_user_id != $gUserid) {
  $name = $row['name'];
  $description = $row['description'];
  $tags = recover_tags($row);
  if ($creator_user_id == $gUserid) {
    goto show;
  }
  echo 'You may not update a folder owned by ', $creator_user_id;
  goto preview;
}

$name     = getpost('name');
$description = getpost('folderdesc');
$tags     = getpost('tags');

if ($mode == 'y') {

  if (!isset($name)) {
	$name = '';
  } else {
    $name = trim($name);
  }
  if (!valid_folder_name($name, $parent_folder_id, $folder_id, false)) {
    goto show;
  }

  $update = true;

  $query = 
'update folders
   set name             = ' . DBstring($name) . ',
       description      = ' . DBstring($description) . ',
       modified         = utc_timestamp()
 where folder_id        = ' . DBnumber($folder_id);

  $ret = DBquery($query);
  if (!$ret) {
    goto close;
  }

  if (isset($description)) {
    $query =
'insert into folderfulltexts(folder_id,fdescription)
 values (' . DBnumberC($folder_id) . DBstring($description) . ')
     on duplicate key
 update fdescription = ' . DBstring($description);
  } else {
    $query =
'delete from folderfulltexts
 where folder_id = ' . DBnumber($folder_id);
  }

  $ret = DBquery($query);
  if (!$ret) {
    goto close;
  }
  update_tags($tags, $folder_id, 'folder_id','foldertags');

  echo '
<h3>Updated Folder ', $folder_id, '</h3>';
  
  enterJavascript();
  echo '
updated_folder(', $folder_id, ',', json_encode($name), ');
';
  exitJavascript();
  echo '
<h3>Name</h3>', htmlspecialchars($name);
  if (isset($description)) {
    echo '<h3>Description</h3>', $description;
  }
  if (isset($tags)) {
     echo '<h3>Tags</h3>', htmlspecialchars($tags);
  }
  echo '
<h3>Creator</h3>', htmlspecialchars($creator_user_id), '
<h3>created</h3>', htmlspecialchars($created);
  if (isset($modified)) {
    echo '
<h3>Last modified</h3>', htmlspecialchars($modified);
  }
preview:
  echo '
<p>
<form id=form name=form action="update_folder.php" method="post">';
hidden('folder_id');
if ($update) {
  echo '
<input type="submit" value="Update" />';
}
if ($delete) {
  echo '
<input type="button" value="Delete" onclick="delete_folder()" />';
}
?>
<input type="button" value="Show" onclick="show_folder()" />
<input id=prev type="button" value="<" style="display:none" onclick="prev_folder()" />
<input id=list type="button" value="List" style="display:none" onclick="list_folders()" />
<input id=next type="button" value=">" style="display:none" onclick="next_folder()" />
<input type="button" id="fullWidthButton" style="display:none" value="Full Frame" onclick="toggle_frames()" />
</form>
<?php
  goto close;
} 
show:

if (isset($_SESSION['imageMAT_language_code2'])) {
  $mylanguage = $_SESSION['imageMAT_language_code2'];
} else {
  $mylanguage = '';
}
require_once($dir . '/../include/language.php');

$update = true;
?>
<form id=form name=form action="update_folder.php" onsubmit="return update();" method="post">
<input type=hidden id=mode name=mode value=y />
<?php
hidden('folder_id');
if ($parent_folder_id < 2) {
  hidden('name');
}
?>
<table class=iframetable width='99%'>

<tr>
<td align=right>Name:</td>
<td><?php
if ($parent_folder_id < 2) {
  echo htmlspecialchars($name);
} else {
  echo '<input type="text" id="name" name="name" size="50" maxlength="255" value="', htmlspecialchars($name), '" />';
}
?></td>
</tr>

<tr>
<td align=right>Tags:</td>
<td><input type="text" name="tags" size="50" maxlength="255" value="<?php echo htmlspecialchars($tags); ?>" />
</td>
</tr>

<tr>
<td colspan=2>Description:</td>
</tr>

<tr>
<td colspan=2>
<textarea id="folderdesc" name="folderdesc" width="100%" rows="6"><?php echo htmlspecialchars($description); ?></textarea>
<?php enterJavascript(); ?>
createEditor('folderdesc', false, '<?php echo $mylanguage; ?>', false);
<?php exitJavascript(); ?>
</td>
</tr>

<tr>
<td colspan=2>
<input type="submit" value="Update" />
<?php
if ($delete) {
  echo '
<input type="button" value="Delete" onclick="delete_folder()" />';
}
?>
<input type="button" value="Show" onclick="show_folder()" />
<input type=reset value="Restart" onclick="reset_form()" />
<input id=prev type="button" value="<" style="display:none" onclick="prev_folder()" />
<input id=list type="button" value="List" style="display:none" onclick="list_folders()" />
<input id=next type="button" value=">" style="display:none" onclick="next_folder()" />
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
