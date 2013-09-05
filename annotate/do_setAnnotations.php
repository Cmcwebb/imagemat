<?php

$gPHPscript = __FILE__;
$dir = dirname(__FILE__);
require_once($dir . '/../include/boilerplate.php');

$setAnnotations = getpost('setAnnotations');

if (isset($setAnnotations)) {
  $_SESSION['imageMAT_setAnnotations'] = json_decode($setAnnotations);
  // echo json_encode($_SESSION['imageMAT_setAnnotations']);
  return;
} 
echo 'failed';
?>
