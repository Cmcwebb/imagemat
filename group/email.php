<!DOCTYPE HTML>
<?php

$gPHPscript = __FILE__;
$dir = dirname(__FILE__);
require_once($dir . '/../include/boilerplate.php');

if (mustlogon()) {
  /* This can happen if the frame is left idle a long time */
  return;
}

$tocontact = getparameter('tocontact');
$tomember  = getparameter('tomember');
$userid    = $tocontact;
if (isset($userid)) {
  $type   = 2;
  $role   = 'contact';
} else {
  $userid = $tomember;
  $type   = 1;
  $role   = 'member';
}

htmlHeader('Send E-mail to an imageMAT ' . $role);
srcStylesheet('../css/style.css');
?>
</head>
<body>

<?php
bodyHeader();

$group_id  = getparameter('group_id');
if (!isset($group_id)) {
  echo '<p>Invalid group id';
  goto done;
}

if (!isset($userid)) {
  echo '<p>Recipient of email not specified';
  goto done;
}
$message   = getpost('message');

if (!isset($message)) {
  goto show;
}
$message   = trim($message);
if ($message == '') {
  goto show;
}

require_once($dir . '/../include/db.php');

if (!DBconnect()) {
  goto done;
}

require_once($dir . '/../include/usersgroups.php');

$query =
'select title
  from usersgroups
 where group_id = ' . DBnumber($group_id);

$ret = DBquery($query);
if (!$ret) {
  goto close;
}

$row = DBfetch($ret);
if (!$row) {
  echo '<p>Group ', htmlspecialchars($group_id), ' does not exist';
  goto close;
}

$title = $row['title'];

$ismember = isUserMemberOf($gUserid, $group_id);
if ($ismember < 0) {
  goto close;
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

