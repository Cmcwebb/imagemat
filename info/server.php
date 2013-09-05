<?php
$dir = dirname(__FILE__);
require_once($dir . '/../include/boilerplate.php');
$gPHPscript = __FILE__;

$server = (livesystem() ? 'imagemat' : 'dev');
echo HtmlHeader('Server details on ' . $server);
?>
</head>
<body>
<h3>Server Details</h3>
<p>
<table>
<?
foreach ($_SERVER as $colname => $value) {
  echo '<tr><td>';
  echo htmlspecialchars($colname);
  echo '</td><td>';
  echo htmlspecialchars($value);
}
?>
</table>
</body>
</html>
