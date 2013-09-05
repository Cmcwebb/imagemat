<?php

require_once(dirname(__FILE__) . '/../include/db.php');

# Must have connection to mat

function select_country($val)
{
  if (!isset($val)) {
    $val = 'ca';
  }

  $query = 
'select country_code, name
  from countries';
  $ret = DBquery($query);
  if (!$ret) {
    return null;
  }
  $msg = 
'<select name=country_code>
<option size=32></option>
';
  while ($row = DBfetch($ret)) {
    $country_code = $row['country_code'];
    $msg .= '<option value=' . $country_code;
    if ($country_code == $val) {
      $msg .= ' selected';
    }
    $msg .= '>' . htmlspecialchars($row['name']) . '</option>
';
  }
  $msg .= '</select>
';
  return $msg;
}
