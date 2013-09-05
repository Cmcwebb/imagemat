<?php

$gUpdateTime = null;

// Returns -1 on hard error 0 on success +ve on sync errors
function archive_annotation($annotation_id, $maxversion, $minor)
{
  global $gUpdateTime;

  if (!isset($maxversion)) {
    javascriptAlert('Unknown version', 'error.png', 'Annotation ' . $annotation_id . ' version number is unknown', 'Error' );
    return 3;
  }

  $sql_annotation_id = DBnumber($annotation_id);

  $query =
'select annotation_id, version, language_code, title, content, template_code, tags, coalesce(modifier_user_id,creator_user_id) as modifier_user_id, coalesce(modified, created) as modified, utc_timestamp() as updateTime
  from annotations
 where annotation_id = ' . $sql_annotation_id;

  $ret = DBquery($query);
  if (!$ret) {
    return -1;
  }
  $row = DBfetch($ret);
  if (!$row) {
    javascriptAlert('Annotation missing', 'error.png', 'Annotation ' . $annotation_id . ' you are attempting to update has been concurrently deleted', 'Error' );
    return 1;
  }
  $gUpdateTime   = $row['updateTime'];
  $last_version = $row['version'];
  if ($last_version != $maxversion) {
    javascriptAlert('Inconsistent annotation version','error.png',
'Version ' . $maxversion . ' of the annotation you are attempting to update has been concurrently updated to version ' . $last_version, 'Error');
    return 2;
  }
  if (isset($minor)) {
	return 0;
  }

  $sql_last_version = DBnumber($last_version);
  $last_template_code = $row['template_code'];

  $query =
'insert into annotations_history
       (version, annotation_id, language_code, title, content, template_code,  
        tags, modifier_user_id, modified,archived)
values (' .
   $sql_last_version . ', ' .
   $sql_annotation_id . ', ' .
   DBstringC($row['language_code']) .
   DBstringC($row['title']) .
   DBstringC($row['content']) .
   DBstringC($last_template_code) .
   DBstringC($row['tags']) .
   DBstringC($row['modifier_user_id']) .
   DBstringC($row['modified']) . 
   DBstring($gUpdateTime) . ')';

  // Avoid failure of future archiving because of duplicate version number
  $query1 =
'update annotations
   set version  = version + 1,
       modified = ' . DBstring($gUpdateTime) . '
 where annotation_id = ' . $sql_annotation_id;

  if (!DBquery($query1)) {
	return -1;
  }

  if (!DBquery($query)) {
    return -1;
  }

  $query =
'insert into fulltexts_history(version,annotation_id,ftitle,fcontent)
select ' . $sql_last_version . ',annotation_id,ftitle,fcontent
  from fulltexts
 where annotation_id = ' . $sql_annotation_id;

  if (!DBquery($query)) {
    return -1;
  }
  $query = 
'insert into tags_history(version, annotation_id, name, value)
select ' . $sql_last_version . ', annotation_id, name, value
  from tags
 where annotation_id = ' . $sql_annotation_id;

  if (!DBquery($query)) {
    return -1;
  }

  $query =
'insert into annotationslanguages_history
       (version, annotation_id, language_code)
select ' . $sql_last_version . ', annotation_id, language_code
  from annotationslanguages
 where annotation_id = ' . $sql_annotation_id;

  if (!DBquery($query)) {
    return -1;
  }

  if ($last_template_code != '') {
    $template_table = 'template_' . $last_template_code . 's';
    $query = 
'insert into ' . $template_table . '_history
select ' . $sql_last_version . ', ' . $template_table . '.*
  from ' . $template_table . '
 where annotation_id = ' . $sql_annotation_id;

    if (!DBquery($query)) {
      return -1;
  } }
  return 0;
}

// Returns -1 on hard error 0 on success +ve on sync errors
function archive_image(&$image, $minor)
{
  global $gUpdateTime;

  $markup_id = $image->markup_id;
  $sql_markup_id = DBnumber($markup_id);

  $query =
'select version, annotation_id, tab, image_url_id, html_url_id, natural_width, natural_height, title, description, modified' .
  (isset($gUpdateTime) ? '' : ', utc_timestamp() as updateTime') . '
  from annotationsofurls
 where markup_id = ' . $sql_markup_id;

  $ret = DBquery($query);
  if (!$ret) {
	return -1;
  }

  $row = DBfetch($ret);
  if (!$row) {
    javascriptAlert('Image missing', 'error.png', 'Image ' . $markup_id . ' you are attempting to update has been deleted', 'Error' );
	return 1;
  }
  $version = $row['version'];
  if (isset($image->maxversion)) {
	$maxversion = $image->maxversion;
	if ($maxversion != $version) {
      javascriptAlert('Inconsistent image version','error.png',
'Version ' . $maxversion . ' of image ' . $markup_id . ' you are attempting to update has been concurrently updated to version ' . $version, 'Error');
	  return 2;
  } }

  if (!isset($gUpdateTime)) {
	$gUpdateTime = $row['updateTime'];
  }

  if (isset($minor)) {
    return 0;
  }
  $query =
'insert into annotationsofurls_history
       (version, markup_id, annotation_id, tab, image_url_id, html_url_id, natural_width, natural_height, title, description, modified, archived)
values (' .
  DBnumberC($version) .
  DBnumberC($markup_id) .
  DBnumberC($row['annotation_id']) .
  DBnumberC($row['tab']) .
  DBnumberC($row['image_url_id']) .
  DBnumberC($row['html_url_id']) .
  DBnumberC($row['natural_width']) .
  DBnumberC($row['natural_height']) .
  DBstringC($row['title']) .
  DBstringC($row['description']) .
  DBstringC($row['modified']) .
  DBstring($gUpdateTime) . '
)';

  // Avoid failure of future archiving because of duplicate version number
  $query1 =
'update annotationsofurls
   set version  = version + 1,
       modified = ' . DBstring($gUpdateTime) . '
 where markup_id = ' . $sql_markup_id;

  if (!DBquery($query1)) {
	return -1;
  }

  if (!DBquery($query)) {
    return -1;
  }

  if (isset($row['natural_width'])) {

    $query =
'insert into markuplayers_history
       (version, markup_id, layer, title, description, svg)
select ' . DBnumberC($version) . 'markup_id, layer, title, description, svg
  from markuplayers
 where markup_id = '. DBnumber($markup_id);

    if (!DBquery($query)) {
	  return -1;
  } }
  return 0;
}

function update_image(&$image, $minor)
{
  global $gUpdateTime;

  $dirty     = false;
  if (isset($image->markups)) {
    $markups   = $image->markups;
    foreach ($markups as $markup) {
	  if (isset($markup->delete) || isset($markup->title) || isset($markup->description) || isset($markup->svg) ) {
		$dirty = true;
		break;
  } } }

  $markup_id = $image->markup_id;
  $sql_markup_id = DBnumber($markup_id);
  $query     = '';
  if (isset($image->tab)) {
    $query = 'tab = ' . DBnumber($image->tab) . ',
       ';
  }
  if (isset($image->citation_id)) {
	$query .= 'citation_id = ' . DBnumber($image->citation_id) . ',
       ';
  }
  if (isset($image->image_url)) {
    $url = trim($image->image_url);
    if ($url == '') {
      $value = null;
    } else {
	  $value = get_url_id($url, true);
	}
    $query .= 'image_url_id = ' . DBnumber($value) . ',
       ';
  }
  if (isset($image->html_url)) {
	$url = trim($image->html_url);
	if ($url == '') {
	  $value = null;
    } else {
	  $value = get_url_id($url, true);
	}
    $query .= 'html_url_id = ' . DBnumber($value) . ',
       ';
  }
  if (isset($image->naturalWidth)) {
    $value = $image->naturalWidth;
	if ($value < 0) {
	  $value = null;
    }
	$query .= 'natural_width = ' . DBnumber($value) . ',
       ';
  }
  if (isset($image->naturalHeight)) {
	$value = $image->naturalHeight;
	if ($value < 0) {
	  $value = null;
	}
	$query .= 'natural_height = ' . DBnumber($value) . ',
       ';
  }
  if (isset($image->title)) {
    $value = $image->title;
	if ($value == '') {
	  $value = null;
    }
	$query .= 'title = ' . DBstring($value) . ',
       ';
  }
  if (isset($image->description)) {
	$value = $image->description;
	if ($value = '') {
	  $value = null;
	}
	$query .= 'description = ' . DBstring($value) . ',
       ';
  }

  if ($dirty || $query != '') {
	$ret = archive_image($image, $minor);
	if ($ret) {
      return $ret;
  	}
    $query     = 
'update annotationsofurls
   set ' . $query . 
           'modified = ' . DBstring($gUpdateTime) . '
 where markup_id = ' . $sql_markup_id;

    $ret = DBquery($query);
    if (!$ret) {
      return -1;
  } }

  if ($dirty) {
    $query =
'delete from markuplayers
 where markup_id = ' . $sql_markup_id;

    $ret = DBquery($query);
    if (!$ret) {
      return -1;
    }

    if (!insert_markup_layers($image)) {
	  return -1;
  } }
  return 0;
}

function delete_image(&$image)
{
  $markup_id     = $image->markup_id;
  $sql_markup_id = DBnumber($markup_id);

  $query = 
'delete from markuplayers
 where markup_id = ' . $sql_markup_id;

  $ret = DBquery($query);
  if (!$ret) {
	return false;
  }

  $query =
'delete from annotationsofurls
  where markup_id = ' . $sql_markup_id;

  $ret = DBquery($query);
  if (!$ret) {
    return false;
  }
  return true;
}

// Returns -1 on hard error 0 on success else sync errors
function update_images($annotation_id, &$image_data, $minor)
{
  $ret    = 0;
  $images = $image_data->images;
  $tab    = 0;
  foreach ($images as $image) {
    if (!isset($image->markup_id)) {
	  if (!isset($image->delete)) {		
        ++$tab;
		$image->tab = $tab;
	  	if (!insert_image($annotation_id, $image)) {
	      return -1;
      } }
      continue;
    }
	if (isset($image->delete)) {
	  if (!$minor) {
	    $ret1 = archive_image($image, $minor);
	    if ($ret1 < 0) {
	      return $ret1;
	    }
	    if (!$ret) {
	      $ret = $ret1;
      } }
	  if (!delete_image($image)) {
	    return -1;
      }
	  continue;
    }
    ++$tab;
    if (!isset($image->tab) || $tab != $image->orig_tab) {
	  $image->tab = $tab;
    }
    $ret1 = update_image($image,$minor);
    if ($ret1 < 0) {
	  return $ret1;
    } 
	if (!$ret) {
	  $ret = $ret1;
  }	}
  return $ret; 
}
?>
