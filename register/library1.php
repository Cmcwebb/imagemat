<!DOCTYPE HTML>
<?php
$gPHPscript = __FILE__;
$dir = dirname(__FILE__);
require_once($dir . '/../include/boilerplate.php');
require_once($dir . '/../include/util.php');

if (mustlogon()) {
  return;
}

$annotation_id = getparameter('annotation_id');
if (!isset($annotation_id)) {
  $annotation_id = getparameter('id');
}
if (isset($annotation_id)) {
  $version = getparameter('version');
} else {
  $update = getparameter('update');
  if ($update != null) {
    $setAnnotations = getSetAnnotations();
    if ($setAnnotations == null) {
      header('Window-Target: _top');
      header('Location: ' . dirname($_SERVER['REQUEST_URI']) . '/../annotate/search.php?updates=update' );
      return;
    }
    $annotation_id = $setAnnotations[0];
} }

if (!isset($annotation_id)) {
  $urls = getparameter('urls');
  if (!isset($urls)) {
    $url = getparameter('url');
    if (isset($url)) {
      $urls = array($url);
  } }
  $htmls     = getparameter('htmls');
  if (!isset($htmls)) {
    $html = getparameter('html');
    if (isset($html)) {
      $htmls = array($htmls);
  } }
  $citation_ids = getpost('citation_ids');
  $image_ids    = getpost('image_ids');
  $html_ids     = getpost('html_ids');
  
  $ftitle    = getparameter('ftitle');
}

htmlHeader('Annotation View');
srcStylesheet(
  '../css/style.css',
  '../css/alert.css',
  '../css/library.css'
);
srcJavascript('../js/alert.js'); 
enterJavascript();

echo 'var mylanguage = \'';
if (isset($_SESSION['imageMAT_language_code2'])) {
  echo $_SESSION['imageMAT_language_code2'];
}
echo '\';
';
?>

function load_navframe()
{
  var load = document.getElementById("load_nav");
  if (!load) {
    alert("No right frame");
  } else {
    load.submit();
  }
}
<?php exitJavascript(); ?>
</head>

<body onload="load_navframe()">
<?php
bodyHeader();
// print_r($_POST);
/* ExtraFrame used to search for annotations add to images */
?>

<!--<iframe src="../register/dir.html" id="navigationFrame" name="navigationFrame" class="navigationFrame" mozallowfullscreen webkitallowfullscreen ></iframe> -->

<div id="triplePane">
<iframe src="" id="navigationFrame" name="navigationFrame" class="navigationFrame" mozallowfullscreen webkitallowfullscreen ></iframe>
<iframe id="mainFrame" name="mainFrame" class="mainFrame" mozallowfullscreen webkitallowfullscreen></iframe>
<iframe id="metaFrame" name="metaFrame" class="metaFrame" mozallowfullscreen webkitallowfullscreen></iframe>
</div>

<?php
echo '
<form id="load_nav" name="load_nav" method="post" target="navigationFrame" action="';
echo 'nav.php"> 
';
/*
if (!isset($annotation_id)) {
  echo 'create_annotation.php">
';
  hidden('ftitle');
  hidden('urls');
  hidden('htmls');
  hidden('image_ids');
  hidden('html_ids');
  hidden('citation_ids');
} else {
  $tab = getparameter('tab');
  echo 'update_annotation.php">
';
  hidden('annotation_id');
  hidden('version');
  hidden('tab');
}
*/
echo ' 
</form>';

bodyFooter();
?>

</body>

</html>
