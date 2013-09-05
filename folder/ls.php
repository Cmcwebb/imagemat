<!DOCTYPE HTML>
<?php

$gPHPscript = __FILE__;
$dir = dirname(__FILE__);
require_once($dir . '/../include/boilerplate.php');

if (mustlogon()) {
  return;
}

require_once($dir . '/../include/db.php');

echo HtmlHeader('List directories'), '
</head>
<body>';

if (!DBconnect()) {
  goto done;
}

require_once($dir . '/../include/folders.php');

$folder_id = get_folder_id();
if (!$folder_id) {
  goto close;
}
$getpath = getpath($folder_id);
if (!$getpath) {
  goto close;
}
$parent_folder_id = $getpath['parent_id'];
$path = htmlspecialchars($getpath['path']);

echo '
<br>[', $folder_id, '] ',$path, '$ ls';

ls($parent_folder_id, $folder_id);

close:
DBclose();
done:
?>
</body>
</html>
