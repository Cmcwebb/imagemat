<?php

$dir = dirname(__FILE__);
require_once($dir . '/../include/boilerplate.php');
$gPHPscript = __FILE__;
require_once($dir . '/../include/db.php');

function constraint_changes()
{
  global $table;
  global $last_constraint;
  global $constraint_catalog, $constraint_schema, $constraint_name;
  global $schema1, $table1, $from, $to;

  if (!isset($last_constraint)) {
    return;
  }
  if (!isset($table1)) {
    echo '<br>';
    if ($constraint_name != 'PRIMARY') {
      echo 'UNIQUE';
    } else {
      echo $constraint_name;
    }
    echo '(' . $from . ')';
    return;
  }
  echo '<br>foreign key (' . $from . ') references ' . 
       '<a href="http://mat.uwaterloo.ca/mediawiki/index.php/SQLTable:' .
       htmlspecialchars($schema1) . ':' . htmlspecialchars($table1) .
       '" target=_top>' . 
       htmlspecialchars($schema1) . '.' . htmlspecialchars($table1) .
       '</a> (' .  $to   . ')';

  $query = 
'select update_rule, delete_rule
  from information_schema.referential_constraints
 where constraint_schema     = ' . DBstring($constraint_schema) . '
   and constraint_name       = ' . DBstring($constraint_name) . '
   and table_name            = ' . DBstring($table) . '
   and referenced_table_name = ' . DBstring($table1);

  $ret = DBquery($query);
  if ($ret) {
    if ($row = DBfetch($ret)) {
      echo '<br>on update ' , $row['update_rule'] ,
            ' on delete ' , $row['delete_rule'];
  } }
} 

function reverse_constraint_changes()
{
  global $table;
  global $last_constraint;
  global $constraint_catalog, $constraint_schema, $constraint_name;
  global $schema1, $table1, $from, $to;

  if (!isset($last_constraint)) {
    return;
  }
  if (!isset($table1)) {
    return;
  }
  echo '<br>' . 
       '<a href="http://mat.uwaterloo.ca/mediawiki/index.php/SQLTable:' .
       htmlspecialchars($schema1) . ':' . 
       htmlspecialchars($table1) . '" target=_top>' . 
       htmlspecialchars($schema1) . '.' . 
       htmlspecialchars($table1) . 
       '</a>(' . $to . ') links to (' . $from . ')';

  $query = 
'select update_rule, delete_rule
  from information_schema.referential_constraints
 where constraint_schema     = ' . DBstring($constraint_schema) . '
   and constraint_name       = ' . DBstring($constraint_name) . '
   and table_name            = ' . DBstring($table1) . '
   and referenced_table_name = ' . DBstring($table);

  $ret = DBquery($query);
  if ($ret) {
    if ($row = DBfetch($ret)) {
      echo '<br>on update ' , $row['update_rule'] , 
            ' on delete ' , $row['delete_rule'];
  } }
} 

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

echo HtmlHeader(htmlspecialchars($schema . '.' . $table) . ' table on ' . $server);
echo '
</head>
<body>';

if (!DBconnect()) {
  goto done;
}


echo '
<p>
<a href=\'http://mat.uwaterloo.ca/mediawiki/index.php/SQLTable:' ,
  htmlspecialchars($schema) , ':' ,
  htmlspecialchars($table) , '\' target=_top>mediawiki</a>';
echo '
<br>
<a href=\'http://mat.uwaterloo.ca/', $server, '/info/data.php?table="' .
  htmlspecialchars($table)  . '"\' target=_top>' .
  htmlspecialchars($schema . '.' . $table) . ' data</a>
';
echo '
<br>
<a href=\'http://mat.uwaterloo.ca/', $server, '/info/tables.php\' target=_top>All '
 . htmlspecialchars($schema) . ' tables</a>';

echo '
<h3>' .  htmlspecialchars($schema . '.' . $table) . ' table</h3>
<p>';

$query = 
'select column_name, column_type, character_set_name, extra, is_nullable,
        column_key, column_default
  from information_schema.columns
 where table_schema = ' . DBstring($schema) . '
   and table_name   = ' . DBstring($table) . '
 order by ordinal_position'
;

$ret = DBquery($query);
if ($ret) {
  echo '
<table>
<tr><th align=left>Column name</th><th align=left>Type</th><th align=left>Key</th><th align=left>Default</th></tr>';

  for ($cnt = 0; $row = DBfetch($ret); ++$cnt) {
    foreach ($row as $colname => $value) {
      switch ($colname) {
      case 'is_nullable':
        if ($value == 'NO') {
          $value = ' not null';
        } else {
          $value = '';
        }
        break;
      case 'column_key':
        switch ($value) {
        case 'PRI':
          $value = 'primary key';
          break;
        case 'UNI':
          $value = 'unique';
          break;
        case 'MUL':
          $value = 'foreign key';
          break;
        }
        break;
      case 'extra':
        if ($value == 'auto_increment') {
          $value = 'auto';
        }
		break;
      }
      $$colname = $value;
    }
    echo '
<tr><td>' . $column_name . '</td><td>' 
 . $column_type .' ' . $character_set_name . ' ' . $extra . ' ' . $is_nullable
 . '</td><td>' . $column_key . '</td><td>' . $column_default . '</td></tr>';
  }
  echo '
</table>';
}

$query = 
'select constraint_catalog, constraint_schema, constraint_name,
        table_catalog, table_schema, table_name, column_name,
        referenced_table_schema, referenced_table_name, referenced_column_name
  from information_schema.key_column_usage
 where table_schema = ' . DBstring($schema) . '
   and table_name   = ' . DBstring($table) . '
 order by constraint_catalog, constraint_schema, constraint_name,
          ordinal_position'
;

$ret = DBquery($query);
if ($ret) {
  $last_constraint = null;
  $from            = '';
  $to              = '';
  $schema1         = '';

  $table1          = null;
  for (; $row = DBfetch($ret); ) {
    $constraint = $row['constraint_catalog'] . '.' .
                  $row['constraint_schema']  . '.' .
                  $row['constraint_name'];

    if ($constraint != $last_constraint) {
      constraint_changes();
      $last_constraint    = $constraint;
      $constraint_catalog = $row['constraint_catalog'];
      $constraint_schema  = $row['constraint_schema'];
      $constraint_name    = $row['constraint_name'];
      $schema1            = $row['referenced_table_schema'];
      $table1             = $row['referenced_table_name'];
      $from               = '';
      $to                 = '';
    }
    if ($from != '') {
      $from .= ', ';
      $to   .= ', ';
    }
    $from .= $row['column_name'];
    $to   .= $row['referenced_column_name'];
  }
  if (isset($last_constraint)) {
    constraint_changes();
  }
}

echo '
<br>';

$query = 
'select constraint_catalog, constraint_schema, constraint_name,
        table_catalog, table_schema, table_name, column_name,
        referenced_table_schema, referenced_table_name, referenced_column_name
  from information_schema.key_column_usage
 where referenced_table_schema = ' . DBstring($schema) . '
   and referenced_table_name   = ' . DBstring($table) . '
 order by constraint_catalog, constraint_schema, constraint_name,
          ordinal_position'
;

$ret = DBquery($query);
if ($ret) {
  $last_constraint = null;
  $from            = '';
  $to              = '';
  $schema1         = '';

  $table1          = null;
  for (; $row = DBfetch($ret); ) {
    $constraint = $row['constraint_catalog'] . '.' .
                  $row['constraint_schema']  . '.' .
                  $row['constraint_name'];

    if ($constraint != $last_constraint) {
      reverse_constraint_changes();
      $last_constraint    = $constraint;
      $constraint_catalog = $row['constraint_catalog'];
      $constraint_schema  = $row['constraint_schema'];
      $constraint_name    = $row['constraint_name'];
      $schema1            = $row['table_schema'];
      $table1             = $row['table_name'];
      $from               = '';
      $to                 = '';
    }
    if ($from != '') {
      $from .= ', ';
      $to   .= ', ';
    }
    $from .= $row['referenced_column_name'];
    $to   .= $row['column_name'];
  }
  if (isset($last_constraint)) {
    reverse_constraint_changes();
  }
}
echo '
<p>';

$query =
'select table_type, engine, version, auto_increment, table_rows,
       create_time, update_time, check_time, create_options, table_comment
  from information_schema.tables
 where table_schema = ' . DBstring($schema) . '
   and table_name   = ' . DBstring($table);

require_once($dir . '/../include/tables.php');

$cnt = echo_table_down($query);

echo '
<p>
<a href=\'http://mat.uwaterloo.ca/', $server, '/info/data.php?table="' .
  htmlspecialchars($table)  . '"\' target=_top>' .
  htmlspecialchars($schema . '.' . $table) . ' data</a>
';
echo '
<br>
<a href=\'http://mat.uwaterloo.ca/', $server, '/info/tables.php\' target=_top>All '
 . htmlspecialchars($schema) . ' tables</a>';

DBclose();
done:
?>
</body>
</html>
