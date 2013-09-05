<!DOCTYPE HTML>
<?php

$gPHPscript = __FILE__;
$dir = dirname(__FILE__);
require_once($dir . '/../include/boilerplate.php');

if (mustlogon()) {
  /* Should never see this page if not logged on but still */
  return;
}

$annotation_id = getparameter('annotation_id');

htmlHeader('Choose annotation to update');

require_once($dir . '/../include/db.php');

srcStylesheet(
  '../css/style.css',
  '../css/sortable.css'
);
srcJavascript('../js/sortable.js');
enterJavascript();
?>
function getAnnotationId()

{
  return <?php echo (isset($annotation_id) ? $annotation_id : 'null'); ?>;
}
<?php exitJavascript(); ?>
</head>
<body>
<?php
if (!DBconnect()) {
  goto done;
}
$setAnnotations = getSetAnnotations();

if ($setAnnotations == null) {
  goto close;
}
$query =
'select annotation_id, title
  from annotations
 where annotation_id in (' . implode(',',$setAnnotations) . ')
   and annotation_deleted is null';

$ret = DBquery($query);
if (!$ret) {
  goto close;
}
echo '
<table id="annotationtable" class=sortable cellpadding="0" cellspacing="4" width="100%" border=1>
<tr><th class=unsortable></th><th class=startsort></th><th>Title</th></tr>';

$href = getparameter('href');
if (!isset($annotation_id)) {
  $annotation_id = '';
}
while ($row = DBfetch($ret)) {
  $id = $row['annotation_id'];
  $arrow = '';
  if ($id == $annotation_id) {
    $arrow = '<font color=blue>&#x21E8;</font>';
  }
  echo '
<tr>
<td>', $arrow, '</td>
<td align=right>', $id,'</td>
<td>
<a href="', $href, 'annotation_id=',$id,'" target=_self>',
htmlspecialchars($row['title']), '</a>
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
