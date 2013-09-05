<?php

function return_comic_fields() {
 
 return array(
 'comic',
'issue',
'published',
'writers',
'pencils',
'inks',
'colors',
'artist',
'characters',
'letters',
'publisher',
'language',
'translator');


}


function print_comic_template($template)
{
?>
<table class="iframetable2">
<tr class="comic" style="display:none">
<td class="Temp3"><a href="#" title="Official comic book or graphic novel title">Comic Book Title:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[comic][comic]" value="<?php
  if (isset($template) && isset($template["comic"]['comic'])) {
    echo htmlspecialchars($template["comic"]['comic']);
  }
?>"/>
</td>
</tr>

<tr class="comic" style="display:none">
<td class="Temp3">Issue:</td>
<td class="Temp4">
<input type=text size=50 name="template[comic][issue]" value="<?php
  if (isset($template) && isset($template["comic"]['issue'])) {
    echo htmlspecialchars($template["comic"]['issue']);
  }
?>"/>
</td>
</tr>

<tr class="comic" style="display:none">
<td class="Temp3">Date Published:</td>
<td class="Temp4">
<input type=text size=50 name="template[comic][published]" value="<?php
  if (isset($template) && isset($template["comic"]['published'])) {
    echo htmlspecialchars($template["comic"]['published']);
  }
?>"/>
</td>
</tr>

<tr class="comic" style="display:none">
<td class="Temp3">Writers:</td>
<td class="Temp4">
<input type=text size=50 name="template[comic][writers]" value="<?php
  if (isset($template) && isset($template["comic"]['writers'])) {
    echo htmlspecialchars($template["comic"]['writers']);
  }
?>"/>
</td>
</tr>

<tr class="comic" style="display:none">
<td class="Temp3"><a href="#" title="Name(s) of pencillers">Pencils:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[comic][pencils]" value="<?php
  if (isset($template) && isset($template["comic"]['pencils'])) {
    echo htmlspecialchars($template["comic"]['pencils']);
  }
?>"/>
</td>
</tr>

<tr class="comic" style="display:none">
<td class="Temp3"><a href="#" title="Name(s) of inker">Inks:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[comic][inks]" value="<?php
  if (isset($template) && isset($template["comic"]['inks'])) {
    echo htmlspecialchars($template["comic"]['inks']);
  }
?>"/>
</td>
</tr>

<tr class="comic" style="display:none">
<td class="Temp3"><a href="#" title="Name(s) of colorist">Colors:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[comic][colors]" value="<?php
  if (isset($template) && isset($template["comic"]['colors'])) {
    echo htmlspecialchars($template["comic"]['colors']);
  }
?>"/>
</td>
</tr>

<tr class="comic" style="display:none">
<td class="Temp3">Cover Artist:</a></td>

<td class="Temp4">
<input type=text size=50 name="template[comic][artist]" value="<?php
  if (isset($template) && isset($template["comic"]['artist'])) {
    echo htmlspecialchars($template["comic"]['artist']);
  }
?>"/>
</td>
</tr>

<tr class="comic" style="display:none">
<td class="Temp3"><a href="#" title="Artist in charge of character design">Character Design:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[comic][characters]" value="<?php
  if (isset($template) && isset($template["comic"]['characters'])) {
    echo htmlspecialchars($template["comic"]['characters']);
  }
?>"/>
</td>

</tr>

<tr class="comic" style="display:none">
<td class="Temp3"><a href="#" title="Artist in charge of letters and design">Letters &amp; Design:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[comic][letters]" value="<?php
  if (isset($template) && isset($template["comic"]['letters'])) {
    echo htmlspecialchars($template["comic"]['letters']);
  }
?>"/>
</td>
</tr>

<tr class="comic" style="display:none">
<td class="Temp3"><a href="#" title="Publishing house (DC, Marvel, Dark Horse, etc.)">Published By:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[comic][publisher]" value="<?php
  if (isset($template) && isset($template["comic"]['publisher'])) {
    echo htmlspecialchars($template["comic"]['publisher']);
  }
?>"/>
</td>
</tr>

<tr class="comic" style="display:none">
<td class="Temp3"><a href="#" title="Language or languages in which the comic is written">Language:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[comic][language]" value="<?php
  if (isset($template) && isset($template["comic"]['language'])) {
    echo htmlspecialchars($template["comic"]['language']);
  }
?>"/>
</td>
</tr>

<tr class="comic" style="display:none">
<td class="Temp3">Translator:</td>
<td class="Temp4">
<input type=text size=50 name="template[comic][translator]" value="<?php
  if (isset($template) && isset($template["comic"]['translator'])) {
    echo htmlspecialchars($template["comic"]['translator']);
  }
?>"/>
</td>
</tr>


<?php
}

function template_comic($template)
{
?>
<tr name=template>
<td class="Temp3"><a href="#" title="Official comic book or graphic novel title">Comic Book Title:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[comic][comic]" value="<?php
  if (isset($template) && isset($template["comic"]['comic'])) {
    echo htmlspecialchars($template["comic"]['comic']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3">Issue:</td>
<td class="Temp4">
<input type=text size=50 name="template[comic][issue]" value="<?php
  if (isset($template) && isset($template["comic"]['issue'])) {
    echo htmlspecialchars($template["comic"]['issue']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3">Date Published:</td>
<td class="Temp4">
<input type=text size=50 name="template[comic][published]" value="<?php
  if (isset($template) && isset($template["comic"]['published'])) {
    echo htmlspecialchars($template["comic"]['published']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3">Writers:</td>
<td class="Temp4">
<input type=text size=50 name="template[comic][writers]" value="<?php
  if (isset($template) && isset($template["comic"]['writers'])) {
    echo htmlspecialchars($template["comic"]['writers']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3"><a href="#" title="Name(s) of pencillers">Pencils:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[comic][pencils]" value="<?php
  if (isset($template) && isset($template["comic"]['pencils'])) {
    echo htmlspecialchars($template["comic"]['pencils']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3"><a href="#" title="Name(s) of inker">Inks:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[comic][inks]" value="<?php
  if (isset($template) && isset($template["comic"]['inks'])) {
    echo htmlspecialchars($template["comic"]['inks']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3"><a href="#" title="Name(s) of colorist">Colors:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[comic][colors]" value="<?php
  if (isset($template) && isset($template["comic"]['colors'])) {
    echo htmlspecialchars($template["comic"]['colors']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3">Cover Artist:</a></td>

<td class="Temp4">
<input type=text size=50 name="template[comic][artist]" value="<?php
  if (isset($template) && isset($template["comic"]['artist'])) {
    echo htmlspecialchars($template["comic"]['artist']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3"><a href="#" title="Artist in charge of character design">Character Design:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[comic][characters]" value="<?php
  if (isset($template) && isset($template["comic"]['characters'])) {
    echo htmlspecialchars($template["comic"]['characters']);
  }
?>"/>
</td>

</tr>

<tr name=template>
<td class="Temp3"><a href="#" title="Artist in charge of letters and design">Letters &amp; Design:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[comic][letters]" value="<?php
  if (isset($template) && isset($template["comic"]['letters'])) {
    echo htmlspecialchars($template["comic"]['letters']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3"><a href="#" title="Publishing house (DC, Marvel, Dark Horse, etc.)">Published By:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[comic][publisher]" value="<?php
  if (isset($template) && isset($template["comic"]['publisher'])) {
    echo htmlspecialchars($template["comic"]['publisher']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3"><a href="#" title="Language or languages in which the comic is written">Language:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[comic][language]" value="<?php
  if (isset($template) && isset($template["comic"]['language'])) {
    echo htmlspecialchars($template["comic"]['language']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3">Translator:</td>
<td class="Temp4">
<input type=text size=50 name="template[comic][translator]" value="<?php
  if (isset($template) && isset($template["comic"]['translator'])) {
    echo htmlspecialchars($template["comic"]['translator']);
  }
?>"/>
</td>
</tr>
<?php
}

function echo_template_comic($annotation_id)
{
  $query =
'select
annotation_id as "Annotation_id",
comic as "Comic Book Title",
issue as "Issue",
published as "Date Published",
writers as "Writers",
pencils as "Pencils",
inks as "Inks",
colors as "Colors",
artist as "Cover Artist",
characters as "Character Design",
letters as "Letters &amp; Design",
publisher as "Published By",
language as "Language",
translator as "Translator"
  from template_comics
 where annotation_id = ' . DBnumber($annotation_id);

  echo_table($query);
}
?>
