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
  $archive = getparameter('archive');
} else {
  $setAnnotations = getSetAnnotations();
  if ($setAnnotations == null) {
    header('Window-Target: _top');
    header('Location: ' . dirname($_SERVER['REQUEST_URI']) . '/../annotate/search.php?updates=view' );
    return;
  }
  $annotation_id = $setAnnotations[0];
} 

htmlHeader('View Annotations');
srcStylesheet(
  '../css/style.css',
  '../css/annotation.css'
);
?>
</head>
<body class="frame">
<?php
bodyHeader();
?>

<div id="splitpane">
<iframe id="imageFrame" name="imageFrame" class="imageFrame" mozallowfullscreen webkitallowfullscreen></iframe>
<iframe id="annotateFrame" class="annotateFrame" src="view1.php?annotation_id=<?php 
echo htmlspecialchars($annotation_id);
if (isset($version)) {
  echo '&version=', htmlspecialchars($version);
}
if (isset($archive)) {
  echo '&archive=', htmlspecialchars($archive);
}
?>" mozallowfullscreen webkitallowfullscreen></iframe>
</div>

<?php
bodyFooter();
?>

</body>
</html>
