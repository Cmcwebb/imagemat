<?php

## Basic boilerplate stuff

$gVersion    = 2;
$gAdminEmail = 'admin@imagemat.org';
$gEmailSender = 'imagemat@mat.uwaterloo.ca';

if(!isset($_SESSION)) {
  session_start();
}
$gUserid = null;
if (isset($_SESSION['imageMAT_user_id'])) {
  $gUserid = $_SESSION['imageMAT_user_id'];
}

function contact()
{
  global $gAdminEmail;

  return '<br>If this problem persists please contact ' . $gAdminEmail;
}

function emailHeader()
{
  global $gEmailSender;

  return
'From: '     . $gEmailSender . '
Reply-To: ' . $gEmailSender . '
X-Mailer: PHP/' . phpversion();
}

function formatname($prefix, $first, $last)
{
  return htmlspecialchars($prefix . ' ' .  $first . ' ' .  $last);
}

function username()
{
  return formatname($_SESSION['imageMAT_prefix'], $_SESSION['imageMAT_firstname'], $_SESSION['imageMAT_lastname']);
}

function getgetcleanup($name)
{
  $ret = urldecode($name);
  $ret = trim($ret);
  $lth = strlen($ret);
  if ($lth > 0) {
    $c   = $ret{0};
    if ($lth > 1 && $c == $ret{$lth-1}) {
      switch ($c) {
      case '\'':
      case '"':
        $ret = substr($ret, 1, $lth-2);
  } } }
  if ($ret != '') {
    return $ret;
  }
  return null;
}

function getget($name)
{
  if (isset($_GET[$name])) {
    $ret = $_GET[$name];
    if (is_array($ret)) {
      foreach ($ret as $index => $value) {
        $ret[$index] = getgetcleanup($value);
      }
    } else {
      $ret = getgetcleanup($ret);
    }
    return $ret;
  }
  return null;
}

function getpost($name)
{
  if (isset($_POST[$name])) {
    $ret = $_POST[$name];
    if (is_array($ret)) {
      return $ret;
    }
    $ret = trim($_POST[$name]);
    if ($ret != '') {
      return $ret;
  } }
  return null;
}

function getparameter($name)
{
  if (isset($_POST[$name])) {
    return getpost($name);
  } 
  if (isset($_GET[$name])) {
    return getget($name);
  }
  return null;
}

function getSet($type)
{
  $name = 'imageMAT_set' . $type;
  if (isset($_SESSION[$name])) {
    $set = $_SESSION[$name];
    if (is_array($set) && 0 < count($set) ) {
      return $set;
  } }
  return null;
}

function getSetAnnotations()
{
  return getSet('Annotations');
}

function getSetFolders()
{
  return getSet('Folders');
}

function getSetGroups()
{
  return getSet('Groups');
}

function echoSet($type)
{
  $set = getSet($type);
  
  if ($set == null) {
    echo '
var set',$type,'     = null;
var set',$type,'_lth = 0;';
  } else {
    echo '
var set',$type,'     = [';
    echo implode(',', $set);
    echo '];
var set',$type,'_lth = ',count($set),';';
  }
  echo '
var set',$type,'_changed = false;
';
}

function livesystem()
{
  return (substr($_SERVER['REQUEST_URI'],0,9) == '/imagemat');
}

function srcStylesheet()
{
  global $gVersion;

  $args = func_num_args();
  for ($i = 0; $i < $args; ++$i) {
    echo '<link rel="stylesheet" href="',
		 func_get_arg($i),'?',$gVersion,'" />
';
  }
}

function srcJavascript()
{
  global $gVersion;

  $args = func_num_args();
  for ($i = 0; $i < $args; ++$i) {
    echo '<script type="text/javascript" language="Javascript" src="',
         func_get_arg($i),'?',$gVersion, '"></script>
';
  }
}

$gInJavascript = false;

function enterJavascript()
{
  global $gInJavascript;

  if (!$gInJavascript) {
    echo '<script type="text/javascript" language="JavaScript">
<!--
';
    $gInJavascript = true;
} }

function exitJavascript()
{
  global $gInJavascript;

  if ($gInJavascript) {
    echo '
-->
</script>
';
    $gInJavascript = false;
} }

/* The link tells indexers not to index parameters */

function htmlHeader($title)
{
?>
<html>
<head>
<title>ImageMAT</title>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8" /> 
<?php
  $cache = getparameter('cache');
  if (!isset($cache)) {
?>
<meta http-equiv="Pragma" content="no-cache" />
<meta http-equiv="cache-control" content="no-cache, no-store, must-revalidate" />
<meta http-equiv="Expires" content="0" />
<?php
  }
?>
<link rel="shortcut icon" href="http://mat.uwaterloo.ca/imagemat_favicon.ico" type="image/x-icon" />
<link rel="canonical" href="http://mat.uwaterloo.ca<?php echo $_SERVER['PHP_SELF']?>" />
</head>

<?php
  enterJavascript();
  if (isset($_SESSION['imageMAT_hideTooltips'])) {
    echo '
var hideTooltips = true;';
  }

  echoSet('Annotations');
  echoSet('Folders');
  echoSet('Groups');
  exitJavascript();
  srcJavascript('../js/util.js');
}

function scriptName()
{
  return basename($_SERVER['PHP_SELF']);
}

function devsystem()
{
  if (!livesystem()) {
    echo 'dev';
} }

function bodyHeader()
{
?>

<div id="pagewrap" class="pagewrap<?php devsystem(); ?>">

<!--<div id="header">
<div id="logo"></div>
<div id="user">
-->
<div id="page">
<table id="navTable" cellpadding="0">
	<tr>
    	<td rowspan="2" class="td1"><img src="../images/logo-red.png" width="173" height="50" alt="imageMAT"></td>
        <!--<td class="td2">&nbsp;</td>-->
		<td class="td2"><?php
  if (isset($_SESSION['imageMAT_user_id'])) {
    echo 'Hello, ', username(),'
';
  }
?></td>
        <td class="td3"><a href="../library/library1.php" id="td3">My Library</a></td>
        <td class="td3">Notifications</td>
        <td class="td3"><a href="../register/profile.php" id="td3">Settings</a></td>
        <td class="td3"><a href="http://mat.uwaterloo.ca/MAT/?page_id=99" target="_blank">Help</a></td>
        <td class="td3"><a href="../register/logout.php">Log out</a></td>
    </tr>
    <tr>
    	<td class="td4">&nbsp;</td>
    	<td class="td4">&nbsp;</td>
    	<td class="td4">&nbsp;</td>
    	<td class="td4">&nbsp;</td>
    	<td class="td4">&nbsp;</td>
    	<td class="td4">&nbsp;</td>
    	<td class="td4">&nbsp;</td>
	</tr>
</table>
<br class="clearFloat" />

<div id="search">
<a href="../annotate/search.php">Search <img src="../images/search-icon.png"> </a>
</div>
</div>
</div>

<br class="clearFloat" />
<p>




<noscript>
<font color="red">
ImageMAT requires that javascript be enabled, as <a href=../register/enablejavascript.php><font color=blue>described here</font></a>.  
</font>
</noscript>
</p>
</div>
<div id=middle>

<?php
}

// This is just for diagnostics while testing

function bodyFooterFilename()
{

/*
  global $gPHPscript;

  echo '<p><a href="', htmlspecialchars($_SERVER['REQUEST_URI']), '">',$_SERVER['PHP_SELF'],'</a></p>';
*/
}

function bodyFooter()
{
?>
</div>

<div id="footer">

<?php bodyFooterFilename(); ?>
</div>
</div>


<?php
}

function mustlogon()
{
  global $gUserid;

  if (!isset($gUserid) || $_SESSION['imageMAT_livesystem'] != livesystem()) {
    header('Window-Target: _top');
    header('Location: ' . dirname($_SERVER['REQUEST_URI']) . '/../register/logon.php' );
    return true;
  }
  return false;
}

function plainText($string)
{
  if (isset($string)) {
	$ret = strip_tags($string);
    $ret = html_entity_decode($ret);
    return $ret;
  }
  return $string;
}

?>
