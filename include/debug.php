<?php

## Debug utilities

function gettype1($val)
{
  if (is_null($val)) {
    return 'null';
  }
  if (is_string($val)) {
    return 'string';
  } 
  if (is_int($val)) {
    return 'int';
  }
  if (is_double($val)) {
    return 'double';
  } 
  if (is_array($val)) {
    return 'array';
  }
  if (is_bool($val)) {
    return 'bool';
  }
  if (is_float($val)) {
    return 'float';
  }
  if (is_object($val)) {
    return 'object ' . get_class($val);
  }
  if (is_resource($val)) {
    return 'resource';
  }
  return 'unknown';
}

function tinybody($message)
{
  echo '
</html>
<body>
<h3>Tiny page</h3>
<pre>
' . $message . '
</body>
</html>
';
}

function tinypage($message)
{
  echo HtmlHeader("Tiny Page");
  tinybody($message);
}
?>
