<?php

function create_home()
{
  global $gUserid;

  $query = 
'insert ignore into folders
(parent_folder_id, name, new_users, inherits, default_folder_permissions,
 default_mask_owns,default_mask_may_see,default_mask_may_read,
 default_mask_may_post, default_mask_may_copy, default_mask_may_update,
 default_mask_may_delete, default_mask_may_comment,
 default_mask_may_read_comments, 
 default_mask_manage_comments, default_mask_may_x_post,
 creator_user_id, created)
select
 1, user_id, 3, 0, 143,
 default_owns,default_may_see,default_may_read,
 default_may_post, default_may_copy, default_may_update,
 default_may_delete, default_may_comment,
 default_may_read_comments, 
 default_manage_comments, default_may_x_post,
 user_id, utc_timestamp()
  from users
 where user_id = ' . DBstring($gUserid);

  $ret = DBquery($query);

  $folder_id = DBid();
  if (!$folder_id) {
    $query =
'select folder_id
  from folders
 where name = ' . DBstring($gUserid) . '
   and parent_folder_id is null';
    $ret = DBquery($query);
    if ($ret) {
      $row = DBfetch($ret);
      if ($row) {
        $folder_id = $row['folder_id'];
    } }
    if (!$folder_id) {
      echo '
<br>Unable to identify root folder for ', htmlspecialchars($gUserid);
      return false;
  } }

  $query =
'insert into groups
(user_id, folder_id, folder_permissions, monitors,
 mask_owns, mask_may_see, mask_may_read, mask_may_post,
 mask_may_copy, mask_may_update, mask_may_delete, mask_may_comment,
 mask_may_read_comments, mask_manage_comments, mask_may_x_post,
 default_owns, default_may_see, default_may_read, default_may_post,
 default_may_copy, default_may_update, default_may_delete, default_may_comment,
 default_may_read_comments, default_manage_comments, default_may_x_post,
 creator_user_id, created)
values ( ' 
 . DBstringC($gUserid) . DBnumberC($folder_id) . '255, 0,
 255, 255, 255, 255, 255, 255, 255, 255, 255, 255,255,
 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255,
' . DBstringC($gUserid) . 'utc_timestamp())';

  $ret = DBquery($query);
  return $ret;
}

function home_folder()
{
  global $gUserid;

  for ($cnt = 0; ; ++$cnt) {
    $query = 
'select folder_id
  from folders
 where creator_user_id = ' . DBstring($gUserid) . '
   and parent_folder_id = 1';
    $ret = DBquery($query);
    if (!$ret) {
      return false;
    }
    $row = DBfetch($ret);
    if ($row) {
      break;
    }
    if ($cnt != 0) {
      return false;
    }
    if (!create_home()) {
      return false;
    }
  }
  return $row['folder_id'];
}

function cwd()
{
  if (!isset($_SESSION['imageMAT_cwd'])) {
    $ret = home_folder();
    if (!$ret) {
      return false;
    }
    $_SESSION['imageMAT_cwd'] = $ret;
  }
  return $_SESSION['imageMAT_cwd'];
}

function get_folder_id()
{
  $folder_id = getparameter('folder_id');
  if ($folder_id) {
    $_SESSION['imageMAT_cwd'] = $folder_id;
  } else {
    $folder_id = cwd();
    if (!$folder_id) {
      echo '
<br><font color=red>Can\'t determine current working directory</font>';
  } }
  return $folder_id;
}

function getpath($folder_id)
{
  if ($folder_id == 1) {
    return array('path' => '/', 'parent_id' => null, 'owner' => null, 'name' => '/');
  }
  $query = 
'select parent_folder_id, name, creator_user_id
  from folders
 where folder_id = ' . DBnumber($folder_id);
  $ret = DBquery($query);
  if (!$ret) {
    return null;
  }
  $row = DBfetch($ret);
  if (!$row) {
    echo '
<br>Unable to locate folder with id ', htmlspecialchars($folder_id);
    return null;
  }
  $parent_folder_id = $row['parent_folder_id'];
  $array = getpath($parent_folder_id);
  if (!$array) {
    return null;
  }
  if ($parent_folder_id != 1) {
    $array['path'] .= '/';
  }
  $name           = $row['name'];
  $array['path'] .= $name;
  $array['name']  = $name;
  $array['parent_id'] = $parent_folder_id;
  $array['owner']  = $row['creator_user_id'];
  return $array;
}  

function ls($parent_folder_id, $folder_id, $choices=true)
{
  if (!$folder_id) {
    $parent_folder_id = 0;
    $folder_id        = 1;
  }
echo '
<ul>';

  $query =
'select folder_id, name, description
  from folders
 where parent_folder_id = ' . DBnumber($folder_id);
  $ret = DBquery($query);
  if (!$ret) {
    goto done;
  }
  if ($parent_folder_id > 0) {
    $row = array(
 'folder_id'   => $parent_folder_id,
 'name'        => '..',
 'description' => null);
  } else {
    $row = DBfetch($ret);
  }
  for (; $row; $row = DBfetch($ret)) {
    $child_id    = $row['folder_id'];
    $name        = $row['name'];
    $description = $row['description'];
    echo '
<li>',
 htmlspecialchars($child_id), ': <a href="../folder/ls.php?folder_id=',
 htmlspecialchars($child_id), '">',
 htmlspecialchars($name), '</a>';
    if ($description) {
      echo ' (', htmlspecialchars($description), ')';
    }
    echo ' (folder)</li>';
  }

  $query =
'select target_folder_id, name
  from symlinks
 where parent_folder_id = ' . DBnumber($folder_id);
  $ret = DBquery($query);
  if (!$ret) {
    goto done;
  }
  while ($row = DBfetch($ret)) {
    $child_id    = $row['target_folder_id'];
    $name        = $row['name'];
    echo '
<li><font color=aqua>',
 htmlspecialchars($child_id), ': </font>
<a href="../folder/ls.php?folder_id=', htmlspecialchars($child_id), '">@',
 htmlspecialchars($name), ' (symlink)</a></li>';
  }

  $query =
'select user_id
  from groups
 where folder_id = ' . DBnumber($folder_id);
  $ret = DBquery($query);
  if (!$ret) {
    goto done;
  }
  while ($row = DBfetch($ret)) {
    $user = $row['user_id'];
    echo '
<li><a href=../folder/membership.php?folder_id="',
 htmlspecialchars($folder_id), '"&member_id="',
 htmlspecialchars($user), '">',
 htmlspecialchars($user), ' (member)</a></li>';
  }

  $query =
'select user_id
  from vetters
 where folder_id = ' . DBnumber($folder_id);
  $ret = DBquery($query);
  if (!$ret) {
    goto done;
  }
  while ($row = DBfetch($ret)) {
    $user = $row['user_id'];
    echo '
<li><a href=../vetting/changevetter.php?folder_id="',
 htmlspecialchars($folder_id), '"&user="', 
 htmlspecialchars($user), '">',
 htmlspecialchars($user), '</a> (vetter)</li>';
  }

  $query =
'select foldersannotations.annotation_id, title
  from foldersannotations, annotations
 where foldersannotations.annotation_id = annotations.annotation_id
   and folder_id = ' . DBnumber($folder_id) . '
   and annotations.annotation_deleted is null';
  $ret = DBquery($query);
  if (!$ret) {
    goto done;
  }
  while ($row = DBfetch($ret)) {
    $annotation_id = $row['annotation_id'];
    echo '
<li><font color=green>',
 htmlspecialchars($annotation_id), ':</font> <a href="../annotate/changeannotation.php?folder_id="',
 htmlspecialchars($folder_id), '"&annotation_id="',
 htmlspecialchars($annotation_id), '">', 
 htmlspecialchars($row['title']), ' (annotation)</a></li>';
  }

  $query =
'select foldersurls.url_id, urls.url
  from foldersurls, urls
 where foldersurls.url_id = urls.url_id
   and foldersurls.folder_id = ' . DBnumber($folder_id);
  $ret = DBquery($query);
  if (!$ret) {
    goto done;
  }
  while ($row = DBfetch($ret)) {
    echo '
<li><font color=maroon>', 
 htmlspecialchars($row['url_id']), 
 '</font>: <a href=../folder/changeurl.php?folder_id="',
 htmlspecialchars($folder_id), '"&url_id="',
 htmlspecialchars($url_id), '">',
 htmlspecialchars($row['url']), '(url)</a></li>';
  }
done:
  if ($choices) {
    echo '
</ul>
<p>
Select the item above you wish to navigate to or the command you wish to use.
<p>
<table cellpadding=10>
<tr>
<td><a href=../folder/mkdir.php>mkdir</a></td>
<td><a href=../folder/rmdir.php>rmdir</a></td>
<td><a href=../folder/cd.php>cd</a></td>
<td><a href=../folder/ln.php>ln</a></td>
<td><a href=../folder/rmln.php>rmln</a></td>
</tr>
<tr>
<td><a href=../folder/addannotation.php>add annotation</a></td>
<td><a href=../folder/rmannotation.php>rm annotation</a></td>
<td><a href=../folder/addurl.php>add url</a></td>
<td><a href=../folder/rmurl.php>rm url</a></td>
</tr>
<tr>
<td><a href=../folder/membership.php>membership</a></td>
</tr>
</table>';
  }
  return $ret;
}

function find_folder_id($cwd, $path, $verbose)
{
  if (!isset($path)) {
	if ($verbose) {
      echo 'No path provided to find_folder';
    }
    return false;
  }
  $lth = strlen($path);
  if (!$lth) {
    if ($verbose) {
      echo 'Empty path provided to find_folder';
    }
    return false;
  }
  $name = $path;
  switch ($name[0]) {
  case '/':
    $folder_id = 1;
    if ($lth == 1) {
      $rest = null;
    } else {
      $rest = substr($name, 1);
    }
	break;
  case '~':
	$rest = $name;
	break;
  default:
    if (isset($cwd)) {
      $folder_id = $cwd;
      $rest = $path;
    } else {
      $rest = '~/' . $path;
    }
    break;
  }
  while ($name = $rest) {
    $pos = strpos($name, '/');
    if ($pos === false) {
      $rest = null;
    } else if ($pos > 0) {
      $rest = substr($name, $pos+1);
      if ($rest == '') {
        $rest = null;
      }
      $name = substr($name, 0, $pos);
    } else {
      $rest = substr($name, 1);
      continue;
    }
    if ($name == '.') {
      continue;
    }
    if ($name[0] == '~') {
      if ($name == '~') {
        $folder_id = home_folder();
        continue;
      }
      $query = 
'select folder_id
  from folders
 where parent_folder_id = 1
   and name = ' . DBstring(substr($name,1));
      $ret = DBquery($query);
      if (!$ret) {
        return false;
      }
      $row = DBfetch($ret);
    } else if ($name == '..') {
      if ($folder_id == 1) {
		if ($verbose) {
          echo 'Attempting to navigate above root';
		}
        return false;
      }
      $query =
'select parent_folder_id as folder_id
   from folders
  where folder_id = ' . DBnumber($folder_id);
      $ret = DBquery($query);
      if (!$ret) {
        return false;
      }
      $row = DBfetch($ret);
    } else {
      $query =
'select folder_id
  from folders
 where parent_folder_id = ' . DBnumber($folder_id) . '
   and name = ' . DBstring($name);
      $ret = DBquery($query);
      if (!$ret) {
        return false;
      }
      $row = DBfetch($ret);
      if (!$row) {
        $query =
'select target_folder_id as folder_id
  from symlinks
 where parent_folder_id = ' . DBnumber($folder_id) . '
   and name             = ' . DBstring($name);
        $ret = DBquery($query);
        if (!$ret) {
          return false;
        }
        $row = DBfetch($ret);
    } }

    if (!$row) {
	  if ($verbose) {
        echo 'Unable to locate "', 
 htmlspecialchars($name), '" in path "', 
 htmlspecialchars($path), '"';
	  }
      return false;
    }
    $folder_id = $row['folder_id'];
  }
  return $folder_id;
}

?>
