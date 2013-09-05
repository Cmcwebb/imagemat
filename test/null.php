<!DOCTYPE HTML>
<?php
$dir = dirname(__FILE__);
require_once($dir . '/../include/boilerplate.php');

htmlHeader('tooltip test');
srcStylesheet(
  '../css/tooltip.css'
);
srcJavascript(
  '../js/util.js',
  '../js/base64.js',
  '../js/tooltip.js'
);
?>
</head>
<body>
<table>
<col width=40>
<col width=40>
<col width=40>
<col width=40>
<col width=40>
<col width=40>
<tr><th></th><th>Undefined</th><th>null</th><th>''</th><th>'a'</th><th>'b'</th></tr>
<?php
enterJavascript();
?>
var o = {};
var i, j, x, y, x1;
for (i = 0; i < 5; ++i) {
    switch (i) {
    case 0:
	  delete o.x;
      x1 = 'Undefined';
      break;
    case 1:
      o.x  = null;
      x1 = 'Null';
      break;
    case 2:
      o.x  = '';
      x1 = '\'\'';
      break;
    case 3:
      o.x  = 'a';
      x1 = '\'a\'';
      break;
    case 4:
      o.x  = 'b';
      x1 = '\'b\'';
      break;
    }
	document.write('<tr><td>' + x1 + '</td>');
    for (j = 0; j < 5; ++j) {
      switch (j) {
      case 0:
		delete o.y;
        break;
      case 1:
        o.y  = null;
        break;
      case 2:
        o.y  = '';
        break;
      case 3:
        o.y  = 'a';
        break;
      case 4:
        o.y  = 'b';
        break;
      }
	  document.write('<td>');
	  if (o.x == o.y) {
		document.write('<font color=red>T</font>');
	  } else {
		document.write('F');
	  }
	  x = o.x;
	  y = o.y;
	  if (x == y) {
		document.write('<font color=red>T</font>');
	  } else {
		document.write('F');
	  }
	  document.write('</td>');
	}
	document.write('</tr>\n');
}
<?php
exitJavascript();
?>
</table>
</body>
</html>
