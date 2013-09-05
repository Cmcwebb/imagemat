<!DOCTYPE HTML>
<?php

$gPHPscript = __FILE__;
$dir = dirname(__FILE__);
require_once($dir . '/../include/boilerplate.php');
require_once($dir . '/../include/util.php');
require_once($dir . '/../include/db.php');
require_once($dir . '/../include/folders/permissions.php');

$parent_id = getparameter('parent_id');
$gCreated  = 0;

function createLink($parent_id, $parent_name, $target_id, $name)
{
  global $gUserid, $gCreated;

  $query =
'select folder_id, name, description, creator_user_id, created
  from folders
 where folder_id = ' . DBnumber($target_id);

  $ret = DBquery($query);
  if (!$ret) {
    return false;
  }
  $row = DBfetch($ret);
  if (!$row) {
    echo '<br>Target folder ', $target_id, ' does not exist';
    return true;
  }
    
  $visible = may_see_folder($row);
  if (!isset($visible)) {
    return false;
  }
  if (!$visible) {
    echo '<br>Can\'t see target folder';
    return true;
  }
  $target_name = $row['name'];

  if ($parent_id == 1) {
    $query =
'select name
  from favouritefolders
 where user_id          = ' . DBstring($gUserid) . '
   and target_folder_id = ' . DBnumber($target_id);
  } else {
    $query =
'select name
   from symlinks
  where parent_folder_id = ' . DBnumber($parent_id) . '
    and target_folder_id = ' . DBnumber($target_id);
  }

  $ret = DBquery($query);
  if (!$ret) {
    return false;
  }
  $row = DBfetch($ret);
  if ($row) {
    echo '<br>Link duplicates existing link in ',
htmlspecialchars($parent_name), ' of ', $row['name'], '=>',
htmlspecialchars($target_name);
    return true;
  }

  $cnt   = 0;
  $name1 = $name;

  for (;;) {
    $query =
'select count(*) as cnt
  from (select null
          from folders
         where folder_id = ' . DBnumber($parent_id) . '
           and name      = ' . DBstring($name1);
    if ($parent_id == 1) {
      $query .= '
         union all
        select name
          from favouritefolders
         where user_id          = ' . DBstring($gUserid) . '
           and name             = ' . DBstring($name1) . '
       ) t1';
    } else {
	  $query .= '
         union all
        select name
          from symlinks
         where parent_folder_id = ' . DBnumber($parent_id) . '
           and name             = ' . DBstring($name1) . '
       ) t1';
	}

    $ret = DBquery($query);
    if (!$ret) {
	  return false;
	}
    if ($row['cnt'] == 0) {
      break;
    }
	++$cnt;
    $name1 = $name + '[' + $cnt + ']';
  }

  if ($parent_id == 1) {
    $query = 
'insert into favouritefolders 
       (user_id, target_folder_id, name, created)
values (' . DBstringC($gUserid) . DBnumberC($target_id) . DBstringC($name1) . 'utc_timestamp())';
  } else {
    $query = 
'insert into symlinks
       (parent_folder_id, target_folder_id, name, creator_user_id, created)
values (' . DBnumberC($parent_id) . DBnumberC($target_id) . DBstringC($name1) . DBstringC($gUserid) . 'utc_timestamp())';
  }
       
  $ret = DBquery($query);
  if (!$ret) {
    return false;
  }
  ++$gCreated;
  echo '<br>Created link in ', 
htmlspecialchars($parent_name), ' of ', htmlspecialchars($name1), '=>',
htmlspecialchars($target_name);
  return true;
}

if (mustlogon()) {
  /* This can happen if the frame is left idle a long time */
  return;
}

htmlHeader('Create Link');

require_once($dir . '/../include/db.php');
srcStylesheet(
  '../css/style.css',
  '../css/alert.css',
  '../css/iframe.css',
  '../css/sortable.css',
  '../css/tooltip.css'
);
srcJavascript(
  '../js/alert.js',
  '../js/fullwidth.js',
  '../js/enterkey.js',
  '../js/edit.js',
  '../js/sortable.js',
  '../js/base64.js',
  '../js/tooltip.js'
);
enterJavascript(); ?>

function setEdit(value)
{
  var form = document.getElementById('form');
  if (form) {
    var elements = form.elements;
    var i, node;

    for (i = 0; i < elements.length; ++i) {
      node = elements[i];
      if (node.nodeName == 'INPUT') {
        if (node.hidden != true) {
          if (node.type == 'checkbox') {
			node.checked = value;
} } } } } }

function clearAll()
{
  setEdit(false);
}

function setAll()
{
  setEdit(true);
}

function created_links()
{
  if (self != top) {
    /* TODO make safer - can't in general assume frames[0] is right */
    top.frames[0].done_add_links(<?php echo $parent_id; ?>);
} }

function checkForm()
{
  var name = document.getElementById('name');
  var path = document.getElementById('path');
  var cnt  = 0;

  var nameVal = trim(name.value);
  var pathVal = trim(path.value);

  name.value = nameVal;
  path.value = pathVal;

  if (nameVal != '' && pathVal != '') {
    return true;
  }
  if (nameVal != '' || pathVal != '') {
    customAlert(
      { title:'Illegal data',
        icon:'warn.png',
        body:'A link needs both a name and a path'
      });
    return false;
  }

  var cnt  = 0;
  var form = document.getElementById('form');
  if (form) {
    var elements = form.elements;
    var i, node;

    for (i = 0; i < elements.length; ++i) {
      node = elements[i];
      switch (node.nodeName) {
      case 'INPUT':
        if (node.hidden != true) {
          if (node.type == 'checkbox') {
			if (node.checked) {
			  ++cnt;
        } } }
        break;
  } } }

  if (cnt == 0) {
    customAlert(
      { title:'Missing data',
        icon:'warn.png',
        body:'No links would be created'
      });
    return false;
  }
  return true;
}

function loaded()
{
  label_frame_button();
}

<?php exitJavascript(); ?>
</head>
<body id='body' onload="loaded()" >
<div id='rootdiv'>
<?php

// var_dump($_POST);

if (!isset($parent_id)) {
  echo 'Missing parent folder id';
  goto done;
}

$mode     = getpost('mode');
$name     = getpost('name');
$path     = getpost('path');
$edit     = getpost('edit');

if (!DBconnect()) {
  goto done;
}

if (isset($mode) && $mode == 'y') {

  $query =
'select name, creator_user_id
  from folders
 where folder_id = ' . DBnumber($parent_id);

  $ret = DBquery($query);
  if (!$ret) {
    goto close;
  }
  $row = DBfetch($ret);
  if (!$row) {
    echo 'Parent Folder ', htmlspecialchars($parent_id), ' not found';
    goto close;
  }
  $parent_name = $row['name'];
  if ($parent_id != 1 && $row['creator_user_id'] != $gUserid) {
    echo 'Can\'t add links under folder ', htmlspecialchars($parent_name), 
' created by ', htmlspecialchars($row['creator_user_id']);
    goto close;
  }

  if (isset($name) || isset($path)) {
    if (!isset($name)) {
      javascriptAlert(null, null, 'A link needs a name', 'Error');
      goto show;
    }
    if (!isset($path)) {
      javascriptAlert(null, null, 'A link needs a path', 'Error');
      goto show;
    }
    require_once($dir . '/../include/folders/valid.php');
    require_once($dir . '/../include/folders/permissions.php');

    if (!valid_folder_name($name, null, null, true)) {
      goto show;
    }

    require_once($dir . '/../include/folders.php');

    $target_id = find_folder_id($parent_id, $path, true);
    if (!$target_id) {
      goto show;
    }
    if (!createLink($parent_id, $parent_name, $target_id, $name)) {
      goto show;
  } }

  if (isset($edit)) {
    foreach ($edit as $target_id) {
      $query =
'select name
  from folders
 where folder_id = ' . DBnumber($target_id);
      $ret = DBquery($query);
      if (!$ret) {
        goto close;
      }
      $row = DBfetch($ret);
      if (!$row) {
        echo '
<br>Folder ', htmlspecialchars($target_id), ' not found';
        continue;
      }
      createLink($parent_id, $parent_name, $target_id, $row['name']);
  } }

  echo '
<h3>Created ', $gCreated, ' links in folder ', htmlspecialchars($parent_name), '</h3>';
  if ($gCreated > 0) {
    enterJavascript();
    echo '
created_links();
';
    exitJavascript();
  }
  goto close;
} 
show:

require_once($dir . '/../include/language.php');

?>
<form id=form name=form action="create_links.php" method="post" onsubmit="return checkForm()">
<input type=hidden id=mode name=mode value=y />
<?php
hidden('link_id');
hidden('parent_id');
?>
<table class=iframetable width='99%'>

<tr>
<td align=right>Name:</td>
<td><input type="text" id="name" name="name" size="50" maxlength="255" value="<?php echo htmlspecialchars($name); ?>" />
</td>
</tr>

<tr>
<td align=right>Path:</td>
<td><input type="text" id="path" name="path" size="50" maxlength="255" value="<?php echo htmlspecialchars($path); ?>" />
</td>
</tr>
</table>

<?php

$cnt = 0;
if (!isset($edit)) {
  $edit  = getSetFolders();
}
if (isset($edit) && count($edit) > 0) {
  $query =
'select folder_id, name, description, creator_user_id, created
  from folders
 where folder_id in (' . implode(',', $edit) . ')';

  $ret = DBquery($query);
  if (!$ret) {
    goto close;
  }
  $row = DBfetch($ret);
  if ($row) {
    echo '
<p>
<table id="results" class=sortable cellpadding="0" cellspacing="4">
<tr><th class=unsortable></th><th class=startsort>Id</th><th width="40%">Name &amp; Description</th><th>Creator</th><th>Created</th></tr>';

    $cnt1 = 0;
    $checkboxes_cnt     = 0;
    for ($cnt = 0; $row; ++$cnt) {
      $visible = may_see_folder($row);
      if (!isset($visible)) {
        goto close;
      }
      if ($visible) {
        ++$cnt1;
	    ++$checkboxes_cnt;
        $id = $row['folder_id'];
	    echo '
<tr>
<td><input type=checkbox name="edit[]" value=', $id, ' /></td>
<td align=right>', $id,'</td>
<td>';
	    if (isset($row['description'])) {
	      echo '<span class="foldername" onmouseover="tooltip.base64(\'',
base64_encode($row['description']), '\',null,\'tt\');" onmouseout="tooltip.hide();">',
htmlspecialchars($row['name']),'</span>';
	    } else {
	      echo htmlspecialchars($row['name']);
	    }
	    echo '</td>
<td>', htmlspecialchars($row['creator_user_id']), '</td>
<td>', htmlspecialchars($row['created']), '</td>
</tr>';
      }
      $row = DBfetch($ret);
    }
    echo '
</table>
<input type=button id="clearall_button" value="clear all" onclick="clearAll()" />
<input type=button id="setall_button" value="set all" onclick="setAll()" />';
} }

?>
<input type=submit value="Create" />
<input type=reset value="Restart" onclick="restart()" />
<input type="button" id="fullWidthButton" style="display:none" value="Full Frame" onclick="toggle_frames()" />
</form>
<?php
close:
DBclose();
done:
bodyFooterFilename();
?>
</div>
</body>
</html>
