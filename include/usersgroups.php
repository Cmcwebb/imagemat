<?php

function isUserMemberOf($user_id, $group_id)
{
  $query = 
'select null
  from groupsmembers
 where group_id = ' . DBnumber($group_id) . '
   and user_id  = ' . DBstring($user_id);

  $ret = DBquery($query);
  if (!$ret) {
	return -1;
  }

  $row = DBfetch($ret);
  if ($row) {
	return 1;
  }
  return 0;
}

function insert_groupscontact($group_id, $contact, $title)
{
  global $gUserid;

  $contact = trim($contact);
  if ($contact == '') {
	return 0;
  }
  if ($contact == $gUserid) {
    $email = null;
  } else {
    $query =
'select email
  from users
 where user_id = ' . DBstring($contact);

    $ret = DBquery($query);
    if (!$ret) {
      return -1;
    }
    $row   = DBFetch($ret);
    if (!$row) {
	  echo '<br>Contact ', htmlspecialchars($contact), ' does not exist';
	  return 0;
    }
    $email = $row['email'];
    if (!isset($email)) {
	  echo '<br>Contact ', htmlspecialchars($contact), ' has no email';
	  return 0;
    }
  }

  $query =
'insert ignore
  into groupscontacts(group_id, user_id, created)
values (' . DBnumberC($group_id) . DBstringC($contact) . 'utc_timestamp())';

  $ret = DBquery($query);
  if (!$ret) {
	return -1;
  }
  if (DBupdated() == 0) {
    return 0;
  }
  if (isset($email)) {
    $topname = 'http://' . $_SERVER['SERVER_NAME'] . dirname($_SERVER['REQUEST_URI']);
    $body = '
' . username() . ' has suggested that you become an ImageMAT contact for the group "' . htmlspecialchars($title) . '".  As a contact you will be authorised to add and remove members from this group. You will be asked if you accept this responsibility when you next logon to imageMAT.

You may logon to ImageMAT by clicking the link below:

' . $topname . '/../register/logon.php';

    $ret = mail($email, 'You are invited to become an ImageMAT contact', $body, emailHeader());
    if (!$ret) {
      echo '
<br>We were unable to send email to ', htmlspecialchars($contact) , contact();
  } }
  return 1;
}

function insert_groupsmember($group_id, $member)
{
  $member = trim($member);
  if ($member == '') {
    return 0;
  }
  $query =
'insert ignore
  into groupsmembers(group_id, user_id, created)
values (' . DBnumberC($group_id) . DBstringC($member) . 'utc_timestamp())';

  $ret = DBquery($query);
  if (!$ret) {
    return -1;
  }
  return DBupdated();
}
?>
