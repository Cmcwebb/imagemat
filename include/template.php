<?php
srcStylesheet(
  '../css/iframe.css'
);
function get_template_codes()
{
  return array(
'',
'architecture',
'artifact',
'book',
'ceramic',
'comic',
'graphic',
'manuscript',
'movie',
'painting',
'photograph',
'sculpture');
}

function get_template_names()
{
  return array(
'',
'Architecture',
'Artifacts',
'Books',
'Ceramics',
'Comics',
'Graphic Art',
'Manuscripts',
'Movie Clip',
'Painting',
'Photography',
'Sculpture');
}

function select_template($val)
{
  $templates = get_template_codes();
  $names     = get_template_names();
     
  if (!isset($val)) {
    $val = '';
  }
  echo '
<select name="template_code" onchange="template_change()">';
$cnt = count($templates);
for ($i = 0; $i < $cnt; ++$i) {
  $value = $templates[$i];
  echo '
<option value="', $value, '"';
  if ($val == $value) {
    echo ' selected';
  }
  echo '>', $names[$i], '</option>';
}
  echo '
</select>';
}

function insert_template($annotation_id, $template_code, $template)
{
  $query1 = '';
  $seen   = false;

 require_once('../include/template/' . $template_code . '.php');

 $func = 'return_'.$template_code.'_fields';
 $template_fields  = $func();

//  echo ' code is '.$template_code.'<br> ';
  foreach ($template_fields as $name) { 

    if ($name != 'annotation_id') {
      $query1 .= ',';
 // echo 'Name: '.$name.' -> '. $template[$template_code][$name].' <br>'; 
 
	  if (isset($template[$template_code]) && ($template[$template_code][$name] != ' ')){
	//	    echo 'fields name -> '.$name.'value '. $template[$template_code][$name].' <<<< <br> ';
		  $query1 .= DBstring($template[$template_code][$name]);
 	 	  $seen   = true ;
   	  }else {
		$query1 .= 'null';
	 }	
    }		
 }
/*
  foreach ($template as $name => $value) {
	
    if ($name != 'annotation_id') {
      $query1 .= ',';
      if (isset($value) && $value != '') {
        $query1 .= DBstring($value);
        $seen   = true;
      } else {
        $query1 .= 'null';
  } } }
*/

  if (!$seen) {
    return;
  }

  $query = 
'insert into template_' . $template_code . 's(annotation_id';
  foreach ($template_fields as $name) { // => $value) {
    if ($name != 'annotation_id') {
      $query .= ',' . $name;
  } }

  $query .= ')
values(' . DBnumber($annotation_id) . $query1 . ')';

  $ret = DBquery($query);
  if (!$ret) {
    return $ret;
  }
  return true;
}

function template_form($template_code, $template)
{
  global $dir;

  if (isset($template_code) && $template_code != '') {
    require_once($dir . '/../include/template/' . $template_code . '.php');
    $function = 'template_' . $template_code;
    $function($template);
    return;
  }
}

require_once($dir . '/../include/tables.php');

function echo_template($annotation_id)
{
  $query =
'select template_code
  from annotations
 where annotation_id = ' . DBnumber($annotation_id);

  $ret = DBquery($query);
  if (!$ret) {
    return;
  }

  $row = DBfetch($ret);
  if (!$row) {
    echo 'Annotation ', $annotation_id, ' not found';
    return;
  }
  $template_code = $row['template_code'];
  if (isset($template_code) && $template_code != '') {
    require_once($dir . '/../include/template/' . $template_code . '.php');
    $function = 'echo_template_' . $template_code;
    $function($annotation_id);
  }
}
?>
