<!DOCTYPE HTML>
<?php

$gPHPscript = __FILE__;
$dir = dirname(__FILE__);
require_once($dir . '/../include/boilerplate.php');
require_once($dir . '/../include/util.php');
require_once($dir . '/../include/alert.php');

if (mustlogon()) {
  return;
}

$group_id = getparameter('group_id');
if (!isset($group_id)) {
  header('Location: ' . dirname($_SERVER['REQUEST_URI']) . '/search1.php');
  return;
}

htmlHeader('Update Group');

require_once($dir . '/../include/alert.php');
require_once($dir . '/../include/db.php');
require_once($dir . '/../include/usersgroups.php');

srcStylesheet(
  '../css/style.css',
  '../css/group.css'
);
srcJavascript(
  '../js/alert.js',
  '../js/ajax.js'
);

enterJavascript();
?>

var old = null;

function getFormData(form_id, data)
{
  var form = document.getElementById(form_id);
  if (form) {
    var elements = form.elements;
    var i, node;

    for (i = 0; i < elements.length; ++i) {
      node = elements[i];
      switch (node.nodeName) {
      case 'INPUT':
        if (node.hidden != true) {
          if (node.type == 'text') {
			data.push(node.name);
            data.push(trim(node.value));
          } else if (node.type == 'checkbox') {
			data.push(node.name);
            data.push(node.checked);
        } }
        break;
      case 'SELECT':
        var options = node.options;
        var selected = '';
        var bar = '';
        var j;

        for (j = 0; j < options.length; ++j) {
          option = options[j];
          if (option.selected) {
            selected += bar + option.text;
            bar = '|';
        } }
		data.push(node.name);
        data.push(selected);
        break;
      case 'TEXTAREA':
		data.push(node.name);
        data.push(trim(node.value));
        break;
} } } }

function clickHelp()
{
  customAlert(
    { title:'Help',
      icon:'help.png',
      body:
'You may see strange characters if your system does not support the selected language'
    } );
}

function changeRole(select)
{
  var tr = document.getElementById('startMembers');
  var td;

  for (td = tr.firstChild; td; td = td.nextSibling) {
	if (td.tagName == 'TD') {
	  switch(select.selectedIndex) {
	  case 0:
		td.innerHTML = 'Members:';
		break;
	  case 1:
		td.innerHTML = 'Exclude:';
		break;
	  }
	  break;
  } }
  return;
}

function doneCheckUserid()
{
  if (this.readyState == 4 && this.status == 200) {
    var response = this.responseText;
	var input    = this.imagematState;
    if (response == '') {
  	  input.className = 'userid_ok';
	  return;
	}
	var userid = input.value;
    var msg = { title:'Invalid user id', icon:'warn.png',
                body:'The user "'+ userid + '" does not appear to exist'};
    customAlert(msg);
  }
  return;
}

function checkUserid(input, userid)
{
  if (userid == '') {
	input.className = 'userid_ok';
	return;
  }
  input.className = 'userid_bad';
  do_ajax(input, 'do_check_userid.php', 'user_id=' + userid, doneCheckUserid);
}

function changeContact(input)
{
  var value = input.value; 
  var tr, tr1, td, blank;

  value = trim(value);
  checkUserid(input, value);
  if (value == '') {
	return true;
  }

/*
  var focus = document.activeElement;
  if (focus.tagName == 'INPUT')
	switch (focus.name) {
	case 'Create':
	case 'Restart':
	  return;
  } }
*/

  for (tr = input; (tr = tr.parentNode); ) {
	if (tr.tagName == 'TR') {
	  break;
  }	}
  for (; (tr = tr.nextSibling); ) {
	if (tr.tagName == 'TR') {
	  if (tr.id == 'startMembers') {
		tr1 = document.createElement('TR');
		td  = document.createElement('TD');
		tr1.appendChild(td);
	    td  = document.createElement('TD');
		tr1.appendChild(td);
		blank = document.createElement('INPUT');
	    td.innerHTML = '<input type="text" class="userid_ok" name="contacts[]" size="60" onchange="changeContact(this)" />';
		tr.parentNode.insertBefore(tr1, tr);
		td.firstChild.focus();
	  }
	  break;
  } }
  return true;
}

function changeMember(input)
{
  var value = input.value; 
  var tr, tr1, td, blank;

  value = trim(value);
  checkUserid(input, value);
  if (value == '') {
	return;
  }
  for (tr = input; (tr = tr.parentNode); ) {
	if (tr.tagName == 'TR') {
	  break;
  }	}
  for (; (tr = tr.nextSibling); ) {
	if (tr.tagName == 'TR') {
	  if (tr.id == 'endMembers') {
		tr1 = document.createElement('TR');
		td  = document.createElement('TD');
		tr1.appendChild(td);
	    td  = document.createElement('TD');
		tr1.appendChild(td);
		blank = document.createElement('INPUT');
	    td.innerHTML = '<input type="text" class="userid_ok" name="members[]" size="60" onchange="changeMember(this)" />';
		tr.parentNode.insertBefore(tr1, tr);
		td.firstChild.focus();
	  }
	  break;
  } }
  return;
}

function clickUpdate()
{
  var title = document.getElementById('title');
  var msg;

  title = trim(title.value);
  if (title == '') {
    title = null;
  }

  if (title == null) {
    msg = { title:'Missing data', icon:'warn.png',
			body:'A group needs a title' };
    customAlert(msg);
    return false;
  }

  var elements, input, cnt, lth, i, value;

  elements = document.getElementsByName('contacts[]');
  lth      = elements.length;
  for (i = 0; i < lth; ++i) {
	input = elements[i];
	if (input.className != 'userid_ok') {
      msg = { title:'Invalid user id', icon:'warn.png',
			  body:'The contact "' + input.value + '" is invalid'};
	  customAlert(msg);
	  return false;
  } }
	
  elements = document.getElementsByName('members[]');
  lth      = elements.length;
  for (i = 0; i < lth; ++i) {
	input = elements[i];
	if (input.className != 'userid_ok') {
      msg = { title:'Invalid user id', icon:'warn.png',
			  body:'The member "' + input.value + '" is invalid'};
	  customAlert(msg);
	  return false;
  }	}
	
  var data    = [];
  getFormData('form', data);
  var length  = data.length;
  var i;

  if (length == old.length) {
	for (i = length;; ) {
	  if (i == 0) {
	    msg = { title:'Data unchanged', icon:'warn.png',
				body:'The group information has not been changed'};
		customAlert(msg);
		return false;
  	  }
	  --i;
	  if (data[i] != old[i]) {
		break;
  } } }

  var form = document.getElementById('form');
  addhidden(form, 'update','Y');
  return true;
}

function do_destroy()
{
  var form = document.getElementById('form');
  addhidden(form, 'delete','Y');
  form.submit();
}

function clickDelete()
{
  customAlert(
    { title:'Deleting Group',
      icon:'question.png',
      body:
'Deleting a group revokes access to *EVERY* folder that currently associates permissions with this group.  Are you *ABSOLUTELY* sure you wish to do this.',
      buttons:{
        Destroy:do_destroy,
        Cancel:null
      }
    });
}

function loaded()
{
  old = [];
  getFormData('form', old);
}

<?php 
exitJavascript();
echo '
</head>
<body onload="loaded()" >
';

//var_dump($_GET);
//var_dump($_POST);

if (!DBconnect()) {
  goto done;
}
$sql_group_id = DBnumber($group_id);

$query =
'select creator_user_id
  from usersgroups
 where group_id = ' . $sql_group_id . '
   and creator_user_id  = ' . DBstring($gUserid);

$ret = DBquery($query);
if (!$ret) {
  goto done;
}
$row = DBfetch($ret);
if (!$row) {
  $query =
'select user_id
  from groupscontacts
 where group_id = ' . $sql_group_id . '
   and user_id  = ' . DBstring($gUserid);

  $ret = DBquery($query);
  if (!$ret) {
    goto close;
  }
  $row = DBfetch($ret);
  if (!$row) {
	javascriptAlert(
	  'Permission denied',
	  'lock.png',
      'You are neither the creator not a contact for group ' .
		$group_id . '. Therefore you may not update this group.',
	  null
	);
	return;
} }

$delete = getpost('delete');
if (isset($delete)) {
  $records  = 0;
  $cnt      = 0;
  $tables   = array();
  $tables[] = 'groupscontacts';
  $tables[] = 'groupsmembers';
  $tables[] = 'usersgroups';

  foreach ($tables as $table) {
    $query = 
'delete from ' . $table . '
 where group_id = ' . $sql_group_id;

    $ret = DBquery($query);
    if (!$ret) {
	  goto close;
    }
    $updated = DBupdated();
    if ($updated != 0) {
      echo $updated, ' records deleted from table ', $table, '<br/>';
      $records += $updated;
      ++$cnt;
  } } 

  echo '<h3>', $records, ' records deleted from ', $cnt, ' tables</h3>';
  goto close;
}

$title       = getpost('title');
$description = getpost('description');
$contacts    = getpost('contacts');
$members     = getpost('members');
$exclude     = getpost('role');
if ($exclude != 1) {
  $exclude = null;
}
$access = getpost('access');
if ($access != 1 && $access != 2) {
  $access = null;
}

$update = getpost('update');
if (!isset($update)) {
  goto show;
} 
// Do the update
if (!isset($title)) {
  javascriptAlert(null, null, 'A group needs a title', 'Error');
  goto show;
}

$query =
'update usersgroups
   set title       = ' . DBstring($title) . ',
       description = ' . DBstring($description) . ',
       exclude     = ' . DBnumber($exclude) . ',
       access      = ' . DBnumber($access) . ',
       modified    = utc_timestamp()
 where group_id    = ' . $sql_group_id;

$ret = DBquery($query);
if (!$ret) {
  goto close;
}

$cnt = 0;
if (isset($contacts)) {
  $keep      = '';
  $connector = '';
  foreach ($contacts as $contact) {
    $contact = trim($contact);
    if ($contact != '') {
	  $keep     .= $connector . DBstring($contact);
      $connector = ', ';
	  ++$cnt;
} } }

$query =
'delete from groupscontacts
 where group_id = ' . $sql_group_id;

switch ($cnt) {
case 0:
  break;
case 1:
  $query .= '
   and user_id <> ' . $keep;
  break;
default:
  $query .= '
   and user_id not in (' . $keep . ')';
  break;
}

DBquery($query);

if ($cnt > 0) {
  foreach ($contacts as $contact) {
    $ret = insert_groupscontact($group_id, $contact, $title);
    if ($ret < 0) {
	  goto close;
} } }

$cnt = 0;
if (isset($members)) {
  $keep      = '';
  $connector = '';
  foreach ($members as $member) {
    $member = trim($member);
    if ($member != '') {
	  $keep     .= $connector . DBstring($member);
      $connector = ', ';
	  ++$cnt;
} } }

$query =
'delete from groupsmembers
 where group_id = ' . $sql_group_id;

switch ($cnt) {
case 0:
  break;
case 1:
  $query .= '
   and user_id <> ' . $keep;
  break;
default:
  $query .= '
   and user_id not in (' . $keep . ')';
  break;
}

DBquery($query);

if ($cnt > 0) {
  foreach ($members as $member) {
    $ret = insert_groupsmember($group_id, $member);
    if ($ret < 0) {
      goto close;
} } }

echo '
<h3>Updated Group ', $group_id, '</h3>
<p>', htmlspecialchars($title),'
<p>
<form id=form1 method="post" action="update1.php">
<input type="submit" value="Update Group" />';
hidden('group_id');
echo '
</form>';

goto close;

show:

$mode = getpost('mode');
if (!isset($mode)) {
  $query = 
'select title, description, access, exclude,
        creator_user_id, created, modified
  from usersgroups
 where group_id = ' . $sql_group_id;

  $ret = DBquery($query);
  if (!$ret) {
    goto close;
  }
  $row = DBfetch($ret);
  if (!$row) {
	echo 'Group ', htmlspecialchars($group_id), ' not found';
    goto close;
  }
  foreach ($row as $colname => $value) {
	$$colname = $value;
  }

  $contacts = array();
  $query = 
'select user_id
  from groupscontacts
 where group_id = ' . $sql_group_id;

  $ret = DBquery($query);
  if (!$ret) {
    goto close;
  }
  while ($row = DBfetch($ret)) {
	$contacts[] = $row['user_id'];
  }

  $members = array();
  $query = 
'select user_id
  from groupsmembers
 where group_id = ' . $sql_group_id;

  $ret = DBquery($query);
  if (!$ret) {
    goto close;
  }
  while ($row = DBfetch($ret)) {
	$members[] = $row['user_id'];
  }
}
?>
<form id=form name=form action="update1.php" method="post">
<input type=hidden id=mode name=mode value=y />
<?php
hidden('group_id');
?>
<table>
<tr><th></th><th align=left><font size="+1">Update group</font></th></tr>
<tr><th></th><th></th></tr>
<tr>
<td align=right>Group id:</td>
<td><?php echo htmlspecialchars($group_id); ?></td>
</tr>

<tr>
<td align=right>Title:</td>
<td><input type="text" id="title" name="title" size="60" value="<?php echo htmlspecialchars($title); ?>" /></td>
</tr>
<tr>
<td align=right>Description:</td>
<td><textarea name="description" cols="60" rows="7"><?php echo htmlspecialchars($description); ?></textarea>
</td>
</tr>
<tr>
<td align="right">Role:</td>
<td>
<select name="exclude" onchange="changeRole(this)" >
<option value=''<?php if (!isset($exclude)) echo ' selected';?>>Include</option>
<option value=1<?php if ($exclude == 1) echo ' selected';?>>Exclude</option>
</select>
&nbsp;Access:
<select id="access" name="access" >
<option value=''<?php if (!isset($access)) echo ' selected';?>>Closed</option>
<option value=1<?php if ($access == 1) echo ' selected';?>>Request</option>
<option value=2<?php if ($access == 2) echo ' selected';?>>Open</option>
</select>
</td>
</tr>

<tr id=startContacts>
<td align=right>Contacts:</td><?php
foreach ($contacts as $userid) {
  echo '
<td><input type="text" class="userid_ok" name="contacts[]" size="60" onchange="changeContact(this)" value="', htmlspecialchars($userid), '"/></td>
</tr>
<td></td>';
}
?>
<td><input type="text" class="userid_ok" name="contacts[]" size="60" onchange="changeContact(this)" /></td>
</tr>
<tr id=startMembers>
<td align="right">Members:</td><?php
foreach ($members as $userid) {
  echo '
<td><input type="text" class="userid_ok" name="members[]" size="60" onchange="changeMember(this)" value="', htmlspecialchars($userid), '"/></td>
</tr>
<td></td>';
}
?>
<td><input type="text" class="userid_ok" name="members[]" size="60" onchange="changeMember(this)" /></td>
</tr>

<tr id='endMembers'>
<td align=right>Creator:</td>
<td><?php echo htmlspecialchars($creator_user_id); ?></td>
</tr>

<tr>
<td align=right>Created:</td>
<td><?php echo htmlspecialchars($created); ?></td>
</tr>

<tr>
<td align=right>Modified:</td>
<td><?php echo htmlspecialchars($modified); ?></td>
</tr>

<tr>
<td></td>
<td>
<input type="submit" value="Update" onclick="return clickUpdate()" />
<input type=reset value="Restart" />
<input type="button" value="Delete" onclick="clickDelete()" />
<input type="button" value="Help" onclick="clickHelp()" />
</td>
</tr>
</table>
</form>

<?php

close:
DBclose();
done:
bodyFooter();
?>
</body>
</html>
