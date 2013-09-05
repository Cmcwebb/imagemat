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



 global $gDBc;
 global $gUserid;



 echo '<html> <head> </head> <body> ';

 if (mustlogon()) {  return; }

 DBconnect();
 $mode = 'x';

 if (isset($_REQUEST['id'])){
	$annotation_id = $_REQUEST['id'];
 }else{
	 goto close1;
 }


 if ($mode == 'x') {
    $delete_query =  'update annotations   set annotation_deleted = 1  where annotation_id      = ' . $annotation_id;

   $ret = DBquery($delete_query);

    if (!$ret) {
          goto close1;
    }
    echo '<h3>Annotation ', htmlspecialchars($annotation_id), ' deleted</h3>';
    $mode = '';
       // goto proceed;
 }
 close1:
 DBcommit();
 DBclose();


 echo '</body> </html>';

