<!DOCTYPE HTML>
<?php

$gPHPscript = __FILE__;
$dir = dirname(__FILE__);
require_once($dir . '/../include/boilerplate.php');

if (mustlogon()) {
  /* Should never see this page if not logged on but still */
  return;
}

$folder_id = getpost('folder_id');
$url       = getpost('url');
$set       = getpost('set');

if (isset($set)) {
  $_SESSION['imageMAT_setFolders'] = json_decode($set);
} 

htmlHeader('Choose project');

require_once($dir . '/../include/db.php');

srcStylesheet(
  '../css/style.css',
  '../css/sortable.css'
);
srcJavascript('../js/sortable.js');
enterJavascript();
?>
function getFolderId()
{
  return <?php echo (isset($folder_id) ? $folder_id : 'null'); ?>;
}
<?php exitJavascript(); ?>
</head>
<body>
<?php
if (!DBconnect()) {
  goto done;
}
$setFolders = getSetFolders();

if ($setFolders == null) {
  goto close;
}
$query =
'select folder_id, name
  from folders
 where folder_id in (' . implode(',',$setFolders) . ')
 order by folder_id';

$ret = DBquery($query);
if (!$ret) {
  goto close;
}
echo '
<table id="foldertable" class=sortable cellpadding="0" cellspacing="4" width="100%" border=1>
<tr><th class=unsortable></th><th class=startsort></th><th>Name</th></tr>';

if (!isset($folder_id)) {
  $folder_id = '';
}
while ($row = DBfetch($ret)) {
  $id = $row['folder_id'];
  $arrow = '';
  if ($id == $folder_id) {
    $arrow = '<font color=blue>&#x21E8;</font>';
  }
  echo '
<tr>
<td>', $arrow, '</td>
<td align=right>', $id,'</td>
<td>
<a href="',$url, '?folder_id=',$id,'" target=_self>',
htmlspecialchars($row['name']), '</a>
</td>
</tr>';
}
echo '
</table>';

close:
DBclose();
done:
bodyFooterFilename();
?>
</body>
</html>
