<!DOCTYPE HTML>
<?php

$gPHPscript = __FILE__;
$dir = dirname(__FILE__);
require_once($dir . '/../include/boilerplate.php');
require_once($dir . '/../include/util.php');

if (mustlogon()) {
  /* This can happen if the frame is left idle a long time */
  return;
}

htmlHeader('Search for annotations');
srcStylesheet(
  '../css/style.css',
  '../css/project.css'
);

require_once($dir . '/../include/db.php');
require_once($dir . '/../include/tags.php');
require_once($dir . '/../include/alert.php');

srcJavascript(
 '../js/alert.js',
  '../js/fullwidth.js',
  '../js/enterkey.js',
  '../js/edit.js',
  '../../tools/ckeditor/ckeditor.js'
);
enterJavascript();
?>

function userChoice( form ){
	form.submit();
}

function reset_form()
{
  CKEDITOR.instances['folderdesc'].setData('');
}

function created_folder(parent_id, folder_id, name)
{
  if (self != top) {
    /* TODO make safer - can't in general assume frames[0] is right */
    top.frames[0].done_add_folder(parent_id);
} }


function loaded()
{
  pageLoaded();
  label_frame_button();
}

<?php exitJavascript(); ?>
</head>
<body id='body' onload="loaded()" >
<!-- <div id='rootdiv'> -->
<?php
bodyHeader();

// var_dump($_POST);

$parent_id = getparameter('parent_id');
if (!isset($parent_id)) {
  echo 'Missing parent folder id';
  goto done;
}
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
 
 // set this folder id 
 $_SESSION['current_folder_id'] = $folder_id;

 
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
// sleep(7);
  goto nextstep;	

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

<? /*<form id=form name=form action="create_folder.php" method="post" >

*/
?>

<?php
nextstep:
?>
<form id=form name=form action="addAnnotations.php" method="post"> <!-- onSubmit="return userChoice(this)" --> 
<input type="hidden" name="current_folder_id" value="<?php echo $folder_id ?>">
<input type ="hidden" name="current_folder_title" value = "<?php echo htmlspecialchars($name) ?>">
<input type="submit" name ="add" value="Add Annotations" >
<input type="submit"  name ="skip" value="Skip and Return"> 
</form>


<?php
close:
DBclose();
done:
bodyFooterFilename();
?>
<!-- </div> -->

</body>
</html>
