<?php

function return_book_fields() {

return array(

'book',
'authors',
'editor',
'printer',
'location',
'country',
'date',
'provenance',
'repository',
'callno',
'estcid',
'language',
'script',
'pages',
'dimensions',
'decoration',
'marginalia',
'artists' );

}


function  print_book_template($template){
?>
<table class="iframetable2">
<tr class="book" style="display:none">
<td class="Temp3"><a href="#" title="Official title of the book">Book Title:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[book][book]" value="<?php
  if (isset($template["book"]) && isset($template["book"]['book'])) {
    echo htmlspecialchars($template["book"]['book']);
  }
?>"/>
</td>
</tr>

<tr class="book" style="display:none">
<td class="Temp3">Author(s):</td>
<td class="Temp4">
<input type=text size=50 name="template[book][authors]" value="<?php
  if (isset($template) && isset($template["book"]['authors'])) {
    echo htmlspecialchars($template["book"]['authors']);
  }
?>"/>
</td>
</tr>

<tr class="book" style="display:none">
<td class="Temp3">Editor(s):</td>
<td class="Temp4">
<input type=text size=50 name="template[book][editor]" value="<?php
if (isset($template["book"]) && isset($template["book"]['editor'])) {
        echo htmlspecialchars($template["book"]['editor']);
}
?>"/>
</td>
</tr>



<tr class="book" style="display:none">
<td class="Temp3"><a href="#" title="Printer or printers of the book">Printed By:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[book][printer]" value="<?php
  if (isset($template["book"]) && isset($template["book"]['printer'])) {
    echo htmlspecialchars($template["book"]['printer']);
  }
?>"/>
</td>
</tr>

<tr class="book" style="display:none">
<td class="Temp3"><a href="#" title="City where the book was printed">Print Location:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[book][location]" value="<?php
  if (isset($template["book"]) && isset($template["book"]['location'])) {
    echo htmlspecialchars($template["book"]['location']);
  }
?>"/>
</td>
</tr>

<tr class="book" style="display:none">
<td class="Temp3"><a href="#" title="Country where the book was printed">Country:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[book][country]" value="<?php
  if (isset($template) && isset($template["book"]['country'])) {
    echo htmlspecialchars($template["book"]['country']);
  }
?>"/>
</td>
</tr>

<tr class="book" style="display:none">
<td class="Temp3">Date:</td>
<td class="Temp4">
<input type=text size=50 name="template[book][date]" value="<?php
  if (isset($template) && isset($template["book"]['date'])) {
    echo htmlspecialchars($template["book"]['date']);
  }
?>"/>
</td>
</tr>

<tr class="book" style="display:none">
<td class="Temp3"><a href="#" title="Chronology of the ownership or location of the book">Provenance:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[book][provenance]" value="<?php
  if (isset($template) && isset($template["book"]['provenance'])) {
    echo htmlspecialchars($template["book"]['provenance']);
  }
?>"/>

<tr class="book" style="display:none">
<td class="Temp3"><a href="#" title="Instituion (national library, university library, municipal library, private collection, etc.) where the book is located">Repository:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[book][repository]" value="<?php
  if (isset($template) && isset($template["book"]['repository'])) {
    echo htmlspecialchars($template["book"]['repository']);
  }
?>"/>
</td>
</tr>

<tr class="book" style="display:none">
<td class="Temp3"><a href="#" title="Call number or shelfmark of book">Call number</a></td>
<td class="Temp4">
<input type=text size=50 name="template[book][callno]" value="<?php
if (isset($template) && isset($template["book"]['callno'])) {
        echo htmlspecialchars($template["book"]['callno']);
}
?>"/>
</td>
</tr>

<tr class="book" style="display:none">
<td class="Temp3">ESTC ID number</td>
<td class="Temp4">
<input type=text size=50 name="template[book][estcid]" value="<?php
if (isset($template) && isset($template["book"]['estcid'])) {
        echo htmlspecialchars($template["book"]['estcid']);
}
?>"/>
</td>
</tr>

<tr class="book" style="display:none">
<td class="Temp3"><a href="#" title="Language or languages in which this book is written">Language:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[book][language]" value="<?php
  if (isset($template) && isset($template["book"]['language'])) {
    echo htmlspecialchars($template["book"]['language']);
  }
?>"/>
</td>
</tr>

<tr class="book" style="display:none">
<td class="Temp3"><a href="#" title="Type of script used to print this book (blackletter, italics, gothic, etc.)">Script:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[book][script]" value="<?php
  if (isset($template) && isset($template["book"]['script'])) {
    echo htmlspecialchars($template["book"]['script']);
  }
?>"/>
</td>
</tr>

<tr class="book" style="display:none">
<td class="Temp3">Number of pages:</td>
<td class="Temp4">
<input type=text size=50 name="template[book][pages]" value="<?php
if (isset($template) && isset($template["book"]['pages'])) {
        echo htmlspecialchars($template["book"]['pages']);
}
?>"/>
</td>
</tr>

<tr class="book" style="display:none">
<td class="Temp3"><a href="#" title="Book dimensions (in metric units) if known">Dimensions:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[book][dimensions]" value="<?php
if (isset($template) && isset($template["book"]['dimensions'])) {
        echo htmlspecialchars($template["book"]['dimensions']);
}
?>"/>
</td>
</tr>

<tr class="book" style="display:none">
<td class="Temp3"><a href="#" title="Illustrations or decorative elements (engraved etching, woodcut, border, colophon, printer's mark, etc.)">Decoration:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[book][decoration]" value="<?php
if (isset($template) && isset($template["book"]['decoration'])) {
        echo htmlspecialchars($template["book"]['decoration']);
}
?>"/>
</td>
</tr>


<tr class="book" style="display:none">
<td class="Temp3"><a href="#" title="Marginalia in the book (written or drawn)">Marginalia:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[book][marginalia]" value="<?php
  if (isset($template) && isset($template["book"]['marginalia'])) {
    echo htmlspecialchars($template["book"]['marginalia']);
  }
?>"/>
</td>
</tr>

<tr class="book" style="display:none">
<td class="Temp3">Artist(s):</td>
<td class="Temp4">
<input type=text size=50 name="template[book][artists]" value="<?php
  if (isset($template) && isset($template["book"]['artists'])) {
    echo htmlspecialchars($template["book"]['artists']);
  }
?>"/>
</td>
</tr>


<?php

}





function template_book($template)
{
?>
<tr name=template>
<td class="Temp3"><a href="#" title="Official title of the book">Book Title:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[book][book]" value="<?php
  if (isset($template) && isset($template["book"]['book'])) {
    echo htmlspecialchars($template["book"]['book']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3">Author(s):</td>
<td class="Temp4">
<input type=text size=50 name="template[book][authors]" value="<?php
  if (isset($template) && isset($template["book"]['authors'])) {
    echo htmlspecialchars($template["book"]['authors']);
  }
?>"/>
</td>
</tr>
<tr name=template>
<td class="Temp3">Editor(s):</td>
<td class="Temp4">
<input type=text size=50 name="template[book][editor]" value="<?php
if (isset($template) && isset($template["book"]['editor'])) {
	echo htmlspecialchars($template["book"]['editor']);
}
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3"><a href="#" title="Printer or printers of the book">Printed By:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[book][printer]" value="<?php
  if (isset($template["book"]) && isset($template["book"]['printer'])) {
    echo htmlspecialchars($template["book"]['printer']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3"><a href="#" title="City where the book was printed">Print Location:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[book][location]" value="<?php
  if (isset($template["book"]) && isset($template["book"]['location'])) {
    echo htmlspecialchars($template["book"]['location']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3"><a href="#" title="Country where the book was printed">Country:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[book][country]" value="<?php
  if (isset($template["book"]) && isset($template["book"]['country'])) {
    echo htmlspecialchars($template["book"]['country']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3">Date:</td>
<td class="Temp4">
<input type=text size=50 name="template[book][date]" value="<?php
  if (isset($template["book"]) && isset($template["book"]['date'])) {
    echo htmlspecialchars($template["book"]['date']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3"><a href="#" title="Chronology of the ownership or location of the book">Provenance:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[book][provenance]" value="<?php
  if (isset($template["book"]) && isset($template["book"]['provenance'])) {
    echo htmlspecialchars($template["book"]['provenance']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3"><a href="#" title="Instituion (national library, university library, municipal library, private collection, etc.) where the book is located">Repository:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[book][repository]" value="<?php
  if (isset($template["book"]) && isset($template["book"]['repository'])) {
    echo htmlspecialchars($template["book"]['repository']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3"><a href="#" title="Call number or shelfmark of book">Call number</a></td>
<td class="Temp4">
<input type=text size=50 name="template[book][callno]" value="<?php
if (isset($template["book"]) && isset($template["book"]['callno'])) {
	echo htmlspecialchars($template["book"]['callno']);
}
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3">ESTC ID number</td>
<td class="Temp4">
<input type=text size=50 name="template[book][estcid]" value="<?php
if (isset($template["book"]) && isset($template["book"]['estcid'])) {
	echo htmlspecialchars($template["book"]['estcid']);
}
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3"><a href="#" title="Language or languages in which this book is written">Language:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[book][language]" value="<?php
  if (isset($template["book"]) && isset($template["book"]['language'])) {
    echo htmlspecialchars($template["book"]['language']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3"><a href="#" title="Type of script used to print this book (blackletter, italics, gothic, etc.)">Script:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[book][script]" value="<?php
  if (isset($template["book"]) && isset($template["book"]['script'])) {
    echo htmlspecialchars($template["book"]['script']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3">Number of pages:</td>
<td class="Temp4">
<input type=text size=50 name="template[book][pages]" value="<?php
if (isset($template["book"]) && isset($template["book"]['pages'])) {
	echo htmlspecialchars($template["book"]['pages']);
}
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3"><a href="#" title="Book dimensions (in metric units) if known">Dimensions:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[book][dimensions]" value="<?php
if (isset($template["book"]) && isset($template["book"]['dimensions'])) {
	echo htmlspecialchars($template["book"]['dimensions']);
}
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3"><a href="#" title="Illustrations or decorative elements (engraved etching, woodcut, border, colophon, printer's mark, etc.)">Decoration:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[book][decoration]" value="<?php
if (isset($template["book"]) && isset($template["book"]['decoration'])) {
	echo htmlspecialchars($template["book"]['decoration']);
}
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3"><a href="#" title="Marginalia in the book (written or drawn)">Marginalia:</a></td>
<td>
<input type=text size=50 name="template[book][marginalia]" value="<?php
  if (isset($template["book"]) && isset($template["book"]['marginalia'])) {
    echo htmlspecialchars($template["book"]['marginalia']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3">Artist(s):</td>
<td class="Temp4">
<input type=text size=50 name="template[book][artists]" value="<?php
  if (isset($template["book"]) && isset($template['artists'])) {
    echo htmlspecialchars($template["book"]['artists']);
  }
?>"/>
</td>
</tr>
<?php
}

function echo_template_book($annotation_id)
{
  $query =
'select
annotation_id as "Annotation_id",
book as "Book Title",
authors as "Author(s)",
editor as "Editor(s)",
printer as "Printed By",
location as "Location",
country as "Country",
date as "Date",
provenance as "Provenance",
repository as "Repository",
callno as "Coll Number",
estcid as "ESTC ID",
language as "Language",
script as "Script",
dimensions as "Dimensions",
illustrations as "Illustrations",
decoration as "Decoration",
marginalia as "Marginalia",
artists as "Artist(s)"
  from template_books
 where annotation_id = ' . DBnumber($annotation_id);

  echo_table($query);
}
