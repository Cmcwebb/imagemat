<?php

function return_artifact_fields() {

return array(
'artifact',
'artist',
'date',
'origin',
'country',
'provenance',
'material',
'dimensions',
'technique',
'present_location' );
}

function print_artifact_template($template) {
?>
<table class="iframetable2">
<tr class="artifact" style="display:none">
<td class="Temp3"><a href="#" title="Official name of the artifact, usually as it appears in catalogs">Artifact Title:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[artifact][artifact]" value="<?php
  if (isset($template["artifact"]) && isset($template["artifact"]['artifact'])) {
    echo htmlspecialchars($template["artifact"]['artifact']);
  }
?>"/>
</td>
</tr>

<tr class="artifact" style="display:none">
<td class="Temp3">Artist(s):</td>
<td class="Temp4">
<input type=text size=50 name="template[artifact][artist]" value="<?php
  if (isset($template) && isset($template["artifact"]['artist'])) {
    echo htmlspecialchars($template["artifact"]['artist']);
  }
?>"/>
</td>
</tr>
<tr class="artifact" style="display:none">
<td class="Temp3">Date:</td>
<td class="Temp4">
<input type=text size=50 name="template[artifact][date]" value="<?php
  if (isset($template) && isset($template["artifact"]['date'])) {
    echo htmlspecialchars($template["artifact"]['date']);
  }
?>"/>
</td>
</tr>
<tr class="artifact" style="display:none">
<td class="Temp3"><a href="#" title="City, town or region where the artifact was created">Origin:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[artifact][origin]" value="<?php
  if (isset($template) && isset($template["artifact"]['origin'])) {
    echo htmlspecialchars($template["artifact"]['origin']);
  }
?>"/>
</td>
</tr>
<tr class="artifact" style="display:none">
<td class="Temp3"><a href="#" title="Country (using current geographical standards) where the artifact was created">Country:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[artifact][country]" value="<?php
  if (isset($template) && isset($template["artifact"]['country'])) {
    echo htmlspecialchars($template["artifact"]['country']);
  }
?>"/>
</td>
</tr>
<tr class="artifact" style="display:none">
<td class="Temp3"><a href="#" title="Chronology of the ownership or location of the artifact">Provenance:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[artifact][provenance]" value="<?php
  if (isset($template) && isset($template["artifact"]['provenance'])) {
    echo htmlspecialchars($template["artifact"]['provenance']);
  }
?>"/>
</td>
</tr>
<tr class="artifact" style="display:none">
<td class="Temp3"><a href="#" title="Material(s) used in the creation of the artifact (e.g. glass, porcelain, granite, etc.) List all that apply">Material:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[artifact][material]" value="<?php
  if (isset($template) && isset($template["artifact"]['material'])) {
    echo htmlspecialchars($template["artifact"]['material']);
  }
?>"/>
</td>
</tr>
<tr class="artifact" style="display:none">
<td class="Temp3"><a href="#" title="Dimensions (in metric units) of the artifact">Dimensions:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[artifact][dimensions]" value="<?php
  if (isset($template) && isset($template["artifact"]['dimensions'])) {
    echo htmlspecialchars($template["artifact"]['dimensions']);
  }
?>"/>
</td>
</tr>
<tr class="artifact" style="display:none">
<td class="Temp3"><a href="#" title="Technique used for the creation of the artifact (e.g. circe perdu, sculpture, painting, gilding, mosaic, etc.)">Technique:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[artifact][technique]" value="<?php
  if (isset($template) && isset($template["artifact"]['technique'])) {
    echo htmlspecialchars($template["artifact"]['technique']);
  }
?>"/>
</td>
</tr>
<tr class="artifact" style="display:none">
<td class="Temp3"><a href="#" title="Place where the artifact currently resides (e.g. museum, library, university, private collection, etc.)">Present Location:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[artifact][present_location]" value="<?php
  if (isset($template["artifact"]) && isset($template["artifact"]['present_location'])) {
    echo htmlspecialchars($template["artifact"]['present_location']);
  }
?>"/>
</td>
</tr>

<?php
}



function template_artifact($template)
{
?>
<tr name=template>
<td class="Temp3"><a href="#" title="Official name of the artifact, usually as it appears in catalogs">Artifact Title:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[artifact][artifact]" value="<?php
  if (isset($template["artifact"]) && isset($template["artifact"]['artifact'])) {
    echo htmlspecialchars($template["artifact"]['artifact']);
  }
?>"/>
</td>
</tr>
<tr name=template>
<td class="Temp3">Artist(s):</td>
<td class="Temp4">
<input type=text size=50 name="template[artifact][artist]" value="<?php
  if (isset($template) && isset($template["artifact"]['artist'])) {
    echo htmlspecialchars($template["artifact"]['artist']);
  }
?>"/>
</td>
</tr>
<tr name=template>
<td class="Temp3">Date:</td>
<td class="Temp4">
<input type=text size=50 name="template[artifact][date]" value="<?php
  if (isset($template) && isset($template["artifact"]['date'])) {
    echo htmlspecialchars($template["artifact"]['date']);
  }
?>"/>
</td>
</tr>
<tr name=template>
<td class="Temp3"><a href="#" title="City, town or region where the artifact was created">Origin:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[artifact][origin]" value="<?php
  if (isset($template) && isset($template["artifact"]['origin'])) {
    echo htmlspecialchars($template["artifact"]['origin']);
  }
?>"/>
</td>
</tr>
<tr name=template>
<td class="Temp3"><a href="#" title="Country (using current geographical standards) where the artifact was created">Country:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[artifact][country]" value="<?php
  if (isset($template) && isset($template["artifact"]['country'])) {
    echo htmlspecialchars($template["artifact"]['country']);
  }
?>"/>
</td>
</tr>
<tr name=template>
<td class="Temp3"><a href="#" title="Chronology of the ownership or location of the artifact">Provenance:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[artifact][provenance]" value="<?php
  if (isset($template) && isset($template["artifact"]['provenance'])) {
    echo htmlspecialchars($template["artifact"]['provenance']);
  }
?>"/>
</td>
</tr>
<tr name=template>
<td class="Temp3"><a href="#" title="Material(s) used in the creation of the artifact (e.g. glass, porcelain, granite, etc.) List all that apply">Material:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[artifact][material]" value="<?php
  if (isset($template) && isset($template["artifact"]['material'])) {
    echo htmlspecialchars($template["artifact"]['material']);
  }
?>"/>
</td>
</tr>
<tr name=template>
<td class="Temp3"><a href="#" title="Dimensions (in metric units) of the artifact">Dimensions:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[artifact][dimensions]" value="<?php
  if (isset($template) && isset($template["artifact"]['dimensions'])) {
    echo htmlspecialchars($template["artifact"]['dimensions']);
  }
?>"/>
</td>
</tr>
<tr name=template>
<td class="Temp3"><a href="#" title="Technique used for the creation of the artifact (e.g. circe perdu, sculpture, painting, gilding, mosaic, etc.)">Technique:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[artifact][technique]" value="<?php
  if (isset($template) && isset($template["artifact"]['technique'])) {
    echo htmlspecialchars($template["artifact"]['technique']);
  }
?>"/>
</td>
</tr>
<tr name=template>
<td class="Temp3"><a href="#" title="Place where the artifact currently resides (e.g. museum, library, university, private collection, etc.)">Present Location:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[artifact][present_location]" value="<?php
  if (isset($template["artifact"]) && isset($template["artifact"]['present_location'])) {
    echo htmlspecialchars($template["artifact"]['present_location']);
  }
?>"/>
</td>
</tr>
<?php
}

function echo_template_artifact($annotation_id)
{
  $query =
'select
annotation_id as "Annotation_id",
artifact as "Artifact Title",
artist as "Artist",
date as "Date",
origin as "Origin",
country as "Country",
provenance as "Provenance",
material as "Material",
dimensions as "Dimensions",
technique as "Technique",
present_location as "Present Location"
  from template_artifacts
 where annotation_id = ' . DBnumber($annotation_id);

  echo_table($query);
}
?>
