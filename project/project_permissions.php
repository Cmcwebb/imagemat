<!DOCTYPE HTML>
<?php

$gPHPscript = __FILE__;
$dir = dirname(__FILE__);
require_once($dir . '/../include/boilerplate.php');
require_once($dir . '/../include/db.php');
require_once($dir . '/../include/users.php');

htmlHeader('Annotation Definition');
srcStylesheet('../css/style.css');
?>
</head>
<body>

<?php
bodyHeader(''); 
?>


<?php
done:
bodyFooter();
?>
</body>
</html>
