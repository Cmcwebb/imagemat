<!DOCTYPE HTML>
<?php

$gPHPscript = __FILE__;
$dir = dirname(__FILE__);
require_once($dir . '/../include/boilerplate.php');
require_once($dir . '/../include/util.php');

if (mustlogon()) {
  return;
}

htmlHeader('Contacts');

require_once($dir . '/../include/alert.php');
require_once($dir . '/../include/db.php');
srcStylesheet(
  '../css/style.css',
  '../css/tooltip.css',
  '../css/sortable.css',
  '../css/group.css'
);
srcJavascript(
  '../js/base64.js',
  '../js/tooltip.js',
  '../js/sortable.js',
  '../js/alert.js',
  '../js/ajax.js'
);

enterJavascript();
?>

function doneAjax()
{
  if (this.readyState == 4 && this.status == 200) {
    var response = this.responseText;
    if (response != '') {
      alert(response);
} } }

function doneContactChanged()
{
  if (this.readyState == 4 && this.status == 200) {
    var response = this.responseText;
    if (response != '') {
      alert(response);
	  return;
    }
	var select = this.imagematState;
    if (select) {
	  select.disabled = true;
  } }
}

function declineContact(state)
{
  do_ajax(state.select, 'do_contact.php', 'group_id=' + state.group_id + '&value=99', doneContactChanged);
}

var gLastRequest = null;

function clickContact(select, group_id)
{
  var index = select.selectedIndex;
  var value = select.options[index].value;

  if (gLastRequest) {
    removeCustomAlert('alertBox' + gLastRequest, false);
    gLastRequest = null;
  }

  if (value == 99) {

    gLastRequest = customAlert(
      { title:'Decline Invite',
        icon:'warn.png',
        body:
'If you decline this invite it may not be offered again',
      
        buttons:{
          "Decline":declineContact,
          Cancel:null
        },
		state:{group_id:group_id, select:select}
      } );
	return;
  }
  do_ajax(null, 'do_contact.php', 'group_id=' + group_id + '&value=' + value, doneContactChanged);
}

function clickMembersSee(select)
{
  var index = select.selectedIndex;
  var value = select.options[index].value;

  do_ajax(null, 'do_membersSee.php', 'value=' + value, doneAjax);
}

function clickMembersEmail(select)
{
  var index = select.selectedIndex;
  var value = select.options[index].value;

  do_ajax(null, 'do_membersEmail.php', 'value=' + value, doneAjax);
}

function clickVisible(select, item)
{
  var index = select.selectedIndex;
  var value = select.options[index].value;

  do_ajax(null, 'do_visible.php', 'item=' + item + '&value=' + value, doneAjax);
}

<?php exitJavascript(); ?>
</head>
<body >
<div id='rootdiv'>
<?php

if (!DBconnect()) {
  goto done;
}

$query =
'select g1.group_id, g1.agreed,
       g2.title, g2.description, g2.creator_user_id, g2.created
  from groupscontacts g1, usersgroups g2
 where g1.group_id = g2.group_id
   and g1.user_id = '. DBstring($gUserid);

$ret = DBquery($query);
if (!$ret) {
  goto close;
}


for ($cnt = 0; $row = DBfetch($ret); ++$cnt) {
  if ($cnt == 0) {
    echo '
<p>These are the groups you have been invited to be a contact for:
<p>
<form action="contacts.php">
<table id="results" class=sortable cellpadding="0" cellspacing="4" width="100%">
<tr><th class=unsortable></th><th class=startsort>Id</th><th width="75%">Title &amp; Description</th><th>Creator</th><th>Created</th></tr>';
  }
  foreach ($row as $colname => $value) {
    $$colname = $value;
  }
  echo '
<tr>
<td>
<select onchange="clickContact(this,', $group_id, ');">';
  if (!isset($agreed)) {
    echo '
<option value="" selected></option>';
  }
  echo '
<option value=1', (($agreed==1) ? ' selected' : ''), '>Accept</option>
<option value=99', (($agreed==99) ? ' selected' : ''), '>Decline</option>
<option value=2', (($agreed==2) ? ' selected' : ''), '>Disable</option>
</select>
</td>
<td>', $group_id, '</td>
<td>';

  if (isset($description)) {
    echo '<span class="grouptitle" onmouseover="tooltip.base64(\'',
base64_encode($description), '\',null,\'tt\');" onmouseout="tooltip.hide();">',
htmlspecialchars($title),'</span>';
  } else {
    echo htmlspecialchars($title);
  }
  echo '</td>
<td>', htmlspecialchars($creator_user_id), '</td>
<td>', htmlspecialchars($created), '</td>
</tr>';

}
if ($cnt == 0) {
  echo '
<P>You are not identified as a contact for any group';
} else {
  echo '
</table>
</form>';
}

$query = 
'select visible, visible_email, global, global_email
  from users
 where user_id = ' . DBstring($gUserid);

$ret = DBquery($query);
if (!$ret) {
  goto close;
}
if (!DBrows($ret)) {
  echo '
<br>User id ' . htmlspecialchars($gUserid) . ' not found in database';
  goto close;
}
$row = DBfetch($ret);
foreach ($row as $colname => $value) {
  $$colname = $value;
}    
?>
<br/>
These are your profile visibility settings:
<p>
<form>
<table>
<tr>
<td align=right>Members See:</td>
<td>
<select onchange="clickVisible(this,1);">';
<option value=''<?php if (!isset($visible)) echo ' selected';?>>No</option>
<option value=1<?php if ($visible == 1) echo ' selected';?>>As Member</option>
<option value=2<?php if ($visible == 2) echo ' selected';?>>As Contact</option>
<option value=3<?php if ($visible == 3) echo ' selected';?>>Both</option>
</select>
</td>
<td align=right>
Can email:
</td>
<td>
<select onchange="clickVisible(this,2);">';
<option value=''<?php if (!isset($visible_email)) echo ' selected';?>>No</option>
<option value=1<?php if ($visible_email == 1) echo ' selected';?>>As Member</option>
<option value=2<?php if ($visible_email == 2) echo ' selected';?>>As Contact</option>
<option value=3<?php if ($visible_email == 3) echo ' selected';?>>Both</option>
</select>
</td>
</tr>
<tr>
<td align=right>
World See:
</td>
<td>
<select onchange="clickVisible(this,3);">';
<option value=''<?php if (!isset($global)) echo ' selected';?>>No</option>
<option value=1<?php if ($global == 1) echo ' selected';?>>As Member</option>
<option value=2<?php if ($global == 2) echo ' selected';?>>As Contact</option>
<option value=3<?php if ($global == 3) echo ' selected';?>>Both</option>
</select>
</td>
<td align=right>
Can email:
</td>
<td>
<select onchange="clickVisible(this,4);">';
<option value=''<?php if (!isset($global_email)) echo ' selected';?>>No</option>
<option value=1<?php if ($global_email == 1) echo ' selected';?>>As Member</option>
<option value=2<?php if ($global_email == 2) echo ' selected';?>>As Contact</option>
<option value=3<?php if ($global_email == 3) echo ' selected';?>>Both</option>
</select>
</td>
</tr>
</table>
</form>

<?php

close:
DBclose();
done:
?>
</div>
</body>
</html>
