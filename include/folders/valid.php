<?php

require_once($dir . '/../include/alert.php');

function valid_folder_name($name, $parent_id, $folder_id, $isSymlink)
{
  global $gUserid;

  if (!isset($name)) {
    $name = '';
    $msg  = 'Folder name is undefined.';
    goto done;
  }
  if ($name == '') {
    $msg = 'is empty.';
    goto done;
  }
  $msg = null;
  if (strpos($name, '/') === false) {
  } else {
    $msg = 'may not contain a \'/\'.';
    goto done;
  }
  if (strpos($name, '"') === false) {
  } else {
    $msg = 'may not contain a \'"\'';
    goto done;
  }
  if ($name == '.') {
    $msg = 'may not be "."';
    goto done;
  }
  if ($name == '..') {
    $msg = 'may not be ".."';
    goto done;
  }
  if (isset($parent_id)) {
    $query =
'select folder_id
  from folders
 where parent_folder_id = ' . DBnumber($parent_id) . '
   and name             = ' . DBstring($name);

    if ($parent_id == 1) {
	  if (!$isSymlink) {
	    if ($name != $gUserid) {
  	      $msg =  'You may not rename home folders';
          goto done;
        } 
	  } else {
		$query .= '
   and name             = ' . DBstring($gUserid);
    } }

    $ret = DBquery($query);
	if (!$ret) {
	  return false;
	}
	$row = DBfetch($ret);
	if ($row) {
	  if (!isset($folder_id) || $isSymlink || $row['folder_id'] != $folder_id) {
        $msg = 'duplicates an existing folder name';
		goto done;
    } }

    if ($parent_id == 1) {
      $query = '
 select target_folder_id
   from favouritefolders
  where user_id  = ' . DBstring($gUserid) . '
    and name     = ' . DBstring($name);
    } else {
      $query = '
 select target_folder_id
   from symlinks
  where parent_folder_id = ' . DBnumber($parent_id) . '
    and name             = ' . DBstring($name);
    }

    $ret = DBquery($query);
    if (!$ret) {
      return false;
    }
    $row = DBfetch($ret);
	if ($row) {
	  if (!isset($folder_id) || !$isSymlink || $row['target_folder_id'] != $folder_id) {
        $msg = 'duplicates an existing link name';
		goto done;
  } } }
done:
  if ($msg) {
    javascriptAlert('Erroneous folder name', 'warn.png', 'Folder name "'. $name. '" '. $msg, null);
    return false;
  }
  return true;
}

function removeSetFolder($folder_id)
{
  if (isset($_SESSION['imageMAT_setFolders'])) {
    $setFolders = $_SESSION['imageMAT_setFolders'];
    for ($i = count($setFolders); --$i >= 0; ) {
      if ($setFolders[$i] == $folder_id) {
        unset($setFolders[$i]);
        $_SESSION['imageMAT_setFolders'] = array_values($setFolders);
        return $i;
  } } }
  return -1;
}

?>
