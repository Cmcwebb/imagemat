<?php

function return_ceramic_fields() {

 return array(
 'ceramic',
'type',
'artist',
'origin',
'country',
'year',
'style',
'motifs',
'materials',
'dimensions',
'location' );

};




function  print_ceramic_template($template){
?>
<table class="iframetable2">
<tr class="ceramic" style="display:none">
<td class="Temp3"><a href="#" title="Title of the ceramic piece if known, or give a generic title  to it">Piece Title:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[ceramic][ceramic]" value="<?php
  if (isset($template) && isset($template["ceramic"]['ceramic'])) {
    echo htmlspecialchars($template["ceramic"]['ceramic']);
  }
?>"/>
</td>
</tr>

<tr class="ceramic" style="display:none">
<td class="Temp3"><a href="#" title="Type of ceramic product (structural, refractory, whitewares, technical, etc.)">Type:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[ceramic][type]" value="<?php
  if (isset($template) && isset($template["ceramic"]['type'])) {
    echo htmlspecialchars($template["ceramic"]['type']);
  }
?>"/>
</td>
</tr>

<tr class="ceramic" style="display:none">
<td class="Temp3">Artist:</td>
<td class="Temp4">
<input type=text size=50 name="template[ceramic][artist]" value="<?php
  if (isset($template) && isset($template["ceramic"]['artist'])) {
    echo htmlspecialchars($template["ceramic"]['artist']);
  }
?>"/>
</td>
</tr>

<tr class="ceramic" style="display:none">
<td class="Temp3"><a href="#" title="Place where the piece was created">Origin:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[ceramic][origin]" value="<?php
  if (isset($template) && isset($template["ceramic"]['origin'])) {
    echo htmlspecialchars($template["ceramic"]['origin']);
  }
?>"/>
</td>
</tr>

<tr class="ceramic" style="display:none">
<td class="Temp3"><a href="#" title="Country where the piece was created">Country:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[ceramic][country]" value="<?php
  if (isset($template) && isset($template["ceramic"]['country'])) {
    echo htmlspecialchars($template["ceramic"]['country']);
  }
?>"/>
</td>
</tr>

<tr class="ceramic" style="display:none">
<td class="Temp3">Date:</td>
<td class="Temp4">
<input type=text size=50 name="template[ceramic][year]" value="<?php
  if (isset($template) && isset($template["ceramic"]['year'])) {
    echo htmlspecialchars($template["ceramic"]['year']);
  }
?>"/>
</td>
</tr>

<tr class="ceramic" style="display:none">
<td class="Temp3"><a href="#" title="Style of the ceramic piece (Islamic, Japanese, Chinese, etc.)">Style:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[ceramic][style]" value="<?php
  if (isset($template) && isset($template["ceramic"]['style'])) {
    echo htmlspecialchars($template["ceramic"]['style']);
  }
?>"/>
</td>
</tr>

<tr class="ceramic" style="display:none">
<td class="Temp3"><a href="#" title="Gneral decorative motifs surrounding the piece">Decorative Motifs:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[ceramic][motifs]" value="<?php
  if (isset($template) && isset($template["ceramic"]['motifs'])) {
    echo htmlspecialchars($template["ceramic"]['motifs']);
  }
?>"/>
</td>
</tr>

<tr class="ceramic" style="display:none">
<td class="Temp3"><a href="#" title="Materials used in the creation of the piece (kaolinite, alumina, silicon carbide, tungsten carbide, etc.)">Materials:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[ceramic][materials]" value="<?php
  if (isset($template) && isset($template["ceramic"]['materials'])) {
    echo htmlspecialchars($template["ceramic"]['materials']);
  }
?>"/>
</td>
</tr>

<tr class="ceramic" style="display:none">
<td class="Temp3"><a href="#" title="Dimensions (in metric units) of the piece">Dimensions:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[ceramic][dimensions]" value="<?php
  if (isset($template) && isset($template["ceramic"]['dimensions'])) {
    echo htmlspecialchars($template["ceramic"]['dimensions']);
  }
?>"/>
</td>
</tr>


<tr class="ceramic" style="display:none">
<td class="Temp3"><a href="#" title="Place where the piece is displayed or being held (museum, gallery, private collection, etc.)">Present Location:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[ceramic][location]" value="<?php
  if (isset($template) && isset($template["ceramic"]['location'])) {
    echo htmlspecialchars($template["ceramic"]['location']);
  }
?>"/>
</td>
</tr>

<?php
}

function template_ceramic($template)
{
?>
<tr name=template>
<td class="Temp3"><a href="#" title="Title of the ceramic piece if known, or give a generic title  to it">Piece Title:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[ceramic][ceramic]" value="<?php
  if (isset($template) && isset($template["ceramic"]['ceramic'])) {
    echo htmlspecialchars($template["ceramic"]['ceramic']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3"><a href="#" title="Type of ceramic product (structural, refractory, whitewares, technical, etc.)">Type:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[ceramic][type]" value="<?php
  if (isset($template) && isset($template["ceramic"]['type'])) {
    echo htmlspecialchars($template["ceramic"]['type']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3">Artist:</td>
<td class="Temp4">
<input type=text size=50 name="template[ceramic][artist]" value="<?php
  if (isset($template) && isset($template["ceramic"]['artist'])) {
    echo htmlspecialchars($template["ceramic"]['artist']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3"><a href="#" title="Place where the piece was created">Origin:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[ceramic][origin]" value="<?php
  if (isset($template) && isset($template["ceramic"]['origin'])) {
    echo htmlspecialchars($template["ceramic"]['origin']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3"><a href="#" title="Country where the piece was created">Country:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[ceramic][country]" value="<?php
  if (isset($template) && isset($template["ceramic"]['country'])) {
    echo htmlspecialchars($template["ceramic"]['country']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3">Date:</td>
<td class="Temp4">
<input type=text size=50 name="template[ceramic][year]" value="<?php
  if (isset($template) && isset($template["ceramic"]['year'])) {
    echo htmlspecialchars($template["ceramic"]['year']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3"><a href="#" title="Style of the ceramic piece (Islamic, Japanese, Chinese, etc.)">Style:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[ceramic][style]" value="<?php
  if (isset($template) && isset($template["ceramic"]['style'])) {
    echo htmlspecialchars($template["ceramic"]['style']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3"><a href="#" title="Gneral decorative motifs surrounding the piece">Decorative Motifs:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[ceramic][motifs]" value="<?php
  if (isset($template) && isset($template["ceramic"]['motifs'])) {
    echo htmlspecialchars($template["ceramic"]['motifs']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3"><a href="#" title="Materials used in the creation of the piece (kaolinite, alumina, silicon carbide, tungsten carbide, etc.)">Materials:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[ceramic][materials]" value="<?php
  if (isset($template) && isset($template["ceramic"]['materials'])) {
    echo htmlspecialchars($template["ceramic"]['materials']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3"><a href="#" title="Dimensions (in metric units) of the piece">Dimensions:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[ceramic][dimensions]" value="<?php
  if (isset($template) && isset($template["ceramic"]['dimensions'])) {
    echo htmlspecialchars($template["ceramic"]['dimensions']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3"><a href="#" title="Place where the piece is displayed or being held (museum, gallery, private collection, etc.)">Present Location:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[ceramic][location]" value="<?php
  if (isset($template) && isset($template["ceramic"]['location'])) {
    echo htmlspecialchars($template["ceramic"]['location']);
  }
?>"/>
</td>
</tr>

<?php
}

function echo_template_ceramic($annotation_id)
{
  $query =
'select
annotation_id as "Annotation_id",
ceramic as "Piece Title",
type as "Type",
artist as "Artist",
origin as "Origin",
country as "Country",
date as "Date",
style as "Style",
motifs as "Decorative Motifs" ,
materials as "Materials",
dimensions as "Dimensions",
location as "Present Location"
  from template_ceramics
 where annotation_id = ' . DBnumber($annotation_id);

  echo_table($query);
}
?>
