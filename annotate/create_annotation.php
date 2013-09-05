<!DOCTYPE HTML>
<?php

$gPHPscript = __FILE__;
$dir = dirname(__FILE__);
require_once($dir . '/../include/boilerplate.php');
require_once($dir . '/../include/util.php');

if (mustlogon()) {
  /* This can happen if the frame is left idle a long time */
  return;
}

$mode = getpost('mode');
if (!isset($mode)) {
  $mode = 'y';
}
$clone_language = getpost('clone_language');

htmlHeader('Create Annotation');

require_once($dir . '/../include/alert.php');
require_once($dir . '/../include/db.php');
require_once($dir . '/../include/urls.php');
require_once($dir . '/../include/annotations.php');
require_once($dir . '/../include/insert_markup.php');
require_once($dir . '/../include/simple_view.php');
require_once($dir . '/../include/template.php');
require_once($dir . '/../include/tags.php');
srcStylesheet(
  '../css/style.css',
  '../css/iframe.css'
);
srcJavascript(
  '../js/base64.js',
  '../js/fullwidth.js',
  '../js/enterkey.js',
  '../js/edit.js',
  '../js/annotate.js',
  '../js/images.js',
  '../../tools/ckeditor/ckeditor.js',
  '../js/help.js'
//  '../js/usableforms.js'
);
enterJavascript();
?>

var annotation_id = null;

function reset_form()
{
  CKEDITOR.instances['editor1'].setData('');
}

function loaded()
{
  disableEnterKey('form');
  showImages();
  label_frame_button();
}

function form_submit()
{
  var form = document.getElementById('form');

      addhidden(form, 'hiddenvar', 'Y');

  form.submit();
}

function testjs(){
    var selectBox = document.getElementById("select_template");
    var selectedValue = selectBox.options[selectBox.selectedIndex].value;
 
   var templates = ["painting","architecture","artifact","book","ceramic","comic","graphic", "manuscript" , "movie", "photograph",  "sculpture"];

   for ( var i = 0 ; i <= templates.length; i++ ){ 

	if ( templates[i] === selectedValue) {
   		var elements = document.getElementsByClassName(templates[i]);
   		for ( var j = 0 ; j < elements.length; j++ ){
		   elements[j].style.display = (elements[j].style.display === 'none') ? 'block' : 'none';
		}
	
	}else{
	     var nonelements = document.getElementsByClassName(templates[i]);
	     for ( var k = 0 ; k < nonelements.length; k++ ){
		   nonelements[k].style.display = 'none';
	     }
	}	

 }	
  //return false;
}



function clickDuplicate()
{
  var mode = document.getElementById("mode");

  if (mode) {
    mode.value = 'd';
    /* We will start re-entry */
	form_submit();
} }

function create()
{
  if (checkEdit()) {
<?php
if ($mode == 't') {
  global $clone_language;

  echo '
    var language_code = document.getElementById("language_code");
    var language      = language_code.options[language_code.selectedIndex].value;
    if (language == "', $clone_language, '") {
	  customAlert(
		{ title:"Illegal translation",
		  body:"The translation is in the same language as the original document",
		  icon:"warn.png"
        } );
      return false;
    }
';
}
?>
    var form = document.getElementById('form');
	var image_data1 = sendImageData();
    if (image_data1) {
	  var data = JSON.stringify(image_data1);
      addhidden(form, 'dirtyImages', data);
    }
    addhidden(form, 'dirtyAnnotation','Y');
    return true;
  }
  return false;
}

// Stupid function used to avoid two commit buttons

function setDraft(value)
{
  if (create()) {
    var form = document.getElementById('form');
    addhidden(form, 'draft', value);
    addhidden(form, 'hiddenval', 'B');
   

   	


	form.submit();
  }
}

function preview_draft()
{
  setDraft('Y');
}

function preview_save()
{
  setDraft('S');
}

function preview_publish()
{
  setDraft('N');
}

function clickEdit(annotation_id )
{
  var form1 = document.getElementById('form1');
  addhidden(form1, 'annotation_id', annotation_id);
  form1.submit();
}

function clicksaveto( annotation_id ) {
	var form1 = document.getElementById('form1');
 	addhidden(form1,'annotation_id', annotation_id);
	addhidden(form1,'saveto', "yes");
	form1.submit();
}

<?php exitJavascript(); ?>
</head>
<body class="frame" onload="loaded()">
<div id="frame">
<?php

 //print_r($_POST);
 //var_dump($_GET);

if (!DBconnect()) {
  goto done;
}
$language_code = getpost('language_code');
$clone_id      = getpost('clone_id');
$clone_version = getpost('clone_version');
$template_code = getpost('select_template');
$template      = getpost('template');
$draft         = getpost('draft');
$title         = getpost('title');
$tags          = getpost('tags');
$content       = getpost('editor1');
$language_codes = getpost('language_codes');
if (!isset($template)) {
  $template = array();
}


if (!isset($paintingVals)){
  $paintingVals = array();
}


$dirtyAnnotation = getpost('dirtyAnnotation');
if (!isset($dirtyAnnotation)) {
  switch ($mode) {
  case 'd':	// duplicate
    if (!isset($clone_id)) {
	  if (isset($_SESSION['imageMAT_annotation_id'])) {
        $clone_id      = $_SESSION['imageMAT_annotation_id'];
        $clone_version = null;
      }  else {
        javascriptAlert(null, null, 'Cloning unspecified annotation', 'Error' );
        goto close;
    } }
    $row = read_extended_annotation($clone_id, $clone_version,'N');
    if (!isset($row)) {
      goto close;
    }
    foreach ($row as $colname => $value) {
      $$colname = $value;
    }
    $clone_version = $version;
    if (!isset($template)) {
      $template = array();
    }
  case 't':	// translate
    break;
  case 'y':
    // Passed in by bookmarklet
    $urls = getpost('urls');
    if (!isset($urls)) {
      $urls = array();
    }
    $htmls = getpost('htmls');
    if (!isset($htmls)) {
      $htmls = array();
    }
	$image_ids    = getpost('image_ids');
	$html_ids     = getpost('html_ids');
	$citation_ids = getpost('citation_ids');
    enterJavascript();
    echo 'top.image_data = 
{ images:
  [';
    $cnt = 0;
    $connector = '';
    foreach ($urls as $index => $value) {
      echo $connector, '
    { id:', $cnt;
      if (isset($value)) {
	    echo ',
      image_url:',json_encode($value);
      }
      if (isset($htmls[$index])) {
	    echo ',
      html_url:',json_encode($htmls[$index]);
      }
	  echo '
    }';
	  $connector = ',
';
	  ++$cnt;
    }
	if (isset($image_ids)) {
	  foreach ($image_ids as $value) {
		$url = get_url_string($value);
		if (!$url) {
		  continue;
		}
        echo $connector, '
    { id:', $cnt, ',
      image_url:',json_encode($url), '
    }';
	    $connector = ',
';
	    ++$cnt;
	} }
	if (isset($html_ids)) {
	  foreach ($html_ids as $value) {
		$url = get_url_string($value);
		if (!$url) {
		  continue;
		}
        echo $connector, '
    { id:', $cnt, ',
      html_url:',json_encode($url), '
    }';
	    $connector = ',
';
	    ++$cnt;
	} }
	if (isset($citation_ids)) {
	  foreach ($citation_ids as $value) {
        echo $connector, '
    { id:', $cnt, ',
      citation_id:',json_encode($value), '
    }';
	    $connector = ',
';
	    ++$cnt;
	} }
    echo '
  ]
};
';
    exitJavascript();
  }
  goto show;
} 
// Do the update
if (!isset($title) || !isset($content)) {
  if (isset($content)) {
    javascriptAlert(null, null, 'An annotation needs a title', 'Error');
  } else if (isset($title)) {
    javascriptAlert(null, null, 'An annotation needs content', 'Error');
  } else {
    javascriptAlert(null, null, 'An annotation needs a title and content', 'Error');
  }
  goto show;
}

$dirtyImages = getpost('dirtyImages');
if (isset($dirtyImages)) {
  $image_data = json_decode($dirtyImages);
  if (!isset($image_data)) {
    echo '<h3>Can\'t decode image data!!</h3><p>',
		 htmlspecialchars($dirtyImages);
} }

if (!isset($language_code)) {
  $language_code = 'eng';
}

switch ($draft) {
case 'Y':
case 'S':
  $draft1 = $draft;
  break;
default:
  $draft1 = null;
}

$query =
'insert into annotations(language_code, template_code,title,content,tags,version,draft,creator_user_id,created)
 values ('
 . DBstringC($language_code)
 . DBstringC($template_code)
 . DBstringC($title)
 . DBstringC($content)
 . DBstringC($tags)
 . '1,'
 . DBstringC($draft1)
 . DBstringC($gUserid)
 . 'utc_timestamp())';

$ret = DBquery($query);
if (!$ret) {
  goto close;
}

$annotation_id = DBid();

if ($annotation_id == 0) {
  javascriptAlert(null, null, 'Unable to create annotation','Error');
  goto close;
}
$_SESSION['imageMAT_annotation_id'] = $annotation_id;
if (isset($clone_id)) {
  switch ($mode) {
  case 'd':
    $query =
'insert into duplicates(annotation_id, was_annotation_id, was_version)
 values('
 . DBnumberC($annotation_id)
 . DBnumberC($clone_id)
 . DBnumber($clone_version)
 . ')';
    break;
  case 't':
    $query =
'insert into translates(annotation_id, was_annotation_id, was_version,was_language_code,language_code)
 values('
 . DBnumberC($annotation_id)
 . DBnumberC($clone_id)
 . DBnumberC($clone_version)
 . DBstringC($clone_language)
 . DBstring($language_code) . ')';
  }
  $ret = DBquery($query);
  if (!$ret) {
	goto close;
  }
}

$query =
'insert into fulltexts(annotation_id,ftitle,fcontent)
 values ('
 . DBnumberC($annotation_id)
 . DBstringC($title)
 . DBstring(plainText($content)) . ')';

$ret = DBquery($query);
if (!$ret) {
  goto close;
}
if (isset($tags)) {
  insert_tags($tags, $annotation_id, 'annotation_id', 'tags');
}
insert_annotationslanguages($annotation_id, $language_codes);

if ($template_code != '') {
  insert_template($annotation_id, $template_code, $template);
}
if (isset($image_data)) {
  $ret = insert_images($annotation_id, $image_data);
  if (!$ret) {
    goto close;
  }
  echo '
<h3>Created Annotation and Images</h3>';
} else {
  echo '
<h3>Created Annotation</h3>';
}

showSimpleView($annotation_id, null, 'N');
echo '
<p>
<form id=form1 method="post" action=annotate.php target=_top>

<input type="hidden" name= "template_code" value ='.$template_code.' > 

<input type="button" value="Edit" onclick="clickEdit(',$annotation_id, ')" /> ';
if ( $draft== 'N') {
echo '
<input type="button" value="Save to Project" onclick="clicksaveto(',$annotation_id,')" />';
}
echo '
<input type="submit" value="Create Another Annotation" />
</form>';

/*echo '
<p>
<form id=form2 method="post" action=../project/save_to_project.php target=_top>
<input type="button" value="Save to project" onclick="save_to_project(',$annotation_id,')" />
</form>';
*/

goto close;

show:

if (isset($_SESSION['imageMAT_language_code2'])) {
  $mylanguage = $_SESSION['imageMAT_language_code2'];
} else {
  $mylanguage = '';
}
require_once($dir . '/../include/language.php');
?>
<form id=form name=form action="create_annotation.php" method="post">
<?php
hidden('mode');
hidden('clone_id');
hidden('clone_version');
hidden('clone_language');
?>
<table class="iframetable">
<?php
switch ($mode) {
case 'd':
  echo '
<tr>
<td>DUPLICATE</td>
<td align=right>DUPLICATE</td>
</tr>';
  break;
case 't':
  echo '
<tr>
<td>TRANSLATE</td>
<td align=right>TRANSLATE</td>
</tr>
<tr>
<td align=right>Annotation:</td>
<td>', $clone_id, ' Version ', $clone_version, ' from ', $clone_language, '
</td>
</tr>';
  break;
}
?>
<tr>
<td class="Lang1"><a href="#" title="Language in which the annotation is to be written or translated" onclick="return clickLanguage()">Language:</a></td>
<td class="Lang2"><?php annotation_language_code($language_code, false); ?></td>
</tr>
<tr>
<td class="Title1"><a href="#" title="Each annotation must have a unique title" onclick="return clickTitle()">Title:</a></td>
<td class="Title2"><input type="text" id="title" name="title" size="50" maxlength="255" value="<?php echo htmlspecialchars($title); ?>" />
</td>
</tr>
<tr>
<td class="Tags1"><a href="#" title="Tags, or keywords, help categorize your work" onclick="return clickTags()">Tags:</a></td>
<td class="Tags2"><input type="text" name="tags" size="50" maxlength="255" value="<?php echo htmlspecialchars($tags); ?>" />
</td>
</tr>
<!-- <tr> 
 <td align=right><a href="#" title="Choose a template that identifies your image" onclick="return clickTemplate()">Template:</a></td> 
-->
<td>

<?php
// select_template($template_code);
// if (isset($template_code)) {
//   echo '&nbsp;<input id=flipTemplate type=button value="Hide" onclick="flipTemplateVisible()" />';
//}
?>
</td>

</tr>
</table>
<table class="iframetable2">
<tr>
<td class="Temp1"><label for="select_template">Template</label></td>
	
	 <td class="Temp2">
                        <select name="select_template" id="select_template" onChange="testjs()"> 
				<option rel="none"></option>
                                <option rel="none" value="painting"> Painting </option>
                                <option rel="none" value="architecture"> Architecture </option>
                                <option rel="none" value="artifact"> Artifact</option>
                                <option rel="none" value="book"> Book </option>
                                <option rel="none" value="ceramic"> Ceramic</option>
                                <option rel="none" value="comic"> Comic </option>
	                        <option rel="none" value="movie"> Movie </option>
	                        <option rel="none" value="graphic"> Graphic </option>
	                        <option rel="none" value="manuscript"> Manuscript </option>
	                        <option rel="none" value="photograph"> Photograph </option>
	                        <option rel="none" value="sculpture"> Sculpture </option>

                        </select>
                </td>

	</tr>

	<?php 
	   // trying to tackle the template/image refresh problem
	    // Using javascript to change display value  to "block"  (css)
	   // for fields that belong to the "template" class
	   // down side is that the template array is bloated into having 
	   // fields as array elements

 	    require_once('../include/template/architecture.php');
	    require_once('../include/template/painting.php');
 	    require_once('../include/template/artifact.php');
 	    require_once('../include/template/book.php');
 	    require_once('../include/template/ceramic.php');
 	    require_once('../include/template/comic.php');
 	    require_once('../include/template/graphic.php');
 	    require_once('../include/template/manuscript.php');
 	    require_once('../include/template/movie.php');
 	    require_once('../include/template/photograph.php');
 	    require_once('../include/template/sculpture.php');
	
	 	echo print_painting_template($template) ;
		echo print_architecture_template($template) ;
	 	echo print_artifact_template($template) ;
	 	echo print_book_template($template) ;
	 	echo print_ceramic_template($template) ;
	 	echo print_comic_template($template) ;
	 	echo print_graphic_template($template) ;
	 	echo print_manuscript_template($template) ;
	 	echo print_movie_template($template) ;
	 	echo print_photograph_template($template) ;
	 	echo print_sculpture_template($template) ;

	?>
</table>
<table class="iframetable3">
<tr>
<td class="Editor">
<textarea id="editor1" name="editor1" width="100%" rows="6"><?php echo htmlspecialchars($content); ?></textarea>
<?php enterJavascript(); ?>
createEditor('editor1', true, '<?php echo $mylanguage; ?>', false);
<?php exitJavascript(); ?>
</td>
</tr>
<!--<tr>
<td>
<a href="#" title="Choose all languages included in the annotation text" onclick="return clickRelLang()">Relevant</a><br>to:
</td>
<td><?php select_multiple_languages($language_codes, 6); ?></td>
</tr>-->
<tr>
<td class="Buttons">
<input type="button" value="Save" title="Saves your work to your personal directory" onclick="setDraft('S')"/>
<input type="button" value="Publish" onclick="setDraft('N')" />
<input type="button" value="Preview" title="View work in annotation form before saving" onclick='preview()' />
<input type=reset value="Restart" title="Clear work and begin annotation again" onclick="reset_form()" />
<?php
if ($mode != 't' && isset($_SESSION['imageMAT_annotation_id'])) {
  echo '
<input type=button value="Duplicate" onclick="clickDuplicate()"/>';
}
?>
<!--<input type="button" id="fullWidthButton" style="display:none" value="Full Frame" title="Annotation frame expands across interface" onclick="toggle_frames()" /> onclick="setDraft('S')"/>-->
</td>
</tr>
</table>
</form>

<?php
if (($mode != 't')  && ($mode != 'x')){  
 echo ' <form id="load_left" name="load_left" method="post" target="imageFrame" action="images.php"> 
  <input type=hidden name=loaded value=y /> 
  </form>'; 
 }

close:
DBclose();
done:
bodyFooter();
?>

</div>
</body>
</html>
