<?php

function removeSetAnnotation($annotation_id)
{
  if (isset($_SESSION['imageMAT_setAnnotations'])) {
    $setAnnotations = $_SESSION['imageMAT_setAnnotations'];
    for ($i = count($setAnnotations); --$i >= 0; ) {
      if ($setAnnotations[$i] == $annotation_id) {
        unset($setAnnotations[$i]);
        $_SESSION['imageMAT_setAnnotations'] = array_values($setAnnotations);
        return $i;
  } } }
  return -1;
}

function read_annotation($annotation_id, $version, $archive)
{
  global $gUserid;

  if ($archive == 'Y') {
    $query =
'select a1.*, a2.created, a2.annotation_deleted
  from annotations_history a1,
	   annotations a2
 where a1.annotation_id = ' . DBnumber($annotation_id) . '
   and a1.version       = ' . DBnumber($version) . '
   and a2.annotation_id = ' . DBnumber($annotation_id);
  } else {
    $query = 
'select *
  from annotations
 where annotation_id = ' . DBnumber($annotation_id);
  } 
  $ret = DBquery($query);
  if (!$ret) {
   return null;
  }

  $row = DBfetch($ret);
  if (!$row) {
    removeSetAnnotation($annotation_id);
    echo '
Annotation ', htmlspecialchars($annotation_id),
(isset($version) ? '/' . htmlspecialchars($version) : ''), ' does not exist';
    return false;
  }

  if (isset($row['draft'])) {
    $user_id = $row['modifier_user_id'];
    if (!isset($user_id)) {
      $user_id = $row['creator_user_id'];
    }
    if ($user_id != $gUserid) {
      $archive = 'Y';
      $query =
'select *
  from annotations_history
 where annotation_id = ' . DBnumber($annotation_id);
      if (isset($version)) {
        $query .= '
   and version < ' . DB($version);
      }
      $query .= '
 order by version desc';
      $ret = DBquery($query);
      if (!$ret) {
        return false;
      }
      for (;;) {
        $row = DBfetch($ret);
        if (!$row) {
          echo '
Annotation ', htmlspecialchars($annotation_id), 
(isset($version) ? '/' . htmlspecialchars($version) : ''),
' has not been published.
You are not permitted to view other peoples earlier draft annotations';
	      return false;
	    }
	    if (!isset($row['draft'])) {
	      echo '
Annotation ', htmlspecialchars($annotation_id), 
(isset($version) ? '/' . htmlspecialchars($version) : ''),
' is being drafted by another.
You are viewing the most recent earlier published version of this annotation.';
	      break;
	    }
        $user_id = $row['modifier_user_id'];
        if (!isset($user_id)) {
          $user_id = $row['creator_user_id'];
        }
	    if ($user_id == $gUserid) {
	      echo '
Annotation ', htmlspecialchars($annotation_id), 
(isset($version) ? '/' . htmlspecialchars($version) : ''),
' is being drafted by another.
You are viewing your most recent earlier draft version of this annotation.';
	      break;
      }	}
  } }
  $row['archive'] = $archive;
   if ($archive != 'Y') {
	$row['maxversion'] = $row['version'];
  }
  return $row;
}

function add_fulltext(&$row)
{
  if ($row['archive'] == 'Y') {
    $query = 
'select ftitle, fcontent
  from fulltexts_history
 where annotation_id = ' . DBnumber($row['annotation_id']) . '
   and version       = ' . DBnumber($row['version']);
  } else {
    $query = 
'select ftitle, fcontent
  from fulltexts
 where annotation_id = ' . DBnumber($row['annotation_id']);
  }
  $ret = DBquery($query);
  if (!$ret) {
    return null;
  }
  $row1 = DBfetch($ret);
  if ($row1) {
    $row['ftitle']   = $row1['ftitle'];
    $row['fcontent'] = $row1['fcontent'];
  }
  return true;
}

function add_language_codes(&$row)
{
  if ($row['archive'] == 'Y') {
    $query = 
'select language_code
  from annotationslanguages_history
 where annotation_id = ' . DBnumber($row['annotation_id']) . '
   and version       = ' . DBnumber($row['version']); 
  } else {
    $query = 
'select language_code
  from annotationslanguages
 where annotation_id = ' . DBnumber($row['annotation_id']);
  }

  $language_codes = null;
  $ret = DBquery($query);
  if (!$ret) {
    return null;
  }
  for ($cnt = 0; $row1 = DBfetch($ret); ++$cnt) {
    if (!isset($language_codes)) {
      $language_codes = array();
    }
    $language_codes[$cnt] = $row1['language_code'];
  }
  $row['language_codes'] = $language_codes;

  return true;
}

function read_template($annotation_id, $version, $archive, $template_code)
{
  if ($archive == 'Y') {
    $query =
'select *
  from template_' . $template_code . 's_history
 where annotation_id = ' . DBnumber($annotation_id) . '
   and version       = ' . DBnumber($version);
  } else {
    $query =
'select *
  from template_' . $template_code . 's
 where annotation_id = ' . DBnumber($annotation_id);
  }
  $ret = DBquery($query);
  if (!$ret) {
    return null;
  }
  $template[$template_code] = DBfetch($ret);
  if (!isset($template)) {
    $template = array();
  }
  return $template;
}

function read_extended_annotation($annotation_id, $version, $archive)
{
  $row = read_annotation($annotation_id, $version, $archive);
  if (!isset($row) || !$row) {
	return null;
  }
  //$row['tags'] = recover_tags($row);

  if (!add_language_codes($row)) {
	return null;
  }
  if (isset($row['template_code'])) {
	$template = read_template($annotation_id, $version, $archive, $row['template_code']);
	if (!isset($template)) {
	  return null;
	}
    $row['template'] = $template;
  }
  return $row;
}

function insert_annotationslanguages($annotation_id, $language_codes)
{
  $ret = true;

  if (isset($language_codes)) {
    foreach($language_codes as $value) {
      $query =
'insert into annotationslanguages(annotation_id, language_code)
values (' . DBnumberC($annotation_id) . DBstring($value) . ')';
      $ret1 = DBquery($query);
      if (!$ret1) {
        $ret = false;
  } } }
  return $ret;
}
?>
