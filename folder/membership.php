<!DOCTYPE HTML>
<?php

$gPHPscript = __FILE__;
$dir = dirname(__FILE__);
require_once($dir . '/../include/boilerplate.php');
require_once($dir . '/../include/util.php');

if (mustlogon()) {
  return;
}

require_once($dir . '/../include/db.php');
require_once($dir . '/../include/folders.php');

echo HtmlHeader('Folder Group Membership Permissions') , '
</head>
<body>';

if (!DBconnect()) {
  goto done;
}

require_once($dir . '/../include/permissions.php');
require_once($dir . '/../include/groups.php');

$folder_id = get_folder_id();
if (!$folder_id) {
  goto close;
}
if ($folder_id == 1) {
  echo '
<br>You may not add group members to the root "/" folder';
  goto close;
}

$getpath = getpath($folder_id);
if (!$getpath) {
  goto close;
}
$path = htmlspecialchars($getpath['path']);

$member_id = getparameter('member_id');
echo '
<h3>Member';
if (isset($member_id)) {
  echo ' ', htmlspecialchars($member_id);
}
echo ' options for folder ', $path, '</h3>';

$folder_owner_id = null;
$user_member = resolve_member($gUserid, $folder_id, $folder_owner_id);
if (!isset($user_member)) {
  goto close;
}
if ($gUserid == $folder_owner_id) {
  $is_manager = true;
  $is_owner   = true;
} else if ($user_member && ($user_member['folder_permissions'] & MANAGER)) {
  $is_manager = true;
  $is_owner   = true;
} else {
  $is_manager = false;
  $is_owner   = false;
}

echo '
<br>You ', htmlspecialchars($gUserid);
if ($is_manager) {
  echo
 ' own this folder';
} else if (!$user_member) {
  echo ' are not a member of this folder';
} else if ($user_member['folder_id'] == $folder_id) {
  if ($user_member['folder_permissions'] & MANAGER) {
    echo ' are a manager for this folder';
  } else {
    echo ' are a regular group member of this folder';
  }
} else {
  if ($user_member['folder_permissions'] & MANAGER) {
    echo ' are a manager for this folder (by subfolder inheritance)';
  } else {
    echo ' are a regular group member of this folder (by subfolder inheritance)';
} }

$mode = getpost('mode');
if (isset($mode) && isset($member_id)) {
  $member  = getpost('member');
  $new_folder_permissions = 0;
  $button = getpost('no_folder');
  if (isset($button)) {
    goto have_folder;
  }
  $button = getpost('all_folder');
  if (isset($button)) {
    $new_folder_permissions = 127;
    goto have_folder;
  }
  if (!$is_manager) {
    $new_folder_permissions = $member['folder_permissions'];
    goto have_folder;
  }
  $shift = 0;
  foreach($folder_options as $name => $value) {
    $permission = getpost($name);
    if (isset($permission)) {
      $new_folder_permissions |= (1 << $shift);
    }
    ++$shift;
  }
have_folder:

  if ($gUserid != $member_id) {
    $new_monitors = $member['monitors'];
    goto have_monitor;
  }
  $new_monitors = 0;
  $button = getpost('no_monitor');
  if (isset($button)) {
    goto have_monitor;
  }
  $button = getpost('all_monitor');
  if (isset($button)) {
    $new_monitors = 127;
    goto have_monitor;
  }
  $shift = 0;
  foreach($monitor_options as $name => $value) {
    $permission = getpost($name);
    if (isset($permission)) {
      $new_monitors |= (1 << $shift);
    }
    ++$shift;
  }
have_monitor:

  $new_masks = array();
  if (!$is_manager) {
    foreach ($annotate_defaults as $name => $value) {
      $new_masks[$name] = $member['mask_' . $name];
    }
    goto have_masks;
  }
  $button = getpost('all_masks');
  if (isset($button)) {
    foreach ($annotate_defaults as $name => $value) {
      $new_masks[$name] = 255;
    }
    goto have_masks;
  }
  $button = getpost('no_masks');
  if (isset($button)) {
    foreach ($annotate_defaults as $name => $value) {
      $new_masks[$name] = 0;
    }
    goto have_masks;
  }
  $button  = getpost('default_masks');
  if (isset($button)) {
    foreach ($annotate_defaults as $name => $value) {
      $new_masks[$name] = $value;
    }
    goto have_masks;
  }
  foreach($annotate_options as $name => $value) {
    $value = 0;
    $permission = getpost('mask_' . $name);
    if (isset($permission)) {
      for ($shift = 0; $shift < 8; ++$shift) {
        if (isset($permission[$shift])) {
          $value |= (1 << $shift);
    } } }
    $new_masks[$name] = $value;
  }
have_masks:
  $new_masks['owns']    |= 1;
  $new_masks['may_see'] |= 1;
   
  $new_bits = array();
  if ($member_id != $gUserid) {
    foreach($annotate_options as $name => $value) {
      $new_bits[$name] = $member['default_' . $name];
    }
    goto have_bits;
  }

  $button  = getpost('default_permissions');
  if (isset($button)) {
    foreach ($annotate_defaults as $name => $value) {
      $new_bits[$name] = $value;
    }
    goto have_bits;
  }
  $button = getpost('all_permissions');
  if (isset($button)) {
    foreach ($annotate_defaults as $name => $value) {
      $new_bits[$name] = 255;
    }
    goto have_bits;
  }
  $button = getpost('no_permissions');
  if (isset($button)) {
    foreach ($annotate_defaults as $name => $value) {
      $new_bits[$name] = 0;
    }
    goto have_bits;
  }
  foreach($annotate_options as $name => $value) {
    $value = 0;
    $permission = getpost($name);
    if (isset($permission)) {
      for ($shift = 0; $shift < 8; ++$shift) {
        if (isset($permission[$shift])) {
          $value |= (1 << $shift);
    } } }
    $new_bits[$name] = $value;
  }
have_bits:
  $new_bits['owns']    |= 1;
  $new_bits['may_see'] |= 1;

  echo '
<h3>Updated permissions for ', htmlspecialchars($member_id), ' in folder ',
  $path, '</h3>';

  $common = '
user_id = ' . DBstringC($member_id) . '
folder_id = ' . DBnumberC($folder_id) . '
folder_permissions = ' . DBnumberC($new_folder_permissions) . '
monitors = ' . DBnumberC($new_monitors) . '
mask_owns = ' . DBnumberC($new_masks['owns']) . '
mask_may_see = ' . DBnumberC($new_masks['may_see']) . '
mask_may_read = ' . DBnumberC($new_masks['may_read']) . '
mask_may_post = ' . DBnumberC($new_masks['may_post']) . '
mask_may_copy = ' . DBnumberC($new_masks['may_copy']) . '
mask_may_update = ' . DBnumberC($new_masks['may_update']) . '
mask_may_delete = ' . DBnumberC($new_masks['may_delete']) . '
mask_may_comment = ' . DBnumberC($new_masks['may_comment']) . '
mask_may_read_comments = ' . DBnumberC($new_masks['may_read_comments']) . '
mask_manage_comments = ' . DBnumberC($new_masks['manage_comments']) . '
mask_may_x_post = ' . DBnumberC($new_masks['may_x_post']) . '
default_owns = ' . DBnumberC($new_bits['owns']) . '
default_may_see = ' . DBnumberC($new_bits['may_see']) . '
default_may_read = ' . DBnumberC($new_bits['may_read']) . '
default_may_post = ' . DBnumberC($new_bits['may_post']) . '
default_may_copy = ' . DBnumberC($new_bits['may_copy']) . '
default_may_update = ' . DBnumberC($new_bits['may_update']) . '
default_may_delete = ' . DBnumberC($new_bits['may_delete']) . '
default_may_comment = ' . DBnumberC($new_bits['may_comment']) . '
default_may_read_comments = ' . DBnumberC($new_bits['may_read_comments']) . '
default_manage_comments = ' . DBnumberC($new_bits['manage_comments']) . '
default_may_x_post = ' . DBnumberC($new_bits['may_x_post']);

  $query =
'insert into groups set ' . $common . '
creator_user_id = ' . DBstringC($gUserid) . '
created = utc_timestamp()
on duplicate key update ' . $common . '
updated = utc_timestamp()';

  DBquery($query);
} 
$member = false;
if (isset($member_id)) {
  $delete = getpost('delete_member');
  if (isset($delete)) {
    if ($is_owner) {
      if ($member_id == $gUserid) {
        echo '
<br>You may not delete yourself from being a member of a folder you own';
        goto abort_delete;
      }
    } else if (!$is_manager) {
      echo '
<br>You may not delete ', htmlspecialchars($member_id), ' from ', $path,
' because you are not a manager of this folder.';
      goto abort_delete;
    }
    $query =
'delete from groups
 where folder_id = ' . DBnumber($folder_id) . '
   and user_id   = ' . DBstring($member_id);
    $ret = DBupdate1($query);
    if (!$ret) {
      goto close;
    }
    echo '
<br>User ', htmlspecialchars($member_id), ' is no longer a member of ', $path;
    goto close;
  }
abort_delete:

  if ($gUserid == $member_id) {
    $member = $user_member;
  } else {
    $member = resolve_member($member_id, $folder_id, $folder_owner_id);
    if (!isset($member)) {
      goto close;
  } }
} else {
  echo '
<p>
Please identify the group member whose permissions you wished to change
within folder ', $path;
}
echo '
<p>
<form action="membership.php" method="post">
<select name=member_id>';
$query = 
'select user_id
  from users
 order by user_id';
$ret = DBquery($query);
if (!$ret) {
  goto close;
}
for (; $row = DBfetch($ret); ) {
  $this_id = $row['user_id'];
  $text    = htmlspecialchars($this_id);
  echo '
<option value="', $text, '"';
  if ($this_id == $member_id) {
    echo ' selected';
  }
  echo '>', $text, '</option>';
}
echo '
</select>
<input type=submit name=send value=Set />';
if (!isset($member_id)) {
  echo '
</form>';
  goto close;
}

if (is_array($member) && ($member['folder_id'] == $folder_id) && $is_manager) {
  echo '
<input type=submit name=delete_member value="Delete Member" />';
}
echo '
</form>
<p>';

if (is_array($member)) {
  if ($member['folder_id'] != $folder_id) {
    $getpath = getpath($member['folder_id']);
    if (!$getpath) {
      $path1 = '???';
    } else {
      $path1 = htmlspecialchars($getpath['path']);
    }
    echo '
<br>Caution: ', htmlspecialchars($member_id), ' is a member of ', $path1,
' which ', $path, ' inherits membership from.
<br>Membership settings will be physically set on subfolder if you continue.';
    $member['creator_user_id'] = $gUserid;
  }
} else { 
  echo '
<br>Caution: ', htmlspecialchars($member_id), ' is not currently a member of ',
$path;
  $permit = permit_new_members($folder_id);
  if (!isset($permit)) {
    goto close;
  }
  switch ($permit) {
  case NEW_USER_MAYBE:
  case NEW_USER_ASK_OWNER:
    if ($is_owner) {
      break;
    }
    echo '
<br>Only the owner ', htmlspecialchars($folder_owner_id), ' is permitted to add new members to this folder.';
    if ($permit == NEW_USER_ASK_OWNER) {
      goto do_ask;
    }
    goto close;
  case NEW_USER_ASK:
    if ($is_manager) {
      break;
    }
    echo '
<br>Only managers and the owner of this folder may add new members to it.';
    goto do_ask;
  case NEW_USER_ASK_MANAGER:
    if ($is_manager) {
      break;
    }
    echo '
<br>Only the managers of this folder may add new members to it.';
do_ask:
    if ($member_id != $gUserid) {
      echo '
<br>You are not the user who you are seeking to add to this folders membership.
<br>Only the user to be granted membership may request it.';
      goto close;
    }
    echo '
<br>If you wish to send an email to those that may grant you priviledged
access to this folder, <a href=request.php>click here</a>.';
    goto close;
  case NEW_USER_ANYONE:
    break;
  default:
    echo '
<br>This folder permits no new group members to be added to it.';
    if ($is_owner) {
      echo '
<br>As the owner of this folder
<a href=folder_permissions.php>click here</a> to change this setting.';
    }
    goto close;
  }
  $query =
'select default_folder_permissions as folder_permissions,
       default_mask_owns as mask_owns,
       default_mask_may_see as mask_may_see,
       default_mask_may_read as mask_may_read,
       default_mask_may_post as mask_may_post,
       default_mask_may_copy as mask_may_copy,
       default_mask_may_update as mask_may_update,
       default_mask_may_delete as mask_may_delete,
       default_mask_may_comment as mask_may_comment,
       default_mask_may_read_comments as mask_may_read_comments,
       default_mask_manage_comments as mask_manage_comments,
       default_mask_may_x_post as mask_may_x_post,
       creator_user_id
  from folders
 where folder_id = ' . DBnumber($folder_id);
  $ret = DBquery($query);
  if (!$ret) {
    goto close;
  }
  $member = DBfetch($ret);
  if (!$member) {
    echo '
<br>Folder ', $path, ' not found';
    goto close;
  }
  $member['creator_user_id'] = $gUserid;

  $query =
'select default_monitors as monitors,
       default_owns,
       default_may_see,
       default_may_read,
       default_may_post,
       default_may_copy,
       default_may_update,
       default_may_delete,
       default_may_comment,
       default_may_read_comments,
       default_manage_comments,
       default_may_x_post
  from users
 where user_id = ' . DBstring($member_id);
  $ret = DBquery($query);
  if (!$ret) {
    goto close;
  }
  $row = DBfetch($ret);
  if (!$row) {
    echo '
<br>User ', htmlspecialchars($gUserid), ' not found.';
    goto close;
  }
  foreach ($row as $name => $value) {
    $member[$name] = $value;
} }

echo '
<p>
<form action="membership.php" method="post">
<input type=hidden name=mode value=y />';
hidden('member_id');
hidden('member');
echo '
<p>
<h3>Folder options</h3>
<p>
The values specified below indicate what operation '; 
  if ($member_id == $gUserid) {
    echo 'you may';
  } else {
    echo htmlspecialchars($member_id), ' may';
  }
  echo ' perform on the folder ', $path, '.
<table>';

  $shift    = 0;
  $folder_permissions = $member['folder_permissions'];
  foreach($folder_options as $name => $value) {
    echo '
<tr>
<td align=right>', $value, ':</td>
<td><input type=checkbox name=', $name, bit_checked($folder_permissions, $shift);
    if (!$is_manager) {
      echo ' disabled';
    }
    echo ' /></td>
</tr>';
    ++$shift;
  }
  if ($is_manager) {
    echo '
<tr>
<td>
<input type=submit name=send value=Set />
<input type=submit name=all_folder value=All />
<input type=submit name=no_folder value=None />
<input type=reset />
</td>
</tr>';
  }
  echo '
</table>
<p>
<h3>Monitor options</h3>
<p>
The values specified below indicate what email notification ';
if ($member_id == $gUserid) {
  echo 'you wish';
} else {
  echo htmlspecialchars($member_id), ' wishes';
}
echo ' to receive when events occur in ', $path, '.
<table>';

$shift    = 0;
$monitors = $member['monitors'];
foreach($monitor_options as $name => $value) {
  echo '
<tr>
<td align=right>', $value, ':</td>
<td><input type=checkbox name=', $name, bit_checked($monitors, $shift);
  if ($gUserid != $member_id) {
    echo ' disabled';
  }
  echo ' /></td>
</tr>';
  ++$shift;
}
if ($member_id == $gUserid) {
  echo '
<td>
<input type=submit name=send value=Set />
<input type=submit name=all_monitor value=All />
<input type=submit name=no_monitor value=None />
<input type=reset />
</td>';
}
echo '
</table>';
if ($member_id == $folder_owner_id) {
  echo '
<input type=hidden name=all_masks value=y />';
  foreach($annotate_options as $name => $value) {
    $member['mask_' .$name] = 0xFF;
  }
} else {
  echo '
<h3>Permitted Annotation Permissions</h3>
<p>
The permissions that annotations created by ', htmlspecialchars($member_id),
' may be granted when in ', $path, '.
<p>
<table border=1>
<tr><th></th><th>Creator<br>(', htmlspecialchars($member_id), ')</th><th>ImageMAT<br>System</th><th>Owner<br>(', htmlspecialchars($folder_owner_id), ')</th><th>Folder<br>Managers</th><th>Folder<br>Vetters</th><th>Folder<br>Members</th><th>Creator\'s<br>Followers</th><th>World<br>(Anyone)</th></tr>';

  foreach($annotate_options as $name => $value) {
    $bits = $member['mask_' .$name];
    echo '
<tr>
<td align=right>', $value, ':</td>';
    for ($shift = 0; $shift < 8; ++$shift) {
      $add_star = false;  
      $box      = null;
      switch ($name) {
      case 'owns':
        if ($shift == 1 || $shift == 2) {
          $add_star = true;
          break;
        }
      case 'may_see':
        if ($shift == 0) {
          $box = '<td>YES</td>';
          break;
      } } 
      if ($box) {
        echo $box;
      } else {
        echo '
<td><input type=checkbox name=mask_', $name , '[', $shift, '] ', bit_checked($bits, $shift);
        if (!$is_manager) {
          echo ' disabled';
        }
        echo ' />';
        if ($add_star) {
          echo '<font color=red>*</font>';
        }
        echo '</td>';
    } }
    echo '
</tr>';
  }
  echo '
</table>';
  if ($is_manager) {
    echo '
<td><input type=submit name=send value=Set />
<td><input type=submit name=all_masks value=All />
<td><input type=submit name=no_masks value=None />
<td><input type=submit name=default_masks value=Default />
<input type=reset /></td>';
} }
echo '
<h3>Default Annotation Permissions for ', htmlspecialchars($member_id), '</h3>
<p>
The permissions that annotations created by ', htmlspecialchars($member_id),
' may be granted when in ', $path, '.
<p>
<table border=1>
<tr><th></th><th>Creator<br>(', htmlspecialchars($member_id), ')</th><th>ImageMAT<br>System</th><th>Owner<br>(', htmlspecialchars($folder_owner_id),')</th><th>Folder<br>Managers</th><th>Folder<br>Vetters</th><th>Folder<br>Members</th><th>Creator\'s<br>Followers</th><th>World<br>(Anyone)</th></tr>';

foreach($annotate_options as $name => $value) {
  $bits  = $member['default_' . $name];
  $mask  = $member['mask_' . $name];
  echo '
<tr>
<td align=right>', $value, ':</td>';
  for ($shift = 0; $shift < 8; ++$shift) {
    $add_star = false;  
    $box      = null;
    switch ($name) {
    case 'owns':
      if ($shift == 1 || $shift == 2) {
        $add_star = true;
        break;
      }
    case 'may_see':
      if ($shift == 0) {
        $box = '<td>YES</td>';
        break;
    } } 
    if ($box) {
      echo $box;
    } else if (($mask & (1 << $shift)) == 0) {
      echo '<td>NO</td>';
    } else {
      echo '
<td><input type=checkbox name=', $name , '[', $shift, '] ', bit_checked($bits, $shift);
      if ($member_id != $gUserid) {
        echo ' disabled';
      }
      echo ' />';
      if ($add_star) {
        echo '<font color=red>*</font>';
      }
      echo '</td>';
  } }
  echo '
</tr>';
}
echo '
</table>';
if ($member_id == $gUserid) {
  echo '
<td><input type=submit name=send value=Set />
<td><input type=submit name=all_permissions value=All />
<td><input type=submit name=no_permissions value=None />
<td><input type=submit name=default_permissions value=Default />
<input type=reset /></td>';
}
echo '
</form>
<p>
<font color=red>*</font>&nbsp;The owner of a folder is always at liberty to delete any entry in their folder, or to delete the folder itself.
<p>
<font color=red>*</font>&nbsp;
ImageMAT reserves the right to police all data stored on its servers.
<br>
<font color=red>*</font>&nbsp;
Granting ImageMAT the right to read annotation permits their
transfer to alternative annotation software.
<br>
<font color=red>*</font>&nbsp;
It also permits sharing with those managing images
that the annotation cites.';

close:
DBclose();
done:
?>
</body>
</html>

