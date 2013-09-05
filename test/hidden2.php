<!DOCTYPE HTML>
<html>
<title> Hidden test</title>
<script language="JavaScript" type="text/javascript">

function addhidden(after, name, cnt, value)
{
  var e = document.createElement('input');

  e.type  = 'hidden';
  e.name  = name + '[' + cnt + ']';
  e.value = value;
  after.appendChild(e);
}

function create(event)
{
  var node = document.getElementById('form');


  if (node == null) {
    alert('no mode node');
    return false;
  }
  addhidden(node, 'urls', 0, 'test');
  if (parent != self) {
    parent.frames[0].addLeft(node);
  }
  return true;
}
</script>
<body>
<?php
var_dump($_POST);
?>
<form id=form name=form action=hidden2.php onsubmit="return create(event);" method="post">
<input type=hidden id=mode name=mode value=y />
<input type=text name=text />
<input type="submit" name=save value="Save" />
</form>
</body>
</html>
