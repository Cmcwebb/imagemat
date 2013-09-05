<!DOCTYPE HTML>
<?php
$gPHPscript = __FILE__;
$dir = dirname(__FILE__);
require_once($dir . '/../include/boilerplate.php');

htmlHeader('Who am I');
srcStylesheet('../css/style.css');
?>
</head>
<body>
<?php
bodyHeader();

echo '
<h3>Who am I</h3>
<p>';

if (!isset($gUserid)) {
  echo 'Some dude that ain\'t logged in';
  goto done;
}
echo '
<h3>Session Variables</h3>
<table rules=all border=2>
<tr><td>User</td><td>' , htmlspecialchars($gUserid) , '</td></tr>
<tr><td>Name</td><td>' , username() , '</td></tr>
<tr><td>Moniker&nbsp;</td><td>' , htmlspecialchars($_SESSION['imageMAT_moniker']) , '</td></tr>
<tr><td>Language Code</td><td>' , htmlspecialchars($_SESSION['imageMAT_language_code']) , '</td></tr>
<tr><td>Email</td><td>' , htmlspecialchars($_SESSION['imageMAT_email']) , '</td></tr>
</table>';

require_once($dir . '/../include/db.php');

if (!DBconnect()) {
  goto done;
}

echo '<h3>Database record</h3>';

require_once($dir . '/../include/users.php');

echo_user($gUserid);

echo '
<p>
Click <a href=time.php>here</a> to check timezone setting';

done:
bodyFooter();
?>
</body>
</html>
