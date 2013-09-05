<?php
function self($folder_id)
{
  return array( $folder_id => $folder_id);
}

function child($folder_id, $show)
{
  global $gUserid;

  $result = self($folder_id);

  $query = 
'select folder_id
  from folders
 where parent_folder_id = ' . DBnumber($folder_id);
  if ($folder_id == 1 && $show != 2) {
    $query .= '
   and creator_user_id = ' . DBstring($gUserid);
  }
  $ret = DBquery($query);
  if (!$ret) {
    return false;
  }
  while ($row = DBfetch($ret)) {
    $id = $row['folder_id'];
	$result[$id] = $id;
  } 
  return $result;
}


function descendants($folder_id, $show)
{
  global $gUserid;

  for ($result = self($folder_id); $item = current($result); next($result)) {
    $query = 
'select folder_id
  from folders
 where parent_folder_id = ' . DBnumber($item);
    if ($item == 1 && $show != 2) {
      $query .= '
   and creator_user_id = ' . DBstring($gUserid);
    }
    $ret = DBquery($query);
    if (!$ret) {
      return false;
    }
    while ($row = DBfetch($ret)) {
      $id = $row['folder_id'];
      if (!isset($result[$id])) {
		$result[$id] = $id;
  } } }
  return $result;
}

function reachable($folder_id, $show)
{
  global $gUserid;

  for ($result = self($folder_id); $item = current($result); next($result)) {
    $parent = DBnumber($item);
    $query = 
'select folder_id
  from folders
 where parent_folder_id = ' . $parent;
    if ($parent == 1) {
      if ($show != 2) {
        $query .=
'  and creator_user_id = ' . DBstring($gUserid);
        if ($show == 1) {
          $query .= '
 union all
select target_folder_id as folder_id
  from favouritefolders
 where user_id = ' . DBstring($gUserid);
      } }
    } else {
      $query .=  '
 union all
select target_folder_id as folder_id
  from symlinks
 where parent_folder_id = ' . $parent;
    }
    $ret = DBquery($query);
    if (!$ret) {
      return false;
    }
    while ($row = DBfetch($ret)) {
      $id = $row['folder_id'];
      if (!isset($result[$id])) {
		$result[$id] = $id;
  } } }
  return $result;
}

function not_descendants($folder_id, $show)
{
  $exclude = descendants($folder_id, $show);
  $result  = reachable($folder_id, $show);

  return array_diff_key($result, $exclude);
}
?>
