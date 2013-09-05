<!DOCTYPE HTML>
<?php

$gPHPscript = __FILE__;
$dir = dirname(__FILE__);
require_once($dir . '/../include/boilerplate.php');
require_once($dir . '/../include/db.php');

$mode = getpost('mode');

header('window-target: _top');
htmlHeader('Logon');
srcStylesheet('../css/style.css');
?>
<meta http-equiv="window-target" content="_top">
</head>
<body>

<?php

if (isset($mode)) {
  session_destroy();
  unset($GLOBALS['_SESSION']);
  session_start();
}
bodyHeader();
?>
<!--[if lt IE 9]>
imageMAT works on Internet Explorer 9+. Please update your browser.
<![endif]-->
<?php
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
update_session();
$query =
'update users
   set last_on = utc_timestamp
 where user_id = ' . DBstring($gUserid);


DBupdate1($query);

// get and setparent id under session 
// next setp would be to take this and actually 

/*
$parent_id_query = 'select folder_id from folders where creator_user_id = '.DBstring($gUserid).'and parent_folder_id = 0';

$folder_id_row = mysqli_query($gDBc, $parent_id_query);
 if (!$folder_id_row || !DBok()) {
    DBerror($parent_id_query);
    $ret = false;
  echo 'Nothing in db '. $gUserid ;
 }

 while (($row = mysqli_fetch_assoc($folder_id_row)) {             
    $user_folder_id = $folder_id_row["folder_id"]
 }

 $_SESSION['parent_id'] = $user_folder_id ;
 
*/

// set the token 
get_token();

show_actionItems();
show_session();
DBclose();

header("Location: ../library/library1.php");

echo '
<table>
<tr>
<td><strong>Welcome ', formatname($prefix, $firstname, $lastname), '</strong></td>
</tr>
<tr>
<td><hr /></td>
</tr>
<p>
<tr>
<td>
Continue on to to your <strong><a href="../library/library1.php" />Library</a></strong>
</td>
</tr>
</table>
<p>';


/*$query =
'update users
   set last_on = utc_timestamp
 where user_id = ' . DBstring($gUserid);


DBupdate1($query);

show_actionItems();
show_session();
*/
echo '
<p>
<table>
<tr><td>Created</td><td>' , clientstime($created) , '</td></tr>
<tr><td>Updated</td><td>' , clientstime($updated) , '</td></tr>
<tr><td>Last on</td><td>' , clientstime($last_on) , '</td></tr>
<tr><td>Time now</td><td>' , clientstime($utc_timestamp) , '</td></tr>
</table>
</div>';
goto done;

retry:

echo '
<table>
<tr>
<td>
<h3>Please enter your account details</h3>
</td>
</tr>
</table>';


if (isset($_SESSION['imageMAT_user_id'])) {

	header("Location: ../library/library1.php");
  echo '
<p>

You are currently logged in as 
<p>
<table align="center" rules=all border=1>
<tr><td>User ID</td><td>' , htmlspecialchars($gUserid) , '</td></tr>
<tr><td>Name</td><td>' , username() , '</td></tr>
<tr><td>Username&nbsp;</td><td>' , htmlspecialchars($_SESSION['imageMAT_moniker']) , '</td></tr>
<tr><td>Email</td><td>' , htmlspecialchars($_SESSION['imageMAT_email']) , '</td></tr>
</table>

<p>

<table>
<tr>
<td>
To connect as someone else enter details below:
</td>
</tr>
</table>';
}

?>

<p>
<form id=logon action="logon.php" method="post" target="_top">
<table>
<input type=hidden name=mode value=y />
<tr>
<td align=right>User Name:</td>
<td>
<input type=text name=id size=32 maxlength=255 value="<?php echo htmlspecialchars($id); ?>" autofocus/>
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
<td><input type="submit" name="send" value="Send"><input type=reset />
</td>
</tr>
<tr>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
</tr>
</table>
</form> 

<table>
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

<div class="copyright">
&copy; 2012</div>


<?
done:
DBclose();
bodyFooter();

?>

</body>
</html>
