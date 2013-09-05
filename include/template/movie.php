<?php

function return_movie_fields(){
 return array(
 'movie',
'scene',
'director',
'writers',
'cast',
'country',
'language',
'released',
'specifications',
'production',
'genres',
'filmed',
'url' );
}


function print_movie_template($template)
{
?>
<table class="iframetable2">
<tr class="movie" style="display:none">
<td class="Temp3">Movie Title:</td>
<td class="Temp4">
<input type=text size=50 name="template[movie][movie]" value="<?php
  if (isset($template) && isset($template["movie"]['movie'])) {
    echo htmlspecialchars($template["movie"]['movie']);
  }
?>"/>
</td>
</tr>

<tr class="movie" style="display:none">
<td class="Temp3"><a href="#" title="Title of the movie clip scene">Scene:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[movie][scene]" value="<?php
  if (isset($template) && isset($template["movie"]['scene'])) {
    echo htmlspecialchars($template["movie"]['scene']);
  }
?>"/>
</td>
</tr>

<tr class="movie" style="display:none">
<td class="Temp3">Director(s):</td>
<td class="Temp4">
<input type=text size=50 name="template[movie][director]" value="<?php
  if (isset($template) && isset($template["movie"]['director'])) {
    echo htmlspecialchars($template["movie"]['director']);
  }
?>"/>
</td>
</tr>

<tr class="movie" style="display:none">
<td class="Temp3">Screenwriter(s):</td>
<td class="Temp4">
<input type=text size=50 name="template[movie][writers]" value="<?php
  if (isset($template) && isset($template["movie"]['writers'])) {
    echo htmlspecialchars($template["movie"]['writers']);
  }
?>"/>
</td>
</tr>

<tr class="movie" style="display:none">
<td class="Temp3"><a href="#" title="Actors who appear in the movie clip">Cast:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[movie][cast]" value="<?php
  if (isset($template) && isset($template["movie"]['cast'])) {
    echo htmlspecialchars($template["movie"]['cast']);
  }
?>"/>
</td>
</tr>
<tr class="movie" style="display:none">
<td class="Temp3">Country:</td>
<td class="Temp4">
<input type=text size=50 name="template[movie][country]" value="<?php
  if (isset($template) && isset($template["movie"]['country'])) {
    echo htmlspecialchars($template["movie"]['country']);
  }
?>"/>
</td>
</tr>
<tr class="movie" style="display:none">
<td class="Temp3"><a href="#" title="Original language of the movie">Language:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[movie][language]" value="<?php
  if (isset($template) && isset($template["movie"]['language'])) {
    echo htmlspecialchars($template["movie"]['language']);
  }
?>"/>
</td>
</tr>

<tr class="movie" style="display:none">
<td class="Temp3">Release Date:</td>
<td class="Temp4">
<input type=text size=50 name="template[movie][released]" value="<?php
  if (isset($template) && isset($template["movie"]['released'])) {
    echo htmlspecialchars($template["movie"]['released']);
  }
?>"/>
</td>
</tr>

<tr class="movie" style="display:none">
<td class="Temp3"><a href="#" title="Technical specifications for the movie (run time, sound mix, color, aspect ratio, etc.)">Specifications:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[movie][specifications]" value="<?php
  if (isset($template) && isset($template["movie"]['specifications'])) {
    echo htmlspecialchars($template["movie"]['specifications']);
  }
?>"/>
</td>
</tr>

<tr class="movie" style="display:none">
<td class="Temp3"><a href="#" title="The studio or production company responsible for producing the film">Studio/Production Co.:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[movie][production]" value="<?php
  if (isset($template) && isset($template["movie"]['production'])) {
    echo htmlspecialchars($template["movie"]['production']);
  }
?>"/>
</td>
</tr>

<tr class="movie" style="display:none">
<td class="Temp3"><a href="#" title="Genre of the movie (animation, action/adventure, comedy, etc.)">Genre(s):</a></td>
<td class="Temp4">
<input type=text size=50 name="template[movie][genres]" value="<?php
  if (isset($template) && isset($template["movie"]['genres'])) {
    echo htmlspecialchars($template["movie"]['genres']);
  }
?>"/>
</td>
</tr>

<tr class="movie" style="display:none">
<td class="Temp3"><a href="#" title="Place(s) where the movie clip was filmed">Filming Location(s):</a></td>
<td class="Temp4">
<input type=text size=50 name="template[movie][filmed]" value="<?php
  if (isset($template) && isset($template["movie"]['filmed'])) {
    echo htmlspecialchars($template["movie"]['filmed']);
  }
?>"/>
</td>
</tr>

<tr class="movie" style="display:none">
<td class="Temp3">URL for official movie web site:</td>
<td class="Temp4">
<input type=text size=50 name="template[movie][url]" value="<?php
  if (isset($template) && isset($template["movie"]['url'])) {
    echo htmlspecialchars($template["movie"]['url']);
  }
?>"/>
</td>
</tr>
<?php
}






function template_movies($template)
{
?>
<tr name=template>
<td class="Temp3">Movie Title:</td>
<td class="Temp4">
<input type=text size=50 name="template[movie][movie]" value="<?php
  if (isset($template) && isset($template["movie"]['movie'])) {
    echo htmlspecialchars($template["movie"]['movie']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3"><a href="#" title="Title of the movie clip scene">Scene:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[movie][scene]" value="<?php
  if (isset($template) && isset($template["movie"]['scene'])) {
    echo htmlspecialchars($template["movie"]['scene']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3">Director(s):</td>
<td class="Temp4">
<input type=text size=50 name="template[movie][director]" value="<?php
  if (isset($template) && isset($template["movie"]['director'])) {
    echo htmlspecialchars($template["movie"]['director']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3">Screenwriter(s):</td>
<td class="Temp4">
<input type=text size=50 name="template[movie][writers]" value="<?php
  if (isset($template) && isset($template["movie"]['writers'])) {
    echo htmlspecialchars($template["movie"]['writers']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3"><a href="#" title="Actors who appear in the movie clip">Cast:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[movie][cast]" value="<?php
  if (isset($template) && isset($template["movie"]['cast'])) {
    echo htmlspecialchars($template["movie"]['cast']);
  }
?>"/>
</td>
</tr>
<tr name=template>
<td class="Temp3">Country:</td>
<td class="Temp4">
<input type=text size=50 name="template[movie][country]" value="<?php
  if (isset($template) && isset($template["movie"]['country'])) {
    echo htmlspecialchars($template["movie"]['country']);
  }
?>"/>
</td>
</tr>
<tr name=template>
<td class="Temp3"><a href="#" title="Original language of the movie">Language:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[movie][language]" value="<?php
  if (isset($template) && isset($template["movie"]['language'])) {
    echo htmlspecialchars($template["movie"]['language']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3">Release Date:</td>
<td class="Temp4">
<input type=text size=50 name="template[movie][released]" value="<?php
  if (isset($template) && isset($template["movie"]['released'])) {
    echo htmlspecialchars($template["movie"]['released']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3"><a href="#" title="Technical specifications for the movie (run time, sound mix, color, aspect ratio, etc.)">Specifications:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[movie][specifications]" value="<?php
  if (isset($template) && isset($template["movie"]['specifications'])) {
    echo htmlspecialchars($template["movie"]['specifications']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3"><a href="#" title="The studio or production company responsible for producing the film">Studio/Production Co.:</a></td>
<td class="Temp4">
<input type=text size=50 name="template[movie][production]" value="<?php
  if (isset($template) && isset($template["movie"]['production'])) {
    echo htmlspecialchars($template["movie"]['production']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3"><a href="#" title="Genre of the movie (animation, action/adventure, comedy, etc.)">Genre(s):</a></td>
<td class="Temp4">
<input type=text size=50 name="template[movie][genres]" value="<?php
  if (isset($template) && isset($template["movie"]['genres'])) {
    echo htmlspecialchars($template["movie"]['genres']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3"><a href="#" title="Place(s) where the movie clip was filmed">Filming Location(s):</a></td>
<td class="Temp4">
<input type=text size=50 name="template[movie][filmed]" value="<?php
  if (isset($template) && isset($template["movie"]['filmed'])) {
    echo htmlspecialchars($template["movie"]['filmed']);
  }
?>"/>
</td>
</tr>

<tr name=template>
<td class="Temp3">URL for official movie web site:</td>
<td class="Temp4">
<input type=text size=50 name="template[movie][url]" value="<?php
  if (isset($template) && isset($template["movie"]['url'])) {
    echo htmlspecialchars($template["movie"]['url']);
  }
?>"/>
</td>
</tr>
<?php
}


function echo_template_movie($annotation_id)
{
  $query =
'select
annotation_id as "Annotation_id",
movie as "Movie Title",
scene as "Scene",
director as "Director",
writers as "Writers",
cast as "Cast",
country as "Country",
language as "Language",
released as "Release Date",
specifications as "Technical Specs",
production as "Production Co.",
genres as "Genres",
filmed as "Filming Location",
url as "URL Official Site"
  from template_movies
 where annotation_id = ' . DBnumber($annotation_id);

  echo_table($query);
}
?>
