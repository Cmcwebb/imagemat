<!DOCTYPE HTML>
<html>
<head>
<title>Security Bug Number 2</title>
</head>
<body>
<?php
require_once('/var/www/ijdavis/include/db.php');
DBconnect();
$query = 'select count(*) as cnt from users';
$ret = DBquery($query);
$row = DBfetch($ret);
echo 'Users has ', $row['cnt'], ' records in it';
?>
</body>
</html>
