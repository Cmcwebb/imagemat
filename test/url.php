<!DOCTYPE HTML>
<?php
$gPHPscript = __FILE__;
$dir = dirname(__FILE__);
require_once($dir . '/../include/boilerplate.php');
require_once($dir . '/../include/util.php');
require_once($dir . '/../include/urls.php');

htmlHeader('Canonical URL');

?>
<link rel='stylesheet' href="../css/style.css" />
</head>
<body>
<?php
bodyHeader();

$url = getparameter('url');

echo '
<h3>Test Canonical URLS</h3>
<p>
<h3>', htmlspecialchars($url), '</h3>
<h3>', htmlspecialchars(canonical_url($url)), '</h3>';
bodyFooter();
?>
</body>
</html>
