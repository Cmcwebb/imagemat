<!DOCTYPE HTML>
<?php

$gPHPscript = __FILE__;
$dir = dirname(__FILE__);
require_once($dir . '/../include/boilerplate.php');
require_once($dir . '/../include/db.php');

if (mustlogon()) {
  /* Should never see this page if not logged on but still */
  return;
}

htmlHeader('Convert fulltext to plaintext');

srcStylesheet( '../css/style.css' );
echo '
</head>
<body >
';

if (!DBconnect()) {
  goto done;
}

$query = 
'select annotation_id, fcontent
   from fulltexts';

$ret = DBquery($query);
if (!$ret) {
  goto close;
}

echo '
<table>';
while ($row = DBfetch($ret)) {
  $annotation_id = $row['annotation_id'];
  $fcontent      = $row['fcontent'];
  $plain         = plainText($fcontent);
  echo '
<tr><td>', $annotation_id, '</td><td>', htmlspecialchars($fcontent), '</td><td>', htmlspecialchars($plain), '</td></tr>';
}
echo '
</table>';

enterJavascript();
echo 'alert("Update fulltexts")';
exitJavascript();

$query = 
'select annotation_id, fcontent
   from fulltexts';

$ret = DBquery($query);
if (!$ret) {
  goto close;
}

while ($row = DBfetch($ret)) {
  $annotation_id = $row['annotation_id'];
  $fcontent      = $row['fcontent'];
  $plain         = plainText($fcontent);

  $query1 =
'update fulltexts
    set fcontent = ' . DBstring($plain) . '
  where annotation_id = ' . DBnumber($annotation_id);

  $ret1 = DBquery($query1);
  if (!$ret1) {
	goto close;
  }
  echo '
<p>Done fulltext ', $annotation_id;
}

$query = 
'select version, annotation_id, fcontent
   from fulltexts_history';

$ret = DBquery($query);
if (!$ret) {
  goto close;
}

echo '
<table>';
while ($row = DBfetch($ret)) {
  $annotation_id = $row['annotation_id'];
  $version       = $row['version'];
  $fcontent      = $row['fcontent'];
  $plain         = plainText($fcontent);
  echo '
<tr><td>', $annotation_id, '/', $version, '</td><td>', htmlspecialchars($fcontent), '</td><td>', htmlspecialchars($plain), '</td></tr>';
}
echo '
</table>';

enterJavascript();
echo 'alert("Update fulltexts_history")';
exitJavascript();

$query = 
'select version, annotation_id, fcontent
   from fulltexts_history';

$ret = DBquery($query);
if (!$ret) {
  goto close;
}

while ($row = DBfetch($ret)) {
  $annotation_id = $row['annotation_id'];
  $fcontent      = $row['fcontent'];
  $version       = $row['version'];
  $plain         = plainText($fcontent);

  $query1 =
'update fulltexts_history
    set fcontent = ' . DBstring($plain) . '
  where annotation_id = ' . DBnumber($annotation_id) . '
    and version       = ' . DBnumber($version);

  $ret1 = DBquery($query1);
  if (!$ret1) {
	goto close;
  }
  echo '
<p>Done fulltexts_history ', $annotation_id, '/', $version;
}

$query = 
'select folder_id, fdescription
   from folderfulltexts';

$ret = DBquery($query);
if (!$ret) {
  goto close;
}

echo '
<table>';
while ($row = DBfetch($ret)) {
  $folder_id = $row['folder_id'];
  $fdescription = $row['fdescription'];
  $plain         = plainText($fdescription);
  echo '
<tr><td>', $folder_id, '</td><td>', htmlspecialchars($fdescription), '</td><td>', htmlspecialchars($plain), '</td></tr>';
}
echo '
</table>';

enterJavascript();
echo 'alert("Update folderfulltexts")';
exitJavascript();

$query = 
'select folder_id, fdescription
   from folderfulltexts';

$ret = DBquery($query);
if (!$ret) {
  goto close;
}

while ($row = DBfetch($ret)) {
  $folder_id = $row['folder_id'];
  $fdescription = $row['fdescription'];
  $plain         = plainText($fdescription);

  $query1 =
'update folderfulltexts
    set fdescription = ' . DBstring($plain) . '
  where folder_id = ' . DBnumber($folder_id);

  $ret1 = DBquery($query1);
  if (!$ret1) {
	goto close;
  }
  echo '
<p>Done folderfulltexts ', $folder_id;
}

echo '
<p>Finished';

close:
DBclose();
done:
bodyFooterFilename();
?>
</body>
</html>
