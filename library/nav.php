<!DOCTYPE HTML>
<?php
$gPHPscript = __FILE__;
$dir = dirname(__FILE__);
require_once($dir . '/../include/boilerplate.php');
require_once($dir . '/../include/util.php');
require_once($dir . '/../include/db.php');
require_once($dir . '/../include/simple_view.php');
require_once($dir . '/../include/annotations.php');
//require_once($dir . '/../register/extractinfo.php');
//require_once($dir . '/../register/processMods.php');

if (mustlogon()) {
  return;
}

if (isset($_SESSION['imageMAT_user_id'])) {
  $gUserid = $_SESSION['imageMAT_user_id'];
}
 if (isset($_SESSION['imageMAT_firstname'])){
	$gfirst = $_SESSION['imageMAT_firstname'];
 }
 srcStylesheet(
//   '../css/style.css',
//  '../css/alert.css',
  '../css/library.css'
//  '../css/tbox.css'
);
srcJavascript('../js/alert.js'
//            , '../js/newlib.js'                       
); 

?>
<?php enterJavascript(); ?> 

function loadelems( vals, template ){
   parent.mainFrame.location.href = 'printMain.php?id='+vals+'\&template='+template; <!-- showSimpleView(787, null, "N")' ?>' ;-->
   parent.metaFrame.location.href = 'printMeta.php?id='+vals+'\&annotation=1';

}

function loadProject( vals ) {
   parent.mainFrame.location.href = 'printProject.php?id='+vals;
   parent.metaFrame.location.href = 'printMeta.php?id='+vals+'\&annotation=0';

}


<?php exitJavascript(); ?>

<?php 
 $user_folder_id ;

 echo'<div class="navText"><h3> Hello ' .$gfirst ;
 echo' </h3></div>'; 
 
 $annotations_query = 
'select * from annotations where creator_user_id = '. '"'.$gUserid.'" order by created desc ';
 DBconnect();

 $annotationsretrieved = mysqli_query($gDBc, $annotations_query);
 if (!$annotationsretrieved || !DBok()) {
    DBerror($annotations_query);
    $annotationsretrieved = false;
  echo ' nothing in db'. $gUserid ;
 }

 $count=6;

 echo ' <div class="navHead"> 
 <hr>
 <h2><a href="../annotate/annotate.php" target="_top"><img src="../images/document-icon.png">&nbsp;Create Annotation</a></h2>'; 

 $projects_query = 'select * from folders where creator_user_id = '. '"'.$gUserid.'"';
 DBconnect();

 $ret = mysqli_query($gDBc, $projects_query);
 if (!$ret || !DBok()) {
    DBerror($projects_query);
    $ret = false;
  echo 'Nothing in db '. $gUserid ;
 }

$parent_id_query = 'select folder_id from folders where creator_user_id = '.DBstring($gUserid).'and parent_folder_id = 1';

$folder_id_ret = mysqli_query($gDBc, $parent_id_query);
 if (!$folder_id_ret || !DBok()) {
    DBerror($parent_id_query);
    $folder_id_ret = false;
  echo 'Nothing in db '. $gUserid ;
 }

 while (($row = mysqli_fetch_assoc($folder_id_ret))) {
    $user_folder_id = $row["folder_id"];
 }

 $_SESSION['parent_id'] = $user_folder_id ;


 while (($row = mysqli_fetch_assoc($ret)) && ($count > 0)) {

	if ( $row["parent_folder_id"] == 1){
		
//		 $user_folder_id = $row["folder_id"];
                // echo' <p> '.$row["folder_id"].' </p>';
		echo '<div class="navHead">

		<h2><a href="../project/projectCreate.php" target="_top"><img src="../images/folder-icon.png">&nbsp;Create Project</a></h2>';
		
		echo '<div class="navText">';	
		
		echo '<p> <a href="../project/unpublished.php" target="_top">Unpublished Annotations </a> <br>';
		echo '<h2> Projects </h2>';	
	}else{
		echo '<p><a href="#" onclick="loadProject('.$row["folder_id"].')">'. htmlspecialchars($row["name"]).'</a></p>';
	     	//   echo '<br> <a href="#" onclick="loadProject('.$row.' )" > -> '.$row[2].'</a>';       
	}
 }

 echo '</div> <br> ';
 echo '</div> <br> ';
 echo '<div class="navText">
<hr>
 <h2>Annotations</h2>'; 
// This is annotation listing section

 while (($row = mysqli_fetch_row($annotationsretrieved)) && ($count > 0)) {
	//$templateInfo = processinfo( $row );
        if ($row[12] != 1) {
	 echo '<p><a id="ann" href="#" onclick="loadelems(\''.$row[0].'\''.','.'\''.$row[2].'\')" >'.htmlspecialchars($row[3]).'</a></p>';
	}
 }
 echo '</div>'; 
 echo '<br /> ';
 printf("<p id=\"annotatedata\"> </p>");


DBclose();

?>
