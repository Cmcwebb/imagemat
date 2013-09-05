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

function loadTranslation( vals, template ){
   parent.mainFrame.location.href = 'printMain.php?id='+vals+'\&template='+template; 
   parent.metaFrame.location.href = 'printMeta.php?id='+vals+'\&annotation=1';  
}

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
  var form = document.getElementById('form');
  
  form.action = '../annotate/annotate.php?annotation_id='+annId;
  form.target = '_top';
  form.submit();
}

function clickDelete( annId )
{
   parent.mainFrame.location.href = 'deleteAnn.php?id='+annId;
   parent.metaFrame.location.href = 'printMeta.php?id='+vals+'\&annotation=1'
}


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

printInfo($_GET["id"], $_GET["template"]);



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
	
	// check to see if db is ok 
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
	//removed <blockquote> around translations - but can't figure out style
 return; //$annotation_languages);
}

function printTemplate ( $annotation_id, $template_table ){

	
 global $gDBc;
 global $gUserid;

 if (mustlogon()) {  return; }
 $template_info_query  = 'select * from '.$template_table.' where annotation_id='.$annotation_id.'';
  DBconnect();
  $template_data_retrieved = mysqli_query($gDBc, $template_info_query);
 // check for db being ok 
 if (!DBok()){
      $translate_info_retrieved = false;
      echo 'Error in fetching data ...'. $gUserid ;
 }
   $template_data = '';
  
  if ($result = mysqli_query( $gDBc, $template_info_query)) {
	
   /* Get field information for all columns */
    $fieldinfo = mysqli_fetch_fields($result);
    $template_info =  mysqli_fetch_assoc($template_data_retrieved);

//   echo '<div class="mainAnno">';
    foreach ($fieldinfo as $val) {
	if ( ( $val->name != "annotation_id") &&  ($template_info["$val->name"] != NULL  )){
//		echo '<h3>'.$val->name.'</h3> '.$template_info["$val->name"].'<br>';
		$template_data .= '<h3>'.$val->name.'</h3> '.$template_info["$val->name"].'<br>';
	}
  }
 // echo '</div>';

 }else{
	return NULL; 
 }
 $result->close();
 return ($template_data) ;
}


function printInfo( $annotation_id, $template ) {

 global $gDBc;
 global $gUserid;

 if (mustlogon()) {  return; }
 
 $table = 'annotations';

 if (isset($template)){
	
 }else{
	 $template_table = 'template_'.$template.'s';
 }

 $info_query ='select title,version, tags, template_code,content, annotation_deleted from '.$table.' where annotation_id = '.$annotation_id.'';
 DBconnect();

 $info_retrieved = mysqli_query($gDBc, $info_query);
 if (!$info_retrieved || !DBok()) {
    DBerror($info_query);
    $info_retrieved = false;
   echo ' nothing in db'. $gUserid ;
  }

  // fetch and populate with 
  $info_row = mysqli_fetch_assoc($info_retrieved) ;

  $version = $info_row["version"];

   // check for annotation history    
   //  check for possible one to one 
   // language association   
   $annotation_languages  = getTranslate( $annotation_id, $version );



 //image 
 $image_query = 'select url from urls where url_id in ( select image_url_id from annotationsofurls where annotation_id='.$annotation_id.')';
	
 $image_info = mysqli_query($gDBc, $image_query);
 
 if (!$image_info || !DBok()) {
    DBerror($image_query);
    $image_info = false;
   echo ' nothing in db'. $gUserid ;
 }

 $image_row = mysqli_fetch_row($image_info);
 $http_pholder = '/^http/';
?>

<?php
	echo '<div class="mainHeader">
	
 <input type="image" src="../images/pencil-icon.png" alt="Edit Annotation" title="Edit Annotation" onclick="clickEdit('.$annotation_id.');"/> ';
 
if ( $info_row["annotation_deleted"] == 1){

}else{
echo '
 <input id=mode type=hidden name=mode value=y />
&nbsp; 
 <input type="image" src="../images/trash-icon.png" alt="Delete Annotation" title="Delete Annotation" onclick="clickDelete('.$annotation_id.')"/> 
 <hr />
	</div>';
?>

<!-- This marks the beginning of the image/text frame -->

<?php 
	echo '<html> <head> </head> <body>';// onload="loadMarkup()" > ';
  
if (preg_match($http_pholder,$image_row[0] )) {
 	echo '<div class="mainImage"><img src="'.$image_row[0].'" width="80%" ></div>'; 
}else{
 	echo '<div class="mainImage"><img src="http:'.$image_row[0].'" width="80%" ></div>'; 
	
}


	
//image side on left
// removed "width="100%" height="100%" from imageArea
// added modify/delete buttons here, which places them above tag/template info - still commented out below
echo '
<div class="imageArea">
<ul id="tabs">

</ul>



';

echo '
<form id=form name=form  method="post"> ';
 hidden('annotation_id');
 hidden('version');
}

echo '
</form>';

echo '</div>'; 

// template info -- specific to this current template  - moved from above echo iframe
 $template_info = '' ;

  if ($template == NULL){
	$template_info = NULL;
  }else{
        $template_table = 'template_'.$template.'s';
        $template_info = printTemplate ( $annotation_id, $template_table );
        echo '<br>';
 }
// Tags and Template - needs to move below image - code moved from below Title
// removed "width="100%" height="100%" from imageArea

echo ' <div class="mainTemplate">


	<h3>Tags:</h3> <p>'.$info_row["tags"].'</p> 

	<h3>Template:</h3>';

	// print template info here  
	if ($template_info  == NULL){

	 }else{
 		echo $template_info ;
	} 
echo '</div>';	
/*
echo'<iframe id="imageFrame" name="imageFrame" class="imageFrame"  height=600 width="80%"  mozallowfullscreen webkitallowfullscreen>  </iframe>';

*/
/* */
echo ' <div class="mainAnno">
		
	<h3>Annotation Title:</h3> <p>'.$info_row["title"].'<p>';

echo '	
	<h3>Text:</h3><p>'.$info_row["content"].' </p>';

echo '</div>';

}

close:
echo '</body>
 </html> '; 

?>

