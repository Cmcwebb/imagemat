<!DOCTYPE HTML><head>

<?php

$gPHPscript = __FILE__;
$dir = dirname(__FILE__);
require_once($dir . '/../include/boilerplate.php');

if ($gUserid == null) {
  /* This can happen if the frame is left idle a long time */
  return 'You are not logged on';
  
}
if (mustlogon()) {
  /* This can happen if the frame is left idle a long time */
  return;
}

htmlHeader('Search for annotations');

/*
if (isset($_POST["folder_id"])) {
	header("Location: ../library/library1.php");
}
*/

function isvalidoperation( $groupid  ){

	require_once('../include/db.php');
        global $gDBc;
        global $gUserid;

        $annotations_query = 'select creator_user_id, created, modified   from folders where folder_id = '. ''.$groupid.'';

         DBconnect();

         $ret = mysqli_query($gDBc, $annotations_query);
         if (!$ret || !DBok()) {
            DBerror($annotations_query);
          echo ' nothing in db'. $gUserid ;
           return  false;
         }

         $row = mysqli_fetch_assoc($ret);

         if ($row['creator_user_id']  === $gUserid ){
		return true;
	 }	
 return false;
}



function addAnnotations ( $current_folder_id ){

	global $gUserid;

	 $annids = $_POST["annids"];
         require_once('../include/db.php');
         if (!DBconnect()) {       return false;       }

	if (!isvalidoperation( $current_folder_id ) ) {
			return false ;
	}

          foreach ($annids as $ann){
//                echo $ann.'<br> ' ;
                $query  = 'insert ignore into foldersannotations
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

	  $ret = DBquery($query);
	  DBcommit();

	 if (!$ret) {
	   return false;
	  }

	}

 return true;
}

//deletes a specified set of annotations 
// for a given user with valid operarion`1
function deleteAnnotations ( $current_folder_id ) {

	 global $gUserid;
         $annids = $_POST["annids"];
         require_once('../include/db.php');
         if (!DBconnect()) {       return false;       }

        if (!isvalidoperation( $current_folder_id ) ) {
                        return false ;
        }
	// delete the annotations
	foreach ($annids as $ann){
                $annotation_query = 'delete  from foldersannotations 
                                   where folder_id ='.DBnumber($current_folder_id).'
                                   and annotation_id ='.DBnumber($ann);
                $ret = DBquery($annotation_query);
                if (!$ret) {   return false ;}
	}
		
	
 return true;

}



// delete a given project with groupid. First 
// we delete all the annotations in the folder
// and then delete the ffoilder 
// however, this only wokrs if we 
// are the creator of a project 
function deleteProject($groupid ) {
	// 
	//is person delete this -- only if you are owner	
	require_once('../include/db.php');
	global $gDBc;
        global $gUserid;

	$annotations_query = 'select creator_user_id, created, modified   from folders where folder_id = '. ''.$groupid.'';

         DBconnect();

         $ret = mysqli_query($gDBc, $annotations_query);
         if (!$ret || !DBok()) {
            DBerror($annotations_query);
            $ret = false;
          echo ' nothing in db'. $gUserid ;
         }
 
         $row = mysqli_fetch_assoc($ret);

	 if ($row['creator_user_id']  === $gUserid ){
	
		 $query = 'delete from foldersannotations  where folder_id = ' . $groupid;
		 $ret = DBquery($query);
		  if (!$ret) {
		    return false;
		  }

		// next is remove the actual folder
	         $query = 'delete from folders  where folder_id = ' . $groupid;

		  $ret = DBquery($query);
		  if (!$ret) {
		    return false;
		  }
		  return true;

	}else{
		echo ' Error: Cannot delete this one buddy ! ';
		return false;

	}
}

function updateMeta ( $groupid, $mod_title, $mod_desc, $mod_tags ){
	
	require_once('../include/db.php');
        global $gDBc;
	global $gUserid; 
	 
	$annotations_query = 'select creator_user_id, created, modified   from folders where folder_id = '. ''.$groupid.'';

         DBconnect();

         $ret = mysqli_query($gDBc, $annotations_query);
         if (!$ret || !DBok()) {
            DBerror($annotations_query);
            $ret = false;
          echo ' nothing in db'. $gUserid ;
         }

         $row = mysqli_fetch_assoc($ret);

         if ($row['creator_user_id']  === $gUserid ){

		// update the folder 
		 $query = 'update folders
				   set name             = ' . DBstring($mod_title) . ',
				   description      = ' . DBstring($mod_desc) . ',
			           modified         = utc_timestamp()
				   where folder_id        = ' . DBnumber($groupid);

			  $ret = DBquery($query);
			  if (!$ret) {
				    return false ;
			  }

		 	require_once('../include/tags.php');

			if (isset($tags)) {
				insert_tags($tags, $folder_id, 'folder_id','foldertags');
			}
			
			echo ' Updated Meta Information';
			return true;
		}else{
			echo ' Unable to change meta';
			return false;
	}
}

srcStylesheet(
  '../css/style.css',
  '../css/alert.css',
  '../css/library.css',
  '../css/tabber.css'
);

enterJavascript();
?>


      function activateTab(pageId) {
          var tabCtrl = document.getElementById('tabCtrl');
          var pageToActivate = document.getElementById(pageId);
          for (var i = 0; i < tabCtrl.childNodes.length; i++) {
              var node = tabCtrl.childNodes[i];
              if (node.nodeType == 1) { /* Element */
                  node.style.display = (node == pageToActivate) ? 'block' : 'none';
              }
          }
      }


function alertuser() {
	var choice = confirm("Are you sure about deleting ?");
	if (choice == true){
		var form   = document.getElementById("f1");
		form.submit();	 
	}else{
		return false;
	} 
}

function updateinfo() {
	var form = document.getElementById("f1");
	form.submit();
}



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

echo '</head> <body link="#C0C0C0" vlink="#808080" alink="#FF0000> ';

bodyHeader();

//var_dump($_POST);
if ((!isset($_POST["current_folder_id"])) || (!isset($_POST["current_folder_title"]))){ 
	echo ' error: missing folder details'; 
//eturn false ;
	echo '';
//	goto close ;
 }

$current_folder_id = $_POST["current_folder_id"]; // $_SESSION['current_folder_id'];
$current_folder_title = $_POST["current_folder_title"]; // $_SESSION['currentfolder_title'];

$description = $_POST["folderdesc"] ;  

//if (isset($_POST['tab'])){
?>
<script type="text/javascript" src="../js/tabber.js">
</script>

<div class="tabberlive">
<ul class="tabbernav">
   <li class="tabberactive">
        <a href="javascript:activateTab('page1')">Project Info</a>
      </li>
      <li class="active">         <a href="javascript:activateTab('page2')">Add Annotations to this Project</a>       </li>
      <li class="active">         <a href="javascript:activateTab('page3')">Remove Annotations from Project</a>       </li>
    </ul>
    </div>
    
    <div id="tabCtrl">

     <div id="page1" style="display: block;"> 
	<?php 
//Change Meta and  Permissions  <br>
	if (( isset($_POST['tab']) ) && ($_POST['tab'] == 1))  {

		// if user wans to update meta
		if (isset($_POST["updatemeta"])){
		

		$mod_tags = '';
		if (isset($_POST['mod_tags'])) {
			$mod_tags = $_POST['mod_tags'];
		}
	
		if (updateMeta($_POST['current_folder_id'], $_POST['current_folder_title'] , $_POST['folderdesc'], $mod_tags ) ) {

			$current_folder_title = $_POST['current_folder_title'];
		 	$description = $_POST['folderdesc'];
			}else{
				echo ' Unable to change ';
			}
		}else{ // if(isset($_POST['delete'])) { // == "Delete Project") {
			// or user wants to delete the project
			if (deleteProject($current_folder_id)){
 //                             header("Location: ../library/library1.php");
			enterJavaScript();
			      ?>
			window.location.href = "../library/library1.php";
			<?php	
			  exitJavaScript();
                         }else{
                               echo ' Error in deleting this project. ' ;
                        }

			}		
                  }elseif ((isset($_POST['tab'] )) && ($_POST['tab'] == 2)) {
			if (addAnnotations( $_POST['current_folder_id'] )) {
				echo ' Successfully added new  annotations to your project ';
	                }else{

        	                echo 'Error: Unable to add annotations ';
                	}

		 }elseif ((isset($_POST['tab'] )) && ($_POST['tab'] == 3)) {
                        if (deleteAnnotations( $_POST['current_folder_id'] )) {
                                echo ' Successfully removed annotations from the project ';
                        }else{ 

                                echo 'Error: Unable to remove annotations ';
                        }

		}
 
		//}
	//either ways, we show the form 
	?>
	   <form id="f1" method="POST" action="maintainProject.php" > 
		 <input type="hidden" label="Id" name="current_folder_id" value="<?php echo $current_folder_id ?>"> 
		 <input type="hidden" label="page" name="tab" value="1"> 
		
		  <h3>Project Title:</h3> 	
       		  <input type ="text" name="current_folder_title" value = "<?php echo htmlspecialchars($current_folder_title) ?>"> <br />
			 <h3>Description:</h3>
			<textarea id="folderdesc" name="folderdesc" cols="100" rows="6"><?php echo htmlspecialchars($description) ?> </textarea>
		 <br> <br>

		<input type="submit" name="updatemeta" value="Update Information" onclick="alertUser();" />
        <input type="button" name="returnlibrary" value="Return to Library" onclick="window.location = '../library/library1.php';" />
		<input type="button" name="delete" value="Delete Project"  onclick="alertuser();" /> 
		</form> 
	</div>

      <div id="page2" style="display: none;">

	<?php 
	require_once('../include/db.php');

	if (!DBconnect()) {
		  return;
	}

	$setquery = 'select annotation_id, title, annotation_deleted from annotations where 
	creator_user_id='.DBstring($gUserid);

	$inforet = DBquery($setquery);
	if (!$inforet){
        	goto close;
	}	
                   
	echo '<h3>Select Annotations to add to '.$current_folder_title.'<br></h3>';
	
	echo '<form method="POST" action="maintainProject.php" onSubmit="return checkSelection(this);">';
	echo '<input type="hidden" label="page" name="tab" value="2">'; 
	echo ' <input type="hidden" name="current_folder_id" value="'.$current_folder_id .'">';
	echo ' <input type ="hidden" name="current_folder_title" value = "'.htmlspecialchars($current_folder_title) .'">';
	echo ' <input type ="hidden" name="folderdesc" value = "'.htmlspecialchars($description) .'">';

	echo '<fieldset  style="width:800px">  
	<div style="height: 150px; overflow-y:scroll; background-color: #fff;"> ';

	while($row = mysqli_fetch_row($inforet))
	  {
	   if ($row[2] == 1) {
 
	   }else{
        
        	  echo '<input type="checkbox" name="annids[]" value="'.$row[0].'">'.htmlspecialchars($row[1]).'<br>';
   	  }
 	}
	echo "<br /> </div>";
	echo '</fieldset>';
	echo '<br /><input type="submit" value="Add Annotation" /> ';
	echo '</form>';

	?>
 
	</div>
 
     <div id="page3" style="display: none;">
      <?php
//Remove Annotations from Project <br>
require_once($dir . '/../include/db.php');


if (!DBconnect()) {
  return;
}

echo ' <form method="POST" action="maintainProject.php" onSubmit="return checkSelection(this);">';
echo '<input type="hidden" label="page" name="tab" value="3">';
echo ' <input type="hidden" name="current_folder_id" value="'.$current_folder_id .'">';
echo ' <input type ="hidden" name="current_folder_title" value = "'.htmlspecialchars($current_folder_title) .'">';
echo ' <input type ="hidden" name="folderdesc" value = "'.htmlspecialchars($description) .'">';


$annotation_set_query = 'select annotation_id, title,annotation_deleted  from annotations where
                annotation_id in (select annotation_id from foldersannotations where 
                folder_id='.$current_folder_id.')';

//$setquery = 'select annotation_id, title, annotation_deleted from annotations where 
//creator_user_id='.DBstring($gUserid);

$inforet = DBquery($annotation_set_query);
if (!$inforet){
        goto close;
}

echo '<h3>Select the annotations to be removed from "'.$current_folder_title.'"</h3>';
echo '<fieldset style="width:800px">  
<div style="height: 150px; overflow-x:scroll; background-color: #fff">
';
while($row = mysqli_fetch_row($inforet))
  {
   if ($row[2] == 1) {

   }else{
          echo '<input type="checkbox" name="annids[]" value="'.$row[0].' " >'.htmlspecialchars($row[1]).'<br>';
   }
 }
  echo "<br>";
echo '</div>';
echo '</fieldset>';
echo '<br>';
echo '<input type="submit"  value="Remove Annotations" />';
//echo '<input type="submit"   value ="Cancel" />';
echo '</form>';

?>
</div>

<?php
// if optionis new then 

//}else{

if (0){
	//var_dump($_POST);
	// F	
	global $gUserid;
	echo $gUserid.' '.$current_folder_id ; 

	if ($_POST['tab'] == 1){

	}elseif ($_POST['tab'] == 2){

	}elseif ($_POST['tab'] == 3) {
		//delete annotation 
	}
}

close:
//DBclose();

echo '</body> </html>';

?>
