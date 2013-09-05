<?php
/* PHP json_encode Alternative
   by Mike on Jan.25, 2010, under Web Development
   http://www.mike-griffiths.co.uk/php-json_encode-alternative

   Use this at present to avoid double quoting field names
   TODO: Perhaps improve php json_encode function
 */

function my_json_encode($a=false)
{
  // Some basic debugging to ensure we have something returned
  if (is_null($a)) return 'null';
  if ($a === false) return 'false';
  if ($a === true) return 'true';
  if (is_scalar($a)) {
	if (is_float($a)) {
	  // Always use '.' for floats.
	  return strval($a);
 	}

	if (is_string($a)) {
	  static $jsonSpecial = 
        array('\\',   '/',   '\n',  '\t',  '\r',  '\b',  '\f',  '"');
	  static $jsonReplaces = 
        array('\\\\', '\\/', '\\n', '\\t', '\\r', '\\b', '\\f', '\"');
  	  return '"' . str_replace($jsonSpecial, $jsonReplaces, $a) . '"';
	}
	return $a;
  }
  $result = array();
  if (is_array($a)) {
	foreach ($a as $v) {
      $result[] = my_json_encode($v);
    }
	return '[' . join(',', $result) . ']';
  }
  foreach ($a as $k => $v) {
    $result[] = $k . ':' . my_json_encode($v);
  }
  return '{' . join(',', $result) . '}';
}
?> 
