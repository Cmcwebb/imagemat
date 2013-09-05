<?php

function moniker_availability($userid, $moniker)
{
  if (!isset($moniker)) {
	echo '
<p><font color=red>Provide a moniker to test for availability</font>';
    return;
  }

  $query = 
'select user_id, moniker
from   mat.users
where (user_id = ' . DBstring($moniker) . '
or     moniker = ' . DBstring($moniker) . ')';

  if (isset($userid)) {
    $query .= '
and   user_id <> ' . DBstring($userid);
  }
  $ret = DBquery($query);
  if (!$ret) {
    return;
  }
  $row = DBfetch($ret);
  if (!$row) {
    echo '
<p>The user name ' . htmlspecialchars($moniker) . ' is available';
    return;
  }
  echo '
<p><font color=red>
The user name ' . htmlspecialchars($moniker) . ' is taken.</font>
<br>Here are a list of user names that start with your choice
<br>* indicates initial user name + indicates current nickname
</font>';
  $query = 
'select user_id, moniker, flag
 from (select user_id, user_id as moniker, \'*\' as flag
         from mat.users
        union all
       select user_id, moniker, \'+\' as flag
         from mat.users
      ) t1
 where moniker like ' . DBstring($moniker . '%');

  if (isset($userid)) {
    $query .= '
   and user_id <> ' . DBstring($userid);
  }
  $ret = DBquery($query);
  if (!$ret) {
    return;
  }
  while ($row = DBfetch($ret)) {
    echo '<br>' . htmlspecialchars($row['flag'] . ' ' . $row['moniker']);
  }
}

?>
