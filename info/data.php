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
$table = getget('table');

echo HtmlHeader(htmlspecialchars($schema . '.' . $table) . ' data on ' . $server);
echo '
</head>
<body>';

if (!DBconnect()) {
  goto done;
}

$maxcols = getget('maxcols');
if (!$maxcols) {
  $maxcols = 10;
}

echo '
<a href=\'http://mat.uwaterloo.ca/mediawiki/index.php/SQLTable:' ,
  htmlspecialchars($schema) , ':' ,
  htmlspecialchars($table) , '\' target=_top>mediawiki</a>';
echo '
<br><a href=http://mat.uwaterloo.ca/', $server, '/info/desc.php?table="' .
    htmlspecialchars($table)  . '" target=_top>' .
    htmlspecialchars($schema . '.' . $table) . ' description</a>';

echo '
<br>
<a href=\'http://mat.uwaterloo.ca/', $server, '/info/tables.php\' target=_top>All '
 . htmlspecialchars($schema) . ' tables</a>';

echo '
<h3>' .  htmlspecialchars($schema . '.' . $table) . ' data</h3>';

$query = 
'select *
  from ' . DBnumber($schema) . '.' . DBnumber($table);

require_once($dir . '/../include/tables.php');
$cnt = echo_table($query, $maxcols);

DBclose();

echo '
<p>
' . $cnt . ' records shown' . '
<p>
Note that all dates are expressed in UTC
<br/>
';

echo '
<a href=http://mat.uwaterloo.ca/', $server, '/info/desc.php?table="' .
    htmlspecialchars($table)  . '" target=_top>' .
    htmlspecialchars($schema . '.' . $table) . ' description</a>';

echo '
<br>
<a href=\'http://mat.uwaterloo.ca/', $server, '/info/tables.php\' target=_top>All '
 . htmlspecialchars($schema) . ' tables</a>';

done:
?>
</body>
</html>
