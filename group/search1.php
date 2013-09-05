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

htmlHeader('Search Groups');
srcStylesheet(
  '../css/style.css',
  '../css/tooltip.css',
  '../css/sortable.css',
  '../css/alert.css',
  '../css/group.css'
);
srcJavascript(
  '../js/util.js',
  '../js/base64.js',
  '../js/tooltip.js',
  '../js/sortable.js',
  '../js/alert.js',
  '../js/ajax.js'
);

enterJavascript(); ?>
function setGroups_state_changed()
{
  var button;
  var setGroups_lth = top.setGroups_lth;

  button  = document.getElementById('clearall_button');
  if (button != undefined) {
    button.disabled = ((setGroups_lth == 0) ? true : undefined);
  }
  button  = document.getElementById('clear_button');
  if (button != undefined) {
    button.disabled = ((window.checkboxes_set_cnt == 0) ? true : undefined);
  }
  button  = document.getElementById('set_button');
  if (button != undefined) {
    button.disabled = (window.checkboxes_set_cnt == window.checkboxes_cnt) ? true : undefined;
  }
}

function setGroups_changed1(checkbox)
{
  var setGroups = top.setGroups;
  var lth  = top.setGroups_lth;
  var id   = parseInt(checkbox.value);
  var i;

  if (checkbox.checked) {
    if (setGroups == null) {
	  top.setGroups = setGroups = new Array();
    } else {
	  for (i = lth; 0 <= --i; ) {
        if (setGroups[i] == id) {
          return;
    } } }
    setGroups[top.setGroups_lth++] = id;
	++window.checkboxes_set_cnt;
  } else {
    for (i = lth; ; ) {
      if (--i < 0) {
	    return;
      }
      if (setGroups[i] == id) {
		break;
	} }
    setGroups[i] = setGroups[--top.setGroups_lth];
    --window.checkboxes_set_cnt;
  }
  top.setGroupsChanged();
}

function show_setGroups(prefix)
{
/*
  var setGroups = top.setGroups;
  var lth  = ((setGroups == null) ? -1 : setGroups.length);
  var msg;
  var i;

  msg = prefix + '(' + top.setGroups_lth + '/' + lth + ')';
  for (i = 0; i < lth; ++i) {
    msg += ' ' + setGroups[i];
  }
  alert(msg);
*/
}

function clearall_setGroups()
{
  var checkboxes = document.getElementsByName('edit');
  var i;

  for (i = checkboxes.length; 0 <= --i; ) {
	checkboxes[i].checked = false;
  }
  if (top.setGroups_lth > 0) {
    top.setGroups_lth = 0;
    top.setGroupsChanged();
  }
  window.checkboxes_set_cnt = 0;
  setGroups_state_changed();
  //show_setGroups('clearall ');
}

function setGroups_changed(checkbox)
{
  setGroups_changed1(checkbox);
  setGroups_state_changed();
  //show_setGroups('setGroups_changed ');
}

function clear_setGroups()
{
  var checkboxes = document.getElementsByName('edit');
  var checkbox;
  var i;

  for (i = checkboxes.length; 0 <= --i; ) {
    checkbox = checkboxes[i];
	if (checkbox.checked) {
      checkbox.checked = false;
      setGroups_changed1(checkbox);
  } }
  setGroups_state_changed();
  //show_setGroups('clear_setGroups ');
}

function set_setGroups()
{
  var checkboxes = document.getElementsByName('edit');
  var checkbox;
  var i;

  for (i = 0; i < checkboxes.length; ++i) {
    checkbox = checkboxes[i];
	if (!checkbox.checked) {
      checkbox.checked = true;
      setGroups_changed1(checkbox);
  } }
  setGroups_state_changed();
  //show_setGroups('set setGroups ');
}

function init_checkboxes()
{
  var checkboxes = document.getElementsByName('edit');
  var setGroups       = top.setGroups;
  var i, j, id;

  for (i = 0; i < checkboxes.length; ++i) {
    checkbox = checkboxes[i];
    id       = parseInt(checkbox.value);
    for (j = top.setGroups_lth; 0 <= --j; ) {
      if (setGroups[j] == id) {
        checkbox.checked = true;
		++window.checkboxes_set_cnt;
        break;
  } } }
  setGroups_state_changed();
}

function loaded()
{
  init_checkboxes();
}

function doneClickCnt()
{
  if (this.readyState == 4 && this.status == 200) {
    var response = this.responseText;
    var msg = { title:'Group ' + this.imagematState + ' details', icon:false,
                html:true, body:response};
    customAlert(msg);
  }
  return;
}

function clickCnt(group_id)
{
  do_ajax(group_id, 'do_get_members.php', 'group_id=' + group_id, doneClickCnt);
}

function doneClickJoin()
{
  if (this.readyState == 4 && this.status == 200) {
    var response = this.responseText;
    var msg = { title:'Join Group ' + this.imagematState, 
                 icon:false,
                 html:true, body:response};
    customAlert(msg);
  }
  return;
}

function clickJoin(group_id)
{
  do_ajax(group_id, 'do_join.php', 'group_id=' + group_id, doneClickJoin);
}

var gLastRequest = null;

function clickRequestYes(group_id)
{
  var input = document.getElementById('request');
  var request = input.value;

  do_ajax(group_id, 'do_join.php', 'group_id=' + group_id + '&request="' + encodeURIComponent(request) + '"', doneClickJone);
}

function clickRequest(group_id)
{
  if (gLastRequest) {
	removeCustomAlert('alertBox' + gLastRequest, false);
	gLastRequest = null;
  }

  var width     = Math.floor((windowInnerWidth(self) * 4)/5);

  gLastRequest = customAlert( {
	title:'Provide Explanation for Request',
	icon:false,
	body:'<textarea id="request" rows=6></textarea>\n',
	width:width,
	html:true,
	buttons:{ Request:clickRequestYes, Cancel:null }, 
	state:group_id, editor:false } );
}

function clickRequest(group_id)
{
  do_ajax(group_id, 'do_get_contacts.php', 'group_id=' + group_id, doneGetContacts);
}

function clickEdit(group_id)
{
  submitPost({ action:'update1.php',
               parameters:{ group_id:group_id } } );
}

function doneClickLeave()
{
  if (this.readyState == 4 && this.status == 200) {
    var response = this.responseText;
    if (response != '') {
      alert(response);
	  return;
    }
    var button      =  this.imagematState;
	button.value    = 'Left';
	button.style.color = 'Red';
	button.disabled = true;
  }
}

function clickLeaveYes(state)
{
  do_ajax(state.button, 'do_leave.php', 'group_id=' + state.group_id, doneClickLeave);
}

var gLastLeave = null;

function clickLeave(button,group_id)
{
  if (gLastLeave) {
	removeCustomAlert('alertBox' + gLastLeave, false);
	gLastLeave = null;
  }

  gLastLeave = customAlert( {
	title:'Leaving group',
	icon:'question.png',
	body:'Are you sure you wish to leave group ' + group_id,
	buttons:{ Leave:clickLeaveYes, Cancel:null }, 
	state:{button:button, group_id:group_id } } );
}


<?php exitJavascript(); ?>
</head>
<body onload="loaded()">
<?php

$mine = getparameter('mine');
if (isset($mine)) {
  $mode = 'Y';
} else {
  $mode = getpost('mode');
}

if (isset($mode)) {

  if (!DBconnect()) {
    goto done;
  }

  $constraints = array();

  $query =
'select group_id, title, description, exclude, access, cnt, ismember, creator_user_id, created, modified
  from (select usersgroups.group_id, title, description, exclude, access, coalesce(cnt,0) as cnt,
        ismember, usersgroups.creator_user_id, usersgroups.created, usersgroups.modified
          from usersgroups
          left join 
              (select groupsmembers.group_id, count(*) as cnt, 
                      max(case user_id when ' . DBstring($gUserid) . ' then 1 else 0 end) as ismember
                 from groupsmembers
                group by group_id
              ) t1
           on usersgroups.group_id = t1.group_id
       ) t2';

  $group_id = getpost('group_id');
  if (isset($group_id)) {
    $constraints[] = 'group_id = ' . DBnumber($group_id);
  }
  $title = getpost('title');
  if (isset($title)) {
	$constraints[] = 'title like ' . DBstring('%' . $title . '%');
  }
  $description = getpost('description');
  if (isset($description)) {
    $constraints[] = 'description like ' . DBstring('%' . $description . '%');
  }

  $role = getpost('role');
  switch ($role) {
  case '':
	break;
  case 0:	// includes
	$constraints[] = 'exclude is null';
	break;
  case 1:	// excludes
	$constraints[] = 'exclude is not null';
	break;
  }

  $access = getpost('access');
  switch ($access) {
  case '':
	break;
  case 0:	// closed
	$constraints[] = 'access is null';
	break;
  case 1:	// request
  case 2:	// open
	$constraints[] = 'access = ' . DBnumber($access);
	break;
  }

  $contact = getpost('contact');
  $term      = '';
  $connector = '';
  $cnt       = 0;
  if (isset($contact)) {
    foreach ($contact as $value) {
	  $value = trim($value);
	  if ($value != '') {
        $term     .= $connector . DBstring($value);
        $connector = ',';
        ++$cnt;
  } } }
  if ($cnt != 0) {
    if ($cnt == 1) {
	  $term = 'groupscontacts.user_id  = ' . $term;
    } else {
	  $term = 'groupscontacts.user_id in (' . $term . ')';
    }
	$constraints[] = 
'exists
       (select null
          from groupscontacts
         where groupscontacts.group_id = t2.group_id
           and ' . $term . '
           and (groupscontacts.user_id = ' . DBstring($gUserid) . '
                or exists
              (select null
                 from users
                where users.user_id = groupscontacts.user_id
                  and ( users.global  in (2,3) or
                       (users.visible in (2,3) and ismember = 1))
              ))
       )';
  }

  $member = getpost('member');
  $term      = '';
  $connector = '';
  $cnt       = 0;
  if (isset($member)) {
    foreach ($member as $value) {
	  $value = trim($value);
	  if ($value != '') {
	    $term .= $connector . DBstring($value);
        $connector = ',';
	    ++$cnt;
  } } }
  if ($cnt != 0) {
    if ($cnt == 1) {
	  $term = 'groupsmembers.user_id  = ' . $term;
    } else {
	  $term = 'groupsmembers.user_id in (' . $term . ')';
    }
	$constraints[] = 
'exists
       (select null
          from groupsmembers
         where groupsmembers.group_id = t2.group_id
           and ' . $term . '
           and (groupsmembers.user_id = ' . DBstring($gUserid) . '
                or exists
              (select null
                 from users
                where users.user_id = groupsmembers.user_id
                  and ( users.global   in (1,3) or
                       (users.visible in (1,3) and ismember = 1))
              ))
       )';
  }

  $min_cnt   = getpost('min_cnt');
  $max_cnt   = getpost('max_cnt');
  if (isset($min_cnt) || isset($max_cnt)) {
    if (!isset($max_cnt)) {
	  if ($min_cnt > 0) {
		$constraints[] = DBnumber($min_cnt) . '<= cnt';
	  }
    } else if (!isset($min_cnt)) {
	  $constraints[] = 'cnt <= ' . DBnumber($max_cnt);
    } else if ($min_cnt == $max_cnt) {
      $constraints[] = 'cnt = ' . DBnumber($max_cnt);
    } else {
      $constraints[] = 'cnt between ' . DBnumber($min_cnt) . ' and ' . DBnumber($max_cnt);
  } }

  if (isset($mine)) {
    $creator_user_id = $gUserid;
  } else {
    $creator_user_id = getpost('creator_user_id');
  }
  if (isset($creator_user_id)) {
	$constraints[] = 'creator_user_id = ' . DBstring($creator_user_id);
  }

  $min_created   = getpost('min_created');
  $max_created   = getpost('max_created');
  if (isset($min_created) || isset($max_created)) {
    if (!isset($max_created)) {
      $constraints[] = DBdate($min_created) . '<= created';
    } else if (!isset($min_created)) {
      $constraints[] = 'created <= ' . DBdate($max_created);
    } else if ($min_created == $max_created) {
      $constraints[] = 'created = ' . DBdate($max_created);
    } else {
      $constraints[] = 'created between ' . DBdate($min_created) . ' and ' . DBdate($max_created);
  } }

  $min_modified  = getpost('min_modified');
  $max_modified  = getpost('max_modified');
  if (isset($min_modified) || isset($max_modified)) {
    if (!isset($max_modified)) {
      $constraints[] = DBdate($min_created) . '<= modified';
    } else if (!isset($min_modified)) {
      $constraints[] = 'modified <= ' . DBdate($max_modified);
    } else if ($min_modified == $max_modified) {
      $constraints[] = 'modified = ' . DBdate($max_modified);
    } else {
      $constraints[] = 'modified between ' . DBdate($min_modified) . ' and ' . DBdate($max_modified);
  } }

  if (count($constraints) > 0) {
    $query .= '
 where (' . implode ('
   and ', $constraints) . ')';
  }

  $totalHits = getPost('totalHits');
  if (!isset($totalHits)) {
    $ret = DBquery(
'select count(*) as totalHits
  from 
(
' . $query . '
) hits');
    if (!$ret) {
      goto close;
    }
    $row = DBfetch($ret);
    $totalHits = $row['totalHits'];
  }


  $order = getpost('order');
  if (isset($order)) {
    $query .= '
 order by ' . $order;
    $desc = getpost('desc');
    if (isset($desc)) {
      $query .= ' desc';
  } }

  $at = getpost('at');
  if (!isset($at)) {
    $at = 0;
  } else {
    $at = intval($at);
    if ($at < 0) {
      $at = 0;
  } }

  $page = getpost('page');
  if (!isset($page)) {
    $page = 100;
  } else {
    $page = intval($page);
    if ($page < 1) {
      $page = 100;
    } else if ($page > 999) {
      $page = 999;
  } }

  $next = getpost('next');
  if (isset($next)) {
    $at += $page;
  } else {
    $prev = getpost('prev');
    if (isset($prev)) {
      $at -= $page;
      if ($at < 0) {
        $at = 0;
  } } }

  $query .= '
 limit ' . $page;
  if ($at != 0) {
    $query .= ' offset ' . $at;
  }

  if ($gUserid == 'ijdavis') {
    echo '
<h3>SQL Query</h3>
<pre>', htmlspecialchars($query), '
</pre>';
  }

  if (isset($mine)) {
    echo '
<h3>You have created ',  htmlspecialchars($totalHits), ' groups</h3>';
  } else {
    echo '
<h3>', htmlspecialchars($totalHits), ' Results</h3>';
  }

  $ret = DBquery($query);
  if (!$ret) {
    goto close;
  }
  echo '
<form id=page name=page action="search1.php" method="post" target=_self>
<input id=cludge type=hidden name=mode value=y />';
  hidden('mine');
  hidden('group_id');
  hidden('title');
  hidden('description');
  hidden('role');
  hidden('access');
  hidden('member');
  hidden('min_cnt');
  hidden('max_cnt');
  hidden('creator_user_id');
  hidden('min_created');
  hidden('max_created');
  hidden('min_modified');
  hidden('max_modified');
  hidden('order');
  hidden('desc');
  hidden('page');
  hidden('at');
  hidden('totalHits');

  echo '
<table id="results" class=sortable cellpadding="0" cellspacing="4" width="100%">
<tr><th class=unsortable></th><th class=startsort>Id</th><th width="70%">Title &amp; Description</th><th>Members</th><th>Creator</th><th>Access</th></tr>';

  $cnt1 = 0;
  $checkboxes_cnt = 0;
  for ($cnt = 0; $row = DBfetch($ret); ++$cnt) {
    ++$cnt1;
	++$checkboxes_cnt;
    $id = $row['group_id'];
	echo '
<tr>
<td><input type=checkbox name="edit" value=', $id, ' onclick="setGroups_changed(this);" /></td>
<td align=right>', $id,'</td>
<td>';
	$title       = $row['title'];
	$description = $row['description'];
    if (isset($description)) {
      echo '<span class="grouptitle" onmouseover="tooltip.base64(\'',
base64_encode($description), '\',null,\'tt\');" onmouseout="tooltip.hide();">',
htmlspecialchars($title),'</span>';
    } else {
      echo htmlspecialchars($title);
    }
    $ismember = $row['ismember'];
    echo '</td>
<td><input type="button" class="', ($ismember == 1 ? '' : 'non'), 'member_cnt" value="', $row['cnt'], '" onclick="clickCnt(',$id, ');" /></td>
<td>';
	$creator = $row['creator_user_id'];
	echo htmlspecialchars($creator);
	echo '</td>
<td>';
   	if ($ismember == 1) {
      if (isset($row['exclude'])) {
		if (isset($row['access'])) {
		  $access1 = $row['access'];
		  echo '<input type=button value="Forgive" onclick="clickForgive(', $id, ',', $access1, ');" />';
	    } else {
		  echo 'Banned';
		}
	  } else {
		echo '<input type=button value="Leave" onclick="clickLeave(this,', $id, ');" />';
	  }
    } else {
	  if (isset($row['exclude'])) {
		if (isset($row['access'])) {
		  $access1 = $row['access'];
		  echo '<input type=button value="Exclude" onclick="clickExclude(', $id, ',', $access1, ');" />';
		} else {
		  echo 'Exclude';
		}
	  } else {
		if (!isset($row['access'])) {
	  	  echo 'Closed';
		} else {
		  $access1 = $row['access'];
		  if ($access1 == 1) {
			echo '<input type=button value="Request" onclick="clickRequest(', $id, ');" />';
		  } else {
			echo '<input type=button value="Join" onclick="clickJoin(', $id, ');" />';
	} } } }
    echo '</td>';
	if ($creator == $gUserid) {
	  echo '
<td><input type=button value="Edit" onclick="clickEdit(', $id, ');" /></td>';
	}
	echo '
</tr>';
  }
  echo '
</table>
<input type=button id="clearall_button" value="clear all" onclick="clearall_setGroups()" />';
  if ($checkboxes_cnt != 0) {
    echo '
<input type=button id=clear_button value=clear onclick="clear_setGroups()" />
<input type=button id=set_button value=set onclick="set_setGroups()" />';
  }
  if ($cnt >= $page) {
    echo '
<input type=submit name=next value=next />';
  }
  if ($at != 0) {
    echo '
<input type=submit name=prev value=prev />';
  }
  echo '
</form>';

  enterJavascript();
  echo '
var checkboxes_cnt     = ', $checkboxes_cnt,';
var checkboxes_set_cnt = 0;
';
  exitJavascript();
  echo '

<p>', $cnt+$at, ' groups';
  if ($cnt != $cnt1) {
    echo '(', $cnt1, ' shown)';
  }
  goto close;
}

// $showCheckboxes = true;

?>
<h3>Please enter the group search criteria</h3>
<p>

<form id=form name=form action="search1.php" method="post">
<input type=hidden name=mode value=y />
<?php
hidden('showCheckboxes');
?>
<table>
<tr>
<td align=right>Group id:</td>
<td><input type=text name=group_id size=10 maxlength=10 /></td>
</tr>
<tr>
<td align=right>Title:</td>
<td><input type=text name=title size=50 />
</tr>
<tr>
<td align=right>Description:</td>
<td><input type=text name=description size=50 />
</tr>
<tr>
<td align=right>Role:</td>
<td>
<select name=excludes>
<option value=''></option>
<option value=0>Includes</option>
<option value=1>Excludes</option>
</select>
&nbsp; Access:
<select name=access>
<option value=''></option>
<option value=0>Closed</option>
<option value=1>Request</option>
<option value=2>Open</option>
</select>
</td>
</tr>

<tr>
<td align=right>Contact:</td>
<td><input type=text name=contact[] size=50 />&nbsp;or</td>
</tr>
<tr>
<td align=right></td>
<td><input type=text name=contact[] size=50 />&nbsp;or</td>
</tr>
<tr>
<td align=right></td>
<td><input type=text name=contact[] size=50 /></td>
</tr>

<tr>
<td align=right>Member id:</td>
<td><input type=text name=member[] size=50 />&nbsp;or</td>
</tr>
<tr>
<td align=right></td>
<td><input type=text name=member[] size=50 />&nbsp;or</td>
</tr>
<tr>
<td align=right></td>
<td><input type=text name=member[] size=50 /></td>
</tr>

<tr>
<td align=right>Members:</td>
<td><input type=text name=min_cnt size=10 maxlength=10 />
&nbsp;&le;&nbsp;&nbsp;
<input type=text name=max_cnt size=10 maxlength=10 />
</td>
</tr>

<td align=right>Creator:</td>
<td><input type=text name=creator_user_id size=32 maxlength=255 
<?php
if (isset($mine)) {
  echo ' value="', htmlspecialchars($gUserid),'"';
}
?>/>
</td>
</tr>

<tr>
<td align=right>Created:</td>
<td><input type=text name=min_created size=15 maxlength=255 />
&nbsp;&le;&nbsp;YYYY-MM-DD&nbsp;&le;&nbsp;
<input type=text name=max_created size=15 maxlength=255 />
</td>
</tr>

<tr>
<td align=right>Modified:</td>
<td><input type=text name=min_modified size=15 maxlength=255 />
&nbsp;&le;&nbsp;YYYY-MM-DD&nbsp;&le;&nbsp;
<input type=text name=max_modified size=15 maxlength=255 />
</td>
</tr>

<tr>
<td align=right>Sort:</td>
<td>
<select name=order>
<option value=''></option>
<option value=group_id>id</option>
<option value=title>name</option>
<option value=description>description</option>
<option value=cnt>cnt</option>
<option value=creator_user_id>creator</option>
<option value=created>created</option>
<option value=modified>modified</option>
</select>
&nbsp;Desc
<input type=checkbox name=desc></input>
</td>
</tr>
<tr>
<td align=right>Page Size:</td>
<td>
<input type=text name=page size=3 maxwidth=3></input>
</td>
</tr>
<tr>
<td></td>
<td><input type=submit name=send value=Search /><input type=reset /></td>
</tr>
</table>
</form>
<?php
close:
DBClose();
done:
?>
<p>
</body>
</html>
