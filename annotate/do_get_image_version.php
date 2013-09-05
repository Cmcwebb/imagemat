<?php

$gPHPscript = __FILE__;
$dir = dirname(__FILE__);
require_once($dir . '/../include/boilerplate.php');

if ($gUserid == null) {
  /* This can happen if the frame is left idle a long time */
  return 'You are not logged on';
}

$markup_id = getpost('markup_id');
if (!isset($markup_id)) {
  echo 'Missing id';
  return;
}
$version = getpost('version');
if (!isset($version)) {
  echo 'Missing version';
  return;
}
  
require_once($dir . '/../include/db.php');

if (!DBconnect()) {
  return;
}

// Ugly race condition.. so make atomic
// markuplayers might get updated after reading markups

if (!DBatomic()) {
  goto close;
}

$query =
'select markup_id, version, url as html_url, natural_width, natural_height, title, description, modified, archived, history
  from annotationsofurls_all a0 left join urls u1
    on a0.html_url_id = u1.url_id
 where a0.markup_id = ' . DBnumber($markup_id) . '
   and a0.version   = ' . DBnumber($version);

$ret = DBquery($query);
if (!$ret) {
  goto close;
}
$row = DBfetch($ret);

if (!$row) {
  echo '{}';
  goto close;
}

$history = ($row['history'] != 0);
if ($history) {
  $query = 
'select layer, title, description, svg
  from markuplayers_history
 where markup_id = ' . DBnumber($markup_id) . '
   and version   = ' . DBnumber($version) . '
 order by layer';
} else {
  $query = 
'select layer, title, description, svg
  from markuplayers
 where markup_id = ' . DBnumber($markup_id) . '
 order by layer';
}
$ret1 = DBquery($query);
if (!$ret1) {
  goto close;
}
echo '{ "markup_id":', $markup_id, ',
  "version":', $version;
if ($history) {
	echo ',
  "history":true';
}
if (isset($row['html_url'])) {
  echo ',
  "html_url":', json_encode($row['html_url']);
}
if (isset($row['natural_width'])) {
  echo ',
  "naturalWidth":', $row['natural_width'];
}
if (isset($row['natural_height'])) {
  echo ',
  "naturalHeight":', $row['natural_height'];
}
if (isset($row['title'])) {
  echo ',
  "title":', json_encode($row['title']);
}
if (isset($row['description'])) {
  echo ',
  "description":', json_encode($row['description']);
}
if (isset($row['modified'])) {
  echo ',
  "modified":', json_encode($row['modified']);
}
if (isset($row['archived'])) {
  echo ',
  "archived":', json_encode($row['archived']);
}

echo ',
  "markups":
  [';
for ($connector1 = ''; $row = DBfetch($ret1); $connector1 = ',
   ') {
  echo $connector1, '{ "layer":', $row['layer'];
  if (isset($row['title'])) {
    echo ',
     "title":', json_encode($row['title']);
  }
  if (isset($row['description'])) {
    echo ',
     "description":', json_encode($row['description']);
  }
  if (isset($row['svg'])) {
    echo ',
     "svg":', json_encode($row['svg']);
  }
  echo '
   }';
}
echo '
  ]
}';

close:
DBclose();
?>
