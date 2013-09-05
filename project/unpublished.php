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
if( (mustlogon()) || (!isset($gUserid)) ){
 return ;
}

htmlHeader('Annotation View');
srcStylesheet(
   '../css/style.css',
  '../css/alert.css',
  '../css/bootstrap.css',
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

enterJavaScript();?>

<?php exitJavascript();
echo '</head> <body link="#C0C0C0" vlink="#808080" alink="#FF0000> ';
bodyHeader();

 global $gDBC; 
 global $gUserid;
 
 echo ' <h2> Unpublished Annotations </h2>';



 $annotations_query = 
'select * from annotations where creator_user_id = '. '"'.$gUserid.'" and draft="S" order by created desc ';
 DBconnect();

 $annotationsretrieved = mysqli_query($gDBc, $annotations_query);
 if (!$annotationsretrieved || !DBok()) {
    DBerror($annotations_query);
    $annotationsretrieved = false;
  echo ' nothing in db'. $gUserid ;
 }
$count = 1;
$annotations_list = '<div class="container"> <div class="row">';

 while (($row = mysqli_fetch_row($annotationsretrieved)) && ($count > 0)) {
        //$templateInfo = processinfo( $row );
        if ($row[12] == 1) {
        }else{
		$annotations_list .='<div class="span3">';
	        $annotations_list .= '<p><a href="#" onclick="loadAnnotation(\''.$row[0].'\''.','.'\''.$row[2].'\')">'. $row[3].' </a></p>';
        	   $image_query = 'select url from urls where url_id in ( select image_url_id from annotationsofurls where annotation_id='.$row[0].')'; 
        
                        $image_info = mysqli_query($gDBc, $image_query);
                        if (!$image_info || !DBok()) {
                            DBerror($image_query);
                            $image_info = false;
                           echo         ' nothing in db'. $gUserid ;
                         }

                         $image_row = mysqli_fetch_row($image_info);
                         $http_pholder = '/^http/';

// echo '<html> <head> </head> <body>';// onload="loadMarkup()" > ';
//$annotations_list.= '<div class="img"><img src="'.$image_row[0].'" width="20%" ></div>';

        if (preg_match($http_pholder,$image_row[0] )) {
                $annotations_list.= '<img src="'.$image_row[0].'" width="90%" >';
        }elseif(isset($image_row)){ 
                $annotations_list.= '<img src="http:'.$image_row[0].'" width="90%" >';
        }
       $annotations_list.='</div> '; 

 //        echo '<p><a id="ann" href="#" onclick="loadelems(\''.$row[0].'\''.','.'\''.$row[2].'\')" >'.$row[3].'</a></p>';
        }


 }

   echo $annotations_list;

   echo '</div> </div>';	


?>


