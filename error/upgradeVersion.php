<!DOCTYPE HTML>
<script type="text/javascript">
var ignoreVersions = true;
</script>
<?php
$gPHPscript = __FILE__;
$dir = dirname(__FILE__);
require_once($dir . '/../include/boilerplate.php');

header('window-target: _top');
htmlHeader('Please upgrade your version of ImageMAT');
srcStylesheet('../css/style.css');
?>
<meta http-equiv="window-target" content="_top">
</head>
<body>
<?php
bodyHeader();
?>
<h3>ImageMAT Version Mismatch</h3>
<p>
ImageMAT software kept by your browser on your own computer has been
discovered to be out of date.  To continue using imageMAT you should
remove this cached software from your computer, so that you browser
will then be obliged to upload the most recent version of this software
from our server.
<p>
We perform this validation test constantly while you are using imageMAT
because it is necessary for us to ensure that all of the software that
we provide, is internally consistent, the better to improve the quality
of your experience when using ImageMAT, and to avoid software failure.
<p>
Please clear your browser cache by following the instructions below:
<p>
<h3>Firefox Instructions</h3>
<p>
<ol>
<li>Please click on the Tools menu in your browser menu bar at top of browser</li>
<li>Select clear recent history</li>
<li>Check the cache box</li>
<li>Press the button <b>Clear Now</b></li>
<li>Return to ImageMAT</li>
</ol>
<?php
bodyFooter();
?>
</body>
</html>
