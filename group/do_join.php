<?php

$gPHPscript = __FILE__;
$dir = dirname(__FILE__);
require_once($dir . '/../include/boilerplate.php');

if ($gUserid == null) {
  /* This can happen if the frame is left idle a long time */
  echo 'You are not logged on';
  goto done;
}

$group_id = getparameter('group_id');

if (!$group_id) {
  echo 'Please provide a group id';
  goto done;
}

require_once($dir . '/../include/db.php');

if (!DBconnect()) {
  return;
}

require_once($dir . '/../include/usersgroups.php');

$sql_group_id = DBnumber($group_id);

$ismember = isUserMemberOf($gUserid, $group_id);
switch ($ismember) {
case 0:
  break;
case 1:
  echo 'You are already a member of this group';
  goto close;
default:
  goto close;
}

$query =
'select title, access, exclude
  from usersgroups
 where group_id = ' . $sql_group_id;

$ret = DBquery($query);
if (!$ret) {
  goto close;
}

$row = DBfetch($ret);
if (!$row) {
  echo 'Group does not exist';
  goto close;
}
if (isset($row['exclude'])) {
  echo 'Can\'t join an exclusion group';
  goto close;
}
if (!isset($row['access'])) {
  echo 'Group is closed to new membership';
  goto close;
}
$title = $row['title'];
switch ($row['access']) {
case 1:
  goto request;
case 2:
  goto grant;
default:
  echo 'Unrecognised value for group access';
  goto close;
}

request:

$request = getparameter($request);
if (!$request) {
  $request = '';
} else {
  $request = trim($request);
}
$contacts = getparameter($contacts');

$query =
'select null
  from groupsmembers
 where group_id = ' . DBnumber($group_id) . '
   and user_id  = ' . DBstring($gUserid);

$ret = DBquery($query);
if (!$ret) {
  goto close;
}
$row = DBfetch($ret);
if (!$row) {
  $ismember = 0;
} else {
  $ismember = 1;
}

if ($type == 2) {
  $query =
'select visible_email, global_email, email
  from groupscontacts, users
 where groupscontacts.group_id = ' . DBnumber($group_id) . '
   and groupscontacts.user_id  = ' . DBstring($userid) . '
   and users.user_id           = ' . DBstring($userid);

  $ret = DBquery($query);
  if (!$ret) {
	goto close;
  }
  $row = DBfetch($ret);
  if (!$row) {
	echo '<p>Recipient is not a contact for group ', htmlspecialchars($group_id);
	$type = 1;
} }

if ($type == 1) {
  $query =
'select visible_email, global_email, email
  from groupsmembers, users
 where groupsmembers.group_id = ' . DBnumber($group_id) . '
   and groupsmembers.user_id  = ' . DBstring($userid) . '
   and users.user_id          = ' . DBstring($userid);

  $ret = DBquery($query);
  if (!$ret) {
	goto close;
  }
  $row = DBfetch($ret);
  if (!$row) {
	echo '<p>Recipient is not a member of group ', htmlspecialchars($group_id);
	goto close;
} }

$visible = $row['global_email'];
if ($visible != $type && $visible != 3) {
  if ($ismember != 1) {
	echo '<p>Email recipient refuses emails from public';
    goto close;
  }
  $visible = $row['visible_email'];
  if ($visible != $type && $visible != 3) {
	echo '<p>Email recipient refuses emails from member';
	goto close;
} }
$email = $row['email'];
if (!isset($email)) {
  echo '<p>Email recipient has no email address';
  goto close;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  echo 'Recipient email address appears invalid';
  goto close;
}

$body = '';
if (isset($_SESSION['imageMAT_firstname']) || isset($_SESSION['imagemat_lastname'])) {
  if (isset($_SESSION['imageMAT_firstname'])) {
    $body = $_SESSION['imageMAT_firstname'] . ' ';
  }
  if (isset($_SESSION['imageMAT_lastname'])) {
    $body .= $_SESSION['imageMAT_lastname'] . ' ';
  }
} else {
  $body = 'Somebody ';
}
if (isset($_SESSION['imageMAT_user_id'])) {
  $body .= '(' . $_SESSION['imageMAT_user_id'] . ') ';
}
if (isset($_SESSION['imageMAT_email'])) {
  $body .= 'having email ' . $_SESSION['imageMAT_email'] . ' ';
}
if ($ismember == 1) {
  $body .= 'as a member of group ' . $group_id . ' (' . $title . ') ';
}
if ($type == 2) {
  $body .= 'for which you are a contact ';
}

$body .= 'writes:

' . $message;

$ret = mail($email, 'Message from imageMAT user ' . $gUserid, $body,
emailHeader());
if (!$ret) {
  echo '
<p>Sorry but we were unable to send the email.' , contact(), '</div>';
  goto close;
}
echo '
<p>Email has been sent.';
close:
DBclose();
goto done;

show:

require_once($dir . '/../include/util.php');

echo '
<h3>Correspond with ImageMAT group ', $role, '</h3>';
?>
<p>
Please provide the message you wish to send.
<p>
<form action="email.php" method="post">
<?php
hidden('group_id');
hidden('tocontact');
hidden('tomember');
?>
<textarea name="message" cols="80" rows="10"><?php echo htmlspecialchars($message); ?></textarea>
<br/>
<input type=submit value=Send /><input type=reset />
</form>

<?php
done:
bodyFooter();
?>
</body>
</html>


  
echo '
<font size="+1">';

$query = 
'select groupscontacts.user_id, visible, visible_email, global, global_email
  from groupscontacts, users
 where groupscontacts.group_id = ' . DBnumber($group_id) . '
   and groupscontacts.user_id  = users.user_id
 order by user_id';

$ret = DBquery($query);
if (!$ret) {
  goto close;
}

$cnt       = 0;
$cnt1      = 0;
$connector = '';
while ($row = DBfetch($ret)) {
  ++$cnt;
  $mayEmail = true;
  $userid   = $row['user_id'];
  if ($userid != $gUserid) {
	$visible = $row['global'];
	if ($visible != 2 && $visible != 3) {
      if ($ismember != 1) {
		continue;
	  } else {
      	$visible = $row['visible'];
      	if ($visible != 2 && $visible != 3) {
		  continue;
	} } }
    $visible = $row['global_email'];
    if ($visible != 2 && $visible != 3) {
	  if ($ismember != 1) {
	    $mayEmail = false;
	  } else {
	    $visible = $row['visible_email'];
	    if ($visible != 2 || $visible != 3) {
		  $mayEmail = false;
  } } } }
  ++$cnt1;
  echo $connector;
  if ($mayEmail) {
	echo '<a href="email.php?group_id=',htmlspecialchars($group_id),
'&tocontact=\'', htmlspecialchars($userid),'\'" target="_top">';
  }
  echo htmlspecialchars($userid);
  if ($mayEmail) {
	echo '</a>';
  }
  $connector = ', ';
}
echo '
<br/>' . $cnt . ' contacts';
if ($cnt1 != $cnt) {
  echo ' (',$cnt - $cnt1, ' not shown)';
}
echo '<br/><br/>';

$query = 
'select groupsmembers.user_id, visible, visible_email, global, global_email
  from groupsmembers, users
 where groupsmembers.group_id = ' . DBnumber($group_id) . '
   and groupsmembers.user_id  = users.user_id
 order by user_id';

$ret = DBquery($query);
if (!$ret) {
  goto close;
}

$cnt       = 0;
$cnt1      = 0;
$connector = '';
while ($row = DBfetch($ret)) {
  ++$cnt;
  $mayEmail = true;
  $userid   = $row['user_id'];
  if ($userid != $gUserid) {
	$visible = $row['global'];
	if ($visible != 1 && $visible != 3) {
	  if ($ismember != 1) {
		continue;
	  }
	  $visible = $row['visible'];
      if ($visible != 1 && $visible != 3) {
		continue;
    } }
    $visible = $row['global_email'];
    if ($visible != 1 && $visible != 3) {
	  if ($ismember != 1) {
	    $mayEmail = false;
	  } else {
	    $visible = $row['visible_email'];
	    if ($visible != 1 || $visible != 3) {
		  $mayEmail = false;
  } } } }
  ++$cnt1;
  echo $connector;
  if ($mayEmail) {
	echo '<a href="email.php?group_id=',htmlspecialchars($group_id),
'&tomember=\'', htmlspecialchars($userid),'\'" target="_top">';
  }
  echo htmlspecialchars($userid);
  if ($mayEmail) {
	echo '</a>';
  }
  $connector = ', ';
}
echo '
<br/>' . $cnt . ' members';
if ($cnt1 != $cnt) {
  echo ' (',$cnt - $cnt1, ' not shown)';
}
echo '
</font>';

close:
DBclose();
done:

?>
