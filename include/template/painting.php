<?php

function return_painting_fields() 
{
  return array(
'painting',
'subject',
'artist',
'origin',
'country',
'date',
'style',
'materials',
'dimensions',
'location');
}



function print_painting_template($template) {
?>
<table class="iframetable2">
<tr class="painting" style="display:none">
<td class="Temp3"><a href+"#" title="Official title of the painting">Painting Title:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[painting][painting]" value="<?php
  if (isset($template["painting"]) && isset($template["painting"]['painting'])) {
    echo htmlspecialchars($template["painting"]['painting']);
  }
?>"/>
</td>
</tr>

<tr class="painting" style="display:none">
<td class="Temp3">Subject:</td>
<td class="Temp4">
<input type=text size=50 name="template[painting][subject]" value="<?php
  if (isset($template["painting"]) && isset($template["painting"]['subject'])) {
    echo htmlspecialchars($template["painting"]['subject']);
  }
?>"/>
</td>
</tr>

<tr class="painting" style="display:none">
<td class="Temp3">Artist(s):</td>
<td class="Temp4">
<input type=text size=50 name="template[painting][artist]" value="<?php
  if (isset($template["painting"]) && isset($template["painting"]['artist'])) {
    echo htmlspecialchars($template["painting"]['artist']);
  }
?>"/>
</td>
</tr>

<tr class="painting" style="display:none">
<td class="Temp3"><a href="#" title="City, town or region where the painting was created">Origin:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[painting][origin]" value="<?php
  if (isset($template["painting"]) && isset($template["painting"]['origin'])) {
    echo htmlspecialchars($template["painting"]['origin']);
  }
?>"/>
</td>
</tr>
    
<tr class="painting" style="display:none">
<td class="Temp3"><a href="#" title="Country where the painting was created">Country:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[painting][country]" value="<?php
  if (isset($template["painting"]) && isset($template["painting"]['country'])) {
    echo htmlspecialchars($template["painting"]['country']);
  }
?>"/>
</td>
</tr>

<tr class="painting" style="display:none">
<td class="Temp3">Date:</td>
<td class="Temp4">
<input type=text size=50 name="template[painting][date]" value="<?php
  if (isset($template["painting"]) && isset($template["painting"]['date'])) {
    echo htmlspecialchars($template["painting"]['date']);
  }
?>"/>
</td>
</tr>

<tr class="painting" style="display:none">
<td class="Temp3"><a href="#" title="Impressionist, abstract expressionist, etc.">Style:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[painting][style]" value="<?php
  if (isset($template["painting"]) && isset($template["painting"]['style'])) {
    echo htmlspecialchars($template["painting"]['style']);
  }
?>"/>
</td>
</tr>

<tr class="painting" style="display:none">
<td class="Temp3"><a href="#" title="Materials used in the creation of the painting (oil, acrylic, mix-media, etc.)">Materials:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[painting][materials]" value="<?php
  if (isset($template["painting"]) && isset($template["painting"]['materials'])) {
    echo htmlspecialchars($template["painting"]['materials']);
  }
?>"/>
</td>
</tr>

<tr class="painting" style="display:none">
<td class="Temp3"><a href="#" title="Dimensions (in metric units) of the painting">Dimensions:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[painting][dimensions]" value="<?php
  if (isset($template["painting"]) && isset($template["painting"]['dimensions'])) {
    echo htmlspecialchars($template["painting"]['dimensions']);
  }
?>"/>
</td>
</tr>

<tr class="painting" style="display:none">
<td class="Temp3"><a href="#" title="Place where the painting is being displayed or held (museum, art gallery, private collection, etc.">Present Location:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[painting][location]" value="<?php
  if (isset($template["painting"]) && isset($template["painting"]['location'])) {
    echo htmlspecialchars($template["painting"]['location']);
  }
?>"/>
</td>
</tr>



<?php
}





function template_painting($template)
{
?>
<tr name=template>
<td class="Temp3"><a href+"#" title="Official title of the painting">Painting Title:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[painting][painting]" value="<?php
  if (isset($template["painting"]) && isset($template["painting"]['painting'])) {
    echo htmlspecialchars($template["painting"]['painting']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3">Subject:</td>
<td class="Temp4">
<input type=text size=50 name="template[painting][subject]" value="<?php
  if (isset($template["painting"]) && isset($template["painting"]['subject'])) {
    echo htmlspecialchars($template["painting"]['subject']);
  }
?>"/>
</td>
</tr>

<td class="Temp3">Artist(s):</td>
<td class="Temp4">
<input type=text size=50 name="template[painting][artist]" value="<?php
  if (isset($template["painting"]) && isset($template["painting"]['artist'])) {
    echo htmlspecialchars($template["painting"]['artist']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3"><a href="#" title="City, town or region where the painting was created">Origin:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[painting][origin]" value="<?php
  if (isset($template["painting"]) && isset($template["painting"]['origin'])) {
    echo htmlspecialchars($template["painting"]['origin']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3"><a href="#" title="Country where the painting was created">Country:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[painting][country]" value="<?php
  if (isset($template["painting"]) && isset($template["painting"]['country'])) {
    echo htmlspecialchars($template["painting"]['country']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3">Date:</td>
<td class="Temp4">
<input type=text size=50 name="template[painting][date]" value="<?php
  if (isset($template["painting"]) && isset($template["painting"]['date'])) {
    echo htmlspecialchars($template["painting"]['date']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3"><a href="#" title="Impressionist, abstract expressionist, etc.">Style:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[painting][style]" value="<?php
  if (isset($template["painting"]) && isset($template["painting"]['style'])) {
    echo htmlspecialchars($template["painting"]['style']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3"><a href="#" title="Materials used in the creation of the painting (oil, acrylic, mix-media, etc.)">Materials:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[painting][materials]" value="<?php
  if (isset($template["painting"]) && isset($template["painting"]['materials'])) {
    echo htmlspecialchars($template["painting"]['materials']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3"><a href="#" title="Dimensions (in metric units) of the painting">Dimensions:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[painting][dimensions]" value="<?php
  if (isset($template["painting"]) && isset($template["painting"]['dimensions'])) {
    echo htmlspecialchars($template["painting"]['dimensions']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3"><a href="#" title="Place where the painting is being displayed or held (museum, art gallery, private collection, etc.">Present Location:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[painting][location]" value="<?php
  if (isset($template["painting"]) && isset($template["painting"]['location'])) {
    echo htmlspecialchars($template["painting"]['location']);
  }
?>"/>
</td>
</tr>
<?php
}

function echo_template_painting($annotation_id)
{
  $query =
'select
annotation_id as "Annotation_id",
painting as "Painting Title",
subject as "Subject",
artist as "Artist",
origin as "Origin",
country as "Country",
date as "Date",
style as "Style",
materials as "Materials", 
dimensions as "Dimensions",
location as "Present Location"
  from template_paintings
 where annotation_id = ' . DBnumber($annotation_id);

  echo_table($query);
}
?>
