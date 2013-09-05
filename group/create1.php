<!DOCTYPE HTML>
<?php

$gPHPscript = __FILE__;
$dir = dirname(__FILE__);
require_once($dir . '/../include/boilerplate.php');
require_once($dir . '/../include/util.php');

if (mustlogon()) {
  return;
}

htmlHeader('Create Group');

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
  return false;
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

function clickCreate()
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
	
  var form = document.getElementById('form');
  addhidden(form, 'create','Y');
  return true;
}

function clickEdit(group_id)
{
  var form1 = document.getElementById('form1');
  addhidden(form1, 'group_id', group_id);
  form1.action = 'update1.php';
  form1.submit();
}

<?php exitJavascript(); ?>
</head>
<body >
<div id='rootdiv'>
<?php

//var_dump($_POST);
//var_dump($_GET);

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

$create = getpost('create');
if (!isset($create)) {
  goto show;
} 
// Do the update
if (!isset($title)) {
  javascriptAlert(null, null, 'A group needs a title', 'Error');
  goto show;
}

if (!DBconnect()) {
  goto done;
}

$query =
'insert into usersgroups(title, description, exclude, access, creator_user_id,created)
 values ('
 . DBstringC($title)
 . DBstringC($description)
 . DBnumberC($exclude)
 . DBnumberC($access)
 . DBstringC($gUserid)
 . 'utc_timestamp())';

$ret = DBquery($query);
if (!$ret) {
  goto close;
}

$group_id = DBid();

if ($group_id == 0) {
  javascriptAlert(null, null, 'Unable to create group','Error');
  goto close;
}

foreach($contacts as $contact) {
  $ret = insert_groupscontact($group_id, $contact, $title);
  if ($ret < 0) {
    goto close;
  }
}

foreach($members as $member) {
  $ret = insert_groupsmember($group_id, $member);
  if ($ret < 0) {
    goto close;
} }

echo '
<h3>Created Group ', $group_id, '</h3>
<p>', htmlspecialchars($title),'
<p>
<form id=form1 method="post" action="create1.php">
<input type="submit" value="Create Group" />
<input type="button" value="Edit" onclick="clickEdit(',$group_id,')" />
</form>';

goto close;

show:

?>
<form id=form name=form action="create1.php" method="post">
<input type=hidden id=mode name=mode value=y />
<table>
<tr><th></th><th align=left><font size="+1">Create new group</font></th></tr>
<tr><th></th><th></th></tr>
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
<option value=''>Include</option>
<option value=1>Exclude</option>
</select>
&nbsp;Access:
<select id="access" name="access" >
<option value=''>Closed</option>
<option value=1>Request</option>
<option value=2>Open</option>
</select>
</td>
</tr>

<tr id=startContacts>
<td align=right>Contacts:</td>
<td><input type="text" class="userid_ok" name="contacts[]" size="60" onchange="changeContact(this)" /></td>
</tr>
<tr id=startMembers>
<td align="right">Members:</td>
<td><input type="text" class="userid_ok" name="members[]" size="60" onchange="changeMember(this)" /></td>
</tr>
<tr id='endMembers'>
<td></td>
<td>
<input type="submit" value="Create" onclick="return clickCreate()" />
<input type=reset value="Restart" />
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
</div>
</body>
</html>
