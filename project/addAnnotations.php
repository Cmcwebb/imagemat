<!DOCTYPE HTML>
<?php

$gPHPscript = __FILE__;
$dir = dirname(__FILE__);
require_once($dir . '/../include/boilerplate.php');

if ($gUserid == null) {
  /* This can happen if the frame is left idle a long time */
  return 'You are not logged on';
 
}

function gohome() {
	header("Location: ../library/library1.php");
}

if (isset($_POST["skip"])) {
	header("Location: ../library/library1.php");
}

htmlHeader('Create Annotation');
srcStylesheet(
  '../css/style.css',
  '../css/annotation.css',
  '../css/alert.css'
);

enterJavascript();
?>
function checkSelection( form ) {

    var annotationsSelected = checkArray(form, "annids[]");
     
    if(annotationsSelected.length == 0) {	
      alert("Please select atleast one annotation");
      return false 
    }else{
	form.submit();	
	
      }
    return false;
  }


 function checkArray(form, annid)
  {
    var retval = new Array();
    for(var i=0; i < form.elements.length; i++) {
      var el = form.elements[i];
      if(el.type == "checkbox" && el.name == annid && el.checked) {
        retval.push(el.value);
      }
    }
    return retval;
  }

<?php
exitJavascript();

echo '</head>
<body>';

bodyHeader();

//var_dump($_POST);

// folder id 
if ((!isset($_POST["current_folder_id"])) || (!isset($_POST["current_folder_title"]))){
	echo ' erro ';
	return false ;
}

$current_folder_id = $_POST["current_folder_id"]; // $_SESSION['current_folder_id'];
$current_folder_title = $_POST["current_folder_title"]; // $_SESSION['currentfolder_title'];
 
// if optionis new then 

if (!isset($_POST["annids"])){

require_once($dir . '/../include/db.php');

if (!DBconnect()) {
  return;
}

$setquery = 'select annotation_id, title, annotation_deleted from annotations where 
creator_user_id='.DBstring($gUserid);

$inforet = DBquery($setquery);
if (!$inforet){
        goto close;
}

echo 'Select Annotations to add to '.$current_folder_title.'<br>';

echo '
<form method="POST" action="addAnnotations.php" onSubmit="return checkSelection(this);">';
echo ' <input type="hidden" name="current_folder_id" value="'.$current_folder_id .'">';
echo ' <input type ="hidden" name="current_folder_title" value = "'.htmlspecialchars($current_folder_title) .'">';

echo '<fieldset>';
while($row = mysqli_fetch_row($inforet))
  {
   if ($row[2] == 1) {
 
   }else{
	
	  echo '<input type="checkbox" name="annids[]" value="'.$row[0].'">'.htmlspecialchars($row[1]).'<br>';
   }
 }
  echo "<br>";
echo '<input type="submit" value="Add Annotation" />';
echo '</fieldset>';

echo '</form>';
goto close ;

}else{
	//var_dump($_POST);
	$annids = $_POST["annids"];

	require_once($dir . '/../include/db.php');

	if (!DBconnect()) {
	  return;
	}
	echo 'folder is '.$current_folder_id.'<br>'; 
	foreach ($annids as $ann){
		echo $ann ;
		  $query =
'insert ignore into foldersannotations
(folder_id, annotation_id,
 owns, may_see, may_read, may_copy, may_post, may_update, may_delete,
 may_comment, may_read_comments, manage_comments, may_x_post,
 creator_user_id, created)
select ' 
. DBnumberC($current_folder_id)
. DBnumberC($ann)
. 'default_mask_owns, default_mask_may_see, default_mask_may_read, default_mask_may_post,
default_mask_may_copy, default_mask_may_update, default_mask_may_delete,
default_mask_may_comment, default_mask_may_read_comments, default_mask_manage_comments,
default_mask_may_x_post,'
. DBstringC($gUserid)
. 'utc_timestamp()'
.'from folders where 
folder_id='.DBnumber($current_folder_id);


  echo 'Inserted in db <br> <br> '; 
  $ret = DBquery($query);
  if (!$ret) {
    goto close;
  }
  
 }
 gohome();
}

/*
$folder_id = getpost('id');
if (!isset($folder_id)) {
  echo 'Missing id';
  return;
}

if ($folder_id == 1) {
  echo 'You may not add items to the root directory';
  return;
}

if (!isset($_SESSION['imageMAT_setAnnotations'])) {
  echo 'No selected annotations';
  return;
}

$setAnnotations = $_SESSION['imageMAT_setAnnotations'];
if (!count($setAnnotations)) {
  echo 'No selected annotations';
  return;
}
  
require_once($dir . '/../include/db.php');

if (!DBconnect()) {
  return;
}

$setquery = 'select annotation_id, title from annotations where 
creator_user_id='.DBstring($gUserid);

$inforet = DBquery($setquery);

if (!$inforet){
	goto close; 
}

while($row = mysql_fetch_array($inforet))
  {
  echo $row['annotation_id'];
  echo " " . $row['title'];
  echo "<br>";
 }

$query =
'select creator_user_id
  from folders
 where folder_id = ' . DBnumber($folder_id);

$ret = DBquery($query);
if (!$ret) {
  goto close;
}
$row = DBfetch($ret);
if (!$row) {
  echo 'Folder not found';
  goto close;
}
if ($row['creator_user_id'] != $gUserid) {
  echo 'Can\'t add annotations under folder created by ', htmlspecialchars($row['creator_user_id']);
  goto close;
}

$member = false;

foreach ($setAnnotations as $annotation_id) {
  $query =
'insert ignore into foldersannotations
(folder_id, annotation_id,
 owns, may_see, may_read, may_copy, may_post, may_update, may_delete,
 may_comment, may_read_comments, manage_comments, may_x_post,
 creator_user_id, created)
select ' 
. DBnumberC($folder_id)
. DBnumberC($annotation_id)
. 'default_owns, default_may_see, default_may_read, default_may_post,
default_may_copy, default_may_update, default_may_delete,
default_may_comment, default_may_read_comments, default_manage_comments,
default_may_x_post,'
. DBstringC($gUserid)
. 'utc_timestamp()';

  if ($member) {
    $query .= '
 from groups
where user_id   = ' . DBstring($gUserid) . '
  and folder_id = ' . DBnumber($folder_id);
  } else {
    $query .= '
 from users
where user_id = ' . DBstring($gUserid);
  }

  $ret = DBquery($query);
  if (!$ret) {
    goto close;
} }


*/

close:
DBclose();

echo '
</body>
</html>
';
?>
