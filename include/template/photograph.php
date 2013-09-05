<?php
function return_photograph_fields(){

 return array(
 'photograph',
'subject',
'photographer',
'imagedate',
'printdate',
'printtype',
'dimensions',
'location'
 );

}


function print_photograph_template($template)
{
?>
<table class="iframetable2">
<tr class="photograph" style="display:none">
<td class="Temp3">Photo Title:</td>
<td class="Temp4">
<input type=text size=50 name="template[photograph][photograph]" value="<?php
  if (isset($template) && isset($template["photograph"]['photograph'])) {
    echo htmlspecialchars($template["photograph"]['photograph']);
  }
?>"/>
</td>
</tr>

<tr class="photograph" style="display:none">
<td class="Temp3"><a href="#" title="Subject matter">Subject:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[photograph][subject]" value="<?php
  if (isset($template) && isset($template["photograph"]['subject'])) {
    echo htmlspecialchars($template["photograph"]['subject']);
  }
?>"/>
</td>
</tr>

<tr class="photograph" style="display:none">
<td class="Temp3">Photographer:</td>
<td class="Temp4">
<input type=text size=50 name="template[photograph][photographer]" value="<?php
  if (isset($template) && isset($template["photograph"]['photographer'])) {
    echo htmlspecialchars($template["photograph"]['photographer']);
  }
?>"/>
</td>
</tr>

<tr class="photograph" style="display:none">
<td class="Temp3"><a href="#" title="Date when the photograph was taken">Image Date:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[photograph][imagedate]" value="<?php
  if (isset($template) && isset($template["photograph"]['imagedate'])) {
    echo htmlspecialchars($template["photograph"]['imagedate']);
  }
?>"/>
</td>
</tr>

<tr class="photograph" style="display:none">
<td class="Temp3"><a href="#" title="Date when the photograph was first printed">Print Date:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[photograph][printdate]" value="<?php
  if (isset($template) && isset($template["photograph"]['printdate'])) {
    echo htmlspecialchars($template["photograph"]['printdate']);
  }
?>"/>
</td>
</tr>

<tr class="photograph" style="display:none">
<td class="Temp3"><a href="#" title="Cynaotype, digital, etc.">Print Type:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[photograph][printtype]" value="<?php
  if (isset($template) && isset($template["photograph"]['printtype'])) {
    echo htmlspecialchars($template["photograph"]['printtype']);
  }
?>"/>
</td>
</tr>

<tr class="photograph" style="display:none">
<td class="Temp3"><a href="#" title="Dimensions (in metric units) of the photograph">Dimensions:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[photograph][dimensions]" value="<?php
  if (isset($template) && isset($template["photograph"]['dimensions'])) {
    echo htmlspecialchars($template["photograph"]['dimensions']);
  }
?>"/>
</td>
</tr>

<tr class="photograph" style="display:none">
<td class="Temp3"><a href="#" title="Place where the photograph is being displayed or is held (museum, art gallery, private collection, etc.">Present Location:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[photograph][location]" value="<?php
  if (isset($template) && isset($template["photograph"]['location'])) {
    echo htmlspecialchars($template["photograph"]['location']);
  }
?>"/>
</td>
</tr>
<?php
}



function template_photograph($template)
{
?>
<tr name=template>
<td class="Temp3">Photo Title:</td>
<td class="Temp4">
<input type=text size=50 name="template[photograph][photograph]" value="<?php
  if (isset($template) && isset($template["photograph"]['photograph'])) {
    echo htmlspecialchars($template["photograph"]['photograph']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3"><a href="#" title="Subject matter">Subject:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[photograph][subject]" value="<?php
  if (isset($template) && isset($template["photograph"]['subject'])) {
    echo htmlspecialchars($template["photograph"]['subject']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3">Photographer:</td>
<td class="Temp4">
<input type=text size=50 name="template[photograph][photographer]" value="<?php
  if (isset($template) && isset($template["photograph"]['photographer'])) {
    echo htmlspecialchars($template["photograph"]['photographer']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3"><a href="#" title="Date when the photograph was taken">Image Date:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[photograph][imagedate]" value="<?php
  if (isset($template) && isset($template["photograph"]['imagedate'])) {
    echo htmlspecialchars($template["photograph"]['imagedate']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3"><a href="#" title="Date when the photograph was first printed">Print Date:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[photograph][printdate]" value="<?php
  if (isset($template) && isset($template["photograph"]['printdate'])) {
    echo htmlspecialchars($template["photograph"]['printdate']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3"><a href="#" title="Cynaotype, digital, etc.">Print Type:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[photograph][printtype]" value="<?php
  if (isset($template) && isset($template["photograph"]['printtype'])) {
    echo htmlspecialchars($template["photograph"]['printtype']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<tr class="photograph" style="display:none">
<td class="Temp3"><a href="#" title="Dimensions (in metric units) of the photograph">Dimensions:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[photograph][dimensions]" value="<?php
  if (isset($template) && isset($template["photograph"]['dimensions'])) {
    echo htmlspecialchars($template["photograph"]['dimensions']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3"><a href="#" title="Place where the photograph is being displayed or is held (museum, art gallery, private collection, etc.">Present Location:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[photograph][location]" value="<?php
  if (isset($template) && isset($template["photograph"]['location'])) {
    echo htmlspecialchars($template["photograph"]['location']);
  }
?>"/>
</td>
</tr>
<?php
}



function echo_template_photograph($annotation_id)
{
  $query =
'select
annotation_id as "Annotation_id",
photograph as "Photo Title",
subject as "Subject",
photographer as "Photographer",
imagedate as "Image Date",
printdate as "Print Date",
printtype as "Print Type",
dimensions as "Dimensions",
location as "Present Location"
  from template_photographs
 where annotation_id = ' . DBnumber($annotation_id);

  echo_table($query);
}
?>
