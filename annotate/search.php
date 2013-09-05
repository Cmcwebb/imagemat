<!DOCTYPE HTML>
<?php
$gPHPscript = __FILE__;
$dir = dirname(__FILE__);
require_once($dir . '/../include/boilerplate.php');

if (mustlogon()) {
  return;
}

htmlHeader('Search for annotations');
srcStylesheet(
  '../css/style.css',
  /*'../css/annotation.css'*/
  '../css/search.css'
);
srcJavascript(
  '../js/ajax.js',
  '../js/search.js'
);

$updates = getparameter('updates');

if (isset($updates)) {
  srcStylesheet('../css/alert.css');
  srcJavascript('../js/alert.js');
}

enterJavascript();
?>
function sortNumber(a, b)
{
  return a - b;
}

function unload()
{
  annotationIdsToServer();
}

function load()
{
  window.onbeforeunload = function () { unload(); }
<?php
  if (isset($updates)) {
    echo '
  customAlert( { icon:"warn.png", body:"Please select the annotation(s) you wish to ', $updates, '" } );
';
  }
?>
}
<?php
exitJavascript();
?>
</head>
<body onload="load()" onunload="unload()" >
<?php
bodyHeader();
$list = getparameter('list');
if (isset($list)) {
  $list = '?list=y';
} else {
  $list = '';
}
?>

<iframe id="searchFrame" name="searchFrame" class="fullFrame" src="search1.php<?php echo $list; ?>"></iframe>

<?php
bodyFooter();
?>

</body>
</html>
