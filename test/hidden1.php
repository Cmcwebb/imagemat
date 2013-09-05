<!DOCTYPE HTML>
<html>
<title>Hidden test left frame</title>
<script language="JavaScript" type="text/javascript">

function addleft1(after, name, cnt, value)
{
  var e = document.createElement('input');

  e.type  = 'hidden';
  e.name  = name + '[' + cnt + ']';
  e.value = value;
  after.appendChild(e);
}

function addLeft(node)
{
  addleft1(node, 'left', 0, 'frame');
  return true;
}
</script>
<body>
<h3>Left frame</h3>
</body>
</html>
