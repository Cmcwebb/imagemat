<!DOCTYPE HTML>
<?php
$gPHPscript = __FILE__;
$dir = dirname(__FILE__);
require_once($dir . '/../include/boilerplate.php');

htmlHeader('Test iframe zoom inside an iframe');
srcStylesheet(
  '../css/style.css',
  '../css/annotation.css'
);
?>
</head>
<body>
<?php
bodyHeader();
?>

<div id="splitpane">
<iframe class="imageFrame" height="700" src="zoom.php" width="49%"></iframe>
<iframe class="annotateFrame" height="700" width="49%"></iframe>
</div>

<?php
bodyFooter();
?>

</body>
</html>
