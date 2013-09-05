<!DOCTYPE HTML>
<?php

$gPHPscript = __FILE__;
$dir = dirname(__FILE__);
require_once($dir . '/../include/boilerplate.php');
require_once($dir . '/../include/util.php');

function visit_children($id, $r)
{
  if (!$r) {
    $query = 
'select folder_id
  from foldersannotations
 where folder_id = ' . $id;

    $ret = DBquery($query);
    if (!$ret) {
      return false;
    }
    $row = DBfetch($ret);
    if ($row) {
      echo 'Annotations below folder';
      return false;
    }
    $query =
'select parent_folder_id
  from symlinks
 where parent_folder_id = ' . $id;

    $ret = DBquery($query);
    if (!$ret) {
      return false;
    }
    $row = DBfetch($ret);
    if ($row) {
      echo 'Symlinks below folder';
      return false;
    }
    $query =
'select folder_id
  from foldersurls
 where folder_id = ' . $id;

    $ret = DBquery($query);
    if (!$ret) {
      return false;
    }
    $row = DBfetch($ret);
    if ($row) {
      echo 'URLS below folder';
      return false;
  } }

  $query = 
'select folder_id
  from folders
 where parent_folder_id = ' . $id;

  $ret = DBquery($query);
  if (!$ret) {
    return false;
  }

  while ($row = DBfetch($ret)) {
    if (!$r) {
      echo 'Folder contains sub-folders';
      return false;
    }
    if (!visit_children($row['folder_id'], $r)) {
      return false;
  } }

  $query =
'delete from foldersurls
 where folder_id = ' . $id;

  $ret = DBquery($query);
  if (!$ret) {
    return false;
  }

  $query =
'delete from symlinks
 where parent_folder_id = ' . $id;

  $ret = DBquery($query);
  if (!$ret) {
    return false;
  }
  $query =
'delete from foldersannotations
 where folder_id = ' . $id;

  $ret = DBquery($query);
  if (!$ret) {
    return false;
  }

  $query =
'delete from symlinks
 where target_folder_id = ' . $id;

  $ret = DBquery($query);
  if (!$ret) {
    return false;
  }

  $query =
'delete from folders
 where folder_id = ' . $id;

  $ret = DBquery($query);
  if (!$ret) {
    return false;
  }
  return true;
}

if (mustlogon()) {
  return;
}

$folder_id = getparameter('folder_id');
if (!isset($folder_id)) {
  header('Location: ' . dirname($_SERVER['REQUEST_URI']) . '/searchFolders.php');
  return;
}

htmlHeader('Delete Folder');

require_once($dir . '/../include/db.php');
srcStylesheet(
  '../css/style.css'
);
srcJavascript(
  '../js/fullwidth.js',
  '../js/enterkey.js',
  '../js/edit.js',
  '../js/folder.js'
);
enterJavascript();

echo '
var folder_id = ', $folder_id, ';
var myScript  = \'', scriptName(), '\';
';
?>

function deleted_folder(parent_id, folder_id)
{
  if (self != top) {
    // TODO make safer - can't in general assume frames[0] is right
    top.frames[0].done_delete_folder(parent_id, folder_id); 
} }

function loaded()
{
  showSetButtons();
  label_frame_button();
}

<?php exitJavascript(); ?>
</head>
<body onload="loaded()" >
<?php

// var_dump($_POST);

$parent_id = getparameter('parent_id');
if (!isset($parent_id)) {
  echo '<br>Parent id not specified';
  goto done;
}
$maydelete = false;
$mayupdate = false;
if ($folder_id < 2) {
  echo 'You may not delete the root folder';
  goto ask;
}

require_once($dir . '/../include/db.php');

if (!DBconnect()) {
  goto done;
}

$query = 
'select parent_folder_id, name, creator_user_id
  from folders
 where folder_id = ' . DBnumber($folder_id);

$ret = DBquery($query);
if (!$ret) {
  goto close;
}
$row = DBfetch($ret);
if (!$row) {
  echo 'Folder ', $folder_id, ' not found';
  goto close;
}

echo '<h3>Deleting folder ', htmlspecialchars($row['name']), '</h3>';

if ($row['creator_user_id'] != $gUserid) {
  echo 'You may not delete a folder created by ', htmlspecialchars($row['creator_user_id']);
  goto ask;
}

$mayupdate = true;
if ($row['parent_folder_id'] < 2) {
  echo 'You may not delete a home folder';
  goto ask;
}

$mode = getpost('mode');
if (isset($mode)) {
  if ($mode == 'Delete Folder') {
    if (!visit_children($folder_id, false)) {
      echo '<br>Deletion of folder aborted';
       goto show;
    }
	echo '<br>Folder deleted';
  } else if ($mode == 'Delete Tree') {
    if (!visit_children($folder_id, true)) {
      echo '<br>Deletion of tree cancelled';
      goto show;
    }
    echo '<br>Tree deleted';
  } else {
    echo 'Delete cancelled';
    goto buttons;
  }
  enterJavascript();
  echo '
deleted_folder(', $parent_id, ',', $folder_id , ');
';
  exitJavascript();
buttons:
  echo '
<p>
<form>
<input id=prev type="button" value="<" style="display:none" onclick="prev_folder()" />
<input id=list type="button" value="List" style="display:none" onclick="list_folders()" />
<input id=next type="button" value=">" style="display:none" onclick="next_folder()" />
</form>';
  goto close;
}

show:
$empty = true;
$query = 
'select count(*) as cnt
  from folders
 where parent_folder_id = ' . DBnumber($folder_id);
$ret = DBquery($query);
if (!$ret) {
  goto close;
}
$row = DBfetch($ret);
if (!$row) {
  goto strange;
}
$maydelete = true;
$cnt = $row['cnt'];
if ($cnt > 0) {
  echo '<br>Contains <b>' . $cnt . '</b> subfolders';
  $empty = false;
}
$query = 
'select count(*) as cnt
  from symlinks
 where parent_folder_id = ' . DBnumber($folder_id);
$ret = DBquery($query);
if (!$ret) {
  goto close;
}
$row = DBfetch($ret);
if (!$row) {
  goto strange;
}
$cnt = $row['cnt'];
if ($cnt > 0) {
  echo '<br>Contains <b>' . $cnt . '</b> links';
  $empty = false;
}
$query = 
'select count(*) as cnt
  from symlinks
 where target_folder_id = ' . DBnumber($folder_id);
$ret = DBquery($query);
if (!$ret) {
  goto close;
}
$row = DBfetch($ret);
if (!$row) {
  goto strange;
}
$cnt = $row['cnt'];
if ($cnt > 0) {
  echo '<br>Folder referenced from <b>' . $cnt . '</b> links';
}
  
$query = 
'select count(*) as cnt
  from foldersannotations
 where folder_id = ' . DBnumber($folder_id);
$ret = DBquery($query);
if (!$ret) {
  goto close;
}
$row = DBfetch($ret);
if (!$row) {
  goto strange;
}
$cnt = $row['cnt'];
if ($cnt > 0) {
 echo '<br>Contains <b>', $cnt, '</b> annotations';
 $empty = false;
}
  
$query = 
'select count(*) as cnt
  from foldersurls
 where folder_id = ' . DBnumber($folder_id);
$ret = DBquery($query);
if (!$ret) {
  goto close;
}
$row = DBfetch($ret);
if (!$row) {
  goto strange;
}
$cnt = $row['cnt'];
if ($cnt > 0) {
  echo '<br>Contains <b>', $cnt, '</b> urls';
  $empty = false;
}

if ($empty) {
  echo '<br>This folder is empty';
}
ask:
echo '
<p>
<form id=form name=form action="delete_folder.php" method="post">';
hidden('folder_id');
hidden('parent_id');
if ($maydelete) {
  if ($empty) {
    echo '
Are you sure you wish to delete this folder?
<p>
<input type="submit" name=mode value="Delete Folder" />';
  } else {
    echo '
Are you <b>SURE</b> you wish to delete this folder <b>AND</b> its content?
<p>
<input type="submit" name=mode value="Delete Tree" />';
} }
if ($mayupdate) {
  echo '
<input type="button" value="Update" onclick="update_folder()" />';
}
echo '
<input type="button" value="Show" onclick="show_folder()" />
<input id=prev type="button" value="<" style="display:none" onclick="prev_folder()" />
<input id=list type="button" value="List" style="display:none" onclick="list_folders()" />
<input id=next type="button" value=">" style="display:none" onclick="next_folder()" />
<input type="button" id="fullWidthButton" style="display:none" value="Full Frame" onclick="toggle_frames()" />
<input type="submit" name=mode value="Cancel" />
</form>';
goto close;
strange:
echo 'Count returned no rows';
close:
DBclose();
done:
bodyFooterFilename();
?>
</body>
</html>
