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

htmlHeader('User Profile Form');

srcStylesheet(
  '../css/style.css',
  '../css/tooltip.css'
);
srcJavascript(
  '../js/tooltip.js'
);

?>
</head>
<body>

<?php

bodyHeader();

if (!DBconnect()) {
  goto done;
}

# These variable names must match the names in users table
$prefix = getpost('prefix');
$gender = getpost('gender');
$firstname = getpost('firstname');
$lastname = getpost('lastname');
$moniker= getpost('moniker');
$email = getpost('email');
$email2 = getpost('email2');
$institute = getpost('institute');
$institute_url = getpost('institute_url');
$job = getpost('job');
$url = getpost('url');
$country_code = getpost('country_code');
$language_code = getpost('language_code');
$timezone_code = getpost('timezone_code');
$bio = getpost('bio');
$language_codes = getpost('language_codes');
$visible      = getpost('visible');
$visible_email = getpost('visible_email');
$global        = getpost('global');
$global_email  = getpost('global_email');
$hideTooltips = getpost('hideTooltips');

$ok = true;
$mode = getpost('mode');
if (!isset($mode)) {
  if (!isset($hideTooltips) && isset($_SESSION['imageMAT_hideTooltips'])) {
    $hideTooltips = 'Y';
  }
} else {
  $password1 = getpost('password1');
  $password2 = getpost('password2');

  require_once($dir . '/../include/users.php');

  $orig_email = null;
  $crypt = null;
  $password0 = getpost('password0');

  $query = 
'select email, password from users
 where user_id = ' . DBstring($gUserid);
  $ret = DBquery($query);
  if (!$ret) {
    goto close;
  }
  $row = DBfetch($ret);
  if (!$row) {
    echo '
<br><div class=error>Userid ' . htmlspecialchars($gUserid) . ' not found</div>';
    $ok = false;
  } else {
    $orig_email = $row['email'];
    $crypt = $row['password'];
    if ($crypt != crypt($password0, $crypt)) {
      echo '
<br><div class=error>Provided password does not match the one on file</div>';
      $ok = false;
  } }

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
<br><div class=error>Username missing</div>';
    $ok = false;
  } 

  if (!isset($email)) {
    echo '
<br><div class=error>Email missing</div>';
    $ok = false;
  } else if (isset($email2) || $email != $orig_email) {
    if ($email != $email2) {
      echo '
<br><div class=error>New email "' , htmlspecialchars($email) , '" and confirmation email "' , htmlspecialchars($email2) , '" disagree</div>';
      $ok = false;
      if (isset($orig_email)) {
        echo '
<br><div class=error>Current email "' , htmlspecialchars($orig_email) , '"</div>';
      }
    } else {
      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo '
<br><div class=error>Email "' , htmlspecialchars($email) , '" appears invalid</div>';
        $ok = false;
  } } }

  if (isset($password1)) {
    if ($password1 != $password2) {
      echo '
<br><div class=error>New password and confirmation password disagree</div>';
      $ok = false;
    } else {
      $crypt = crypt($password1);
  } }

  $ret = users_constraints($gUserid, $email, $moniker);
  if (!$ret) {
    $ok = false;
  }
  if ($ok) {

    if (isset($hideTooltips)) {
	  $hideTooltips = 'Y';
	}
    $query =
'update users
    set email=' . DBstringC($email) . '
       gender=' . DBstringC($gender) . '
       prefix=' . DBstringC($prefix)  . '
    firstname=' . DBstringC($firstname) . '
     lastname=' . DBstringC($lastname) . '
      moniker=' . DBstringC($moniker)  . '
    institute=' . DBstringC($institute) . '
institute_url=' . DBstringC($institute_url) . '
          job=' . DBstringC($job) . '
          url=' . DBstringC($url) . '
 country_code=' . DBstringC($country_code) . '
timezone_code=' . DBstringC($timezone_code) . '
language_code=' . DBstringC($language_code) . '
     password=' . DBstringC($crypt) . '
          bio=' . DBstringC($bio) . '
      visible=' . DBnumberC($visible) . '
visible_email=' . DBnumberC($visible_email) . '
       global=' . DBnumberC($global) . '
 global_email=' . DBnumberC($global_email) . '
 hideTooltips=' . DBstringC($hideTooltips) . '
      updated=utc_timestamp
 where user_id = ' . DBstring($gUserid) 
;
/*
. '
   and not exists
      (select null
         from users
        where user_id != ' . DBstring($gUserid) . '
          and user_id  = ' . DBstring($moniker) . '
      )'
;
*/
    DBupdate1($query);

    $old_moniker = getpost('old_moniker');
    $ret = users_constraints($gUserid, null, $moniker);
    if (!$ret) {
      $query =
'update users
    set moniker = ' . DBstring($gUserid) . '
  where user_id = ' . DBstring($gUserid);

      $ret = DBupdate1($query);
      if ($ret) {
        echo '
<br>Moniker changed from ' . $moniker . ' back to ' . $gUserid;
        $moniker = $gUserid;
      }
    }

    $query =
'delete from usersoflanguages
 where user_id = ' . DBstring($gUserid);

    DBquery($query);

    if ($language_codes){
      foreach ($language_codes as $reads){
        $query = 
'insert into usersoflanguages(user_id, language_code)
values (' . DBstringC($gUserid) .  DBstring($reads) . ')';
        DBquery($query);
    } }

    require_once($dir . '/../include/date.php');
    require_once($dir . '/../include/session.php');
    update_session();
    echo '
<h3>Profile updated</h3>';

    require_once($dir . '/../include/language.php');

    show_session();
    $reads = reads_languages();
    if (isset($reads)) {
      echo '<p>You read ', $reads;
    }
    echo '
<p>
Check your timezone settings 
<a href=time.php>here</a>
<p>';
    if ($moniker != $old_moniker) {
      if ($old_moniker != $gUserid) {
        echo '
<br>Because you are now no longer using the moniker "' , $old_moniker , '"
others may claim it which may permit them to impersonate you.';
      }
      if ($moniker != $gUserid) {
        echo '
<br>If someone else earlier abandoned the moniker "' , $moniker , '" you may 
be presumed to be them.
<br>Using your user id of "' , $gUserid , '" as your moniker avoids such
problems since only you may assign yourself this moniker.';
      }
      echo '
<br>Consider carefully the implications of changing your moniker.
<p>';
    }
    goto close;
} }

echo '
<h3>Profile</h3>
<p>
<font color=#990000>All fields marked with a "*" are required.</font>
<br />
<h4>Name</h4>';
$query = 
'select email, gender, prefix, firstname, lastname, moniker,
       institute, institute_url, job, url, country_code,
       timezone_code, language_code, bio, hideTooltips,
	   visible, visible_email, global, global_email
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
$old_moniker = $row['moniker'];
foreach ($row as $colname => $value) {
  if (!$$colname) {
    $$colname = $value;
} }    

if (!isset($language_codes)) {
  $query = 
'select language_code
  from usersoflanguages
 where user_id = ' . DBstring($gUserid);

  $ret = DBquery($query);
  if ($ret) {
    for ($cnt = 0; $row = DBfetch($ret); ++$cnt) {
      if (!isset($language_codes)) {
        $language_codes = array();
      }
      $language_codes[$cnt] = $row['language_code'];
} } }

require_once($dir . '/../include/language.php');
require_once($dir . '/../include/country.php');
require_once($dir . '/../include/timezone.php');

?>
<form id=profile action="profile.php" method="post">
<input type=hidden name=mode value=y />
<?php
  hidden('old_moniker');
?>
<table>
<tr><td align=right>Username:</td><td> 
<?php
echo htmlspecialchars($gUserid)
?>
</td>

</tr>

<tr>
<td align=right>Username:</td>
<td>
<input type=text id=moniker name=moniker size=36 maxlength=255 value="<?php echo htmlspecialchars($moniker); ?>" />
</td>
<td><em>You must re-enter your username and password</em></td>
<!--<td align=left>
<input type="submit" name="check" value="check availability" />
</td>-->
</tr>


<tr><td align=right>Password:</td>
<td>
<input type=password id=password0 name=password0 size=36 maxlength=255 />
</td>
<td><em>in order to make changes to your profile</em>
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
<input type=text id=firstname name=firstname size=36 maxlength=255 value="<?php echo htmlspecialchars($firstname); ?>" />
<font color=#990000>*</font></td>
</tr>

<tr>
<td align=right>Last Name:</td>
<td>
<input type=text id=lastname name=lastname size=36 maxlength=255 value="<?php echo htmlspecialchars($lastname); ?>" />
<font color=#990000>*</font></td>
</tr>

<tr>
<td align=right>Current Email:</td>
<td>
<input type=text id=email name=email size=36 maxlength=255 value="<?php echo htmlspecialchars($email); ?>" />
<font color=#990000>*</font></td>
</tr>

<tr>
<td></td>
</tr>

<tr>
<td></td>
<td colspan=2><em>Do you want to change your email address?</em></td>
</tr>

<tr>
<td align=right>New Email:</td>
<td>
<input type=text id=email name=email size=36 maxlength=255 value="<?php echo htmlspecialchars($email); ?>" />
<font color=#990000>*</font></td>
</tr>

<tr>
<td align=right>Confirm Email:</td>
<td>
<input type=text id=email2 name=email2 size=36 maxlength=255 value="<?php echo htmlspecialchars($email2); ?>" />
<font color=#990000>*</font></td>
</tr>

<tr>
<td></td>
</tr>

<tr>
<td></td>
<td colspan=2><em>Do you want to change your password?</em></td>
</tr>

<tr>
<td align=right>New Password:</td>
<td>
<input type=password id=password1 name=password1 size=36 maxlength=255 />
<!--<font color=#990000>*</font>
-->
</td>
</tr>

<tr>
<td align=right>Confirm New Password:</td>

<td>
<input type=password id=password2 name=password2 size=36 maxlength=255 />
<!--<font color=#990000>*</font></td>
-->
</td>
</tr>
</table>

<h4>About Yourself</h4>

<table>
<tr>
<td align=right>Professional Affiliation:</td>
<td>
<input type=text id=institute name=institute size=36 maxlength=255 value="<?php echo htmlspecialchars($institute); ?>" />
</td>
</tr>

<tr>
<td align=right>Occupation:</td>
<td>
<input type=text id=job name=job size=36 maxlength=255 value="<?php echo htmlspecialchars($job); ?>" />
</td>
</tr>

<tr>
<td align=right>Country:</td>
<td>
<?php echo select_country($country_code); ?>
</td>
</tr>

<tr>
<td align=right>Biographical<br />Information:</td>
<td colspan=3><textarea   name=bio wrap=virtual rows="9" cols=90>
<?php echo htmlspecialchars($bio); ?>
</textarea>
</td>
</tr>

<tr>
<td align=right>Professional URL:</td>
<td>
<input type=text id=institute_url name=institute_url size=36 maxlength=255 value="<?php echo htmlspecialchars($institute_url); ?>" />
</td>
</tr>

<tr>
<td align=right>Personal URL:</td>
<td>
<input type=text id=url name=url size=36 maxlength=255 value="<?php echo htmlspecialchars($url); ?>" />
</td>
</tr>

<tr>
<td align=right>Language:<br>(Preferred)</td>
<td>
<?php select_language($language_code); ?>
</td>
</tr>

<tr>
<td align=right>Can Read:<br>(Multiple<br>Selection)</td>
<td>
<?php select_multiple_languages($language_codes, 6); ?>
</td>
</tr>
</table>

<h4>Additional Settings</h4>

<table>
<tr>
<td align=right>Set Timezone:</td>
<td>
<?php echo select_timezone($timezone_code); ?>
</td>
</tr>

<!--
<tr>
<td align="right">Visible:</td>
<td colspan=3>
<table>
<tr>
<td align=right>(Members)</td>
<td>
<select name="visible">
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
<select name="visible_email">
<option value=''<?php if (!isset($visible_email)) echo ' selected';?>>No</option>
<option value=1<?php if ($visible_email == 1) echo ' selected';?>>As Member</option>
<option value=2<?php if ($visible_email == 2) echo ' selected';?>>As Contact</option>
<option value=3<?php if ($visible_email == 3) echo ' selected';?>>Both</option>
</select>
</td>
</tr>
<tr>
<td align=right>
(World)
</td>
<td>
<select name="global">
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
<select name="global_email">
<option value=''<?php if (!isset($global_email)) echo ' selected';?>>No</option>
<option value=1<?php if ($global_email == 1) echo ' selected';?>>As Member</option>
<option value=2<?php if ($global_email == 2) echo ' selected';?>>As Contact</option>
<option value=3<?php if ($global_email == 3) echo ' selected';?>>Both</option>
</select>
</td>
</tr>
</table>
</tr>

<tr>
<td></td><td><input type=submit name=send value=Send /><input type=reset /></td>
</tr>
</table>
</form>

<h4>Review Permissions</h4>
<a href="user_permissions.php">Check permissions for annotations, projects, and groups</a>
-->
</table>

<?php 
// This is the update section
?>


<table>
<tr>
<td>&nbsp;</td>
</tr>
<tr>
<td align=right><strong>Click "Send" to update your profile</strong></td>
<td><input type=submit name=send value=Send /><input type=reset /></td>
</tr>
</table>
</form>
<br />

<h4>Delete Account</h4>
<p>
<table>
<tr>
<td>Do you want to delete your imageMAT account? <em>Remember that this action is irreversible. <br />
Once you have deleted your account all of your annotations will be removed from imageMAT.</em><br />
 Click <a href="../register/delete.php"><strong>here</strong></a> if you are certain that you want to delete your imageMAT account.</td>
 </tr>
 <tr>
 <td>&nbsp;</td>
 </tr>
 </table>
 <p>
<?
$check = getpost('check');
if (isset($check)) {
  require_once($dir . '/../include/moniker.php');
  moniker_availability($gUserid, $moniker);
}

close:
DBclose();
done:

bodyFooter();
?>
</body>
</html>

