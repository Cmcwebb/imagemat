<!DOCTYPE HTML>
<?php

$gPHPscript = __FILE__;
$dir = dirname(__FILE__);
require_once($dir . '/../include/boilerplate.php');
require_once($dir . '/../include/util.php');
require_once($dir . '/../include/db.php');
require_once($dir . '/../include/users.php');

htmlHeader('Registration Verification');
srcStylesheet('../css/style.css');
enterJavascript();
?>
function submit()
{
  var form = document.getElementById("form");

  form.submit();
  return true;
}
<?php
exitJavascript();
?>

</head>
<body>

<?php

bodyHeader();

$code = getparameter('code');
if (!isset($code)) {
  echo '<h3>No key code provided</h3>';
  goto done;
}

if (!DBconnect()) {
  goto done;
}

$ok = true;
$email = null;
$gender = null;
$prefix = null;
$firstname = null;
$lastname = null;
$moniker = null;
$institute = null;
$institute_url = null;
$job = null;
$url = null;
$country_code = null;
$timezone_code = null;
$language_code = null;
$bio = null;
$password = null;

$query = 
'select email, gender, prefix, firstname, lastname,
       moniker, institute, institute_url, job, url, country_code,
       language_code, timezone_code, bio, password
  from shadows
 where random_key = ' . DBstring($code);

$ret = DBquery($query);
if (!$ret) {
  goto failed;
}
$row = DBfetch($ret);
if (!$row) {
  echo '
<br>The key <font color=blue>' , htmlspecialchars($code) , '</font> was not found';
  goto failed;
}
foreach ($row as $colname => $value) {
  $$colname = $value;
}

$confirm = getparameter('confirm');
if (!isset($confirm)) {
  goto get_confirm;
}

if ($password != crypt($confirm, $password)) {
  echo '
<br><font color=red>Password does not match earlier password provided</font>
<br>';
  goto get_confirm;
}

$accept = getparameter('accept');
if (!isset($accept)) {
  echo '
<font color=red>You must accept the terms and conditions</font>
<p>';
  goto get_confirm;
}

if (DBfetch($ret)) {
  echo '
<br>The key <font color=blue>' , htmlspecialchars($code) , '</font> was duplicated';
  goto failed;
}
$ret = users_constraints(null, $email, $moniker);
if (!$ret) {
  goto failed;
}

$query =
'INSERT into users
       (user_id, email, gender, prefix, firstname, lastname,
        moniker, url, institute, institute_url, job, country_code,
        language_code, timezone_code, bio, password,
        default_monitors, default_owns, default_may_see, default_may_read,
        default_may_post, default_may_copy, default_may_update,
        default_may_delete, default_may_comment, default_may_read_comments,
        default_manage_comments, default_may_x_post,
        created)
values (' .
DBstringC($moniker) .
DBstringC($email) .
DBstringC($gender) .
DBstringC($prefix) .
DBstringC($firstname) .
DBstringC($lastname) .
DBstringC($moniker) .
DBstringC($url) .
DBstringC($institute) .
DBstringC($institute_url) .
DBstringC($job) .
DBstringC($country_code) .
DBstringC($language_code) .
DBstringC($timezone_code) .
DBstringC($bio) .
DBstringC($password) . 
'0, 7, 31, 31, 1, 1, 17, 1, 17, 17, 1, 1,
utc_timestamp())';
$ret = DBquery($query);
if (!$ret) {
  goto failed;
}

$gUserid = $moniker;
$query =
'insert into usersoflanguages(user_id, language_code)
 select ' . DBstringC($gUserid) . 'language_code
   from shadowslanguages
  where random_key = ' . DBstring($code);
$ret = DBquery($query);
if (!$ret) {
  goto failed;
}

require_once($dir . '/../include/folders.php');

$ret = create_home();
if (!$ret) {
  goto failed;
}

echo '
<h3>Welcome to ImageMAT</h3>
<p>
Greetings ', formatname($prefix, $firstname, $lastname) , '
<p>
You should now be able to access ImageMAT with the userid ',
htmlspecialchars($gUserid), ' or the email ' ,
htmlspecialchars($email) , ' and your provided password
<p>
<a href=time.php>Check timezone here</a>
<p>
<a href=logon.php>Logon here</a>';

#Should cascase delete to shadowslanguages
$query = 
'delete from shadows
 where random_key = ' . DBstring($code);

DBquery($query);
goto close;

failed:
echo '
<h3>Verification failed</h3>';
goto close;

get_confirm:
echo '
<br>
Please enter the password you provided when registering.
<p>
<form name=form id=form action="verify.php" method="post">';
hidden('code');
echo 'Password: <input type=password id=confirm name=confirm size=38 maxlength=255 />
<p>
<input type=checkbox name=accept value=Y onclick=submit() /> I (',
formatname($prefix, $firstname, $lastname),
') accept the terms and conditions earlier emailed me.
</form>';

close:
DBclose();
done:
bodyFooter();
?>
</body>
</html>
