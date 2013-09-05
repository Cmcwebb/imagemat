<?php

function echo_table($query, $maxcols=10)
{
  $ret = DBquery($query);
  if (!$ret) {
    return -1;
  }
  echo '
<table border=1>';
  for ($cnt = 0; $row = DBfetch($ret); ++$cnt) {
	if ($cnt == 0) {
	  $cnt1 = count($row);
      if ($cnt1 > $maxcols) {
        echo '
<tr><th>Field</th><th>Value</th><tr>';
      } else {
        echo '
<tr>';
        foreach ($row as $colname => $value) {
          echo '<th align=left>' . $colname . '</th>';
        }
        echo '</tr>';
    } }
    if ($cnt1 > $maxcols) {
      foreach ($row as $colname => $value) {
        if (!isset($value)) {
          continue;
        }
        $value = trim($value);
        if ($value == '') {
          continue;
        }
        echo '
<tr><td>' . htmlspecialchars($colname) . '</td><td>' .
            htmlspecialchars($value) .   '</td></tr>';
      } 
      echo '
<tr bgcolor="#f0f0f0"><td>&nbsp;</td><td>&nbsp;</td></tr>';
    } else {
      echo '
<tr>';
      foreach ($row as $colname => $value) {
        echo '<td align=left>';
        echo htmlspecialchars($value);
        echo '</td>';
      }
      echo '</tr>';
  } }
  echo '
</table>';
  return $cnt;
}

function echo_table_across($query)
{
  return echo_table($query, 10000);
}

function echo_table_down($query)
{
  return echo_table($query, 0);
}

?>
