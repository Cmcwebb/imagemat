<?php
function tz_string($offset)
{
  if (0 <= $offset) {
    $sign = 'GMT +';
  } else {
    $sign = 'GMT -';
    $offset = -$offset;
  }
  $hours   = $offset / (60 * 60);
  $offset -= $hours * (60 * 60);
  $min     = $offset / 60;
  $offset -= $min * 60;
  $min     = sprintf('%02d', $min);
  if ($offset != 0) {
    $offset = sprintf('%02d', $offset);
    return $sign . $hours . ':' . $min . ':' . $offset;
  }
  return $sign . $hours . ':' . $min;
}

function gmttod($gmt)
{
  return date('Y-m-d H:i:s', $gmt) . ' UTC ' . date('(l)', $gmt);
}

function clientstod($gmt)
{
  if (isset($_SESSION['imageMAT_tz_offset'])) {
    $offset = $_SESSION['imageMAT_tz_offset'];
    $gmt   += $offset;
    return date('Y-m-d H:i:s ', $gmt) . tz_string($offset) . date(' (l)', $gmt);
  }
  return gmttod($gmt);
}

function shortclientstod($gmt)
{
  if (isset($_SESSION['imageMAT_tz_offset'])) {
    $gmt += $_SESSION['imageMAT_tz_offset'];
  }
  return date('Y-m-d H:i:s ', $gmt);
}

function gmtnow()
{
  $tod = gettimeofday();
  return $tod['sec'] + ($tod['minuteswest'] * 60);
}

function clientstimenow()
{
  return clientstod(gmtnow());
}

function clientstime($time)
{
  if ($time == '0000-00-00 00:00:00') {
    return $time;
  }
  $unixtime = strtotime($time, 0);
  return clientstod($unixtime);
}

function shortclientstime($time)
{
  if ($time == '0000-00-00 00:00:00') {
    null;
  }
  $unixtime = strtotime($time, 0);
  return shortclientstod($unixtime);
}

function DatetimeInGMT() 
{
  return date("Y-m-d H:i:s", time()-date("Z",time()));
}

function GMTDatetimeToLocal($datetime)
{
  $time = strtotime($datetime);
  return date("Y-m-d H:i:s", $time+date("Z",$time));
}
?>
