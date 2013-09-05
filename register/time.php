<!DOCTYPE HTML>
<?php

$gPHPscript = __FILE__;
$dir = dirname(__FILE__);
require_once($dir . '/../include/boilerplate.php');
require_once($dir . '/../include/db.php');
require_once($dir . '/../include/date.php');
require_once($dir . '/../include/debug.php');

htmlHeader('Time Check');
srcStylesheet('../css/style.css');
?>
</head>
<body>

<?php

bodyHeader();

if (!DBconnect()) {
  goto done;
}

echo '
<h3>MySQL UTC time</h3>';

$query = 'select utc_timestamp, current_timestamp';

$ret = DBquery($query);
if ($ret) {
  $row = DBfetch($ret);
  if (!$row) {
    echo '
<br>No row returned';
  } else {
    foreach ($row as $colname => $value) {
      $$colname = $value;
} } }

echo $utc_timestamp , '
<h3>MySQL Current Time</h3>
<p>', $current_timestamp, '
<h3>PHP UTC time</h3>
<p>';
$now = gmtnow();
echo gmttod($now), '
<h3>PHP Current Time</h3>
<p>', date('Y-m-d H:i:s ');
$tod = gettimeofday();
#print_r($tod);
echo tz_string(-60 * $tod['minuteswest']), '
<h3>Your PHP time</h3>
<p>', clientstimenow(), '
<h3>Your MySQL time</h3>
<p>', clientstime($utc_timestamp), '
<p>Your timezone is ';
if (isset($_SESSION['imageMAT_tz_code'])) {
  echo $_SESSION['imageMAT_tz_code'] . ' ';
} 
if (!isset($_SESSION['imageMAT_tz_offset'])) {
  echo 'GMT+/-?';
} else {
  echo tz_string($_SESSION['imageMAT_tz_offset']);
}
  
echo '
<p>Server timezone is ', date_default_timezone_get();
done:
bodyFooter();
?>
</body>
</html>
