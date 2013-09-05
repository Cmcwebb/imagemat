<?php
srcStylesheet('../css/alert.css');
srcJavascript('../js/alert.js');

function javascriptAlert($title, $icon, $body, $buttons)
{
  global $gPHPscript;

  $comma = '';
  enterJavascript();
  echo '
customAlert({';
  if (!isset($title)) {
    $title = basename($gPHPscript);
  }
  if (isset($title)) {
    if ($title == false) {
      echo 'title:false';
    } else {
      echo 'title:', json_encode($title);
    }
    $comma = ',';
  }
  if (isset($icon)) {
    echo $comma;
    if ($icon == false) {
      echo 'icon:false';
    } else {
      echo 'icon:"', urlencode($icon), '"';
    }
    $comma = ',';
  }
  if (isset($body)) {
    echo $comma, 'body:', json_encode($body);
    $comma = ',';
  }
  if (isset($buttons)) {
    echo $comma,'buttons:{';
    if (is_array($buttons)) {
      $comma = '';
      foreach ($buttons as $name => $value) {
        echo $comma, $name, ':', htmlspecialchars($value);
        $comma = ',';
      }
    } else {
      echo $buttons, ':null';
    }
    echo '}';
  }
  echo '});';
  exitJavascript();
}
?>
