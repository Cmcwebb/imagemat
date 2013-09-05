<?php
require_once($dir . '/../include/date.php');
require_once($dir . '/../include/permissions.php');
require_once($dir . '/../include/groups.php');
require_once($dir . '/../include/language.php');

srcStylesheet(
  '../css/simple_view.css'
);


function tr($cnt)
{
  if ($cnt & 1) {
	echo '
<tr style="background-color:#fff">'; 
  } else {
	echo '
<tr style="background-color:#fff">'; 
  }
}

function showSimpleView($annotation_id, $version, $archive)
{
  $row = read_extended_annotation($annotation_id, $version, $archive);
  if (!isset($row) || !$row) {
    return false;
  }
  if (isset($row['archive'])) {
    $archive = $row['archive'];
    $version = $row['version'];
  }
  switch ($row['draft']) {
  case 'Y':
    echo '
<h3>DRAFT</h3>';
// was <font color=red>**DRAFT**</font>
    break;
  case 'S':
    echo '
<h3>SAVED</h3>';
// was <font color=orange>*SAVED*</font>
    break;
  default:
    echo '
<h3>PUBLISHED</h3>';
// was <font color=blue>PUBLISHED</font>
  }
  $cnt   = 0;
  $value = $row['title'];
  if (isset($value)) {
    echo '
<h3>', htmlspecialchars($value), '</h3>';
// was <font color=blue size="+2"></font
  }
  echo '
<table class="simpleTable">
';
tr(++$cnt);
echo '<td class="tdSimple1">Annotation</td><td class="tdSimple2">', htmlspecialchars($annotation_id);
  if (isset($row['version'])) {
    $value = $row['version'];
	echo '/', $version;
	if (isset($row['max_version'])) {
	  $value1 = $row['max_version'];
	  if ($value1 != $value) {
		echo ' of ', $value1;
  } } }
  echo '</td></tr>';

  if (isset($row['tags'])) {
    tr(++$cnt);
    echo '<td class="tdSimple1">Tags</td><td class="tdSimple2">', htmlspecialchars($row['tags']),'</td></tr>';
  }

  if (isset($row['template_code'])) {
	tr(++$cnt);
	echo '<td class="tdSimple1">Template</td><td class="tdSimple2">', htmlspecialchars($row['template_code']),'</td></tr>';
  }

  if (isset($row['template'])) {
 	// modified by mkolla. 
	// since template is a multidimensional 
	$value = $row['template'][$row['template_code']] ;
    foreach ($value as $name => $value1) {
	  tr(++$cnt);
	  echo '<td class="tdSimple1">', htmlspecialchars(ucfirst($name)),'</td><td class="tdSimple2">', htmlspecialchars($value1), '</td></tr>';
	//  echo '<td align=right><b>', htmlspecialchars(ucfirst($name)),'</b></td><td>', htmlspecialchars($value1), '</td></tr>';
  } }

  if (isset($row['language_code']) || isset($row['language_codes'])) {

	$languages = db_languages();
	if (isset($row['language_code'])) {
	  tr(++$cnt);
	  $value     = $row['language_code'];
	  echo '<td class="tdSimple1">Language</td><td class="tdSimple2">', htmlspecialchars(getLanguageName($languages, $value)), '</td></tr>';
	}
    if (isset($row['language_codes'])) {
	  tr(++$cnt);
	  $value     = $row['language_codes'];
	  echo '<td class="tdSimple1">Relevant to</td><td class="tdSimple2">';
	  $connector = '';
	  foreach ($value as $value1) {
		echo $connector, htmlspecialchars(getLanguageName($languages,$value1));
		$connector = ', ';
	  }
	  echo '</td></tr>';
	}
  }

  if (isset($row['creator_user_id'])) {
	tr(++$cnt);
    echo '<td class="tdSimple1">Creator</td><td class="tdSimple2">', htmlspecialchars($row['creator_user_id']), '</td></tr>';
  }
  if (isset($row['created'])) {
	tr(++$cnt);
    echo '<td class="tdSimple1">Created</td><td class="tdSimple2">', htmlspecialchars(clientstime($row['created'])), '</td></tr>';
  }
  if (isset($row['modifier_user_id'])) {
	tr(++$cnt);
    echo '<td class="tdSimple1">Last Modifier</td><td class="tdSimple2">', htmlspecialchars($row['modifier_user_id']), '</td></tr>';
  }
  if (isset($row['modified'])) {
	tr(++$cnt);
    echo '<td class="tdSimple1">Last Modified</td><td class="tdSimple2">', htmlspecialchars(clientstime($row['modified'])), '</td></tr>';
  }

  $sql_annotation_id = DBnumber($annotation_id);

  $query =
'select was_annotation_id, was_version, was_language_name, name
 from (select t1.was_annotation_id, t1.was_version, t1.was_language_name, name
         from (select was_annotation_id, was_version, name as was_language_name,
                      translates.language_code as is_language_code
                 from translates left join languages
                   on translates.was_language_code = languages.language_code
                where annotation_id = ' . $sql_annotation_id . '
              ) t1 left join languages
           on t1.is_language_code = languages.language_code
      ) a1,
      annotations a2
where a2.annotation_id = a1.was_annotation_id
  and a2.annotation_deleted is null';

  $ret = DBquery($query);
  if (!$ret) {
    return false;
  }
  for ($cnt1 = 0; $row1 = DBfetch($ret); ++$cnt1) {
	if ($cnt1 == 0) {
	  tr(++$cnt);
	  echo '<td class="tdSimple1">Translates</td><td class="tdSimple2">';
	} else {
	  echo '
<br/>';
	}
	echo 'Annotation ', $row1['was_annotation_id'], '/', $row1['was_version'],
' from ', htmlspecialchars($row1['was_language_name']), ' to ', htmlspecialchars($row1['name']);
  }
  if ($cnt1 != 0) {
	echo '</td></tr>';
  }

  $query =
'select was_annotation_id, was_version
 from (select was_annotation_id,was_version
         from duplicates
        where annotation_id = ' . $sql_annotation_id . '
      ) a1,
      annotations a2
where a2.annotation_id = a1.was_annotation_id
  and a2.annotation_deleted is null';

  $ret = DBquery($query);
  if (!$ret) {
    return false;
  }
  for ($cnt1 = 0; $row1 = DBfetch($ret); ++$cnt1) {
	if ($cnt1 == 0) {
	  tr(++$cnt);
	  echo '<td class="tdSimple1">Duplicates</td><td class="tdSimple2">';
	} else {
	  echo '
<br/>';
	}
    echo 'Annotation ', $row1['was_annotation_id'], '/', $row1['was_version'];
  }
  if ($cnt1 != 0) {
	echo '</td></tr>';
  }

  $query = 
'select a1.annotation_id
  from annotationsofurls a1, annotations a2
 where a1.citation_id   = ' . $sql_annotation_id . '
   and a2.annotation_id = a1.annotation_id
   and a2.annotation_deleted is null';

  $ret = DBquery($query);
  if (!$ret) {
    return false;
  }
  for ($cnt1 = 0; $row1 = DBfetch($ret); ++$cnt1) {
    if ($cnt1 == 0) {
	  tr(++$cnt);
      echo '<td class="tdSimple1">Target of</td><td class="tdSimple2">';
	} else {

    }
	echo 'Annotation ', $row1['annotation_id'];
  }
  if ($cnt1 != 0) {
    echo '</td></tr>';
  }

  if (isset($row['content'])) {
	tr(++$cnt);
	
	echo '
<td colspan="2" class="tdSimple4">
', $row['content'], '</td></tr>';
  
  echo '
</table>';
  }

  return true;
}
