<?php

require_once(dirname(__FILE__) . '/../include/db.php');

# Must have connection to mat

function select_timezone($val)
{
  $query = 
'select timezone_code, timezone_region
  from timezones
 where timezone_region != \'Etc\'
 order by timezone_region, timezone_place';
  $ret = DBquery($query);
  if (!$ret) {
    return null;
  }
  $msg = 
'<select name=timezone_code>
<option size=32></option>
';
  $lastregion = null;

  while ($row = DBfetch($ret)) {
    $timezone_code   = $row['timezone_code'];
    $timezone_region = $row['timezone_region'];
    if ($timezone_region != $lastregion) {
      if (isset($lastregion)) {
        $msg .= '
</optgroup>';
      }
      $msg .= '
<optgroup label="' . $timezone_region . '">';
      $lastregion = $timezone_region;
    }

    $msg .= '<option value=' . $timezone_code;
    if ($timezone_code == $val) {
      $msg .= ' selected';
    }
    $msg .= '>' . htmlspecialchars($timezone_code) . '</option>
';
  }
  $msg .= '</optgroup></select>
';
  return $msg;
}
