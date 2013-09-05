<?php

function recover_tags($row)
{
  if (isset($row['folder_id'])) {
    $query = 
'select name, value
  from foldertags
 where folder_id = ' . DBnumber($row['folder_id']) . '
 order by name, value';
  } else if (isset($row['archive'])) {
    $query =
'select name, value
  from tags_history
 where annotation_id = ' . DBnumber($row['annotation_id']) . '
   and version       = ' . DBnumber($row['version']) . '
 order by name, value';
  } else {
    $query =
'select name, value
  from tags
 where annotation_id = ' . DBnumber($row['annotation_id']) . '
 order by name, value';
  }
  $ret = DBquery($query);
  if (!$ret) {
    return null;
  }
  $terms = array();
  while ($row = DBfetch($ret)) {
    $name  = $row['name'];
	$value = $row['value'];
	if (isset($name)) {
      if (isset($value)) {
		$terms[] = $name . '=' . $value;
      } else {
        $terms[] = $name . '=';
      }
    } else if (isset($value)) {
      $terms[] = $value;
  } }
  if (count($terms) != 0) {
    return implode(',', $terms);
  }
  return null;
}

function explode_tags($tags)
{
  $tags = strtolower($tags);
  $tags = str_replace(';', ',', $tags);
  $ret  = explode(',', $tags);
  $a    = null;

  foreach ($ret as $string) {
   $name = null;
   $value = null;
   $eq = strpos($string, '=');
   if ($eq === false) {
     $value = trim($string);
   } else if ($eq == 0) {
     $value = trim(substr($string, 1));
   } else {
     $name = trim(substr($string, 0, $eq));
     $value = trim(substr($string, $eq+1));
   }
   if ($name == '') {
     $name = null;
   }
   if ($value == '') {
	 if (!isset($name)) {
       continue;
     }
     $value = null;
   }
   if (!isset($a)) {
     $a = array();
   }
   $seen = false;
   foreach ($a as $term) {
     if ($term['n'] == $name && $term['v'] == $value) {
       $seen = true;
       break;
   } }
   if (!$seen) {
     $a[] = array('n' => $name, 'v' => $value);
   }
 }
 return $a;
}

function insert_tags($tags, $id, $key, $table)
{
  $a = explode_tags($tags);
  if (!isset($a)) {
    return;
  }
  $query =
'insert ignore into ' . $table . '(' . $key . ', name, value)
values';

  $seen = false;
  foreach ($a as $term) {
   if ($seen) {
     $query .= ',';
   } else {
     $seen = true;
   }
   $query .= ' (' .  DBnumberC($id) . DBstringC($term['n']) . DBstring($term['v']) . ')';
 }
 if ($seen) {
   $ret = DBquery($query);
 }
}

function update_tags($tags, $id, $key, $table)
{
  $query = 
'delete from ' . $table . '
 where ' . $key . ' = ' . DBnumber($id);
  $ret = DBquery($query);

  if (isset($tags)) {
    insert_tags($tags, $id, $key, $table);
} }

function echo_tags($id, $key, $table)
{
  $query =
'select name,value
  from ' . $table . '
 where ' . $key . ' = ' . DBnumber($id);

  echo '<h3>Tags</h3>';

  echo_table($query);

  $ret = recover_tags($id, null, $key, $table);
  if (isset($ret)) {
    echo '<h3>Formatted tags</h3>', htmlspecialchars($ret);
  }
}
?>
