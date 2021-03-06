<?php 
$gPHPscript = __FILE__;
$dir = dirname(__FILE__);
require_once($dir . '/../include/boilerplate.php');
require_once($dir . '/../include/util.php');
require_once($dir . '/../include/db.php');
require_once($dir . '/../include/simple_view.php');
require_once($dir . '/../include/annotations.php');

if(!isset($_SESSION)) {
  session_start();
}
$gUserid = null;
if (isset($_SESSION['imageMAT_user_id'])) {
  $gUserid = $_SESSION['imageMAT_user_id'];
}


if (mustlogon()) {
  return;
}

 srcStylesheet(
//   '../css/style.css',
//  '../css/alert.css',
  '../css/library.css'
//  '../css/tbox.css'
);

/*var_dump($_GET);

*/

if (isset($_REQUEST["annotation"])) {
	if (($_REQUEST["annotation"] == 1)) { 

        	echo '<div class="metaText"><h3> Related </h3> ';
		extractInfo($_GET["id"]);
		
	}else{
	//project
        	echo '<h3> Related <h3>';
		printGroupMeta ( $_GET["id"] );		
	}
}

echo '</div>';

function printStatus( $draft_code ){

	switch ( $draft_code) {
	case 'Y':
		return "DRAFT";
		break;
	case 'S':
		return "SAVED" ;
	    	break;
	default:
	 return"PUBLISHED";
  	}
}


function extractInfo( $annotation_id ) {

/*Owner:
State:
Created:
Last Modified:
Permissions:
*/

global $gDBc;
$annotations_query = 'select creator_user_id, draft, created, modified, modifier_user_id  from annotations where annotation_id = '. ''.$annotation_id.'';

 DBconnect();

 $ret = mysqli_query($gDBc, $annotations_query);
 if (!$ret || !DBok()) {
    DBerror($annotations_query);
    $ret = false;
  echo ' nothing in db'. $gUserid ;
 }
 
 $row = mysqli_fetch_assoc($ret);

 $draft_status = printStatus($row["draft"]);
 

 echo '<div class="metaText"> <h3>Owner</h3> <p> '.$row["creator_user_id"].' </p>
 <hr>
	<h3> State</h3><p> '.$draft_status.' </p>
<hr>
	<h3> Created </h3><p> '.$row["created"].' </p>
<hr>
	<h3> Last modified </h3><p> '.$row["modified"].' </p>
<hr>
	<h3> Modified by </h3><p>'.$row["modifier_user_id"].' </p>
<hr>
	<h3> Permissions </h3><p> '.$row["creator_user_id"].' </p>
		
	
 ';
echo '</div>'; 
}


function printGroupMeta ( $groupid ) {
	global $gDBc;
	$annotations_query = 'select creator_user_id, created, modified   from folders where folder_id = '. ''.$groupid.'';

	 DBconnect();

	 $ret = mysqli_query($gDBc, $annotations_query);
	 if (!$ret || !DBok()) {
	    DBerror($annotations_query);
	    $ret = false;
	  echo ' nothing in db'. $gUserid ;
	 }
 
	 $row = mysqli_fetch_assoc($ret);

//.$row["modifier_user_id"].' </p>
//.$row["creator_user_id"].' </p>

 echo '<div class="metaText"> <h3>Owner</h3> <p> '.$row["creator_user_id"].' </p>
 <hr>
        <h3> Project Created </h3><p> '.$row["created"].' </p>
<hr>
        <h3> Last modified </h3><p> '.$row["modified"].' </p>
<hr>
        <h3> Modified by </h3><p> </p> 
<hr>
        <h3> Permissions </h3><p> </p> 
                
        
 ';
echo '</div>'; 



}





?>

