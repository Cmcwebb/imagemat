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

echo HtmlHeader('Default Folder Permissions') , '
</head>
<body>';

if (!DBconnect()) {
  goto done;
}

require_once($dir . '/../include/permissions.php');

$folder_id = get_folder_id();
if (!$folder_id) {
  goto close;
}

$getpath = getpath($folder_id);
if (!$getpath) {
  goto close;
}
$parent_folder_id = $getpath['parent_id'];
$path = htmlspecialchars($getpath['path']);

if (!isset($parent_folder_id)) {
  echo '
<br>You are not permitted to alter settings on the "/" folder.';
  goto close;
}

require_once($dir . '/../include/groups.php');

$folder_owner_id  = null;
$user_member      = resolve_member($gUserid, $folder_id, $folder_owner_id);
if (!isset($user_member)) {
  goto close;
}
if ($folder_owner_id != $gUserid) {
  if (!$user_member || !($user_member['folder_permissions'] & MANAGER)) {
    echo '
<br>Only the creator of the folder ', $path, ' or one of those individuals
designated a manager of this folder may change the permissions on this folder.';
    goto close;
} }

$mode = getpost('mode');
if (isset($mode)) {
  $row                 = getpost('row');
  $parent_folder_id    = $row['parent_folder_id'];
  $new_inherits_change = false;
  $new_users_change    = false;
  $folder_change       = false;
  $annotate_change     = false;

  if ($parent_folder_id == 1) {
    $new_inherits = $row['inherits'];
    goto have_inherit;
  }
  $new_inherits = 0;
  $button = getpost('no_inherit');
  if (isset($button)) {
    goto have_inherit;
  }
  $button = getpost('all_inherit');
  if (isset($button)) {
    $new_inherits = 127;
    goto have_folder;
  }
  $shift = 0;
  foreach($folder_options as $name => $value) {
    $permission = getpost('inherit_' . $name);
    if (isset($permission)) {
      $new_inherits |= (1 << $shift);
    }
    ++$shift;
  }
  $permission = getpost('inherit_members');
  if (isset($permission)) {
    $new_inherits |= (1 << $shift);
  }
have_inherit:
  if ($new_inherits != $row['inherits']) {
    $new_inherits_change = true;
  }

  $new_users        = getpost('new_users');
  if ($new_users != $row['new_users']) {
    $new_users_change = true;
  }

  $new_folder_permissions = 0;
  $button = getpost('no_folder');
  if (isset($button)) {
    goto have_folder;
  }
  $button = getpost('all_folder');
  if (isset($button)) {
    $new_folder_permissions = 255;
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
  if ($new_folder_permissions != $row['default_folder_permissions']) {
    $folder_change = true;
  }

  $new_annotate_permissions = array();
  $button                   = getpost('default_permissions');
  if (isset($button)) {
    foreach ($annotate_defaults as $name => $value) {
      $new_annotate_permissions[$name] = $value;
    }
    goto have_new;
  }
  $button = getpost('all_permissions');
  if (isset($button)) {
    foreach ($annotate_defaults as $name => $value) {
      $new_annotate_permissions[$name] = 255;
    }
    goto have_new;
  }
  $button = getpost('no_permissions');
  if (isset($button)) {
    foreach ($annotate_defaults as $name => $value) {
      $new_annotate_permissions[$name] = 0;
    }
    goto have_new;
  }
  foreach($annotate_options as $name => $value) {
    $value = 0;
    $permission = getpost($name);
    if (isset($permission)) {
      for ($shift = 0; $shift < 8; ++$shift) {
        if (isset($permission[$shift])) {
          $value |= (1 << $shift);
    } } }
    $new_annotate_permissions[$name] = $value;
  }
have_new:
  $new_annotate_permissions['owns']    |= 1;
  $new_annotate_permissions['may_see'] |= 1;

  foreach($new_annotate_permissions as $name => $value) {
    if ($value != $row[$name]) {
      $annotate_change = true;
      break;
  } }
   
  echo '
<h3>Update permissions for folder ', $path, '</h3>';

  if ($new_inherits_change || $new_users_change || $folder_change || $annotate_change) {
    $query =
'update folders set
new_users = ' . DBnumberC($new_users);
    if ($parent_folder_id != 1) {
      $query .= '
inherits  = ' . DBnumberC($new_inherits);
    }
    $query .= '
default_folder_permissions = ' . DBnumberC($new_folder_permissions) . '
default_mask_owns = ' . DBnumberC($new_annotate_permissions['owns']) . '
default_mask_may_see = ' . DBnumberC($new_annotate_permissions['may_see']) . '
default_mask_may_read = ' . DBnumberC($new_annotate_permissions['may_read']) . '
default_mask_may_post = ' . DBnumberC($new_annotate_permissions['may_post']) . '
default_mask_may_copy = ' . DBnumberC($new_annotate_permissions['may_copy']) . '
default_mask_may_update = ' . DBnumberC($new_annotate_permissions['may_update']) . '
default_mask_may_delete = ' . DBnumberC($new_annotate_permissions['may_delete']) . '
default_mask_may_read_comments = ' . DBnumberC($new_annotate_permissions['may_read_comments']) . '
default_mask_may_comment = ' . DBnumberC($new_annotate_permissions['may_comment']) . '
default_mask_manage_comments = ' . DBnumberC($new_annotate_permissions['manage_comments']) . '
default_mask_may_x_post = ' . DBnumber($new_annotate_permissions['may_x_post']) . '
 where folder_id = ' . DBnumber($folder_id);

    DBupdate1($query);
  }
  if ($parent_folder_id != 1) {
    if ($new_inherits_change) {
      echo '
<br><font color=blue>Your inherits option has been updated</font>';
    } else {
      echo '
<br><font color=blue>Your inherits option is unchanged</font>';
  } }
  if ($new_users_change) {
    echo '
<br><font color=blue>Your new group members option has been updated</font>';
  } else {
    echo '
<br><font color=blue>Your new group members option is unchanged</font>';
  }
  if ($folder_change) {
    echo '
<br><font color=blue>Your folder options have been updated</font>';
  } else {
    echo '
<br><font color=blue>Your folder options are unchanged</font>';
  }
  if ($annotate_change) {
    echo '
<br><font color=blue>Your annotation permissions have been updated</font>';
  } else {
    echo '
<br><font color=blue>Your annotation permissions are unchanged</font>';
  }
} else {
  echo '
<h3>Default options for folder ', $path, '</h3>';
}

$query = 
'select parent_folder_id,
       new_users,
       inherits,
       default_folder_permissions,
       default_mask_owns as owns,
       default_mask_may_see as may_see,
       default_mask_may_read as may_read,
       default_mask_may_post as may_post,
       default_mask_may_copy as may_copy,
       default_mask_may_update as may_update,
       default_mask_may_delete as may_delete,
       default_mask_may_read_comments as may_read_comments,
       default_mask_may_comment as may_comment,
       default_mask_manage_comments as manage_comments,
       default_mask_may_x_post as may_x_post,
       creator_user_id
  from folders
 where folder_id = ' . DBnumber($folder_id);

$ret = DBquery($query);
if (!$ret) {
  goto close;
}
$row = DBfetch($ret);
if (!$row) {
  echo '
<br>Folder id ' . htmlspecialchars($folder_id) . ' does not exist';
  goto close;
}

echo '
<form action="folder_permissions.php" method="post">
<input type=hidden name=mode value=y />';
hidden('row');
if ($parent_folder_id != 1) {
  $new_inherits = $row['inherits'];
  echo '
<h3>Inherits</h3>
<p>
The bit settings below indicate what properties associated with being a group
member in the parent folder are presumed to also infer group membership with
the same properties in this folder.
<table>';

  $shift    = 0;
  foreach($folder_options as $name => $value) {
    echo '
<tr>
<td align=right>', $value, ':</td>
<td><input type=checkbox name=inherit_', $name, bit_checked($new_inherits, $shift), ' /></td>
</tr>';
    ++$shift;
  }
  echo '
<tr>
<td align=right>Members:</td>
<td><input type=checkbox name=inherit_members', bit_checked($new_inherits, $shift), ' /></td>
</tr>
<tr>
<td>
<input type=submit name=send value=Set />
<input type=submit name=all_inherit value=All />
<input type=submit name=no_inherit value=None />
<input type=reset />
</td>
</tr>
</table>
<p>';
}

$new_users = $row['new_users'];
echo '
<h3>New Group Members</h3>
<p>
<select name=new_users>';
foreach ($new_user_options as $shift => $value) {
  echo '
<option value=', $shift, '';
  if ($new_users == $shift) {
    echo ' selected';
  }
  echo '>', $value, '</option>';
}
echo '
</select>
<input type=submit name=send value=Set />
<p>';

switch ($new_users) {
case FOLDER_NOT_VISIBLE:
case NEW_USER_NO:
  echo '
</form>';
  goto close;
}
echo '
<p>
The values specified below indicate the type of actions that individuals other
than yourself as creator of ', $path, 
' will be permitted by you to perform on this your folder.  There are no
restrictions imposed on what the creator of a folder may subsequently do
with this folder, or on what permissions are permitted on annotations they
subsequently place in this folder
<p>
<h3>Folder options</h3>
<p>
Folder options specify what actions by default members other than the creator
of a folder are permitted to perform on this folder. This default value will
be recorded as part of each group member\'s information, when they become a
member of the group permitted to access this folder.  It may subsequently be 
independently modified as needed for each such member of the group.
<table>';

$shift = 0;
$default_folder_permissions = $row['default_folder_permissions'];
foreach($folder_options as $name => $value) {
  echo '
<tr>
<td align=right>', $value, ':</td>
<td><input type=checkbox name=', $name, bit_checked($default_folder_permissions, $shift), ' /></td>
</tr>';
  ++$shift;
}
echo '
<td>
<input type=submit name=send value=Set />
<input type=submit name=all_folder value=All />
<input type=submit name=no_folder value=None />
<input type=reset />
</td>
</table>

<h3>Annotation Permissions</h3>
<p>
The permissions associated with the annotations that others place
in your folder ', $path, ' may only be some subset of those permissions specified here. These
values are merely defaults.  Each group member with access to this folder
may have their own restrictions as specified by the creator of this folder
or by those designated as managing it. The creator of a folder may assign
annotations any desired permissions.
<p>
<table border=1>
<tr><th></th><th>Creator<br>(Not You)</th><th>ImageMAT<br>System</th><th>Owner<br>(', htmlspecialchars($folder_owner_id), ')</th><th>Folder<br>Managers</th><th>Folder<br>Vetters</th><th>Folder<br>Members</th><th>Creator\'s<br>Followers</th><th>World<br>(Anyone)</th></tr>';

foreach($annotate_options as $name => $value) {
  $value = $row[$name];
  echo '
<tr>
<td align=right>', $name, ':</td>';
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
<td><input type=checkbox name=', $name , '[', $shift, '] ', bit_checked($value, $shift), ' />';
      if ($add_star) {
        echo '<font color=red>*</font>';
      }
      echo '</td>';
  } }
  echo '
</tr>';
}
echo '
</table>
<td><input type=submit name=send value=Set />
<td><input type=submit name=all_permissions value=All />
<td><input type=submit name=no_permissions value=None />
<td><input type=submit name=default_permissions value=Default />
<input type=reset /></td>
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

