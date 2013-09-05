<!DOCTYPE HTML>
<?php
$gPHPscript = __FILE__;
$dir = dirname(__FILE__);
require_once($dir . '/../include/boilerplate.php');

if (mustlogon()) {
  return;
}

htmlHeader('Folder View');

$show = getparameter('show');
if (!isset($show)) {
  $show = 0;
}
srcStylesheet(
  '../css/style.css',
  '../css/alert.css',
  '../css/annotation.css' 

);
srcJavascript(
  '../js/alert.js',
  '../js/ajax.js',
  '../js/search.js'
);
enterJavascript();
?>
function sortNumber(a, b)
{
  return a - b;
}

function setGroupsChanged()
{
  window.setGroups_changed = true;
}

function setFoldersChanged()
{
  window.setFolders_changed = true;
}

function folderIdsToServer()
{
  if (window.setFolders_changed) {
    var lth   = window.setFolders_lth;
    var parms = 'setFolders=[';
    var http;
  
    if (lth > 0) {
      var setFolders_array  = window.setFolders;
  
      setFolders_array.sort(sortNumber);
      parms += setFolders_array.toString();
    }
    parms += ']';
  
    // alert('Parms=' + parms);
    http  = getXMLHttpRequest();
  
    // Must be synchronous
    do_synchronous_ajax(http, "do_setFolders.php", parms);
	if (http.responseText != '') {
      alert(http.responseText);
	}
    window.setFolders_changed = false;
  }
}

function unload()
{
  annotationIdsToServer();
  folderIdsToServer();
}

function load()
{
  window.onbeforeunload = function () { unload(); }
}
<?php
exitJavascript();
?>
</head>
<body onload="load()" onunload="unload()">
<?php
bodyHeader();
?>

<div id="splitpane">
<iframe id="imageFrame" name="imageFrame" class="imageFrame" src="folders.php<?php echo '?show=',$show; ?>" mozallowfullscreen webkitallowfullscreen></iframe>
<iframe id="annotateFrame" name="annotateFrame" class="annotateFrame" src="searchFolders.php<?php echo '?show=',$show; ?>" mozallowfullscreen webkitallowfullscreen></iframe>
</div>

<?php
bodyFooter();
?>

</body>
</html>
