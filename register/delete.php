<!DOCTYPE HTML>
<?php

$gPHPscript = __FILE__;
$dir = dirname(__FILE__);
require_once($dir . '/../include/boilerplate.php');

if (mustlogon()) {
  return;
}

htmlHeader('Delete user id ' . htmlspecialchars($gUserid));
srcStylesheet('../css/style.css');
?>
</head>
<body>';

<?php


$reallysure = getpost('reallysure');
if ($reallysure != 'Yes') {
  bodyHeader();
} else {
  session_destroy();
  unset($GLOBALS['_SESSION']);
  bodyHeader();
  require_once($dir . '/../include/db.php');

  if (!DBconnect()) {
    goto done;
  }

  $query =
'delete from users
 where user_id = ' . DBstring($gUserid)
;
  $ret = DBquery($query);
  if (!$ret) {
    echo '
<br>Failed to delete ', htmlspecialchars($gUserid);
  } else {
    echo '
<p>
We are sorry to see you go. <h3>User ' , htmlspecialchars($gUserid) , ' has been deleted</h3>
<p>
You no longer have an account on imageMAT.';

  }
  goto done;
}

echo '
<h3>Deleting user</h3>
<p>
<table rules=all border=2>
<tr><td>User</td><td>', $gUserid , '</td></tr>
<tr><td>Name</td><td>' , username() , '</td></tr>
<tr><td>Moniker&nbsp;</td><td>' , htmlspecialchars($_SESSION['imageMAT_moniker']) , '</td></tr>
<tr><td>Email</td><td>' , htmlspecialchars($_SESSION['imageMAT_email']);
?>
</td></tr>
</table>
<p>
<form action="delete.php" method="post">
<table>
<tr>
<td align=right>Really Delete:</td>
<td>
<select name=reallysure>
<option size=10></option>
<option >No</option>
<option >Yes</option>
</select>
</td>
</tr>

<tr>
<td></td>
<td><input type=submit name=send value=Send /></td>
</tr>
</table>
</form>

<?php
done:

bodyFooter();
?>

</body>
</html>

