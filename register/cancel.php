<!DOCTYPE HTML>
<?php

$gPHPscript = __FILE__;
$dir = dirname(__FILE__);
require_once($dir . '/../include/boilerplate.php');
require_once($dir . '/../include/db.php');
require_once($dir . '/../include/users.php');

htmlHeader('Registration Cancellation');
srcStylesheet('../css/style.css');
?>
</head>
<body>

<?php
bodyHeader();

$random = getget('code');
if (!isset($random)) {
  echo '<h3>No key code provided</h3>';
  goto done;
}

if (!DBconnect()) {
  goto done;
}

$ok     = true;

$query = 
'select email, gender, prefix, firstname, lastname,
        moniker, institute, institute_url, job, country_code,
        language_code, timezone_code, bio, password,
        ip_address
 from shadows
where random_key = ' . DBstring($random);

$ret = DBquery($query);
if (!$ret) {
  $ok = false;
} else {
  $row = DBfetch($ret);
  if (!$row) {
    echo '
<br>The key <font color=blue>' . htmlspecialchars($random) . '</font> was not found';
    $ok = false;
  } else {
    foreach ($row as $colname => $value) {
      $$colname = $value;
} } }

$query = 
'delete from shadows
 where random_key = ' . DBstring($random);

$ret = DBquery($query);
if (!$ret) {
  $ok = false;
}

if (!$ok) {
 echo '
<h3>Cancellation failed</h3>';
} else {
  echo '
<h3>Cancellation</h3>
<p>
The registration request submitted from IP address ' , $ip_address , '
claiming to be from
', formatname($prefix, $firstname, $lastname) , '
with email address ' , htmlspecialchars($email) , ' has been cancelled.';
}
done:
bodyFooter();
?>
</body>
</html>
