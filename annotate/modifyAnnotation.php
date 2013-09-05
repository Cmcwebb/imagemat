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

enterJavascript(); ?>
<?php exitJavascript(); ?>

<?php

/* function to check if current user can delete this 
annotation. if so, delete it and return status
*/
function isvaliddelete ( $annotation_id ){
        
 global $gDBc;
 global $gUserid;
 require_once('../include/db.php');

 // remove this if we get the creator use rid by default 
 $annotations_query = 'select creator_user_id  from annotations where annotation_id = '. ''.$annotation_id.'';

// echo ' in delete ';

 DBconnect();

 $ret = mysqli_query($gDBc, $annotations_query);
         if (!$ret || !DBok()) {
            DBerror($annotations_query);
          echo ' nothing in db'. $gUserid ;
           return  false;
         }

         $row = mysqli_fetch_assoc($ret);

         if ($row['creator_user_id']  === $gUserid ){
              
                // good now delete
                $delete_query =  'update annotations set annotation_deleted = 1  where annotation_id ='.$annotation_id;

                 $ret = DBquery($delete_query);

                 if (!$ret) {
                  return false;
                 }
   //                 echo '<h3>Annotation ', htmlspecialchars($annotation_id), ' deleted</h3>';

                return true;
         }      

return false;
}

?>

<?php
//echo 'mode is '.$_REQUEST["mode"].'and id is '.$_REQUEST["id"].'';

if ($_REQUEST["mode"] == "x") {
	if ( isvaliddelete($_REQUEST["id"]) ){
//		header("target: _top");		
//		header("Location: ../library/library1.php");
	//	header("../library1.php");
		enterJavaScript();
	          ?>
                        top.location.href = "../library/library1.php";
                        <?php   
                          exitJavaScript();


	}else{

			echo 'error in deleting the Annotation';
	}


}else{
	//echo 'here for '. $_REQUEST["mode"].'and id is '.$_REQUEST["id"].'';
	$id = $_REQUEST["id"];
  //      top.location.href = "../annotate/annotate.php?annotation_id=+$id";
	header("Location: ../annotate/annotate.php?annotation_id=+$id");

}

close:

?>
