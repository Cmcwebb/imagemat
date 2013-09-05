<?php

/*
function makeint($value)
{
  if (isset($value)) {
    return intval($value);
  }
  return($value);
}
*/

function hidden($name)
{
  if (!isset($GLOBALS[$name])) {
    return;
  }
  $val = $GLOBALS[$name];
  if (!is_array($val)) {
    echo '
<input type=hidden name=', $name, ' value="', htmlspecialchars($val) , '"/>';
    return;
  }
  foreach ($val as $key => $val1) {
    echo '
<input type=hidden name=', $name, '[', $key, '] value="', htmlspecialchars($val1) , '"/>';
  }
}
?>
