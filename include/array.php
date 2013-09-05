<?php

## Basic array manipulation extensions

function implode_key($glue = "", $pieces = array())
{ 
  $arrK = array_keys($pieces); 
  return implode($glue, $arrK); 
} 

