<!DOCTYPE HTML>
<?php

$gPHPscript = __FILE__;
$dir = dirname(__FILE__);
require_once($dir . '/../include/boilerplate.php');

$language = getpost('language_code');
if (!isset($language)) {
  $language = 'eng';
}
if ($language != 'eng') {
  header('Window-Target: _top');
  header('Location: ' . dirname($_SERVER['REQUEST_URI']) . '/../' . $language . '/register/language.php' );
  return;
}

htmlHeader('Preferred Language');
srcStylesheet('../css/style.css');
enterJavascript();
?>
function submit()
{
  var form = document.getElementById("form");
 
  form.submit();
  return true;
}
<?php
exitJavascript();
?>
</head>
<body>
<?php

bodyHeader();
?>

<h3>Preferred Language</h3>
<p>
<font color=blue>English</font>
<p>
<h3>Please choose your preferred Language</h3>
<p>
<form id="form" action="language.php" method="post">
<input type=hidden name=mode value=y />
<table>
<tr><td align=right>Language:</td>
<td>
<select name="language_code" size=4 onclick="return submit()">
<option value='eng' selected>English</option>
<option value='fre'>French</option>
<option value='ger'>German</option>
<option value='spa'>Spanish</option>
</select>
</td></tr>
</table>
</form>

<?php
done:

bodyFooter();
?>
</body>
</html>

