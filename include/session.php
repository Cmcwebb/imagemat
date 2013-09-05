<?php

# This is useless until DateInterval's can be marshalled for _SESSION use
function create_session_tz($offset)
{
  if (0 <= $offset) {
    $invert = 0;
  } else {
    $invert = 1;
    $offset = 0 - $offset;
  }
  $hour    = $offset / 3600;
  $offset -= $hour * 3600;
  $min     = $offset / 60;
  $offset -= $min * 60;
  $offset  = 'PT' . $hour . 'H' . $min . 'M' . $offset . 'S';
  $di      = new DateInterval($offset);
  $di->invert = $invert;
  $_SESSION['imageMAT_tz'] = $di;
  #return $offset . ' ' . $di->format('%R%h:%I:%S');
  return '';
}

function update_session()
{
  global $gUserid, $prefix, $firstname, $lastname, $moniker, $email,
         $timezone_code, $language_code, $hideTooltips;

  $old_language_code = null;
  if (isset($_SESSION['imageMAT_language_code'])) {
     $old_language_code = $_SESSION['imageMAT_language_code'];
  }
$_SESSION['imageMAT_livesystem'] = livesystem();

  $_SESSION['imageMAT_user_id'] = $gUserid;
  $_SESSION['imageMAT_prefix']  = $prefix;
  $_SESSION['imageMAT_firstname'] = $firstname;
  $_SESSION['imageMAT_lastname'] = $lastname;
  $_SESSION['imageMAT_moniker'] = $moniker;
  $_SESSION['imageMAT_email'] = $email;
  $_SESSION['imageMAT_tz_code'] = $timezone_code;
  $_SESSION['imageMAT_tz_offset'] = 0;
  $_SESSION['imageMAT_cwd']       = null;
  $_SESSION['imageMAT_language_code'] = $language_code;
  if (isset($hideTooltips)) {
    $_SESSION['imageMAT_hideTooltips'] = 'Y';
  } else if (isset($_SESSION['imageMAT_hideTooltips'])) {
    unset($_SESSION['imageMAT_hideTooltips']);
  }

  if ($old_language_code != $language_code) {
    $language_code2 = null;
    $language = null;
    if (isset($language_code)) {
      $query = 
'select language_code2, name from languages
 where language_code = ' . DBstring($language_code);
      $ret = DBquery($query);
      if ($ret) {
        $row = DBfetch($ret);
        if (!$row) {
          echo '
<br><div class=error>The language_code ' , htmlspecialchars($language_code) , ' is unknown.</div>';
        } else {
          $language_code2 = $row['language_code2'];
          $language = $row['name'];
        }
    } }
    $_SESSION['imageMAT_language'] = $language;
    $_SESSION['imageMAT_language_code2'] = $language_code2;
  }

  if (!isset($timezone_code)) {
    return;
  }
  $tz = timezone_open($timezone_code);
  if (!$tz) {
?>
<br>Timezone ' . $timezone_code . ' not recognised
<br>Presuming GMT+0
<?php
    return;
  }
  $gmt  = timezone_open('GMT+0');
  if (!$gmt) {
?>
<br>Can't create a comparison GMT+0 timezone
<br>Presuming user timezone GMT+0
<?php
    return;
  }
  $date   = date_create(null, $gmt);
  $offset = timezone_offset_get($tz, $date);
  $_SESSION['imageMAT_tz_offset'] = $offset;

  return;
}

function show_session()
{
  global $gUserid;

  echo '
<p>

You are user id <i>' , htmlspecialchars($gUserid) , '</i>';
  echo '
<p>
Your current username is <i>' , htmlspecialchars($_SESSION['imageMAT_moniker']) , '</i>';
  echo '
<p>
Your email is <i>', htmlspecialchars($_SESSION['imageMAT_email']), '</i>';
  if (!isset($_SESSION['imageMAT_language_code2'])) {
    echo '<p>No preferred language';
  } else {
    echo '<p>Preferred language is <i>';
    if (isset($_SESSION['imageMAT_language'])) {
      echo '"', $_SESSION['imageMAT_language'], '" ';
    }
    echo '(', $_SESSION['imageMAT_language_code2'], ')';
  }       
  echo '
<p>Your timezone is ';
  if (!isset($_SESSION['imageMAT_tz_code'])) {
    echo 'unknown ';
  } else {
    echo $_SESSION['imageMAT_tz_code'] , ' ';
  } 
  if (!isset($_SESSION['imageMAT_tz_offset'])) {
    echo 'unknown';
  } else {
    echo tz_string($_SESSION['imageMAT_tz_offset']);
  }
}

function show_actionItems()
{
  global $gUserid;

  $query =
'select group_id
  from groupscontacts
 where groupscontacts.user_id = '. DBstring($gUserid) .'
   and groupscontacts.agreed is null';

  $ret = DBquery($query);
  if (!$ret) {
	return;
  }
  $row = DBfetch($ret);
  if ($row) {
	echo '
<b>Outstanding contact requests</b>
<p>
Click <a href="../group/manages.php">here</a> to update the groups you are a contact for.';
  }
}

//
// get_token() : functions taken from the code review to 
// avoid csrf attack.
// credit: Kevin
//
function get_token() {

if (isset ($_SESSION[‘token_val’])) {
return $_SESSION[‘token_val’];
} else {
$token = hash("sha256", rand(500));
$_SESSION[‘token_val’] = $token;
return $token;
}
}

?>

