<?php

function displayXMLerror($error, $lines)
{
  echo '
<font color=red>
<br/>', htmlspecialchars($lines[$error->line - 1]),'
<br/>', str_repeat('-', $error->column), '^
<br/>';
  switch ($error->level) {
  case LIBXML_ERR_WARNING:
    echo 'Warning ';
    break;
  case LIBXML_ERR_ERROR:
    echo 'Error ';
	break;
  case LIBXML_ERR_FATAL:
    echo 'Fatal ';
    break;
  }
  echo $error->code, ':', htmlspecialchars(trim($error->message)),'
<br/>Line: ', $error->line, '
<br/>Column: ', $error->column;

  if ($error->file) {
    echo '
<br/>File: ', htmlspecialchars($error->file);
  }
  echo '
<br/>
<br/>--------------------------------------------
</font>
';
}

function getXMLNodeType($nodetype)
{
  switch ($nodeType) {
  case XMLReader::NONE:
	$type = 'none';
	break;
  case XMLReader::ELEMENT:
	$type = 'element';
    if ($xmlReader->attributeCount > 0) {
      $about = $xmlReader->getAttributeNs('about','http://www.w3.org/1999/02/22-rdf-syntax-ns#');
    }
	break;
  case XMLReader::ATTRIBUTE:
	$type = 'attribute';
	break;
  case XMLReader::TEXT:
	$type = 'text';
	break;
  case XMLReader::CDATA:
	$type = 'cdata';
	break;
  case XMLReader::ENTITY_REF:
	$type = 'entityref';
	break;
  case XMLReader::ENTITY:
	$type = 'entity';
	break;
  case XMLReader::PI:
	$type = 'pi';
	break;
  case XMLReader::COMMENT:
	$type = 'comment';
	break;
  case XMLReader::DOC:
	$type = 'doc';
	break;
  case XMLReader::DOC_TYPE:
	$type = 'doctype';
	break;
  case XMLReader::DOC_FRAGMENT:
	$type = 'docfragment';
	break;
  case XMLReader::NOTATION:
	$type = 'notation';
	break;
  case XMLReader::WHITESPACE:
	$type = 'whitespace';
	break;
  case XMLReader::SIGNIFICANT_WHITESPACE:
	$type = 'blank';
	break;
  case XMLReader::END_ELEMENT:
	$type = 'endElement';
	break;
  case XMLReader::END_ENTITY:
	$type = 'endEntity';
	break;
  case XMLReader::XML_DECLARATION:
	$type = 'xml';
	break;
  default;
	$type = '?? ' . htmlspecialchars($nodeType) . ' ??';
  }
  return $type;
}
?>
