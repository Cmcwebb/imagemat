<?php

function return_sculpture_fields(){

return array(
'sculpture',
'artist',
'date',
'origin',
'country',
'provenance',
'material',
'dimensions',
'technique',
'location');

}


function print_sculpture_template($template)
{
?>
<table class="iframetable2">
<tr class="sculpture" style="display:none">
<td class="Temp3"><a href="#" title="Official title of sculpture">Sculpture Title:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[sculpture][sculpture]" value="<?php
  if (isset($template["sculpture"]) && isset($template["sculpture"]['sculpture'])) {
    echo htmlspecialchars($template["sculpture"]['sculpture']);
  }
?>"/>
</td>
</tr>

<tr class="sculpture" style="display:none">
<td class="Temp3">Artist:</td>
<td class="Temp4">
<input type=text size=50 name="template[sculpture][artist]" value="<?php
  if (isset($template["sculpture"]) && isset($template["sculpture"]['artist'])) {
    echo htmlspecialchars($template["sculpture"]['artist']);
  }
?>"/>
</td>
</tr>

<tr class="sculpture" style="display:none">
<td class="Temp3">Date:</td>
<td class="Temp4">
<input type=text size=50 name="template[sculpture][date]" value="<?php
  if (isset($template["sculpture"]) && isset($template["sculpture"]['date'])) {
    echo htmlspecialchars($template["sculpture"]['date']);
  }
?>"/>
</td>
</tr>

<tr class="sculpture" style="display:none">
<td class="Temp3"><a href="#" title="City, town or region where the sculpture was created">Origin:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[sculpture][origin]" value="<?php
  if (isset($template["sculpture"]) && isset($template["sculpture"]['origin'])) {
    echo htmlspecialchars($template["sculpture"]['origin']);
  }
?>"/>
</td>
</tr>

<tr class="sculpture" style="display:none">
<td class="Temp3"><a href="#" title="Country where the sculpture was created">Country:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[sculpture][country]" value="<?php
  if (isset($template["sculpture"]) && isset($template["sculpture"]['country'])) {
    echo htmlspecialchars($template["sculpture"]['country']);
  }
?>"/>
</td>
</tr>

<tr class="sculpture" style="display:none">
<td class="Temp3"><a href="#" title="Chronology of the ownership or location of the sculpture">Provenance:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[sculpture][provenance]" value="<?php
  if (isset($template["sculpture"]) && isset($template["sculpture"]['provenance'])) {
    echo htmlspecialchars($template["sculpture"]['provenance']);
  }
?>"/>
</td>
</tr>

<tr class="sculpture" style="display:none">
<td class="Temp3"><a href="#" title="Materials used in the creation of the sculpture (bronze, clay, marble, glass, etc.)">Material:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[sculpture][material]" value="<?php
  if (isset($template["sculpture"]) && isset($template["sculpture"]['material'])) {
    echo htmlspecialchars($template["sculpture"]['material']);
  }
?>"/>
</td>
</tr>

<tr class="sculpture" style="display:none">
<td class="Temp3"><a href="#" title="Dimensions (in metric units) of the sculpture">Dimensions:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[sculpture][dimensions]" value="<?php
  if (isset($template["sculpture"]) && isset($template["sculpture"]['dimensions'])) {
    echo htmlspecialchars($template["sculpture"]['dimensions']);
  }
?>"/>
</td>
</tr>

<tr class="sculpture" style="display:none">
<td class="Temp3"><a href="#" title="Technique used in the creation of the sculpture (lost-wax process, bas-relief, carving, repouss&eacute;, etc.)">Technique:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[sculpture][technique]" value="<?php
  if (isset($template["sculpture"]) && isset($template["sculpture"]['technique'])) {
    echo htmlspecialchars($template["sculpture"]['technique']);
  }
?>"/>
</td>
</tr>

<tr class="sculpture" style="display:none">
<td class="Temp3"><a href="#" title="Place where the sculpture is being displayed or held (museum, art gallery, private collection, etc.)">Present Location:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[sculpture][location]" value="<?php
  if (isset($template["sculpture"]) && isset($template["sculpture"]['location'])) {
    echo htmlspecialchars($template["sculpture"]['location']);
  }
?>"/>
</td>
</tr>
<?php
}

function template_sculpture($template)
{
?>
<tr name=template>
<td class="Temp3"><a href="#" title="Official title of sculpture">Sculpture Title:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[sculpture][sculpture]" value="<?php
  if (isset($template["sculpture"]) && isset($template["sculpture"]['sculpture'])) {
    echo htmlspecialchars($template["sculpture"]['sculpture']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3">Artist:</td>
<td class="Temp4">
<input type=text size=50 name="template[sculpture][artist]" value="<?php
  if (isset($template["sculpture"]) && isset($template["sculpture"]['artist'])) {
    echo htmlspecialchars($template["sculpture"]['artist']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3">Date:</td>
<td class="Temp4">
<input type=text size=50 name="template[sculpture][date]" value="<?php
  if (isset($template["sculpture"]) && isset($template["sculpture"]['date'])) {
    echo htmlspecialchars($template["sculpture"]['date']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3"><a href="#" title="City, town or region where the sculpture was created">Origin:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[sculpture][origin]" value="<?php
  if (isset($template["sculpture"]) && isset($template["sculpture"]['origin'])) {
    echo htmlspecialchars($template["sculpture"]['origin']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3"><a href="#" title="Country where the sculpture was created">Country:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[sculpture][country]" value="<?php
  if (isset($template["sculpture"]) && isset($template["sculpture"]['country'])) {
    echo htmlspecialchars($template["sculpture"]['country']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3"><a href="#" title="Chronology of the ownership or location of the sculpture">Provenance:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[sculpture][provenance]" value="<?php
  if (isset($template["sculpture"]) && isset($template["sculpture"]['provenance'])) {
    echo htmlspecialchars($template["sculpture"]['provenance']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3"><a href="#" title="Materials used in the creation of the sculpture (bronze, clay, marble, glass, etc.)">Material:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[sculpture][material]" value="<?php
  if (isset($template["sculpture"]) && isset($template["sculpture"]['material'])) {
    echo htmlspecialchars($template["sculpture"]['material']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3"><a href="#" title="Dimensions (in metric units) of the sculpture">Dimensions:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[sculpture][dimensions]" value="<?php
  if (isset($template["sculpture"]) && isset($template["sculpture"]['dimensions'])) {
    echo htmlspecialchars($template["sculpture"]['dimensions']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3"><a href="#" title="Technique used in the creation of the sculpture (lost-wax process, bas-relief, carving, repouss&eacute;, etc.)">Technique:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[sculpture][technique]" value="<?php
  if (isset($template["sculpture"]) && isset($template["sculpture"]['technique'])) {
    echo htmlspecialchars($template["sculpture"]['technique']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3"><a href="#" title="Place where the sculpture is being displayed or held (museum, art gallery, private collection, etc.)">Present Location:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[sculpture][location]" value="<?php
  if (isset($template["sculpture"]) && isset($template["sculpture"]['location'])) {
    echo htmlspecialchars($template["sculpture"]['location']);
  }
?>"/>
</td>
</tr>
<?php
}

function echo_template_sculpture($annotation_id)
{
  $query =
'select
annotation_id as "Annotation_id",
sculpture as "Sculpture Title",
artist as "Artist",
date as "Date",
origin as "Origin",
country as "Country",
provenance as "Provenance",
material as "Material",
dimensions as "Dimensions",
technique as "Technique",
location as "Present Location"
  from template_sculptures
 where annotation_id = ' . DBnumber($annotation_id);

  echo_table($query);
}
?>
