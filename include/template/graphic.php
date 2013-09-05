<?php

function return_graphic_fields(){

 return array(
'graphic',
'artists',
'date',
'origin',
'country',
'materials',
'dimensions',
'location');

}



function print_graphic_template($template)
{
?>
<table class="iframetable2">
<tr class="graphic" style="display:none">
<td class="Temp3">Work Title:</td>
<td class="Temp4">
<input type=text size=50 name="template[graphic][graphic]" value="<?php
  if (isset($template) && isset($template["graphic"]['graphic'])) {
    echo htmlspecialchars($template["graphic"]['graphic']);
  }
?>"/>
</td>
</tr>

<tr class="graphic" style="display:none">
<td class="Temp3">Artists:</td>
<td class="Temp4">
<input type=text size=50 name="template[graphic][artists]" value="<?php
  if (isset($template) && isset($template["graphic"]['artists'])) {
    echo htmlspecialchars($template["graphic"]['artists']);
  }
?>"/>
</td>
</tr>

<tr class="graphic" style="display:none">
<td class="Temp3">Date:</td>
<td class="Temp4">
<input type=text size=50 name="template[graphic][date]" value="<?php
  if (isset($template) && isset($template["graphic"]['date'])) {
    echo htmlspecialchars($template["graphic"]['date']);
  }
?>"/>
</td>
</tr>

<tr class="graphic" style="display:none">
<td class="Temp3"><a href="#" title="Place where the piece was created">Origin:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[graphic][origin]" value="<?php
  if (isset($template) && isset($template["graphic"]['origin'])) {
    echo htmlspecialchars($template["graphic"]['origin']);
  }
?>"/>
</td>
</tr>

<tr class="graphic" style="display:none">
<td class="Temp3"><a href="#" title="Country where the piece was created">Country:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[graphic][country]" value="<?php
  if (isset($template) && isset($template["graphic"]['country'])) {
    echo htmlspecialchars($template["graphic"]['country']);
  }
?>"/>
</td>
</tr>

<tr class="graphic" style="display:none">
<td class="Temp3"><a href="#" title="Materials used for the creation of the piece (drawings, engravings, lithographs, etc.)">Materials:</a></td>

<td class="Temp4">
<input type=text size=50 name="template[graphic][materials]" value="<?php
  if (isset($template) && isset($template["graphic"]['materials'])) {
    echo htmlspecialchars($template["graphic"]['materials']);
  }
?>"/>
</td>
</tr>

<tr class="graphic" style="display:none">
<td class="Temp3"><a href="#" title="Dimensions (in metric units) of the piece">Dimensions:</a>
</td>
<td class="Temp4">
<input type=text size=50 name="template[graphic][dimensions]" value="<?php
  if (isset($template) && isset($template["graphic"]['dimensions'])) {
    echo htmlspecialchars($template["graphic"]['dimensions']);
  }
?>"/>
</td>
</tr>

<tr class="graphic" style="display:none">
<td class="Temp3"><a href="#" title="Place where the piece is displayed or being held (museum, art gallery, private collection, etc.)">Present Location:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[graphic][location]" value="<?php
  if (isset($template) && isset($template["graphic"]['location'])) {
    echo htmlspecialchars($template["graphic"]['location']);
  }
?>"/>
</td>
</tr>
<?php
}

function template_graphic($template)
{
?>
<tr name=template>
<td class="Temp3">Work Title:</td>
<td class="Temp4">
<input type=text size=50 name="template[graphic][graphic]" value="<?php
  if (isset($template) && isset($template["graphic"]['graphic'])) {
    echo htmlspecialchars($template["graphic"]['graphic']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3">Artists:</td>
<td class="Temp4">
<input type=text size=50 name="template[graphic][artists]" value="<?php
  if (isset($template) && isset($template["graphic"]['artists'])) {
    echo htmlspecialchars($template["graphic"]['artists']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3">Date:</td>
<td class="Temp4">
<input type=text size=50 name="template[graphic][date]" value="<?php
  if (isset($template) && isset($template["graphic"]['date'])) {
    echo htmlspecialchars($template["graphic"]['date']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3"><a href="#" title="Place where the piece was created">Origin:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[graphic][origin]" value="<?php
  if (isset($template) && isset($template["graphic"]['origin'])) {
    echo htmlspecialchars($template["graphic"]['origin']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3"><a href="#" title="Country where the piece was created">Country:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[graphic][country]" value="<?php
  if (isset($template) && isset($template["graphic"]['country'])) {
    echo htmlspecialchars($template["graphic"]['country']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3"><a href="#" title="Materials used for the creation of the piece (drawings, engravings, lithographs, etc.)">Materials:</a></td>

<td class="Temp4">
<input type=text size=50 name="template[graphic][materials]" value="<?php
  if (isset($template) && isset($template["graphic"]['materials'])) {
    echo htmlspecialchars($template["graphic"]['materials']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3"><a href="#" title="Dimensions (in metric units) of the piece">Dimensions:</a>
</td>
<td class="Temp4">
<input type=text size=50 name="template[graphic][dimensions]" value="<?php
  if (isset($template) && isset($template["graphic"]['dimensions'])) {
    echo htmlspecialchars($template["graphic"]['dimensions']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3"><a href="#" title="Place where the piece is displayed or being held (museum, art gallery, private collection, etc.)">Present Location:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[graphic][location]" value="<?php
  if (isset($template) && isset($template["graphic"]['location'])) {
    echo htmlspecialchars($template["graphic"]['location']);
  }
?>"/>
</td>
</tr>
<?php
}

function echo_template_graphic($annotation_id)
{
  $query =
'select
annotation_id as "Annotation_id",
graphic as "Work Title",
artists as "Artist",
date as "Date",
origin as "Origin",
country as "Country",
materials as "Material",
dimensions as "Dimensions",
location as "Present Location"
  from template_graphics
 where annotation_id = ' . DBnumber($annotation_id);

  echo_table($query);
}
?>
