<!DOCTYPE HTML>
<?php

/* No longer used */

$gPHPscript = __FILE__;
$dir = dirname(__FILE__);
require_once($dir . '/../include/boilerplate.php');

if (mustlogon()) {
  /* This can happen if the frame is left idle a long time */
  return;
}

htmlHeader('Destroy Annotation');

require_once($dir . '/../include/alert.php');
require_once($dir . '/../include/db.php');
require_once($dir . '/../include/template.php');
srcStylesheet(
  '../css/style.css'
);
?>
</head>
<body id="body">
<?php

$annotation_id = getpost('annotation_id');
if (!isset($annotation_id)) {
  javascriptAlert('Destroy failed', 'error.png', 'No annotation_id specified','Error');
  goto done;
}


echo '<h3>Deleting annotation ', htmlspecialchars($annotation_id),'</h3>
<p>';

if (!DBconnect()) {
  goto done;
}

if (!DBatomic()) {
  goto close;
}

/* Don't trust cascading - play it safe */

$records = 0;
$cnt     = 0;
$sql_annotation_id = DBnumber($annotation_id);

$query =
'delete from duplicates
 where annotation_id = ' . $sql_annotation_id . '
    or was_annotation_id = ' . $sql_annotation_id;
$ret = DBquery($query);
if (!$ret) {
  echo '<p class=error>Failed to delete records in duplicates</p>';
} else {
  $updated = DBupdated();
  if ($updated != 0) {
    echo $updated, ' records deleted from table duplicates<br>';
    $records += $updated;
    ++$cnt;
} }

$query =
'delete from translates
 where annotation_id = ' . $sql_annotation_id . '
    or was_annotation_id = ' . $sql_annotation_id;
$ret = DBquery($query);
if (!$ret) {
  echo '<p class=error>Failed to delete records in translates</p>';
} else {
  $updated = DBupdated();
  if ($updated != 0) {
    echo $updated, ' records deleted from table translates<br>';
    $records += $updated;
    ++$cnt;
} }

$query =
'delete from markuplayers_history
where markup_id in
     (select markup_id
        from annotationsofurls_all
       where annotation_id = ' . $sql_annotation_id . '
     )';
$ret = DBquery($query);
if (!$ret) {
  echo '<p class=error>Failed to delete records in markuplayers_history</p>';
} else {
  $updated = DBupdated();
  if ($updated != 0) {
    echo $updated, ' records deleted from table markuplayers_history<br>';
    $records += $updated;
    ++$cnt;
} }

$query =
'delete from markuplayers
where markup_id in
     (select markup_id
        from annotationsofurls_all
       where annotation_id = ' . $sql_annotation_id . '
     )';
$ret = DBquery($query);
if (!$ret) {
  echo '<p class=error>Failed to delete records in markuplayers</p>';
} else {
  $updated = DBupdated();
  if ($updated != 0) {
    echo $updated, ' records deleted from table markuplayers<br>';
    $records += $updated;
    ++$cnt;
} }

$tables = array();

$template_codes = get_template_codes();
foreach($template_codes as $template_code) {
  if ($template_code != '') {
    $tables[] = 'template_' . $template_code . 's_history';
} }
$tables[] = 'tags_history';
$tables[] = 'annotationslanguages_history';
$tables[] = 'annotationsofurls_history';
$tables[] = 'fulltexts_history';
$tables[] = 'annotations_history';

$tables[] = 'comments';
$tables[] = 'likes';
$tables[] = 'foldersannotations';

foreach($template_codes as $template_code) {
  if ($template_code != '') {
    $tables[] = 'template_' . $template_code . 's';
} }
$tables[] = 'tags';
$tables[] = 'annotationslanguages';
$tables[] = 'annotationsofurls';
$tables[] = 'fulltexts';
$tables[] = 'annotations';

foreach($tables as $table) {
  $query =
'delete from ' . $table . '
 where annotation_id = ' . $sql_annotation_id;
  $ret = DBquery($query);
  if (!$ret) {
    echo '<p class=error>Failed to delete records in ',$table,'</p>';
  } else {
    $updated = DBupdated();
    if ($updated != 0) {
      echo $updated, ' records deleted from table ',$table,'<br>';
      $records += $updated;
      ++$cnt;
} } }

require_once($dir . '/../include/annotations.php');

removeSetAnnotation($annotation_id);

echo '<h3>', $records, ' records deleted from ', $cnt, ' tables</h3>';

close:
DBclose();
done:
bodyFooterFilename();
?>
</body>
</html>
