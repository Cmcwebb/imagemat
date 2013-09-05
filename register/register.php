<?php

$gPHPscript = __FILE__;
$dir = dirname(__FILE__);
$topname = 'http://' . $_SERVER['SERVER_NAME'] . dirname($_SERVER['REQUEST_URI']);
require_once($dir . '/../include/boilerplate.php');
require_once($dir . '/../include/util.php');
require_once($dir . '/../include/db.php');
require_once($dir . '/../include/captcha.php');
?>
<!DOCTYPE HTML>
<?php
htmlHeader('Registration');
srcStylesheet('../css/style.css');
enterJavascript();
?>
<?php exitJavascript(); ?>
</head>
<body>

<?php
bodyHeader();

if (livesystem()) {
  $pi = getparameter('pi');
  if (!isset($pi) || $pi != 'McWebb') {
    echo '
<h3>ImageMAT closed to new registrations</h3>
<br><div class=error>If you wish to be added to the live imageMAT system please contact ', $gAdminEmail, '
</div>';
    goto done;
} }

if (!DBconnect()) {
  goto done;
}

# These variable names must match the names in users table
$prefix = getpost('prefix');
$gender = getpost('gender');
$firstname = getpost('firstname');
$lastname = getpost('lastname');
$moniker = getpost('moniker');
$email = getpost('email');
$email2 = getpost('email2');
$institute = getpost('institute');
$institute_url = getpost('institute_url');
$job = getpost('job');
$url = getpost('url');
$country_code = getpost('country_code');
$timezone_code = getpost('timezone_code');
$language_code = getpost('language_code');
$language_codes = getpost('language_codes');
$bio = getpost('bio');

$ok = true;
$check = getpost('check');
$mode = getpost('mode');
if (isset($mode) && !isset($check)) {
  $remote = $_SERVER['REMOTE_ADDR'];
  $password1 = getpost('password1');
  $password2 = getpost('password2');

  require_once($dir . '/../include/users.php');

  if (!isset($firstname)) {
    echo '
<br><div class=error>First name missing</div>';
    $ok = false;
  }
  if (!isset($lastname)) {
    echo '
<br><div class=error>Last name missing</div>';
    $ok = false;
  } 
  if (!isset($moniker)) {
    echo '
<br><div class=error>User name missing</div>';
    $ok = false;
  } 
  require_once($dir . '/../include/folders/valid.php');
  
  if (!valid_folder_name($moniker, null, null, null)) {
    $ok = false;
  }

  if (!isset($email)) {
    echo '
<br><div class=error>Email missing</div>';
    $ok = false;
  } else {
    if ($email != $email2) {
      echo '
<br><div class=error>Email "' , htmlspecialchars($email) , '" and confirmation email "' , htmlspecialchars($email2) , '" disagree</div>';
      $ok = false;
    } else {
      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo '
<br><div class=error>Email "' , htmlspecialchars($email) , '" appears invalid</div>';
        $ok = false;
  } } }

  $ret = users_constraints($gUserid, $email, $moniker);
  if (!$ret) {
    $ok = false;
  }

  if (!isset($password1)) {
    echo '
<br><div class=error>Password missing</div>';
    $ok = false;
  } else {
    if ($password1 != $password2) {
      echo '
<br><div class=error>New password and confirmation password disagree</div>';
      $ok = false;
  } }

  if (!captchaOK()) {
    echo '
<br><div class=error>Captcha word recognition failed</div>';
    $ok = false;
  }

  if ($ok) {
	# Looks good -- commit the action
    $crypt = crypt($password1);

    require_once($dir . '/../include/date.php');
    $now = gmtnow();
    $expires = $now + 7 * 24 * 60 * 60;
    $expiration = gmttod($expires);
    $rand = dechex(mt_rand()) . dechex(mt_rand());
    $salt = $moniker . $email . $expires;
    $key  = md5($rand . $salt);

    $query =
'INSERT into shadows
       (random_key, email, gender, prefix, firstname, lastname,
        moniker, institute, institute_url, job, url, country_code,
        language_code, timezone_code, bio, password,
        ip_address, created)
values (' .
DBstringC($key) .
DBstringC($email) .
DBstringC($gender) .
DBstringC($prefix) .
DBstringC($firstname) .
DBstringC($lastname) .
DBstringC($moniker) .
DBstringC($institute) .
DBstringC($institute_url) .
DBstringC($job) .
DBstringC($url) .
DBstringC($country_code) .
DBstringC($language_code) .
DBstringC($timezone_code) .
DBstringC($bio) .
DBstringC($crypt) . 
DBstringC($remote) .
'utc_timestamp())';
    $ret = DBquery($query);
    if (!$ret) {
      goto close;
    }

    if ($language_codes){
      foreach ($language_codes as $language_code){
        $query = 
'insert into shadowslanguages(random_key, language_code)
values (' . DBstringC($key) .  DBstring($language_code) . ')';
        $ret = DBquery($query);
        if (!$ret) {
          goto close;
    } } }

    $body = '

Someone, probably you, from IP address ' . $remote . '
has registered an account "'. $moniker . '" with e-mail address ' .
$email . ' on imageMAT.

To confirm that this account really does belong to you, and to
enable the earlier provided password, open this link in your browser.

' . $topname . '/verify.php?code=' .
urlencode($key) . '

If you did *not* register the account, follow this link to
cancel this e-mail address confirmation.

' . $topname . '/cancel.php?code=' .
urlencode($key) . '

This confirmation code will expire ' . $expiration . '

TERMS AND CONDITIONS:

Prior to creating an ImageMAT account, you must review and accept the
following terms and conditions.

<<Terms and conditions go here>>
';
    $ret = mail($email, 'Please confirm ImageMAT e-mail address', $body,
emailHeader());
    if (!$ret) {
      echo '
<br><div class=error>Sorry but we were unable to send email to you.' , contact(), '</div>';
      goto close;
    }
    echo '
<p>
Thank you for registering ' ,  formatname($prefix, $firstname, $lastname) , '
<p>
Please respond to the confirmation email being sent to you at ',
htmlspecialchars($email), ', to become an identified user of ImageMAT.
<p>
If you do not receive an email within a matter of minutes please also
check your spam in-box
<p>';
    goto close;
} }

require_once($dir . '/../include/language.php');
require_once($dir . '/../include/country.php');
require_once($dir . '/../include/timezone.php');

?>
<div class="backgroundBox">
<h3>Profile</h3>

<form id='register' action="register.php" method="post">
<input type=hidden name=mode value=y />
<?php
hidden('pi');
?>

<table>
<tr>
<td colspan="2">
<font color=#990000>All fields marked with a "*" are required</font>
</td>
</tr>
</table>

<?php
// this is the section for all priority fields requiring *
?>
<h4>Name</h4>

<table>
<tr>
<td align=right>Username:</td>
<td>
<input type=text id=moniker name=moniker size=38 maxlength=255 value="<?php echo htmlspecialchars($moniker); ?>" />
<font color=#990000>*</font></td>
<td align=left>
<input type="submit" name="check" value="User Name Available?" />
</td>
</tr>

<tr>
<td align=right>Prefix:</td>
<td>
<select name=prefix>
<option size=10></option>
<option <?php if ($prefix == 'Miss') echo 'selected'; ?>>Miss</option>
<option <?php if ($prefix == 'Ms')   echo 'selected'; ?>>Ms</option>
<option <?php if ($prefix == 'Mrs')  echo 'selected'; ?>>Mrs</option>
<option <?php if ($prefix == 'Mr')   echo 'selected'; ?>>Mr</option>
<option <?php if ($prefix == 'Dr')   echo 'selected'; ?>>Dr</option>
</select>
</td>
</tr>

<tr>
<td align=right>First Name:</td>
<td>
<input type=text id=firstname name=firstname size=28 maxlength=255 value="<?php echo htmlspecialchars($firstname); ?>" />
<font color=#990000>*</font></td>
</tr>

<tr>
<td align=right>Last Name:</td>
<td>
<input type=text id=lastname name=lastname size=38 maxlength=255 value="<?php echo htmlspecialchars($lastname); ?>" />
<font color=#990000>*</font></td>
</tr>

<tr>
<td align=right>Email:</td>
<td>
<input type=text id=email name=email size=38 maxlength=255 value="<?php echo htmlspecialchars($email); ?>" />
<font color=#990000>*</font></td>
</tr>

<tr>
<td align=right>Confirm Email:</td>
<td>
<input type=text id=email2 name=email2 size=38 maxlength=255 value="<?php echo htmlspecialchars($email2); ?>" />
<font color=#990000>*</font></td>
</tr>

<tr>
<td align=right>Password:</td>
<td>
<input type=password id=password1 name=password1 size=38 maxlength=255 />
<font color=#990000>*</font></td>
</tr>

<tr>
<td align=right>Confirm Password:</td>
<td>
<input type=password id=password2 name=password2 size=38 maxlength=255 />
<font color=#990000>*</font></td>
<td><em>Type your password again</em></td>
</tr>
</table>

<?php
//This section is for ancillary and optional settings
?>

<?php 
// This is the submit section
?>

<h4>Confirm and Submit</h4>
<table>
<tr>
<td align=right>This form helps <br />us prevent automated <br />spam submissions</td>
<?php
emit_captcha(3);
?>
</tr>
</table>

<table>
<tr>
<td>&nbsp;</td>
</tr>
<tr>
<td align=right><strong><em>Your registration is complete</em></strong></td>
<td><input type=submit name=send value=Send /><input type=reset /></td>
</tr>
<tr>
<td>&nbsp;</td>
</tr>
</table>
</form>
</div>
<?
if (isset($check)) {
  require_once($dir . '/../include/moniker.php');
  moniker_availability(null, $moniker);
}

close:
DBclose();
done:

bodyFooter();
?>
</body>
</html>

