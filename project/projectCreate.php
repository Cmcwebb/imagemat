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
require_once($dir . '/../include/tags.php');

htmlHeader('Search for annotations');
srcStylesheet(
  '../css/style.css',
  '../css/project.css'
);
srcJavascript(
 '../js/alert.js',
 '../js/enterkey.js',
  '../js/edit.js',
  '../js/sortable.js',
  '../js/base64.js',
  '../js/tooltip.js',
  '../js/ajax.js',
  '../js/search.js'
);

$updates = getparameter('updates');

if (isset($updates)) {
  srcStylesheet('../css/alert.css');
  srcJavascript('../js/alert.js');
}

enterJavascript();
?>
function sortNumber(a, b)
{
  return a - b;
}

function unload()
{
  annotationIdsToServer();
}

function load()
{
  window.onbeforeunload = function () { unload(); }
<?php
  if (isset($updates)) {
    echo '
  customAlert( { icon:"warn.png", body:"Please select the annotation(s) you wish to ', $updates, '" } );
';
  }
?>
}
<?php
exitJavascript();
?>
</head>
<body onload="load()" onunload="unload()" >
<?php
bodyHeader();
$list = getparameter('list');
$parent_id = getparameter('parent_id');
//echo $user_folder_id ;

if (isset($list)) {
  $list = '?list=y';
} else {
  $list = '';
}



//<!-- <iframe id="projectFrame" name="projectFrame" class="fullFrame" src="../project/create_folder.php?parent_id="<?php echo $user_folder_id 
//"></iframe> -->

if (( !isset($parent_id) && (!isset ($_SESSION['parent_id'] ))))  {
  echo 'Missing parent folder id';
  goto done;
}
$parent_id = $_SESSION['parent_id'];

if ($parent_id < 2) {
  echo 'You may not add items to the root directory';
  goto done;
}

$atomic = false;

$mode     = getpost('mode');
$name     = getpost('name');
$description = getpost('folderdesc');
$tags     = getpost('tags');

if (isset($mode) && $mode == 'y') {
  if (!isset($name)) {
    javascriptAlert(null, null, 'A folder needs a name', 'Error');
    goto show;
  }

  require_once($dir . '/../include/db.php');

  if (!DBconnect()) {
    goto done;
  }
  $query =
'select creator_user_id
  from folders
 where folder_id = ' . DBnumber($parent_id);

  $ret = DBquery($query);
  if (!$ret) {
    goto close;
  }
  $row = DBfetch($ret);
  if (!$row) {
    echo 'Parent Folder not found';
    goto close;
  }
  if ($row['creator_user_id'] != $gUserid) {
    echo 'Can\'t add folder under folder created by ', htmlspecialchars($row['creator_user_id']);
    goto close;
  }

  require_once($dir . '/../include/folders/valid.php');

  if (!valid_folder_name($name, $parent_id, null, null)) {
    goto show;
  }
  require_once($dir . '/../include/folders/insert.php');

 $folder_id = insert_folder($parent_id, $name, $description);
  if ($folder_id < 0) {
    goto close;
  }
  if (!$folder_id) {
    echo 'Failed to create folder';
    goto close;
  }

  if (isset($description)) {
    $query =
'insert into folderfulltexts(folder_id,fdescription)
 values (' . DBnumberC($folder_id) . DBstring(plainText($description)) . ')';

    $ret = DBquery($query);
    if (!$ret) {
      goto close;
  } }
  if (isset($tags)) {
    insert_tags($tags, $folder_id, 'folder_id','foldertags');
  }

  echo '
<h3>Created Folder ', $folder_id, '</h3>';
  
  enterJavascript();
  echo '
created_folder(', $parent_id, ',', $folder_id, ',', json_encode($name), ');
';
  exitJavascript();
  echo '
<h3>Name</h3>', htmlspecialchars($name);
  if (isset($description)) {
    echo '<h3>Description</h3>', htmlspecialchars($description);
  }
  if (isset($tags)) {
     echo '<h3>Tags</h3>', htmlspecialchars($tags);
  }
  goto close;
} 
show:

if (isset($_SESSION['imageMAT_language_code2'])) {
  $mylanguage = $_SESSION['imageMAT_language_code2'];
} else {
  $mylanguage = '';
}
require_once($dir . '/../include/language.php');

?>
<form id=form name=form action="create_folder.php" method="post" >
<input type=hidden id=mode name=mode value=y />
<?php
hidden('parent_id');
?>
<table class=iframetable width='99%'>

<tr>
<td align=right>Name:</td>
<td><input type="text" id="name" name="name" size="50" maxlength="255" value="<?php echo htmlspecialchars($name); ?>" />
</td>
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
<input type="submit" value="Create" />
<input type=reset value="Restart" onclick="reset_form()" />
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

bodyFooter();
?>

</body>
</html>
