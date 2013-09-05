<?php

function users_constraints($userid, $email, $moniker)
{
  if (!isset($email) && !isset($moniker)) {
    return true;
  }
  if (!isset($email)) {
    $query = 
'select user_id, moniker
  from users
 where (user_id = ' . DBstring($moniker) . '
    or  moniker = ' . DBstring($moniker) . ')';
  } else if (!isset($moniker)) {
    $query =
'select email
  from users
 where email = ' . DBstring($email);
  } else {
    $query = 
'select user_id, moniker, email
  from users
 where (email   = ' . DBstring($email) . '
    or  user_id = ' . DBstring($moniker) . '
    or  moniker = ' . DBstring($moniker) . ')';
  }

  if (isset($userid)) {
    $query .= '
   and user_id <> ' . DBstring($userid);
  }

  $ret = DBquery($query);
  if (!$ret) {
    return $ret;
  }
  $ret1 = true;
  while ($row = DBfetch($ret)) {
    if (isset($moniker)) {
      if ($row['user_id'] == $moniker || $row['moniker'] == $moniker) {
        echo '
<br>The moniker <font color=blue>' . htmlspecialchars($moniker) . '</font> is already in use';
        $ret1 = false;
      }
    }
    
    if (isset($email) && $row['email'] == $email) {
      echo '
<br>The email <font color=blue>' . htmlspecialchars($email) . '</font> has already been registered';
      $ret1 = false;
  } }
  return $ret1;
}

require_once($dir . '/../include/tables.php');

function echo_user($userid)
{
  $query =
'select * from users
where user_id = ' . DBstring($userid);

  $cnt = echo_table($query);

  if ($cnt == 0) {
    echo 'No record in the users table for you';
    return;
  }
  $query =
'select usersoflanguages.Language_code, Name
  from  usersoflanguages, languages
 where  usersoflanguages.language_code = languages.language_code
   and  usersoflanguages.user_id = ' . DBstring($userid);

  $cnt = echo_table($query);
}
?>
