<?php

$gPHPscript = __FILE__;
$dir = dirname(__FILE__);
require_once($dir . '/../include/boilerplate.php');

if ($gUserid == null) {
  /* This can happen if the frame is left idle a long time */
  echo 'You are not logged on';
  goto done;
}

$group_id = getparameter('group_id');

if (!$group_id) {
  echo 'Please provide a group id';
  goto done;
}

require_once($dir . '/../include/db.php');

if (!DBconnect()) {
  return;
}

$sql_group_id = DBnumber($group_id);

require_once($dir . '/../include/usersgroups.php');

$ismember = isUserMemberOf($gUserid, $group_id);
if ($ismember < 0) {
  goto close;
}

$query = 
'select groupscontacts.user_id, visible, visible_email, global, global_email
  from groupscontacts, users
 where groupscontacts.group_id = ' . $sql_group_id . '
   and groupscontacts.user_id  = users.user_id
 order by user_id';

$ret = DBquery($query);
if (!$ret) {
  goto close;
}

$cnt       = 0;
$emailCnt  = 0;
$shownCnt  = 0;
$connector = '';
$msg       = '<table><tbody>';
while ($row = DBfetch($ret)) {
  ++$cnt;
  $userid   = $row['user_id'];
  if ($userid == $gUserid) {
    echo 'ok';
    goto close;
  }
  $visible = $row['global_email'];
  if ($visible != 2 && $visible != 3) {
	if ($ismember != 1) {
	  continue;
	}
	$visible = $row['visible_email'];
	if ($visible != 2 || $visible != 3) {
	  continue;
  } }
  ++$emailCnt;
  $visible = $row['global'];
  if ($visible != 2 && $visible != 3) {
    if ($ismember != 1) {
	  continue;
	}
    $visible = $row['visible'];
    if ($visible != 2 && $visible != 3) {
	  continue;
  } }
  $userid1 = htmlspecialchars($userid);
  $msg .= '
<tr><td><input type=checkbox name="contacts[]" value="' + userid1 +'"/>' . userid1 . '</td></tr>';
  ++$shownCnt;
}
if ($emailCnt == 0) {
  echo 'No';
} else if ($emailCnt > $shownCnt) {
  $msg .= '
<tr><td><input type=checkbox name="contacts[]" value="." />' . ($emailCnt - $shownCnt) . 'others</td></tr>';
}
  

	echo '</a>';
  }
  $connector = ', ';
}
echo '
<br/>' . $cnt . ' contacts';
if ($cnt1 != $cnt) {
  echo ' (',$cnt - $cnt1, ' not shown)';
}
echo '<br/><br/>';

echo '
<font size="+1">';

$query = 
'select groupscontacts.user_id, visible, visible_email, global, global_email
  from groupscontacts, users
 where groupscontacts.group_id = ' . DBnumber($group_id) . '
   and groupscontacts.user_id  = users.user_id
 order by user_id';

$ret = DBquery($query);
if (!$ret) {
  goto close;
}

$cnt       = 0;
$cnt1      = 0;
$connector = '';
while ($row = DBfetch($ret)) {
  ++$cnt;
  $mayEmail = true;
  $userid   = $row['user_id'];
  if ($userid != $gUserid) {
	$visible = $row['global'];
	if ($visible != 2 && $visible != 3) {
      if ($ismember != 1) {
		continue;
	  } else {
      	$visible = $row['visible'];
      	if ($visible != 2 && $visible != 3) {
		  continue;
	} } }
    $visible = $row['global_email'];
    if ($visible != 2 && $visible != 3) {
	  if ($ismember != 1) {
	    $mayEmail = false;
	  } else {
	    $visible = $row['visible_email'];
	    if ($visible != 2 || $visible != 3) {
		  $mayEmail = false;
  } } } }
  ++$cnt1;
  echo $connector;
  if ($mayEmail) {
	echo '<a href="email.php?group_id=',htmlspecialchars($group_id),
'&tocontact=\'', htmlspecialchars($userid),'\'" target="_top">';
  }
  echo htmlspecialchars($userid);
  if ($mayEmail) {
	echo '</a>';
  }
  $connector = ', ';
}
echo '
<br/>' . $cnt . ' contacts';
if ($cnt1 != $cnt) {
  echo ' (',$cnt - $cnt1, ' not shown)';
}
echo '<br/><br/>';

$query = 
'select groupsmembers.user_id, visible, visible_email, global, global_email
  from groupsmembers, users
 where groupsmembers.group_id = ' . DBnumber($group_id) . '
   and groupsmembers.user_id  = users.user_id
 order by user_id';

$ret = DBquery($query);
if (!$ret) {
  goto close;
}

$cnt       = 0;
$cnt1      = 0;
$connector = '';
while ($row = DBfetch($ret)) {
  ++$cnt;
  $mayEmail = true;
  $userid   = $row['user_id'];
  if ($userid != $gUserid) {
	$visible = $row['global'];
	if ($visible != 1 && $visible != 3) {
	  if ($ismember != 1) {
		continue;
	  }
	  $visible = $row['visible'];
      if ($visible != 1 && $visible != 3) {
		continue;
    } }
    $visible = $row['global_email'];
    if ($visible != 1 && $visible != 3) {
	  if ($ismember != 1) {
	    $mayEmail = false;
	  } else {
	    $visible = $row['visible_email'];
	    if ($visible != 1 || $visible != 3) {
		  $mayEmail = false;
  } } } }
  ++$cnt1;
  echo $connector;
  if ($mayEmail) {
	echo '<a href="email.php?group_id=',htmlspecialchars($group_id),
'&tomember=\'', htmlspecialchars($userid),'\'" target="_top">';
  }
  echo htmlspecialchars($userid);
  if ($mayEmail) {
	echo '</a>';
  }
  $connector = ', ';
}
echo '
<br/>' . $cnt . ' members';
if ($cnt1 != $cnt) {
  echo ' (',$cnt - $cnt1, ' not shown)';
}
echo '
</font>';

close:
DBclose();
done:

?>
