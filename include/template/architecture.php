<?php
function return_architecture_fields() 
{
  return array(
'building',
'architects',
'location',
'country',
'period',
'style',
'dimensions',
'materials', 
 'plan'
);
}


function print_architecture_template($template){
?>
<table class="iframetable2">
<tr class="architecture" style="display:none">
<td class="Temp3"><a href="#" title="Building's proper and full name">Building Name:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[architecture][building]" value="<?php
  if (isset($template) && isset($template["architecture"]['building'])) {
    echo htmlspecialchars($template["architecture"]['building']);
  }
?>"/>
</td>
</tr>

<tr class="architecture" style="display:none">
<td class="Temp3"><a href="#" title="Architectural firm or individual architect (if known)">Architects:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[architecture][architects]" value="<?php
  if (isset($template) && isset($template["architecture"]['architects'])) {
    echo htmlspecialchars($template["architecture"]['architects']);
  }
?>"/>
</td>
</tr>

<tr class="architecture" style="display:none">
<td class="Temp3"><a href="#" title="City or town where building is located">Location:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[architecture][location]" value="<?php
  if (isset($template) && isset($template["architecture"]['location'])) {
    echo htmlspecialchars($template["architecture"]['location']);
  }
?>"/>
</td>
</tr>

<tr class="architecture" style="display:none">
<td class="Temp3"><a href="#" title="Country (using current geographical standards) where the building is located">Country:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[architecture][country]" value="<?php
  if (isset($template) && isset($template["architecture"]['country'])) {
    echo htmlspecialchars($template["architecture"]['country']);
  }
?>"/>
</td>
</tr>

<tr class="architecture" style="display:none">
<td class="Temp3"><a href="#" title="Architectural period or periods to which the building belongs">Period:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[architecture][period]" value="<?php
  if (isset($template) && isset($template["architecture"]['period'])) {
    echo htmlspecialchars($template["architecture"]['period']);
  }
?>"/>
</td>
</tr>

<tr class="architecture" style="display:none">
<td class="Temp3"><a href="#" title="Architectural style or styles that best define the building">Style:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[architecture][style]" value="<?php
  if (isset($template) && isset($template["architecture"]['style'])) {
    echo htmlspecialchars($template["architecture"]['style']);
  }
?>"/>
</td>
</tr>

<tr class="architecture" style="display:none">
<td class="Temp3"><a href="#" title="Building dimensions (in metric units) if known">Dimensions:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[architecture][dimensions]" value="<?php
  if (isset($template) && isset($template["architecture"]['dimensions'])) {
    echo htmlspecialchars($template["architecture"]['dimensions']);
  }
?>"/>
</td>
</tr>

<tr class="architecture" style="display:none">
<td class="Temp3"><a href="#" title="Specific major materials employed in the building process (e.g. carrera marble, concrete, oak, limestone, etc.) List all that apply">Materials:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[architecture][materials]" value="<?php
  if (isset($template) && isset($template["architecture"]['materials'])) {
    echo htmlspecialchars($template["architecture"]['materials']);
  }
?>"/>
</td>
</tr>

<tr class="architecture" style="display:none">
<td class="Temp3"><a href="#" title="If you have the URL or URLs for building ground plans, add them here.">URL ground plan:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[architecture][plan]" value="<?php
  if (isset($template) && isset($template["architecture"]['plan'])) {
    echo htmlspecialchars($template["architecture"]['plan']);
  }
?>"/>
</td>
</tr>




<?php 
}


function template_architecture($template)
{
?>
<tr name=template>
<td class="Temp3"><a href="#" title="Building's proper and full name">Building Name:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[architecture][building]" value="<?php
  if (isset($template) && isset($template["architecture"]['building'])) {
    echo htmlspecialchars($template["architecture"]['building']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3"><a href="#" title="Architectural firm or individual architect (if known)">Architects:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[architecture][architects]" value="<?php
  if (isset($template) && isset($template["architecture"]['architects'])) {
    echo htmlspecialchars($template["architecture"]['architects']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3"><a href="#" title="City or town where building is located">Location:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[architecture][location]" value="<?php
  if (isset($template) && isset($template["architecture"]['location'])) {
    echo htmlspecialchars($template["architecture"]['location']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3"><a href="#" title="Country (using current geographical standards) where the building is located">Country:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[architecture][country]" value="<?php
  if (isset($template) && isset($template["architecture"]['country'])) {
    echo htmlspecialchars($template["architecture"]['country']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3"><a href="#" title="Architectural period or periods to which the building belongs">Period:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[architecture][period]" value="<?php
  if (isset($template) && isset($template["architecture"]['period'])) {
    echo htmlspecialchars($template["architecture"]['period']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3"><a href="#" title="Architectural style or styles that best define the building">Style:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[architecture][style]" value="<?php
  if (isset($template) && isset($template["architecture"]['style'])) {
    echo htmlspecialchars($template["architecture"]['style']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3"><a href="#" title="Building dimensions (in metric units) if known">Dimensions:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[architecture][dimensions]" value="<?php
  if (isset($template) && isset($template["architecture"]['dimensions'])) {
    echo htmlspecialchars($template["architecture"]['dimensions']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3"><a href="#" title="Specific major materials employed in the building process (e.g. carrera marble, concrete, oak, limestone, etc.) List all that apply">Materials:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[architecture][materials]" value="<?php
  if (isset($template) && isset($template["architecture"]['materials'])) {
    echo htmlspecialchars($template["architecture"]['materials']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3"><a href="#" title="If you have the URL or URLs for building ground plans, add them here.">URL ground plan:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[architecture][plan]" value="<?php
  if (isset($template) && isset($template["architecture"]['plan'])) {
    echo htmlspecialchars($template["architecture"]['plan']);
  }
?>"/>
</td>
</tr>
<?php
}

function echo_template_architecture($annotation_id)
{
  $query =
'select
annotation_id as "Annotation_id",
building as "Building Name",
architects as "Architects",
location as "Location",
country as "Country",
period as "Period",
style as "Style",
dimensions as "Dimensions",
materials as "Materials",
plan as "URL Ground Plan"
  from template_architectures
 where annotation_id = ' . DBnumber($annotation_id);

  echo_table($query);
}
?>
