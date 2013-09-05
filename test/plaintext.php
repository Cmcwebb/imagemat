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
<tr><td>', $annotation_id, '</td><td>', htmlspecialchars($fcontent), '</td><td>', $plain, '</td></tr>';
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
