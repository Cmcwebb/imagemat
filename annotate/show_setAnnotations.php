<!DOCTYPE HTML>
<?php

$gPHPscript = __FILE__;
$dir = dirname(__FILE__);
require_once($dir . '/../include/boilerplate.php');

htmlHeader('Show Set Annotations');
srcStylesheet('../css/style.css');
?>
</head>
<body>

<?php

bodyHeader();

echo '
<h3>My editable annotations</h3>';

if (isset($_SESSION['imageMAT_setAnnotations'])) {
  $setAnnotations = $_SESSION['imageMAT_setAnnotations'];
  echo gettype($setAnnotations), '<p>';
  print_r($setAnnotations);
  echo '
<table>';
  $cnt = 0;
  foreach ($setAnnotations as $id) {
    echo '
<tr><td>', $id, '</td></tr>';
    ++$cnt;
  }
  echo '
</table>
<p>
', $cnt, ' ids';
} else {
  echo 'Null';
}
$setAnnotations = getSetAnnotations();
print_r($setAnnotations);

bodyFooter();
?>
</body>
</html>
