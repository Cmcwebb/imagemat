<!DOCTYPE HTML>
<?php
$gPHPscript = __FILE__;
$dir = dirname(__FILE__);
require_once($dir . '/../include/boilerplate.php');

if (mustlogon()) {
  return;
}

htmlHeader('Search for groups');
srcStylesheet(
  '../css/style.css',
  '../css/annotation.css'
);

$updates = getparameter('updates');

if (isset($updates)) {
  srcStylesheet('../css/alert.css');
  srcJavascript('../js/alert.js');
}

$mine = getparameter('mine');

enterJavascript();
?>
function setGroupsChanged()
{
  window.setGroups_changed = true;
}

function sortNumber(a, b)
{
  return a - b;
}

function groupIdsToServer()
{
  if (window.setGroups_changed) {
    var lth   = window.setGroups_lth;
    var parms = 'setGroups=[';
    var http;
  
    if (lth > 0) {
      var setGroups_array  = window.setGroups;
  
      setGroups_array.sort(sortNumber);
      parms += setGroups_array.toString();
    }
    parms += ']';
  
    // alert('Parms=' + parms);
    http  = getXMLHttpRequest();
  
    // Must be synchronous
    do_synchronous_ajax(http, "do_setGroups.php", parms);
	if (http.responseText != '') {
      alert(http.responseText);
	}
    window.setGroups_changed = false;
  }
}

function unload()
{
  groupIdsToServer();
}

function load()
{
  window.onbeforeunload = function () { unload(); }
<?php
  if (isset($updates)) {
    echo '
  customAlert( { icon:"warn.png", body:"Please select the group(s) you wish to ', $updates, '" } );
';
  }
?>
}

<?php exitJavascript(); ?>
</head>
<body onload="load()" onunload="unload()" >

<?php
bodyHeader();
?>

<iframe id="searchFrame" name="searchFrame" class="fullFrame" src="search1.php<?php if (isset($mine)) echo '?mine=Y'; ?>"></iframe>

<?php
bodyFooter();
?>

</body>
</html>
