<?php

$dir = dirname(__FILE__);
require_once($dir . '/../include/boilerplate.php');
$gPHPscript = __FILE__;
require_once($dir . '/../include/db.php');

$schema = getget('schema');
if (livesystem()) {
  $server = 'imagemat';
  if (!$schema) {
	$schema = 'imagemat';
  }
} else {
  $server = 'dev';
  if (!$schema) {
    $schema = 'mat';
} }

echo HtmlHeader('Schema ' . htmlspecialchars($schema) . ' tables on ' . $server);
echo '
</head>
<body>';

if (!DBconnect()) {
  goto done;
}

echo '
<p>
<a href=\'http://mat.uwaterloo.ca/mediawiki/index.php/ImageMAT%20Database%20Design\' target=_top>mediawiki</a>';
echo '
<h3>' . htmlspecialchars($schema) . ' tables</h3>';

$query = 
'select table_name, table_schema
  from information_schema.tables
 where table_schema = ' . DBstring($schema);

$ret = DBquery($query);
if ($ret) {
  for ($cnt = 0; $row = DBfetch($ret); ++$cnt) {
    foreach ($row as $colname => $value) {
      $$colname = $value;
    }
    echo '
<br><a href=\'http://mat.uwaterloo.ca/', $server, '/info/desc.php?table="'
 . htmlspecialchars($table_name)   . '"\' target=_top>'
 . htmlspecialchars($table_schema) . '.'
 . htmlspecialchars($table_name)   . '</a>'
;
  }

  echo '
<p>' . $cnt . ' tables';
}
DBclose();
done:
?>
</body>
</html>
