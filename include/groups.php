<?php

# Returns $member record for $gUserid; false if not a member; null on error
# Sets $folder_owner_id to owner of $folder_id if null on input

function resolve_member($userid, $folder_id, &$folder_owner_id)
{
  $inherits = CAN_ANNOTATE | CAN_SUBFOLDER | CAN_DOCUMENT | VETTER | MANAGER | CAN_OWN | INHERITS_MEMBERS;
  for (;$folder_id != 1; $folder_id = $row['parent_folder_id']) {
    $query = 
'select * from groups
 where user_id = ' . DBstring($userid) . '
   and folder_id = ' . DBnumber($folder_id);
    $ret = DBquery($query);
    if (!$ret) {
      return null;
    }
    $member = DBfetch($ret);
    if ($member) {
      $member['folder_permissions'] &= $inherits;
      if (!($inherits & INHERITS_MEMBERS) && !($member['folder_permissions'] & inherits)) {
        return false;
      }
      if (isset($folder_owner_id)) {
        return $member;
    } }
    $query =
'select parent_folder_id, inherits, creator_user_id
  from folders
 where folder_id = ' . DBnumber($folder_id);
    $ret = DBquery($query);
    if (!$ret) {
      return null;
    }
    $folder = DBfetch($ret);
    if (!$folder) {
      echo '
<br>Unable to read folder "', htmlspecialchars($folder_id), '"';
      return null;
    }
    if (!isset($folder_owner_id)) {
      $folder_owner_id = $folder['creator_user_id'];
      if (isset($member)) {
        return $member;
    } }
    $inherits_flag = $folder['inherits'];
    $inherits     &= $inherits_flag;
    if (!$inherits) {
      break;
  } }
  return false;
}

function resolve_membership($folder_id, &$folder_owner_id)
{
  $membership = array();

  $inherits = CAN_ANNOTATE | CAN_SUBFOLDER | CAN_DOCUMENT | VETTER | MANAGER | CAN_OWN | INHERITS_MEMBERS;
  for (;$folder_id != 1; $folder_id = $row['parent_folder_id']) {
    $query = 
'select * from groups
 where folder_id = ' . DBnumber($folder_id);
    $ret = DBquery($query);
    if (!$ret) {
      return null;
    }
    while ($member = DBfetch($ret)) {
      $member_id = $member['user_id'];
      if (!isset($membership[$member_id])) {
        $member['folder_permissions'] &= $inherits;
        if (($inherits & INHERITS_MEMBERS) || ($member['folder_permissions'] & inherits)) {
          $membership[$member_id] = $member;
    } } }
    $query =
'select parent_folder_id, inherits, creator_user_id
  from folders
 where folder_id = ' . DBnumber($folder_id);
    $ret = DBquery($query);
    if (!$ret) {
      return null;
    }
    $folder = DBfetch($ret);
    if (!$folder) {
      echo '
<br>Unable to read folder "', htmlspecialchars($folder_id), '"';
      return null;
    }
    if (!isset($folder_owner_id)) {
      $folder_owner_id = $folder['creator_user_id'];
    }
    $inherits_flag = $folder['inherits'];
    $inherits     &= $inherits_flag;
    if (!$inherits) {
      break;
  } }
  return $membership;
}

function permit_new_members($folder_id)
{
  for ($id = $folder_id; ; $id = $parent_folder_id) {
    $query =
'select new_users, parent_folder_id
  from folders
 where folder_id = ' . DBnumber($id);
    $ret = DBquery($query);
    if (!$ret) {
      return null;
    }
    $row = DBfetch($ret);
    if (!$row) {
      echo '
<br>Unable to read folder with id ', $id;
      return null;
    }
    $flag = $row['new_users'];
    if ($flag != NEW_USER_INHERITS) {
      return $flag;
    }
    $parent_folder_id = $row['parent_folder_id'];
    if (!isset($parent_folder_id)) {
      echo '
<br>Unable to inherit new user value from root folder ', $folder_id;
      return null;
} } }

# return 1 if may see, 2 if may read, 3 if both 0 if neither or null on error

function may_read_annotation($row)
{
  global $gUserid;

  return 3;  // TODO

  if (!isset($row['modifier_user_id'])) {
    if ($row['creator_user_id'] == $gUserid) {
      return 3;
    }
  } else if ($row['modifier_user_id'] == $gUserid) {
    return 3;
  }
  if (isset($row['draft'])) {
    return 0;
  }
  $permit = 0;
  $query =
'select may_see, may_read
  from foldersannotations
 where annotation_id = ' . DBnumber($row['annotation_id']) . '
   and (((may_see | may_read) & ' . GRANT_PUBLIC . ') != 0)';

  $ret = DBquery($query);
  if (!$ret) {
    return null;
  }
  while($row1 = DBfetch($ret)) {
    if ($row1['may_see'] & GRANT_PUBLIC) {
      $permit |= 1;
    }
    if ($row1['may_read'] & GRANT_PUBLIC) {
      $permit |= 2;
    }
    if ($permit == 3) {
      return $permit;
  } }

  $query =
'select folder_id, may_see, may_read
  from foldersannotations
 where annotation_id = ' . DBnumber($row['annotation_id']);

  switch ($permit) {
  case 0:
    $query .= '
   and (((may_see | may_read) & ' . (GRANT_MANAGER|GRANT_MEMBER|GRANT_VETTER) .') != 0)';
    break;
  case 1:
    $query .= '
   and ((may_read & ' . (GRANT_MANAGER|GRANT_MEMBER|GRANT_VETTER) .') != 0)';
    break;
  case 2:
    $query .= '
   and ((may_see & ' . (GRANT_MANAGER|GRANT_MEMBER|GRANT_VETTER) .') != 0)';
    break;
  }

  $ret = DBquery($query);
  if (!$ret) {
    return null;
  }
  while($row1 = DBfetch($ret)) {
    $member = resolve_member($gUserid, $row1['folder_id']);
    if (!isset($member)) {
      return null;
    }
    if ($member) {
      $folder_permissions = $row1['folder_permissions'];
      if ($permit != 1) {
        $may_see  = $row1['may_see'];
        if (($may_see & GRANT_MEMBER) ||
            (($may_see & GRANT_MANAGER) && ($folder_permissions & MANAGER)) ||
            (($may_see & GRANT_VETTER)  && ($folder_permissions & VETTER)) ) {
          $permit |= 1;
          if ($permit == 3) {
            return $permit;
      } } }
      if ($permit != 2) {
        $may_read  = $row1['may_read'];
        if (($may_read & GRANT_MEMBER) ||
            (($may_read & GRANT_MANAGER) && ($folder_permissions & MANAGER)) ||
            (($may_read & GRANT_VETTER)  && ($folder_permissions & VETTER)) ) {
          $permit |= 2;
          if ($permit == 3) {
            return $permit;
  } } } } }

  $query =
'select creator_user_id, may_see, may_read
  from foldersannotations, folders
 where foldersannotations.folder_id = folders.folder_id
   and foldersannotations.annotation_id = ' . DBnumber($row['annotation_id']) . '
   and folders.creator_user_id = ' . DBstring($gUserid);


  switch ($permit) {
  case 0:
    $query .= '
   and (((may_see | may_read) & ' . GRANT_FOLDER .') != 0)';
    break;
  case 1:
    $query .= '
   and ((may_read & ' . GRANT_FOLDER .') != 0)';
    break;
  case 2:
    $query .= '
   and ((may_see & ' . GRANT_FOLDER .') != 0)';
    break;
  }

  $ret = DBquery($query);
  if (!$ret) {
    return null;
  }
  while($row1 = DBfetch($ret)) {
    if ($permit != 1) {
      if ($row1['may_see'] & GRANT_FOLDER) {
        $permit |= 1;
        if ($permit == 3) {
          return $permit;
    } } }
    if ($permit != 2) {
      if ($row1['may_read'] & GRANT_FOLDER) {
        $permit |= 2;
        if ($permit == 3) {
          return $permit;
  } } } }

  $query =
'select may_see, may_read
  from foldersannotations
 where annotation_id = ' . DBnumber($row['annotation_id']);

  switch ($permit) {
  case 0:
    $query .= '
   and (((may_see | may_read) & ' . GRANT_FOLLOWER .') != 0)';
    break;
  case 1:
    $query .= '
   and ((may_read & ' . GRANT_FOLLOWER .') != 0)';
    break;
  case 2:
    $query .= '
   and ((may_see & ' . GRANT_FOLLOWER .') != 0)';
    break;
  }
  $query .= '
   and exists (
       select null
         from follows
        where follows_user_id = ' . DBstring($gUserid) . '
          and user_id         = ' . DBstring($row['creator_user_id']) . '
       )';

  $ret = DBquery($query);
  if (!$ret) {
    return null;
  }
  while($row1 = DBfetch($ret)) {
    if ($permit != 1) {
      if ($row1['may_see'] & GRANT_FOLLOWER) {
        $permit |= 1;
        if ($permit == 3) {
          return $permit;
    } } }
    if ($permit != 2) {
      if ($row1['may_read'] & GRANT_FOLLOWER) {
        $permit |= 2;
        if ($permit == 3) {
          return $permit;
  } } } }
  return $permit;
}

?>
