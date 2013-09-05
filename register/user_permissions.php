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

htmlHeader('Default User Permissions');
srcStylesheet('../css/style.css');
?>
</head>
<body>

<?php

bodyHeader();

if (!DBconnect()) {
  goto done;
}

require_once($dir . '/../include/permissions.php');

$mode = getpost('mode');
if (isset($mode)) {
  $row             = getpost('row');
  $monitor_change  = false;
  $annotate_change = false;

  $new_monitors = 0;
  $button = getpost('no_monitor');
  if (isset($button)) {
    goto have_monitor;
  }
  $button = getpost('all_monitor');
  if (isset($button)) {
    $new_monitors = 255;
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
  if ($new_monitors != $row['default_monitors']) {
    $monitor_change = true;
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
<h3>Update permissions for ', htmlspecialchars($gUserid), '</h3>';

  if ($monitor_change || $annotate_change) {
    $query =
'update users set
default_monitors = ' . DBnumberC($new_monitors) . '
default_owns = ' . DBnumberC($new_annotate_permissions['owns']) . '
default_may_see = ' . DBnumberC($new_annotate_permissions['may_see']) . '
default_may_read = ' . DBnumberC($new_annotate_permissions['may_read']) . '
default_may_post = ' . DBnumberC($new_annotate_permissions['may_post']) . '
default_may_copy = ' . DBnumberC($new_annotate_permissions['may_copy']) . '
default_may_update = ' . DBnumberC($new_annotate_permissions['may_update']) . '
default_may_delete = ' . DBnumberC($new_annotate_permissions['may_delete']) . '
default_may_read_comments = ' . DBnumberC($new_annotate_permissions['may_read_comments']) . '
default_may_comment = ' . DBnumberC($new_annotate_permissions['may_comment']) . '
default_manage_comments = ' . DBnumberC($new_annotate_permissions['manage_comments']) . '
default_may_x_post = ' . DBnumber($new_annotate_permissions['may_x_post']) . '
 where user_id = ' . DBstring($gUserid);

    DBupdate1($query);
  }
  if ($monitor_change) {
    echo '
<br><font color=blue>Your monitor options have been updated</font>';
  } else {
    echo '
<br><font color=blue>Your monitor options are unchanged</font>';
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
<h3>Default options for ', htmlspecialchars($gUserid), '</h3>';
}

$query = 
'select default_monitors,
        default_owns as owns,
        default_may_see as may_see,
        default_may_read as may_read,
        default_may_post as may_post,
        default_may_copy as may_copy,
        default_may_update as may_update,
        default_may_delete as may_delete,
        default_may_read_comments as may_read_comments,
        default_may_comment as may_comment,
        default_manage_comments as manage_comments,
        default_may_x_post as may_x_post
  from users
 where user_id = ' . DBstring($gUserid);

$ret = DBquery($query);
if (!$ret) {
  goto close;
}
$row = DBfetch($ret);
if (!$row) {
  echo '
<br>User id ' . htmlspecialchars($gUserid) . ' does not exist';
  goto close;
}

echo '
<h3>Monitor options</h3>
<p>
The extent to which you wish to receive email notification when events occur
within a folder which you are a member of can be specified globally here.
The values provided here are merely initial defaults.
You may at any time change your specific monitoring options on any folder
that you are a group member of.
<p>
<form action="user_permissions.php" method="post">
<input type=hidden name=mode value=y />';
hidden('row');
echo '
<table>';

$shift = 0;
$default_monitors = $row['default_monitors'];
foreach($monitor_options as $name => $value) {
  echo '
<tr>
<td align=right>', $value, ':</td>
<td><input type=checkbox name=', $name, bit_checked($default_monitors, $shift), ' /></td>
</tr>';
  ++$shift;
}
echo '
<td>
<input type=submit name=send value=Set />
<input type=submit name=all_monitor value=All />
<input type=submit name=no_monitor value=None />
<input type=reset />
</td>
</tr>
</table>

<h3>Annotation Permissions</h3>
<p>
The initial permissions associated with the annotations that you place
in a folder are the intersection
of that that you would like to grant your annotations, with those that the
creator of the folder is willing to let you grant your annotations. The
values specified here are merely defaults.
You
may at any time change the permissions on any annotation within any folder,
within the limits that the creator of the folder has approved your annotations
to have.
<p>
<table border=1>
<tr><th></th><th>Creator<br>(You)</th><th>Image<br>MAT</th><th>Folder<br>Owner</th><th>Folder<br>Managers</th><th>Folder<br>Vetters</th><th>Folder<br>Members</th><th>Creator\'s<br>Followers</th><th>World<br>(Anyone)</th></tr>';

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
<table>
<tr>
<td><input type=submit name=send value=Set />
<td><input type=submit name=all_permissions value=All />
<td><input type=submit name=no_permissions value=None />
<td><input type=submit name=default_permissions value=Default />
<input type=reset /></td>
</form>
</tr>
</table>
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
bodyFooter();
?>

</body>
</html>

