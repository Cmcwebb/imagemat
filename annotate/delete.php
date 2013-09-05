<!DOCTYPE HTML>
<?php

$gPHPscript = __FILE__;
$dir = dirname(__FILE__);
require_once($dir . '/../include/boilerplate.php');
require_once($dir . '/../include/util.php');

if (mustlogon()) {
  /* Should never see this page if not logged on but still */
  return;
}

$setAnnotations = getSetAnnotations();
$size           = (isset($setAnnotations) ? count($setAnnotations) : 0);

if ($size == 0) {
  header('Location: ' . dirname($_SERVER['REQUEST_URI']) . '/search.php?updates=delete');
  return;
}

require_once($dir . '/../include/db.php');


htmlHeader('Delete annotations');

srcStylesheet(
  '../css/style.css',
  '../css/iframe.css'
);
srcJavascript(
  '../js/util.js',
  '../js/enterkey.js',
  '../js/fullwidth.js',
  '../js/edit.js',
  '../js/annotate.js',
  '../js/images.js',
  '../../tools/ckeditor/ckeditor.js'
);

enterJavascript();
?>

function setMode(value)
{
  var form = document.getElementById('form');
  var mode = document.getElementById('mode');
  if (mode) {
    mode.value = value;
	form.submit();
  }
}

function clickUnDelete()
{
  setMode('U');
}

function clickDelete()
{
  setMode('X');
}

<?php
exitJavascript();
echo '
</head>
<body" >
';

bodyHeader();

if (!DBconnect()) {
  goto done;
}

$mode = getpost('mode');
if (!isset($mode)) {
  goto show;
}
switch ($mode) {
case 'X':
  $query = 
'update annotations
   set annotation_deleted = 1
 where annotation_id in (' . implode(',', $setAnnotations) . ')
   and annotation_deleted is null';
  break;
case 'U':
  $query = 
'update annotations
   set annotation_deleted = null
 where annotation_id in (' . implode(',', $setAnnotations) . ')
   and annotation_deleted is not null';
  break;
default:
  echo $size, ' annotations not deleted';
  goto close;
}
$ret = DBquery($query);
if (!$ret) {
  goto close;
}
$size1 = DBupdated();
echo '<h3>', $size1, ' annotations ', (($mode == 'X') ? 'deleted' : 'recovered'), '</h3>';

if ($size != $size1) {
  echo ($size - $size1), ' annotations not changed';
}
if ($mode == 'X') {
  unset($_SESSION['imageMAT_setAnnotations']);
}

goto close;

show:

$query =
'select count(*) as cnt, annotation_deleted
  from annotations
 where annotation_id in (' . implode(',', $setAnnotations) . ')
 group by annotation_deleted';

$ret = DBquery($query);
if (!$ret) {
  goto close;
}
$seen_active  = 0;
$seen_deleted = 0;
while ($row = DBfetch($ret)) {
  $cnt = $row['cnt'];
  if (isset($row['annotation_deleted'])) {
    $seen_deleted += $cnt;
  } else {
	$seen_active += $cnt;
} }

echo '
<h3>Deleting annotations</h3>
<p>';

if ($seen_active == 0) {
  if ($seen_deleted == 0) {
    echo 'Selected records no longer exist';
    goto close;
  }
  echo 'Are you sure you wish to recover ', $seen_deleted, ' deleted annotations?';
} else if ($seen_deleted == 0) {
  echo 'Are you sure you wish to delete ', $seen_active, ' annotations?';
} else {
  echo 'Do you wish to delete ', $seen_active, ' annotations or recover ', $seen_deleted, ' deleted annotations?';
}
echo '
<p>
<form id=form method="post" action=delete.php >
<input type="hidden" id="mode" name="mode" value=N />';
if ($seen_deleted != 0) {
  echo '
<input type="button" value="Undelete" onclick="clickUnDelete()" />';
}
if ($seen_active != 0) {
  echo '
<input type="button" value="Delete" onclick="clickDelete()" />';
}
echo '
<input type="submit" value="Cancel" />
</form>';

close:
DBclose();
done:
bodyFooter();
?>
</body>
</html>
