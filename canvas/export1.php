<?php
$gPHPscript = __FILE__;
$dir = dirname(__FILE__);
require_once($dir . '/../include/boilerplate.php');

$export = getpost('export');
if (isset($export)) {
  header('Content-Type: application/octet-stream');
  header('Content-Disposition: attachment; filename="export"');
  header('Content-Transfer-Encoding: binary');
  header('Content-Length: ' . strlen($export));
  header('Expires: 0');
  header('Pragma: no-cache');
  echo $export;
}
?>
