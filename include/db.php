<?php

## Basic database stuff

$gDBc        = null;
$gDBcnt      = 0;
$gAtomic     = false;

define('DB_SITE', 'localhost');

$gDBname     = null;
$gDBuser     = null;


function DBconnect_error()
{
  global $gDBc, $gPHPscript, $gDBname, $DBuser; 

  exitJavascript();
  echo '
<br>' , $gPHPscript , '
<br>Unable to connect to ' , DB_SITE , ':' , $gDBname , ' as ', $gDBuser, 
'<br>Error: ' , htmlspecialchars(mysqli_error($gDBc)) ,
'<br>The database server may be temporarily unavailable' ,
contact();
}

function DBerror($query)
{
  global $gDBc, $gPHPscript;

  if (!$gDBc) {
    DBconnect_error();
  }
  exitJavascript();
  echo '
<br>' , $gPHPscript ,
'<br>Database query failed:
<br>
<font color=blue>
<pre>
' ,  htmlspecialchars($query) ,  '
</pre>
</font>
<br>
Error: ' , htmlspecialchars(mysqli_error($gDBc)) ,
contact();
}

function DBconnect()
{
  global $gDBc, $gDBcnt, $gDBname, $gDBuser;

  if ($gDBcnt == 0) {
require_once('/etc/imagemat/imagemat.config');
    /* echo '<h3>Connecting to ', $gDBname, ' as ', $gDBuser, '</h3>'; */
    if (!$gDBc) {
      DBconnect_error();
      return null;
  } }
  ++$gDBcnt;
  return $gDBc;
}

function DBescape($value)
{
  global $gDBc;

  return $gDBc->real_escape_string($value);
}

function DBnumberC($value)
{
  if (!isset($value)) {
    return 'null,';
  }
  return DBescape($value) . ',';
}

function DBnumber($value)
{
  if (!isset($value)) {
    return 'null';
  }
  return DBescape($value);
}

function DBstringC($value)
{
  if (!isset($value)) {
    return 'null,';
  }
  return '\'' . DBescape($value) . '\',';
}

function DBstring($value)
{
  if (!isset($value)) {
    return 'null';
  }
  return '\'' . DBescape($value) . '\''; 
}

function DBdate($value)
{
  if (!isset($value)) {
    return 'null';
  }
  return '\'' . DBescape($value) . '\''; 
}

function DBquery($query)
{
  global $gDBc, $gPHPscript;

  $ret = mysqli_query($gDBc, $query);
  if (!$ret || !DBok()) {
    DBerror($query);
    $ret = false;
  }
  if (mysqli_warning_count($gDBc)) {
    exitJavascript();
    echo '
<br>' , $gPHPscript , '
<br>Database warning:
<br>
<font color=blue>
<pre>
' ,  htmlspecialchars($query) ,  '
</pre>
</font>';
    if ($ret1 = mysqli_query($gDBc, "SHOW WARNINGS")) {
      while ($row = mysqli_fetch_row($ret1)) {
        printf("<br>%s (%d): %s\n", $row[0], $row[1], $row[2]);
      }
      mysqli_free_result($ret1);
  } }
  return $ret;
}

function DBok()
{
  global $gDBc;

  return (mysqli_error($gDBc) == '');
}

function DBhiterror($query, $rows)
{
  global $gPHPscript;

  return 
'<br>' . $gPHPscript .
'<br>Unexpected number of rows affected
<br>
<font color=blue>
<pre>
' .  htmlspecialchars($query) .  '
</pre>
</font>
<br>
Affected: ' . $rows . ' records ' .
contact();
}

function DBupdate1($query)
{
  global $gDBc;

  $ret = DBquery($query);
  if (!$ret) {
    return $ret;
  }
  $updated = DBupdated();
  switch ($updated) {
  case 1:
    break;
  case 0:
    $msg = mysqli_info($gDBc);
    if ($msg != 'Rows matched: 1  Changed: 0  Warnings: 0') {
      echo $msg;
    }
    break;
  default:
    exitJavascript();
    echo DBhiterror($query, $updated);
    return false;
  }
  return true;
}

function DBrows($ret)
{
  return mysqli_num_rows($ret);
}

function DBid()
{
  global$gDBc;

  return mysqli_insert_id($gDBc);
}

function DBupdated()
{
  global $gDBc;

  return mysqli_affected_rows($gDBc);
}

function DBfetch($ret)
{
  global $gDBc;

  return mysqli_fetch_assoc($ret);
}

function DBshowrow($ret)
{
  if (!$ret) {
    echo htmlspecialchars(mysqli_error($gDBc));
  }
  $rows = DBrows($ret);
  if ($rows == 0) {
	  echo 'No rows';
  }
  echo $rows . ' rows';
  $row = DBfetch($ret);
  echo ' with ' . count($row) . ' values each';
  echo '<p><table><tr><th>Name</th><th>Value</th></tr>';
  foreach ($row as $colname => $value) {
    echo '<tr><td>' . $colname . '</td><td>' . $value . '</td></tr>';
  }
  echo '</table>';
}

function DBatomic()
{
  global $gDBc, $gAtomic;

  if (!$gAtomic) {
    $query = 'start transaction';
    $ret = mysqli_query($gDBc, $query);
    if (!$ret || !DBok()) {
      DBerror($query);
      return false;
    }
    $gAtomic = true;
  }
  return true;
}

function DBcommit()
{
  global $gDBc, $gAtomic;

  if ($gAtomic) {
    $query = 'commit';
    $ret = mysqli_query($gDBc, $query);
    if (!$ret || !DBok()) {
      DBerror($query);
      return false;
    }
    $gAtomic = false;
  }
  return true;
}

function DBclose()
{
  global $gDBc, $gDBcnt, $gAtomic;

  if (isset($gDBc)) {
    if ($gAtomic) {
      DBcommit();
    }
    --$gDBcnt;
    if ($gDBcnt == 0) {
      mysqli_close($gDBc);
      $gDBc = null;
} } }

// This function is for the request new user name/password on logout - invokes reset.php
function DBreset()
{
	location.replace("reset.php");
}

// This function is for the redirect to Library when user logs into imageMAT
function DBreplaceDoc()
	{window.location.replace("http://mat.uwaterloo.ca/library/library1.php");
	}
?>
