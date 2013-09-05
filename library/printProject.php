<!DOCTYPE HTML>
<?php 
$gPHPscript = __FILE__;
$dir = dirname(__FILE__);

require_once($dir . '/../include/boilerplate.php');
require_once($dir . '/../include/util.php');
require_once($dir . '/../include/db.php');
require_once($dir . '/../include/simple_view.php');
require_once($dir . '/../include/annotations.php');
require_once($dir . '/../include/urls.php');

if(!isset($_SESSION)) {
  session_start();
}

srcStylesheet(
//   '../css/style.css',
//  '../css/alert.css',
  '../css/library.css'
 //  '../css/tbox.css'
);

srcJavascript('../js/alert.js'
	      ,'../js/util.js'
	      ,'../js/annotate.js'
	      ,'../js/enterkey.js'
	      ,'../js/fullwidth.js'
	      ,'../js/edit.js'
  	      ,'../js/images.js'
	      ,'../../tools/ckeditor/ckeditor.js'
//            , '../js/newlib.js'
);

?>

<?php enterJavascript(); ?>

function loadAnnotation( vals, template ){
   parent.mainFrame.location.href = 'printMain.php?id='+vals+'\&template='+template; 
   parent.metaFrame.location.href = 'printMeta.php?id='+vals+'\&annotation=1';  
}

<!--
function loadMarkup(){ 

  var load = document.getElementById('load_left');
  if (!load) {
    alert("Missing something !");
  } else {
    load.submit();
  }

}

function clickEdit( annId )
{
  var form = document.getElementById('load_left');
  
  form.action = '../annotate/annotate.php?annotation_id='+annId;
  form.target = '_top';
  form.submit();
}
-->

<?php exitJavascript(); ?>

<?php
$gUserid = null;
if (isset($_SESSION['imageMAT_user_id'])) {
  $gUserid = $_SESSION['imageMAT_user_id'];
}

if (mustlogon()) {
  return;
}

/*var_dump($_GET);
*/
if (!isset($_REQUEST['id'])) {
	goto close;
}

printInfo($_GET["id"] );

function listAnnotations ( $group_id  ){
	
	global $gDBc;
        global $gUserid;
	global $signature_id ; 
	
        if (mustlogon()) {
	   	
          return;
        }

	// get annotations saved in this project
	// we get annotation id & title
	$annotation_set_query = 'select annotation_id, title, template_code  from annotations where
		annotation_id in (select annotation_id from foldersannotations where 
		folder_id='.$group_id.')'; 	
        DBconnect();

        $annotation_ids = mysqli_query($gDBc, $annotation_set_query);
	$annotations_list = '' ;

        // check for db being ok 
        if (!DBok()){
                $translate_info_retrieved = false;
                echo 'Error in fetching data ...'. $gUserid ;
        }

	// we only care about those that have 
	// atleast one annotation/. 
	// if not, just return 

	if ($annotation_ids->num_rows == 0) {
		//project with no annotations 
		$signature_id = NULL;
		return NULL ; 
	}else{

		while ($ann_info = mysqli_fetch_assoc($annotation_ids)) {
		
			$annotations_list .= '<p><a href="#" onclick="loadAnnotation(\''.$ann_info["annotation_id"].'\''.','.'\''.$ann_info["template_code"].'\')">'. $ann_info["title"].' </a></p>';
	
			$signature_id = $ann_info["annotation_id"] ;
		}	
 	}	

 return ($annotations_list);
}

function getTranslate ( $annotation_id, $version ){

	global $gDBc;
	global $gUserid;
	if (mustlogon()) {
	  return;
	}

	$template = $_REQUEST["template"];
	$translate_query = 'select * from translates where annotation_id= '.$annotation_id.' or was_annotation_id='.$annotation_id.'';
	DBconnect();
	
	$translate_data_retrieved = mysqli_query($gDBc, $translate_query);
	
	// check for db being ok 
	if (!DBok()){
		$translate_info_retrieved = false;
        	echo 'Error in fetching data ...'. $gUserid ;
	}
	// number of rows retrieved== number of translations
	$num_rows = $translate_data_retrieved->num_rows;

	if ($num_rows == 0 ){
		return ; 
	}

	/* print  all other languages */

	echo '<blockquote>'; 
	echo ' ';
	while ($language = mysqli_fetch_assoc($translate_data_retrieved)) {

		// $annotation_languages = mysqli_fetch_assoc($language) ;
		// echo ' '.$language["language_code"].' ' ;
		   
		if ($language["was_annotation_id"] == $annotation_id ){
			echo '<a href="#" onclick="loadTranslation(\''.$language["annotation_id"].'\''.','.'\''.$template.'\')" >'. $language["language_code"].'</a> ' ;			
		}else{
			echo ' <a href="#" onclick="loadTranslation(\''.$language["was_annotation_id"].'\''.','.'\''.$template.'\')" > '.$language["was_language_code"].'</a> ';
		}

	}
	echo '</blockquote>';
	
 return; //$annotation_languages);
}


function printInfo( $project_id ) {

 global $gDBc;
 global $gUserid;
 
// $project_id = 36319;
 global $signature_id; 

 if (mustlogon()) {  return; }

  $projecttable = 'folders';
  $projectannotations = 'foldersannotations';

  $info_query ='select name, description from  '.$projecttable.' where folder_id = '.$project_id.'';
  DBconnect();

  $info_retrieved = mysqli_query($gDBc, $info_query);
  if (!$info_retrieved || !DBok()) {
    DBerror($info_query);
    $info_retrieved = false;
   echo ' nothing in db'. $gUserid ;
  }

  // populate annotation data
  $annotation_data = listAnnotations( $project_id );
  
 // image part 
 /* Signature-id:
		For starters, we are just picking the last annotation id as signature id 
   for a given project. 
	signature-id would be set in listAnnotations() 
 */
 if ($signature_id == NULL ){

 }else{

  $image_query = 'select url from urls where url_id in ( select image_url_id from annotationsofurls where annotation_id='.$signature_id.')';
       
 $image_info = mysqli_query($gDBc, $image_query);
 
 if (!$image_info || !DBok()) {
	    DBerror($image_query);
	    $image_info = false;
	   echo ' nothing in db'. $gUserid ;
  }

 $image_row = mysqli_fetch_row($image_info);
 $http_pholder = '/^http/';
 
/* echo '<html> <head> </head> <body><br> <br> <br>  <br> ';// onload="loadMarkup()" > ';
  
	if (preg_match($http_pholder,$image_row[0] )) {
	        echo '<div class="projectImage"><img src="'.$image_row[0].'" width="80%" ></div>'; 
	}else{
	        echo '<div class="projectImage"><img src="http:'.$image_row[0].'" width="80%" ></div>'; 
	}
  }
*/

 // fetch and populate project meta
  $info_row = mysqli_fetch_assoc($info_retrieved) ;

if ( (!isset($info_row["name"])) && (!isset($info_row["description"]))) {
  //goto close; 

  }else{
	echo '<div class="mainProject">';
  
?>
 <form id=form name=form action="../project/maintainProject.php" method="post" target="_top">
 <input type="hidden" name="current_folder_id" value="<?php echo $project_id ?>">
 <input type ="hidden" name="current_folder_title" value = "<?php echo htmlspecialchars($info_row["name"]) ?>">
 <input type ="hidden" name="folderdesc" value = "<?php echo htmlspecialchars($info_row["description"]) ?>">

 <input type="submit" name ="update" value="Modify this Project" align="right" >
</form>
<br />
<br />
<br />

<?php
        if (preg_match($http_pholder,$image_row[0] )) {
                echo '<div class="projectImage"><img src="'.$image_row[0].'" width="80%" ></div>';
        }else{
                echo '<div class="projectImage"><img src="http:'.$image_row[0].'" width="80%" ></div>';
        }
  }

echo '<div class="projectText">';
 echo ' <h3>Project Title:</h3> <p>'.htmlspecialchars($info_row["name"]).'<p>
        <h3>Project Description:</h3> <p>'.htmlspecialchars($info_row["description"]).'</p> 
     ';
 
 echo '<h3>Associated Annotations:</h3>';

	if ($annotation_data == NULL ){

	}else{
		echo $annotation_data;	
	}
 echo '</div></div>';

 echo '<br />';
 echo '<br />';
 echo '<br />';
 echo '<br />';
 echo '<br />';
?>

<!--
<form id=form name=form action="../project/maintainProject.php" method="post" target="_top">
 <input type="hidden" name="current_folder_id" value="<?php echo $project_id ?>">
 <input type ="hidden" name="current_folder_title" value = "<?php echo htmlspecialchars($info_row["name"]) ?>">
 <input type ="hidden" name="folderdesc" value = "<?php echo htmlspecialchars($info_row["description"]) ?>">

 <input type="submit" name ="update" value="Maintain this Project" >
</form>

-->

<?php
 }
}

close:
echo '</body>
 </html> '; 

?>

