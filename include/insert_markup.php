<?php
// Returns false on failure else true
function insert_markup_layers(&$image)
{
  if (isset($image->markups)) {
    $markups = $image->markups;
    $markup_id = $image->markup_id;
    $layer = 1;
    foreach ($markups as $markup) {
	  if (isset($markups->delete)) {
		continue;
	  }
	  $query1 = '';
	  $query2 = '';

	  if (isset($markup->title)) {
		$value = trim($markup->title);
		if ($value != '') {
		  $query1 = 'title,';
		  $query2 = DBstringC($value);
	  } }
	  if (isset($markup->description)) {
		$value = trim($markup->description);
		if ($value != '') {
		  $query1 .= 'description,';
		  $query2 .= DBstringC($value);
	  }	}
	  if (isset($markup->svg)) {
		$value = trim($markup->svg);
		if ($value != '') {
		  $query1 .= 'svg,';
		  $query2 .= DBstringC($value);
  	  }	}
  	  if ($query1 == '') {
		continue;
	  }
      $query =
'insert into markuplayers(markup_id,' . $query1 . 'layer)
values (' . DBnumberC($markup_id) . $query2 .  DBnumber($layer) .  ')';

	  $ret = DBquery($query);
	  if (!$ret) {
	    return false;
	  }
	  ++$layer;
  } }
  return true;
}

// Returns false on failure else true
function insert_image($annotation_id, &$image)
{
  $query1 = 'annotation_id,version,tab,';
  $query2 = DBnumberC($annotation_id) .  '1,' . DBnumberC($image->tab);

  if (isset($image->citation_id)) {
    $query1 .= 'citation_id,';
	$query2 .= DBnumberC($image->citation_id);
  }
  if (isset($image->image_url)) {
    $value = $image->image_url;
	if ($value != '') {
      $value = get_url_id($value, true);
      if ($value <= 0) {
        return false;
      }
      $query1 .= 'image_url_id,';
	  $query2 .= DBnumberC($value);
  } }
  if (isset($image->html_url)) {
    $value = $image->html_url;
	if ($value != '') {
      $value = get_url_id($value, true);
      if ($value <= 0) {
        return false;
      }
      $query1 .= 'html_url_id,';
	  $query2 .= DBnumberC($value);
  } }
  if (isset($image->naturalWidth)) {
    $value = $image->naturalWidth;
	if (0 <= $value) {
      $query1 .= 'natural_width,';
	  $query2 .= DBnumberC($value);
  } }
  if (isset($image->naturalHeight)) {
    $value = $image->naturalHeight;
	if (0 <= $value) {
      $query1 .= 'natural_height,';
	  $query2 .= DBnumberC($value);
  } }
  if (isset($image->title)) {
    $value = trim($image->title);
    if ($value != '') {
      $query1 .= 'title,';
	  $query2 .= DBstringC($value);
  } }
  if (isset($image->description)) {
    $value = trim($image->description);
    if ($value != '') {
      $query1 .= 'description,';
	  $query2 .= DBstringC($value);
  } }

  $query =
'insert into annotationsofurls
(' . $query1 . 'modified)
values (' . $query2 . 'utc_timestamp())';

  $ret = DBquery($query);
  if (!$ret) {
    return false;
  }

  $markup_id = DBid();

  if ($markup_id == 0) {
    javascriptAlert(null, null, 'Unable to create markup','Error');
    return false;
  }
  $image->markup_id = $markup_id;

  return insert_markup_layers($image);
}

// Returns false on failure else true
function insert_images($annotation_id, &$image_data)
{
  $images = $image_data->images;
  $tab    = 0;
  foreach ($images as $image) {
	if (isset($image->delete)) {
      continue;
	}
    $image->tab = ++$tab;
	if (!insert_image($annotation_id, $image)) {
	  return false;
  }	}
  return true;
}

?>
