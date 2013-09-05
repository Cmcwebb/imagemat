<?php

$gPHPscript = __FILE__;
$dir = dirname(__FILE__);
require_once($dir . '/../include/boilerplate.php');

if ($gUserid == null) {
  return 'You are not logged on';
}

$group_id = getpost('group_id');
if (!isset($group_id)) {
  echo 'Missing group id';
  return;
}
$value = getpost('value');
if (!isset($value)) {
  echo 'Missing value';
  return;
}

require_once($dir . '/../include/db.php');

if (!DBconnect()) {
  return;
}
$email = null;
if ($value == 99) {
  $query =
'select g1.group_id, g1.user_id, g2.title, g2.creator_user_id
  from groupscontacts g1, usersgroups g2
 where g1.group_id = ' . DBnumber($group_id) . '
   and g2.group_id = ' . DBnumber($group_id);

  $ret = DBquery($query);
  if (!$ret) {
    goto close;
  }
  $row   = DBFetch($ret);
  if (!$row) {
	echo '<br>Group contact does not exist';
	return;
  }
  foreach ($row as $colname => $value) {
    $$colname = $value;
  }

  if ($creator_user_id != $gUserid) {
    $query =
'select email
  from users
 where user_id = ' . DBstring($creator_user_id);

    $ret = DBquery($query);
    if (!$ret) {
      goto close;
    }
    $row   = DBFetch($ret);
    if (!$row) {
	  echo '<br>Creator does not exist';
	  return;
    }
    $email = $row['email'];
  }

  $query =
'delete from groupscontacts';
} else {
  $query =
'update groupscontacts
  set agreed   = ' . DBnumber($value);
}
$query .= '
where group_id = ' . DBnumber($group_id) . '
  and user_id  = ' . DBstring($gUserid);

$ret = DBquery($query);
if (!$ret) {
  goto close;
}
if (DBupdated() == 0) {
  echo 'Group contact record not found';
}
if (isset($email)) {
  $body = '
' . $user_id . ' has declined the invitation to become a contact for group '. $group_id . ' that you created titled: 

' . $title;

  $ret = mail($email, $user_id . ' declined invite to become an ImageMAT contact', $body, emailHeader());
}

close:
DBclose();
?>
