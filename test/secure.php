<!DOCTYPE HTML>
<html>
<head>
<title>Security Bug</title>
</head>
<body>
<?php
$file = '/etc/imagemat/demo';
$text = file_get_contents($file);

echo '<h3>Contents of ', $file, '</h3><p><pre>', htmlspecialchars($text), '</pre>';
?>
</body>
</html>
