<?php

$gPHPscript = __FILE__;
$dir = dirname(__FILE__);
require_once($dir . '/../include/boilerplate.php');

$setGroups = getpost('setGroups');

if (isset($setGroups)) {
  $_SESSION['imageMAT_setGroups'] = json_decode($setGroups);
  // echo json_encode($_SESSION['imageMAT_setGroups']);
  return;
} 
echo 'failed';
?>
