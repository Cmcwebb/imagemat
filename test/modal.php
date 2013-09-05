<!DOCTYPE HTML>
<?php

$dir = dirname(__FILE__);
require_once($dir . '/../include/boilerplate.php');
require_once($dir . '/../include/util.php');
$gPHPscript = __FILE__;

htmlHeader('Test modal button');

$mode = getparameter('mode');

require_once($dir . '/../include/alert.php');

enterJavascript();
?>

function button_yes(state)
{
  alert ('yes ' + state);
}

function button_no(state)
{
  alert('no ' + state);
}

function button_maybe(state)
{
  alert('maybe ' + state);
}

function test()
{
  var i;
  var icons = [ false, 'error.png', 'warn.png', 'info.png'];
  for (i = 0; i < 4; ++i) {
    customAlert({html:true, body:'<font color=red>Test '+i+'</font>This is a very long string designed to test if the text does indeed wrap round the icon when it is too long to fit beside the icon. This is a very long string designed to test if the text does indeed wrap round the icon when it is too long to fit beside the icon. This is a very long string designed to test if the text does indeed wrap round the icon when it is too long to fit beside the icon.  ',icon:icons[i],buttons:{Yes:button_yes,No:button_no,Maybe:button_maybe},state:i});
  }
}

function test2()
{
  test();
}

function test3()
{
  customAlert({icon:false, body:'Test'});
}

<?php exitJavascript(); ?>

<link rel='stylesheet' href="../css/style.css" />
</head>
<body id="annotate-frame">
<body>
<h3>Modal dialog tests</h3>

<script>
var ret;

test<?php if (isset($mode)) echo $mode;?>();
alert('test done');
</script>
<?php
bodyFooterFilename();
?>
</body>
</html>
