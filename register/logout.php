<!DOCTYPE HTML>
<?php

$gPHPscript = __FILE__;
$dir = dirname(__FILE__);
require_once($dir . '/../include/boilerplate.php');
require_once($dir . '/../include/db.php');


session_destroy();
unset($GLOBALS['_SESSION']);

htmlHeader('Logout');
srcStylesheet('../css/style.css');
?>
</head>
<body>
<?php
bodyHeader();
?>
<h3>You are now logged out.</h3>
<p>
If you would like to log back into imageMAT, please enter your account details.

<?php
// this is the beginning of the logon insert. If this fails, remove everything until bodyFooter();
$ok = true;
$id = getpost('id');

if (!isset($mode)) {
  goto retry;
}
if (!isset($id)) {
  echo '
<br><div class=error>A user id is required to logon.</div>';
  goto retry;
}

if (!DBconnect()) {
  goto done;
}

$password1 = getpost('password');

$query = 
'select user_id as gUserid, password, prefix, firstname, lastname, moniker,
       email, timezone_code, language_code, hideTooltips,
       created, updated, last_on, utc_timestamp, disabled
  from users
 where user_id  = ' . DBstring($id) . '
    or moniker  = ' . DBstring($id) . '
    or email    = ' . DBstring($id);

$ret = DBquery($query);
if (!$ret) {
  goto done;
}
$row = DBfetch($ret);
if (!$row) {
  echo '
<br><div class=error>The id ' , htmlspecialchars($id) , ' is unknown.</div>';
  goto retry;
} 
foreach ($row as $colname => $value) {
  if ($value != '') {
    $$colname = $value;
} }
if (isset($disabled)) {
  echo '
<br><h3><font color=red>Account Disabled</font></h3>
<p><i>
', $disabled, '</i>';
  goto retry;
}
// echo '<br>Passwd=', $password1, '<br>File=', $password;

if ($password != crypt($password1, $password)) {
  echo 
'<br><div class=error>Password does not match the one on file</div>';
  goto retry;
}

require_once($dir . '/../include/session.php');
require_once($dir . '/../include/date.php');

if (!isset($prefix)) {
  $prefix = '';
}

echo '
<b>Welcome ', formatname($prefix, $firstname, $lastname), '
<p>
Logged on to ', $gDBname, ' as ', $gDBuser, '
<p></b>';

update_session();

$query =
'update users
   set last_on = utc_timestamp
 where user_id = ' . DBstring($gUserid);

DBupdate1($query);

show_session();
echo '
<p>
<table>
<tr><td>Created</td><td>' , clientstime($created) , '</td></tr>
<tr><td>Updated</td><td>' , clientstime($updated) , '</td></tr>
<tr><td>Last on</td><td>' , clientstime($last_on) , '</td></tr>
<tr><td>Time now</td><td>' , clientstime($utc_timestamp) , '</td></tr>
</table>';
goto done;

retry:

if (isset($_SESSION['imageMAT_user_id'])) {
  echo '
<p>
You are currently logged in as 
<table rules=all border=2>
<tr><td>User ID</td><td>' , htmlspecialchars($gUserid) , '</td></tr>
<tr><td>Name</td><td>' , username() , '</td></tr>
<tr><td>Username&nbsp;</td><td>' , htmlspecialchars($_SESSION['imageMAT_moniker']) , '</td></tr>
<tr><td>Email</td><td>' , htmlspecialchars($_SESSION['imageMAT_email']) , '</td></tr>
</table>
<p>
To connect as someone else enter details below:';
}
?>
<p>
<form id=logon action="logon.php" method="post" target="_top">
<table>
<input type=hidden name=mode value=y />
<tr>
<td align=right>User Name:</td>
<td>
<input type=text name=id size=32 maxlength=255 value="<?php echo htmlspecialchars($id); ?>" />
</td>
</tr>

<tr>
<td align=right>Password:</td>
<td>
<input type=password id=password name=password size=32 maxlength=255 />
</td>
</tr>

<tr>
<td></td>
<td><input type=submit name=send value=Send /><input type=reset /></td>
</tr>
</table>
</form>


<table>
<tr>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
</tr>
<tr>
<td>
Don't have an imageMAT account? <input type=button onClick="location.href='register.php'" value='Register'>
</td>
</tr>
<tr>
<td>
<p><form>Lost your user name or password? <input name="manage" type="button" value="Request" onclick="location.href='reset.php'"></form></p>
</td>
</tr>

<tr>
<td>&nbsp;</td>
</tr>
</table> 
<?
done:
DBclose();
bodyFooter();
// this is the end of the insert - if this fails, reinsert <?php bodyFooter(); 
?>



</body>
</html>
