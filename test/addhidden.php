<!DOCTYPE HTML>
<?php

$dir = dirname(__FILE__);
require_once($dir . '/../include/boilerplate.php');
require_once($dir . '/../include/util.php');
require_once($dir . '/../include/urls.php');
$gPHPscript = __FILE__;

htmlHeader('Current Images');
?>
<script src="../js/util.js" language="javascript" type="text/javascript"></script>
<?php enterJavascript(); ?>
function add()
{
  var i;
  var form = document.getElementById('form');

  for (i = 0; i < 4; ++i) {
    addhidden(form, 'urls[' + i + ']', '' + i);
  }
  return;
}
<?php exitJavascript(); ?>
</head>
<body>
<form id=form>
<input type=hidden name=urls[1] value=y />
<input type="button" value="Add" onclick="add()" />
</form>
</body>
</html>
