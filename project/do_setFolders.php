<?php

$gPHPscript = __FILE__;
$dir = dirname(__FILE__);
require_once($dir . '/../include/boilerplate.php');

$setFolders = getpost('setFolders');

if (isset($setFolders)) {
  $_SESSION['imageMAT_setFolders'] = json_decode($setFolders);
  // echo json_encode($_SESSION['imageMAT_setFolders']);
  return;
} 
echo 'failed';
?>
