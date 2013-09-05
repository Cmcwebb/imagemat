<?php

function return_manuscript_fields() {

 return array(
 'manuscript',
'author',
'origin',
'country',
'date',
'provenance',
'repository',
'callno',
'folios',
'materials',
'dimensions',
'binding',
'language',
'script',
'rubric',
'scribes',
'illuminations',
'initials',
'marginalia',
'artists'  );
}



function print_manuscript_template($template)
{
?>
<table class="iframetable2">
<tr class="manuscript" style="display:none">
<td class="Temp3"><a href="#" title="Title of the manuscript (Roman de la rose, Book of Hours, Bible, etc.">Manuscript Title:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[manuscript][manuscript]" value="<?php
  if (isset($template) && isset($template["manuscript"]['manuscript'])) {
    echo htmlspecialchars($template["manuscript"]['manuscript']);
  }
?>"/>
</td>
</tr>

<tr class="manuscript" style="display:none">
<td class="Temp3">Author:</td>
<td class="Temp4">
<input type=text size=50 name="template[manuscript][author]" value="<?php
  if (isset($template) && isset($template["manuscript"]['author'])) {
    echo htmlspecialchars($template["manuscript"]['author']);
  }
?>"/>
</td>
</tr>

<tr class="manuscript" style="display:none">
<td class="Temp3"><a href="#" title="City, town or region where manuscript was created">Origin:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[manuscript][origin]" value="<?php
  if (isset($template) && isset($template["manuscript"]['origin'])) {
    echo htmlspecialchars($template["manuscript"]['origin']);
  }
?>"/>
</td>
</tr>

<tr class="manuscript" style="display:none">
<td class="Temp3"><a href="#" title="Country where manuscript was created">Country:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[manuscript][country]" value="<?php
  if (isset($template) && isset($template["manuscript"]['country'])) {
    echo htmlspecialchars($template["manuscript"]['country']);
  }
?>"/>
</td>
</tr>

<tr class="manuscript" style="display:none">
<td class="Temp3">Date:</td>
<td class="Temp4">
<input type=text size=50 name="template[manuscript][date]" value="<?php
  if (isset($template) && isset($template["manuscript"]['date'])) {
    echo htmlspecialchars($template["manuscript"]['date']);
  }
?>"/>
</td>
</tr>

<tr class="manuscript" style="display:none">
<td class="Temp3"><a href="#" title="Chronology of the ownership or location of the manuscript">Provenance:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[manuscript][provenance]" value="<?php
  if (isset($template) && isset($template["manuscript"]['provenance'])) {
    echo htmlspecialchars($template["manuscript"]['provenance']);
  }
?>"/>
</td>
</tr>

<tr class="manuscript" style="display:none">
<td class="Temp3"><a href="#" title="Institution (national library, university library, municipal library, etc.) where the manuscript is located">Repository:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[manuscript][repository]" value="<?php
  if (isset($template) && isset($template["manuscript"]['repository'])) {
    echo htmlspecialchars($template["manuscript"]['repository']);
  }
?>"/>
</td>
</tr>

<tr class="manuscript" style="display:none">
<td class="Temp3"><a href="#" title="Call number or shelfmark for the manuscript">Call Number:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[manuscript][callno]" value="<?php
if (isset($template) && isset($template["manuscript"]['callno'])) {
	echo htmlspecialchars($template["manuscript"]['callno']);
}
?>"/>
</td>
</tr>

<tr class="manuscript" style="display:none">
<td class="Temp3"><a href="#" title="Total number of folios that make up the manuscript. The foliation can also be given in this field">Number of Folios:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[manuscript][folios]" value="<?php
  if (isset($template) && isset($template["manuscript"]['folios'])) {
    echo htmlspecialchars($template["manuscript"]['folios']);
  }
?>"/>
</td>
</tr>

<tr class="manuscript" style="display:none">
<td class="Temp3"><a href="#" title="Materials used in the creation of the manuscript (parchment, vellum, colors, etc.)">Materials:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[manuscript][materials]" value="<?php
  if (isset($template) && isset($template["manuscript"]['materials'])) {
    echo htmlspecialchars($template["manuscript"]['materials']);
  }
?>"/>
</td>
</tr>

<tr class="manuscript" style="display:none">
<td class="Temp3"><a href="#" title="Dimensions (in metric units) of the manuscript">Dimensions:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[manuscript][dimensions]" value="<?php
  if (isset($template) && isset($template["manuscript"]['dimensions'])) {
    echo htmlspecialchars($template["manuscript"]['dimensions']);
  }
?>"/>
</td>
</tr>

<tr class="manuscript" style="display:none">
<td class="Temp3"><a href="#" title="Type of binding used in the manuscript, including any later re-bindings. Use this field to identify the period when the manuscript was re-bound">Binding:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[manuscript][binding]" value="<?php
  if (isset($template) && isset($template["manuscript"]['binding'])) {
    echo htmlspecialchars($template["manuscript"]['binding']);
  }
?>"/>
</td>
</tr>

<tr class="manuscript" style="display:none">
<td class="Temp3"><a href="#" title="Language(s) used in the manuscript (Latin, French, English, etc.)">Language(s):</a></td>
<td class="Temp4">
<input type=text size=50 name="template[manuscript][language]" value="<?php
  if (isset($template) && isset($template["manuscript"]['language'])) {
    echo htmlspecialchars($template["manuscript"]['language']);
  }
?>"/>
</td>
</tr>

<tr class="manuscript" style="display:none">
<td class="Temp3"><a href="#" title="Script used (Carolingiain miniscule, Bastarda, Humanist, etc.)">Script:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[manuscript][script]" value="<?php
  if (isset($template) && isset($template["manuscript"]['script'])) {
    echo htmlspecialchars($template["manuscript"]['script']);
  }
?>"/>
</td>
</tr>

<tr class="manuscript" style="display:none">
<td class="Temp3"><a href="#" title="Use this field to transcribe or translate the rubric in the folio">Rubric:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[manuscript][rubric]" value="<?php
  if (isset($template) && isset($template["manuscript"]['rubric'])) {
    echo htmlspecialchars($template["manuscript"]['rubric']);
  }
?>"/>
</td>
</tr>

<tr class="manuscript" style="display:none">
<td class="Temp3"><a href="#" title="Name of scribe(s) that wrote/copied the manuscript if known">Scribes:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[manuscript][scribes]" value="<?php
  if (isset($template) && isset($template["manuscript"]['scribes'])) {
    echo htmlspecialchars($template["manuscript"]['scribes']);
  }
?>"/>
</td>
</tr>

<tr class="manuscript" style="display:none">
<td class="Temp3"><a hrhef="#" title="Give account of decoration (5 full folio illuminations, 3 column illuminations, etc.)">Decoration:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[manuscript][illuminations]" value="<?php
  if (isset($template) && isset($template["manuscript"]['illuminations'])) {
    echo htmlspecialchars($template["manuscript"]['illuminations']);
  }
?>"/>
</td>
</tr>

<tr class="manuscript" style="display:none">
<td class="Temp3"><a href="#" title="Types of initiatls used (historiated, foliated, pen-flourished, etc.)">Initials:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[manuscript][initials]" value="<?php
  if (isset($template) && isset($template["manuscript"]['initials'])) {
    echo htmlspecialchars($template["manuscript"]['initials']);
  }
?>"/>
</td>
</tr>

<tr class="manuscript" style="display:none">
<td class="Temp3"><a href="#" title="Any information regarding the marginalia (written or drawn) in the manuscript">Marginalia:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[manuscript][marginalia]" value="<?php
  if (isset($template) && isset($template["manuscript"]['marginalia'])) {
    echo htmlspecialchars($template["manuscript"]['marginalia']);
  }
?>"/>
</td>
</tr>

<tr class="manuscript" style="display:none">
<td class="Temp3"><a href="#" title="Artist or artists that created the decoration (miniatures, initials, etc.) in the manuscript">Artist(s):</a></td>
<td class="Temp4">
<input type=text size=50 name="template[manuscript][artists]" value="<?php
  if (isset($template) && isset($template["manuscript"]['artists'])) {
    echo htmlspecialchars($template["manuscript"]['artists']);
  }
?>"/>
</td>
</tr>

<?php
}




function template_manuscript($template)
{
?>
<tr name=template>
<td class="Temp3"><a href="#" title="Title of the manuscript (Roman de la rose, Book of Hours, Bible, etc.">Manuscript Title:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[manuscript][manuscript]" value="<?php
  if (isset($template) && isset($template["manuscript"]['manuscript'])) {
    echo htmlspecialchars($template["manuscript"]['manuscript']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3">Author:</td>
<td class="Temp4">
<input type=text size=50 name="template[manuscript][author]" value="<?php
  if (isset($template) && isset($template["manuscript"]['author'])) {
    echo htmlspecialchars($template["manuscript"]['author']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3"><a href="#" title="City, town or region where manuscript was created">Origin:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[manuscript][origin]" value="<?php
  if (isset($template) && isset($template["manuscript"]['origin'])) {
    echo htmlspecialchars($template["manuscript"]['origin']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3"><a href="#" title="Country where manuscript was created">Country:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[manuscript][country]" value="<?php
  if (isset($template) && isset($template["manuscript"]['country'])) {
    echo htmlspecialchars($template["manuscript"]['country']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3">Date:</td>
<td class="Temp4">
<input type=text size=50 name="template[manuscript][date]" value="<?php
  if (isset($template) && isset($template["manuscript"]['date'])) {
    echo htmlspecialchars($template["manuscript"]['date']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3"><a href="#" title="Chronology of the ownership or location of the manuscript">Provenance:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[manuscript][provenance]" value="<?php
  if (isset($template) && isset($template["manuscript"]['provenance'])) {
    echo htmlspecialchars($template["manuscript"]['provenance']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3"><a href="#" title="Institution (national library, university library, municipal library, etc.) where the manuscript is located">Repository:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[manuscript][repository]" value="<?php
  if (isset($template) && isset($template["manuscript"]['repository'])) {
    echo htmlspecialchars($template["manuscript"]['repository']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3"><a href="#" title="Call number or shelfmark for the manuscript">Call Number:</a></td>
<td class="Temp4">
<input type=text six=50 name="template[manuscript][callno]" value="<?php
if (isset($template) && isset($template["manuscript"]['callno'])) {
        echo htmlspecialchars($template["manuscript"]['callno']);
}
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3"><a href="#" title="Total number of folios that make up the manuscript. The foliation can also be given in this field">Number of Folios:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[manuscript][folios]" value="<?php
  if (isset($template) && isset($template["manuscript"]['folios'])) {
    echo htmlspecialchars($template["manuscript"]['folios']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3"><a href="#" title="Materials used in the creation of the manuscript (parchment, vellum, colors, etc.)">Materials:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[manuscript][materials]" value="<?php
  if (isset($template) && isset($template["manuscript"]['materials'])) {
    echo htmlspecialchars($template["manuscript"]['materials']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3"><a href="#" title="Dimensions (in metric units) of the manuscript">Dimensions:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[manuscript][dimensions]" value="<?php
  if (isset($template) && isset($template["manuscript"]['dimensions'])) {
    echo htmlspecialchars($template["manuscript"]['dimensions']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3"><a href="#" title="Type of binding used in the manuscript, including any later re-bindings. Use this field to identify the period when the manuscript was re-bound">Binding:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[manuscript][binding]" value="<?php
  if (isset($template) && isset($template["manuscript"]['binding'])) {
    echo htmlspecialchars($template["manuscript"]['binding']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3"><a href="#" title="Language(s) used in the manuscript (Latin, French, English, etc.)">Language(s):</a></td>
<td class="Temp4">
<input type=text size=50 name="template[manuscript][language]" value="<?php
  if (isset($template) && isset($template["manuscript"]['language'])) {
    echo htmlspecialchars($template["manuscript"]['language']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3"><a href="#" title="Script used (Carolingiain miniscule, Bastarda, Humanist, etc.)">Script:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[manuscript][script]" value="<?php
  if (isset($template) && isset($template["manuscript"]['script'])) {
    echo htmlspecialchars($template["manuscript"]['script']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3"><a href="#" title="Use this field to transcribe or translate the rubric in the folio">Rubric:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[manuscript][rubric]" value="<?php
  if (isset($template) && isset($template["manuscript"]['rubric'])) {
    echo htmlspecialchars($template["manuscript"]['rubric']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3"><a href="#" title="Name of scribe(s) that wrote/copied the manuscript if known">Scribes:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[manuscript][scribes]" value="<?php
  if (isset($template) && isset($template["manuscript"]['scribes'])) {
    echo htmlspecialchars($template["manuscript"]['scribes']);
  }
?>"/>
</td>
</tr>



<tr name=template>
<td class="Temp3"><a hrhef="#" title="Give account of decoration (5 full folio illuminations, 3 column illuminations, etc.)">Decoration:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[manuscript][illuminations]" value="<?php
  if (isset($template) && isset($template["manuscript"]['illuminations'])) {
    echo htmlspecialchars($template["manuscript"]['illuminations']);
  }
?>"/>
</td>
</tr>

<tr class="manuscript" style="display:none">
<td class="Temp3"><a href="#" title="Types of initiatls used (historiated, foliated, pen-flourished, etc.)">Initials:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[manuscript][initials]" value="<?php
  if (isset($template) && isset($template["manuscript"]['initials'])) {
    echo htmlspecialchars($template["manuscript"]['initials']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3"><a href="#" title="Any information regarding the marginalia (written or drawn) in the manuscript">Marginalia:</a></td>
<td>
<input type=text size=50 name="template[manuscript][marginalia]" value="<?php
  if (isset($template) && isset($template["manuscript"]['marginalia'])) {
    echo htmlspecialchars($template["manuscript"]['marginalia']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3"><a href="#" title="Artist or artists that created the decoration (miniatures, initials, etc.) in the manuscript">Artist(s):</a></td>
<td class="Temp4">
<input type=text size=50 name="template[manuscript][artists]" value="<?php
  if (isset($template) && isset($template["manuscript"]['artists'])) {
    echo htmlspecialchars($template["manuscript"]['artists']);
  }
?>"/>
</td>
</tr>

<?php
}


function echo_template_manuscript($annotation_id)
{
  $query =
'select
annotation_id as "Annotation_id",
manuscript as Manuscript Title",
author as "Author",
origin as "Origin",
country as "Country",
date as "Date",
provenance as "Provenance",
repository as "Repository",
callno as "Call Number",
folios as "Folios",
materials as "Materials",
dimensions as "Dimensions",
binding as "Binding",
language as "Language",
script as "Script",
rubric as "Rubic",
scribes as "Scribes",
decoration as "Decorations",
initials as "Initials",
marginalia as "Marginalia",
artists as "Artist(s)"
  from template_manuscripts
 where annotation_id = ' . DBnumber($annotation_id);

  echo_table($query);
}
?>
