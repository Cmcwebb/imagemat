<!DOCTYPE HTML>
<?php
$gPHPscript = __FILE__;
$dir = dirname(__FILE__);
require_once($dir . '/../include/boilerplate.php');
require_once($dir . '/../include/util.php');

$width = getparameter('width');
if (!isset($width)) {
  $width = 50;
}
htmlHeader('Test Image Zoom');
?>
<script src="../js/tabs.js" language="javascript" type="text/javascript"></script>
<link rel='stylesheet' href="../css/style.css" />
<link rel='stylesheet' href="../css/annotation.css" />
<script language="JavaScript" type="text/javascript">
</script>
</head>
<frameset rows="5%,*">
<frame>
<form action='image.php?width=<?php echo $width; ?>' >
Width: <input name=width value='<?php echo $width; ?>' />
</form>
</frame>
<frameset cols="<?php echo $width,'%,*'; ?>">
<frame src="../annotate/images.php?urls[0]='http://train-photos.com.s3.amazonaws.com/8920.jpg'">
<frame src=image2.php>
</frameset>
</html>
