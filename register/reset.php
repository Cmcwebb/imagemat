<?php

$gPHPscript = __FILE__;
$dir = dirname(__FILE__);
$topname = 'http://' . $_SERVER['SERVER_NAME'] . dirname($_SERVER['REQUEST_URI']);
require_once($dir . '/../include/boilerplate.php');
require_once($dir . '/../include/db.php');
require_once($dir . '/../include/captcha.php');
?>
<!DOCTYPE HTML>
<?php

htmlHeader('Reset Password');
srcStylesheet('../css/style.css');
?>
</head>

<body>

<?php

bodyHeader();

$dbopen = false;
$mode  = getpost('mode');
$email = getpost('email');
$ok = true;

if (!isset($mode)) {
  goto retry;
}
if (!isset($email)) {
  echo '
<br>An email address is required.';
  goto retry;
}
if (!captchaOK()) {
  echo '
<br><div class=error>Captcha word recognition failed</div>';
  goto retry;
}

if (!DBconnect()) {
  goto done;
}

$dbopen = true;

$query = 
'select user_id
  from users
 where email = ' . DBstring($email);

$ret = DBquery($query);
if (!$ret) {
  goto done;
}
$row = DBfetch($ret);
if (!$row) {
  echo '
<br><div class=error>E-mail address not known</div>';
  goto retry;
}

$userid = $row['user_id'];
$random = rand();

$body = '
We are resetting the imageMAT password for ' . $userid . ' to ' . $random . '

To check that you can log on with this new password visit
' . $topname . '/logon.php
To change your password visit
' . $topname . '/profile.php

If you continue having problems accessing imageMAT please contact: ' .
$gAdminEmail;

$ret = mail($email, 'Here is your requested imageMAT information', $body,
 emailHeader());
if (!$ret) {
  echo '
<br />We are sorry, but we were unable to send email to you.' . contact();
  goto retry;
}

$query = 
'update users
   set password = ' . DBstring(crypt($random)) . '
where email = ' . DBstring($email);

$ret = DBquery($query);
if (!$ret) {
  goto done;
}
$ret = DBupdated($ret);
if ($ret == 0) {
  echo '
<br>Email ' . htmlspecialchars($email) . ' not found';
  goto retry;
}
if ($ret > 1) {
  echo DBhiterror($query, $ret);
  goto retry;
}

echo '
<h3>Password reset succeeded</h3>
<p>
You should now be able to access imageMAT with the userid ' . htmlspecialchars($userid) . ' or the email ' ,
htmlspecialchars($email) , '<br /> and the password we have emailed you. <form method="link" action="logon.php">
<input type="submit" value="Log in">
</form>';
goto done;

retry:

echo '
<h3>Resetting password</h3>
<p>
To reset your password, provide your email address and the indicated security
code in the form below.  A new password will be emailed to you at the
given email address.
<p>
<form id=form name=form action="reset.php" method="post">
<input type=hidden name=mode value=y />
<table>
<tr>
<td align=right>Email:</td>
<td>
<input type=text id=email name=email size=32 maxlength=255 value="',
htmlspecialchars($email), '" />
</td>
</tr>

<tr>
<td></td>';
emit_captcha(1);
echo '
</tr>
<tr>
<td></td>
<td><input type=submit name=send value=Send /><input type=reset /></td>
</tr>
</table>
</form>';

done:
if ($dbopen) {
  DBclose();
}

bodyFooter();
?>
</body>
</html>
