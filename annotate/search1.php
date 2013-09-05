<!DOCTYPE HTML>
<?php

$gPHPscript = __FILE__;
$dir = dirname(__FILE__);
require_once($dir . '/../include/boilerplate.php');
require_once($dir . '/../include/util.php');

if (mustlogon()) {
  return;
}

$folder_info   = getparameter('folder_info');
$addAnnotate   = getparameter('addAnnotate');

require_once($dir . '/../include/db.php');
require_once($dir . '/../include/annotations.php');
require_once($dir . '/../include/urls.php');
require_once($dir . '/../include/template.php');
require_once($dir . '/../include/language.php');
require_once($dir . '/../include/tags.php');

htmlHeader('Search Annotations');
srcStylesheet(
  '../css/style.css',
  '../css/alert.css',
  '../css/search.css'
);
srcJavascript(
  '../js/sortable.js',
  '../js/alert.js',
  '../js/fullwidth.js'
);

function topType()
{
  global $addAnnotate;

  echo (isset($addAnnotate) ? 'ref' : 'set');
}

enterJavascript();
?>

function getTopSetLength()
{
  return top.<?php topType(); ?>Annotations_lth;
}

function setTopSetLength(lth)
{
  top.<?php topType(); ?>Annotations_lth = lth;
}

function getTopSet()
{
  return top.<?php topType(); ?>Annotations;
}

function clearTopSet()
{
  top.<?php topType(); ?>Annotations = new Array();
  top.<?php topType(); ?>Annotations_lth = 0;
}

function changedTopSet()
{
<?php
if (!isset($addAnnotate)) {
  echo  '  top.setAnnotations_changed = true';
}
?>
}

function template_change()
{ 
  // alert("template change");
  cludge = document.getElementById("cludge");
  cludge.setAttribute("disabled","");
  var form = document.getElementById('form');
  form.submit();
}

function setAnnotations_state_changed()
{
  var setAnnotations_lth = getTopSetLength();
  var button;

  button  = document.getElementById('clearall_button');
  if (button != undefined) {
    button.disabled = ((setAnnotations_lth == 0) ? true : undefined);
  }
  button  = document.getElementById('clear_button');
  if (button != undefined) {
    button.disabled = ((window.checkboxes_set_cnt == 0) ? true : undefined);
  }
  button  = document.getElementById('add_folder_button');
  if (button != undefined) {
    button.disabled = ((setAnnotations_lth == 0) ? true : undefined);
  }
  button  = document.getElementById('set_button');
  if (button != undefined) {
    button.disabled = (window.checkboxes_set_cnt == window.checkboxes_cnt) ? true : undefined;
  }
}

function show_setAnnotations(prefix)
{
/*
  var setAnnotations = getTopSet();
  var lth  = ((setAnnotations == null) ? -1 : setAnnotations.length);
  var msg;
  var i;

  msg = prefix + '(' + getTopSetLength() + '/' + lth + ')';
  for (i = 0; i < lth; ++i) {
    msg += ' ' + setAnnotations[i];
  }
  alert(msg);
*/
}

function setAnnotations_changed1(checkbox)
{
  var setAnnotations = getTopSet();
  var lth  = getTopSetLength();
  var id   = parseInt(checkbox.value);
  var i;

  if (checkbox.checked) {
    if (setAnnotations == null) {
	  clearTopSet();
	  setAnnotations = getTopSet();
    } else {
	  for (i = 0; i < lth; ++i ) {
        if (setAnnotations[i] == id) {
          return;
    } } }
    setAnnotations[lth++] = id;
    setTopSetLength(lth);
	++window.checkboxes_set_cnt;
  } else {
    for (i = 0; ; ++i) {
      if (i == lth) {
	    return;
      }
      if (setAnnotations[i] == id) {
        break;
	} }
    setAnnotations[i] = setAnnotations[--lth];
	setTopSetLength(lth);
    --window.checkboxes_set_cnt;
  }
  changedTopSet();
  // show_setAnnotations('');
}

function clearall_setAnnotations()
{
  var checkboxes = document.getElementsByName('edit');
  var lth        = checkboxes.length;
  var i;

  for (i = 0; i < lth; ++i) {
	checkboxes[i].checked = false;
  }
  if (getTopSetLength() > 0) {
    setTopSetLength(0);
    changedTopSet();
  }
  window.checkboxes_set_cnt = 0;
  setAnnotations_state_changed();
  //show_setAnnotations('clearall ');
}

function setAnnotations_changed(checkbox)
{
  setAnnotations_changed1(checkbox);
  setAnnotations_state_changed();
  //show_setAnnotations('setAnnotations_changed ');
}

function clear_setAnnotations()
{
  var checkboxes = document.getElementsByName('edit');
  var lth        = checkboxes.length;
  var checkbox;
  var i;

  for (i = 0; i < lth; ++i) {
    checkbox = checkboxes[i];
	if (checkbox.checked) {
      checkbox.checked = false;
      setAnnotations_changed1(checkbox);
  } }
  setAnnotations_state_changed();
  //show_setAnnotations('clear_setAnnotations ');
}

function set_setAnnotations()
{
  var checkboxes = document.getElementsByName('edit');
  var checkbox;
  var i;

  for (i = 0; i < checkboxes.length; ++i) {
    checkbox = checkboxes[i];
	if (!checkbox.checked) {
      checkbox.checked = true;
      setAnnotations_changed1(checkbox);
  } }
  setAnnotations_state_changed();
  //show_setAnnotations('set setAnnotations ');
}

function init_checkboxes()
{
  var checkboxes = document.getElementsByName('edit');
  var lth        = checkboxes.length;
  var setAnnotations = getTopSet();
  var lth1           = getTopSetLength();
  var i, j, id;

  for (i = 0; i < lth; ++i) {
    checkbox = checkboxes[i];
    id       = parseInt(checkbox.value);
    for (j = 0; j < lth1; ++j) {
      if (setAnnotations[j] == id) {
        checkbox.checked = true;
		++window.checkboxes_set_cnt;
        break;
  } } }
  setAnnotations_state_changed();
}

function clickAddToFolder(folderInfo)
{
  if (parent != self) {
	var lth  = getTopSetLength();
    if (lth > 0) {
	  var msg;
	  if (lth == 1) {
		var setAnnotations = getTopSet();
        msg = 'Annotation ' + setAnnotations[0];
	  } else {
		msg += ' annotations';
	  }
	  top.annotationIdsToServer();
      /* TODO make safer - can't in general assume frames[0] is right */
	  parent.frames[0].add_annotations_callback(folderInfo);
      clearall_setAnnotations();
      customAlert(
        { title:'Added annotations',
        icon:'help.png',
        body:msg + ' added to folder'
      } );
  } }
}

function cancelAddAnnotate()
{
  var left  = top.frames[0];
  var extra = top.frames[2];

  left                = left.frameElement;
  extra               = extra.frameElement;
  extra.style.height  = '0px';
  extra.style.display = 'none';
  left.style.display  = '';
}

function clickAddToAnnotate()
{
  cancelAddAnnotate();
  var left = top.frames[0];
  left.addAnnotations();
}

function loaded()
{
  init_checkboxes();
  label_frame_button();
}

<?php exitJavascript(); ?>
</head>
<body onload="loaded()" id="searchTable">

<?php

$template_code = getpost('template_code');
$mode          = getpost('mode');
$setMode       = getpost('setMode');
$list          = getparameter('list');
$setAnnotations = getSetAnnotations();

if (isset($list)) {
  if (isset($setAnnotations)) {
    $mode    = 'y';
    $setMode = 'and';
  } else {
	echo '<h3>Nothing to List</h3>';
} }

if (isset($mode)) {

  if (!DBconnect()) {
    goto done;
  }

  $min_annotation_id = getparameter('min_annotation_id');
  $max_annotation_id = getparameter('max_annotation_id');
  $draft         = getpost('draft');
  $min_version   = getpost('min_version');
  $max_version   = getpost('max_version');
  $version_mode  = getpost('version_mode');
  $title         = getpost('title');
  $title_mode    = getpost('title_mode');
  $add_content   = getpost('add_content');
  $tags          = getpost('tags');
  $tags_mode     = getpost('tags_mode');
  $template      = getpost('template');
  $fcontent      = getpost('fcontent');
  $fcontent_mode = getpost('fcontent_mode');
  $language_code = getpost('language_code');
  $language_codes= getpost('language_codes');
  $creator_user_id = getpost('creator_user_id');
  $min_created   = getpost('min_created');
  $max_created   = getpost('max_created');
  $modifier_user_id = getpost('modifier_user_id');
  $min_modified  = getpost('min_modified');
  $max_modified  = getpost('max_modified');
  $image_url     = getpost('image_url');
  $image_url_mode= getpost('image_url_mode');
  $html_url      = getpost('html_url');
  $html_url_mode = getpost('html_url_mode');
  $folder        = getpost('folder');
  $folder_mode   = getpost('folder_mode');
  $order         = getpost('order');
  $desc          = getpost('desc');
  $folder_info   = getpost('folder_info');
  $addAnnotate   = getpost('addAnnotate');
 
  $constraints = array();

  if (!isset($version_mode)) {
    $version_mode = 'last';
  }
  if ($version_mode == 'last') {
    $query =
'select annotations.annotation_id, version, draft, annotation_deleted,
        template_code, annotations.title,
       creator_user_id, date(created) as created,
       modifier_user_id, date(modified) as modified
  from annotations';
  } else {
    $query =
'select status, annotations.annotation_id, version, draft, annotation_deleted,
	    template_code, annotations.title,
        creator_user_id, created,
        modifier_user_id, modified
  from (select 0 as status,annotation_id, version, draft, annotation_deleted,
               template_code, title,
               creator_user_id,  date(created) as created,
               modifier_user_id, date(modified) as modified
          from annotations
         union all
        select 1 as status, a1.annotation_id, a1.version, a1.draft, a2.annotation_deleted,
               a1.template_code, a1.title,
               a2.creator_user_id,  date(a2.created) as created,
               a1.modifier_user_id, date(a1.modified) as modified
          from annotations_history a1,
               annotations a2
         where a2.annotation_id = a1.annotation_id
       ) annotations';
    if ($version_mode == 'first') {
      $constraints[] = 'annotations.version = (
       select min(version)
         from (select annotation_id,version
                 from annotations annotations1
                union all 
               select annotation_id,version
                 from annotations_history history1
              ) version
        where annotations.annotation_id = version.annotation_id
       )';
    }
  }

  if (!isset($draft)) {
	$constraints[] = 'annotation_deleted is null';
    $constraints[] = '(draft is null or coalesce(modifier_user_id, creator_user_id) = ' . DBstring($gUserid) . ')';
  } else if ($draft == 'X') {
	$constraints[] = '(annotation_deleted is not null and (modifier_user_id = ' . DBstring($gUserid) . ' or creator_user_id = ' . DBstring($gUserid) . '))';
  } else {
	$constraints[] = 'annotation_deleted is null';
	if ($draft == 'N') {
      $constraints[] = 'draft is null';
    } else {
      $constraints[] = 'draft = ' . DBstring($draft);
      $constraints[] = 'coalesce(modifier_user_id, creator_user_id) = ' . DBstring($gUserid);
  } }

  if (isset($language_code)) {
    if (!is_array($language_code)) {
      $language_code = array($language_code);
    }
    $cnt =  count($language_code);
    switch ($cnt) {
    case 0:
      break;
    case 1:
      $constraints[] = 'language_code = ' . DBstring($language_code[0]);
      break;
    default:
      $term = 'language_code in (';
      $comma = '';
      foreach($language_code as $code) {
        $term .= $comma . DBstring($code);
        $comma = ',';
      }
      $term .= ')';
      $constraints[] = $term;
  } }
  if (isset($min_version) || isset($max_version)) {
    if (!isset($max_version)) {
      $constraints[] = DBnumber($min_version) . '<= version';
    } else if (!isset($min_version)) {
      $constraints[] = 'version <= ' . DBnumber($max_version);
    } else if ($min_version == $max_version) {
      $constraints[] = 'version = ' . DBnumber($min_version);
    } else {
      $constraints[] = 'version between ' . DBnumber($min_version) . ' and ' . DBnumber($max_version);
  } }

  if (isset($fcontent) || (isset($title) && (isset($title_mode) && $title_mode > 1) || isset($add_content))) {
    if ($version_mode == 'last') {
      $query .= ', fulltexts';
    } else {
      $query .= ',
(select annotation_id, fcontent
  from (select annotation_id, fcontent
          from fulltexts
         union all
        select annotation_id, fcontent
          from fulltexts_history
       ) fulltexts
) fulltexts';
    }
    $constraints[] = 'annotations.annotation_id = fulltexts.annotation_id';
  }

  if (isset($min_annotation_id)) {
    if (!isset($max_annotation_id)) {
      $constraints[] = 'annotations.annotation_id = ' . DBnumber($min_annotation_id);
    } else {
      $constraints[] = 'annotations.annotation_id between ' . DBnumber($min_annotation_id) . ' and ' . DBnumber($max_annotation_id);
    }
  } else {
    if (isset($max_annotation_id)) {
      $constraints[] = 'annotations.annotation_id <= ' . DBnumber($max_annotation_id);
  } }


  if (!isset($setMode)) {
	if (!isset($addAnnotate)) {
      $_SESSION['imageMAT_setAnnotations'] = null;
	}
    enterJavascript();
    echo '
setTopSetLength(0);
';
	exitJavascript();
  } else if ($setMode == 'and') {
    $constraints[] = 'annotations.annotation_id in (' . implode(',',$setAnnotations) . ')';
  } 

  if (isset($template_code) && $template_code != '') {
    $constraints[] = 'template_code = ' . DBstring($template_code);
    if (isset($template) && count($template) > 0) {
      $first = true;
      foreach ($template as $name => $value) {
        if ($value != '') {
          if ($first) {
            $template_table = 'template_' . $template_code . 's';
            $query .= ' left join ' . $template_table . '
    on annotations.annotation_id = ' . $template_table . '.' . 'annotation_id';
            $first = false;
          }
          $constraints[] = $template_table . '.' . $name . ' like \'%' . DBnumber($value) . '%\'';
  } } } }

  if (isset($title)) {
    if (!isset($title_mode)) {
      $title_mode = 0;
    }
    switch ($title_mode) {
    case 0:
      $term = 'annotations.title like \'%' . DBnumber($title) . '%\'';
      if ($add_content) {
        $term = '(' . $term . ' or fcontent like \'%' . DBnumber($title) . '%\')';
      }
      break;
    case 1:
      $term = 'annotations.title = ' . DBstring($title);
      if ($add_content) {
        $term = '(' . $term . ' or fcontent = ' . DBstring($title) . ')';
      }
      break;
    default:
      if ($add_content) {
        $term = 'match (ftitle,fcontent) against (' . DBstring($title);
      } else {
        $term = 'match (ftitle) against (' . DBstring($title) . '';
      }
      switch ($title_mode) {
      case 3:
        $term .= ' IN BOOLEAN MODE)';
        break;
      case 4:
        $term .= ' WITH QUERY EXPANSION)';
        break;
      default:
        $term .= ' IN NATURAL LANGUAGE MODE)';
        break;
    } }
    $constraints[] = $term;
  }
  if (isset($fcontent)) {
    if (!isset($fcontent_mode)) {
      $fcontent_mode = 0;
    }
    switch ($fcontent_mode) {
    case 0:
      $term = 'fcontent like \'%' . DBnumber($fcontent) . '%\'';
      break;
    case 1:
      $term = 'fcontent = ' . DBstring($fcontent);
      break;
    default:
      $term = 'match (fcontent) against (' . DBstring($fcontent);
      switch ($fcontent_mode) {
      case 3:
        $term .= ' IN BOOLEAN MODE)';
        break;
      case 4:
        $term .= ' WITH QUERY EXPANSION)';
        break;
      default:
        $term .= ' IN NATURAL LANGUAGE MODE)';
        break;
    } }
    $constraints[] = $term;
  }
  if (isset($creator_user_id)) {
    $constraints[] = 'annotations.creator_user_id = ' . DBstring($creator_user_id);
  }
  if (isset($modifier_user_id)) {
    $constraints[] = 'annotations.modifier_user_id = ' . DBstring($modifier_user_id);
  }
  if (isset($language_codes)) {
    if (!is_array($language_codes)) {
      $language_codes = array($language_codes);
    }
    $cnt =  count($language_codes);
    if ($cnt > 0) {
      $term = '(exists (
       select null from annotationslanguages
        where annotationslanguages.annotation_id = annotations.annotation_id
          and annotationslanguages.language_code ';
      switch ($cnt) {
      case 1:
        $term .= '= ' 
 . DBstring($language_codes[0]) . '
       )';
        break;
      default:
        $term .= 'in (';
        $comma = '';
        foreach($language_codes as $code) {
          $term .= $comma . DBstring($code);
          $comma = ',';
        }
        $term .= ')
       )';
      }
      $constraints[] = $term . '
    or not exists (
       select null from annotationslanguages
        where annotationslanguages.annotation_id = annotations.annotation_id
    )
   )';
      
  } }

  if (isset($image_url)) {
    $term = 'exists (
       select null from annotationsofurls,urls
        where annotationsofurls.annotation_id = annotations.annotation_id
          and annotationsofurls.image_url_id  = urls.url_id
          and urls.url ';
    
	if (isset($image_url_mode)) {
      $term .= 'like \'';
	  if ($image_url_mode != 3) {
		$term .= '%';
	  }
	  $term .= DBnumber($image_url);
	  if ($image_url_mode > 1) {
		$term .= '%';
	  }
	  $term .= '\'';
	} else {
	  $term .= '= ' . DBstring(canonical_url($image_url));
	}
	$term .= '
       )';

	$constraints[] = $term;
  }
  if (isset($html_url)) {
    $term = 'exists (
       select null from annotationsofurls,urls
        where annotationsofurls.annotation_id = annotations.annotation_id
          and annotationsofurls.html_url_id   = urls.url_id
          and urls.url ';
	if (isset($html_url_mode)) {
	  $term .= 'like \'';
	  if ($html_url_mode != 3) {
	    $term .= '%';
	  }
	  $term .= DBnumber($html_url);
	  if ($html_url_mode > 1) {
		$term .= '%';
	  }
	  $term .= '\'';
	} else {
	  $term .= '= ' . DBstring(canonical_url($html_url));
	}
	$term .= '
       )';

	$constraints[] = $term;
  }
  if (isset($folder)) {
require_once($dir . '/../include/folders.php');
    $folder_id = find_folder_id(null, $folder, false);
	if (!$folder_id) {
	  javascriptAlert('Folder not found', 'warn.gif', 
		'Folder ' . htmlspecialchars($folder) . ' does not exist', null);
	  $term = null;
	} else {
      $term = 'exists (
       select null
         from foldersannotations
        where foldersannotations.annotation_id = annotations.annotation_id
          and folder_id ';
	  if ($folder_mode == 2) {
        $term .= '= ' . $folder_id;
	  } else {
require_once($dir . '/../include/reachable.php');
		if ($folder_mode == 1) {
          $folder_id = reachable($folder_id, 2);
		} else {
          $folder_id = descendants($folder_id, 2);
		}
		if (!$folder_id) {
          $term = null;
		} else {
    	  $cnt = count($folder_id);
    	  $folder_id = implode(',', $folder_id);
    	  if ($cnt == 1) {
      		$term .= '= ' . $folder_id;
    	  } else {
      		$term .= 'in (' . $folder_id . ')';
        }  }
	    // TODO visibility issues
	  }
	}
	if (isset($term)) {
      $term .= ')';
	  $constraints[] = $term;
  } }

  if (isset($min_created) || isset($max_created)) {
    if (!isset($max_created)) {
      $constraints[] = DBdate($min_created) . '<= created';
    } else if (!isset($min_created)) {
      $constraints[] = 'created <= ' . DBdate($max_created);
    } else if ($min_created == $max_created) {
      $constraints[] = 'created = ' . DBdate($max_created);
    } else {
      $constraints[] = 'created between ' . DBdate($min_created) . ' and ' . DBdate($max_created);
  } }

  if (isset($min_modified) || isset($max_modified)) {
    if (!isset($max_modified)) {
      $constraints[] = DBdate($min_created) . '<= modified';
    } else if (!isset($min_modified)) {
      $constraints[] = 'modified <= ' . DBdate($max_modified);
    } else if ($min_modified == $max_modified) {
      $constraints[] = 'modified = ' . DBdate($max_modified);
    } else {
      $constraints[] = 'modified between ' . DBdate($min_modified) . ' and ' . DBdate($max_modified);
  } }

  if (isset($tags)) {
    $a = explode_tags($tags);
    if (isset($a)) {
      if ($tags_mode == 'any' || count($a) < 2) {
        $tags1 = null;
        foreach ($a as $term) {
          if (isset($tags1)) {
            $tags1 .= ' or ';
          } else {
            $tags1  = '(';
          }
          if (!isset($term['n'])) {
            $tags1 .= 'value = ' . DBstring($term['v']);
          } else if (!isset($term['v'])) {
            $tags1 .= 'name = ' . DBstring($term['n']);
          } else {
            $tags1 .= '(name = ' . DBstring($term['n']) . ' and value = ' . DBstring($term['v']) . ')';
        } }
        if (isset($tags1)) {
          $query .= ',
      (select distinct annotation_id
         from tags
        where ' . $tags1 . ') 
       ) tags';
          $constraints[] = 'annotations.annotation_id = tags.annotation_id';
        }
      } else {
		// Perform relational division
 		// A / B = Proj(A-B)(A) - Proj(A-B)((Proj(A-B)(A) X B) - A)
        // A is column of annotation ids
		// My approach exploits the tags indices
        // Basically we group by annotation_id and check that count()
		// has the same cardinality as the set we insist must all be present

        $tags1 = null;  // n=v
        $tags2 = null;	// n=
        $tags3 = null;	// v
        $cnt1  = 0;
        $cnt2  = 0;
        $cnt3  = 0;
        foreach ($a as $term) {
          if (!isset($term['n'])) {
            ++$cnt3;
            if (isset($tags3)) {
              $tags3 .= ' union all ';
            } else {
              $tags3 = '
             (';
            }
            $tags3 .= '
              select ' . DBstring($term['v']) . ' v';
          } else if (!isset($term['v'])) {
            ++$cnt2;
            if (isset($tags2)) {
              $tags2 .= ' union all ';
            } else {
              $tags2 = '
             (';
            }
            $tags2 .= '
              select ' . DBstring($term['n']) . ' n';
          } else {
            ++$cnt1;
            if (isset($tags1)) {
              $tags1 .= ' union all ';
            } else {
              $tags1 = '
              (';
            }
            $tags1 .= '
               select ' . DBstring($term['n']) . ' n,' . DBstring($term['v']) . ' v';
          }
        }
        if (isset($tags1)) {
          $tags1 .= '
              ) tags1';
          $query .= ',
       (select annotation_id
          from tags, ' . $tags1 . '
         where tags.name  = tags1.n
           and tags.value = tags1.v
         group by annotation_id
        having count(*) = ' . DBnumber($cnt1) . '
       ) annotations1';
          $constraints[] = 'annotations.annotation_id = annotations1.annotation_id';
        }
        if (isset($tags2)) {
          $tags2 .= '
             ) tags2';
          $query .= ',
      (select annotation_id
        from (select distinct annotation_id, name
                from tags
             ) tags, ' . $tags2 . '
         where tags.name = tags2.n
         group by annotation_id
        having count(*) = ' . DBnumber($cnt2) . '
       ) annotations2';
          $constraints[] = 'annotations.annotation_id = annotations2.annotation_id';
        }
        if (isset($tags3)) {
          $tags3 .= '
             ) tags3';
          $query .= ',
      (select annotation_id
        from (select distinct annotation_id, value
                from tags
             ) tags, ' . $tags3 . '
        where tags.value = tags3.v
        group by annotation_id
       having count(*) = ' . DBnumber($cnt3) . '
      ) annotations3';
          $constraints[] = 'annotations.annotation_id = annotations3.annotation_id';
        }
      } // all
    } // isset($a)
  }	// isset($tags)
  $or = '
  where ';
  if (count($constraints) > 0) {
    $query .= '
 where (' . implode ('
   and ', $constraints) . ')';
   $or = '
    or ';
  }
  if (isset($setMode)) {
    if ($setMode == 'or') {
      $query .= $or . 'annotations.annotation_id in (' . implode(',',$setAnnotations) . ')';
  } }

  $totalHits     = getpost('totalHits');
  if (!isset($totalHits)) {
    $ret = DBquery(
'select count(*) as totalHits
  from (
' . $query . '
) hits');
    if (!$ret) {
      goto close;
    }
	$row = DBfetch($ret);
    $totalHits = $row['totalHits'];
  }

  if (isset($order)) {
    $query .= '
 order by ' . $order;
    if (isset($desc)) {
      $query .= ' desc';
    }
    if ($order != 'version') {
      if (isset($min_version) || isset($max_version)) {
        $query .= ', version desc';
    } }
    if ($order != 'annotation_id') {
      $query .= ', annotation_id';
  } }

  $at = getpost('at');
  if (!isset($at)) {
    $at = 0;
  } else {
    $at = intval($at);
    if ($at < 0) {
      $at = 0;
  } }

  $page = getpost('page');
  if (!isset($page)) {
    $page = 100;
  } else {
    $page = intval($page);
    if ($page < 1) {
      $page = 100;
    } else if ($page > 999) {
      $page = 999;
  } }

  $next = getpost('next');
  if (isset($next)) {
    $at += $page;
  } else {
    $prev = getpost('prev');
    if (isset($prev)) {
      $at -= $page;
      if ($at < 0) {
        $at = 0;
  } } }

  $query .= '
 limit ' . $page;
  if ($at != 0) {
    $query .= ' offset ' . $at;
  }

  require_once($dir . '/../include/permissions.php');
  require_once($dir . '/../include/groups.php');
  if ($gUserid == 'ijdavis') {
    echo '
<h3>SQL Query</h3>
<pre>', htmlspecialchars($query), '
</pre>';
  }

  echo '
<h3>', htmlspecialchars($totalHits), ' Results</h3>';

  $ret = DBquery($query);
  if (!$ret) {
    goto close;
  }
  echo '
<form id="page2" name="page2" action="search1.php" method="post" target="_self">
<input id=cludge type=hidden name=mode value=y />';
  hidden('min_annotation_id');
  hidden('max_annotation_id');
  hidden('user');
  hidden('draft');
  hidden('setMode');
  hidden('min_version');
  hidden('max_version');
  hidden('version_mode');
  hidden('title');
  hidden('title_mode');
  hidden('add_content');
  hidden('tags');
  hidden('tags_mode');
  hidden('template_code');
  hidden('language_codes');
  hidden('fcontent');
  hidden('fcontent_mode');
  hidden('creator_user_id');
  hidden('min_created');
  hidden('max_created');
  hidden('modifier_user_id');
  hidden('min_modified');
  hidden('max_modified');
  hidden('image_url');
  hidden('image_url_mode');
  hidden('html_url');
  hidden('html_url_mode');
  hidden('folder');
  hidden('folder_mode');
  hidden('order');
  hidden('desc');
  hidden('page');
  hidden('at');
  hidden('template');
  hidden('folder_info');
  hidden('addAnnotate');
  hidden('totalHits');

  echo '
<table id="searchTable" class="sortable">
<tr><th class="unsortable"></th><th class="startsort">Id</th><th>Version</th><th>Type</th><th>Title</th><th>Creator</th><th>Created</th><th>Modifier</th><th>Modified</th></tr>';

  $cnt1 = 0;
  $checkboxes_cnt     = 0;
  for ($cnt = 0; $row = DBfetch($ret); ++$cnt) {
    $visible = may_read_annotation($row);
    if (!isset($visible)) {
      goto close;
    }
    if ($visible == 0) {
      continue;
    }
    ++$cnt1;
    $id = $row['annotation_id'];
	echo '
<tr>
<td>';
	if ($visible > 1) {
      echo '<input type=checkbox name="edit" value=', $id, ' onclick="setAnnotations_changed(this)"/>';
      ++$checkboxes_cnt;
    }

    $id1 = $id;
	if (isset($row['annotation_deleted'])) {
	  $id1 = '<font color=fuchsia>' . $id . '</font>';
    } else if ($version_mode != 'last') {
      switch ($row['status']) {
      case 0:
        $id1 = '<font color=blue>' . $id . '</font>';
        break;
      case 1:
        $id1 = '<font color=orange>' . $id . '</font>';
    } }
      
    echo '
</td>
<td class="tdSearch1">', $id1,'</td>
<td class="tdSearch2">', htmlspecialchars($row['version']),'</td>';
    echo '
<td class="tdSearch3">', htmlspecialchars($row['template_code']), '</td>
<td class="tdSearch4">';
    if ($visible > 1) {
      // Can edit
      echo '<a href="annotate.php?annotation_id=',$id,'" target=_top>';
    }
    echo htmlspecialchars($row['title']);
    if ($visible > 1) {
      echo '</a>';
    }
    echo '
</td>
<td class="tdSearch5">', htmlspecialchars($row['creator_user_id']), '</td>
<td class="tdSearch6">', htmlspecialchars($row['created']), '</td>
<td class="tdSearch7">', htmlspecialchars($row['modifier_user_id']), '</td>
<td classf="tdSearch8">', htmlspecialchars($row['modified']), '</td>
</tr>';
  }
  echo '

</table>
<input type=button id="clearall_button" value="clear all" onclick="clearall_setAnnotations()" />';
  if ($checkboxes_cnt != 0) {
    echo '
<input type=button id=clear_button value=clear onclick="clear_setAnnotations()" />
<input type=button id=set_button value=set onclick="set_setAnnotations()" />';
  }
  if ($at != 0) {
    echo '
<input type=submit name=prev value=prev />';
  }
  if ($cnt >= $page) {
    echo '
<input type=submit name=next value=next />';
  }
  if (isset($folder_info)) {
    echo '
<input type=button id=add_folder_button value="Add to Project" onclick="clickAddToFolder(\'', htmlspecialchars($folder_info), '\');"/>';
  }
  if (isset($addAnnotate)) {
    echo '
<input type=button value="Add to Annotation" onclick="clickAddToAnnotate();"/>
<input type=button value="Cancel" onclick="cancelAddAnnotate()" />
<input type=button id="fullWidthButton" style="display:none" value="Full Frame" title="Annotation frame expands across interface" onclick="toggle_frames()" />';
  }
  echo '
</form>';

  enterJavascript();
  echo '
var checkboxes_cnt     = ', $checkboxes_cnt,';
var checkboxes_set_cnt = 0;
';
  exitJavascript();
  echo '
<p>', $cnt+$at, ' annotations';
  if ($cnt != $cnt1) {
    echo '(', $cnt1, ' shown)';
  }
close:
  DBClose();
  goto done;
}
// This is the section where annotation pulled into folder
if (isset($folder_info)) {
  echo '
<h3>Select an annotation to add to this project</h3>';
} else if (isset($addAnnotate)) {
  echo '
<h3>Select annotations to reference from annotation</h3>';
} else {
  echo '
<h3>Advanced Search</h3>';
}
?>

<form id=form name=form action="search1.php" method="post">
<input id=cludge type=hidden name=mode value=y />
<?php
hidden('folder_info');
hidden('addAnnotate');
?>
<table>

<!-- Title section NB: on 8/12/2012 I changed 0/link - from "like" to "all terms", commented out =, changed 2/link - from "natural" to "all terms", left Boolean as is for the time being, and changed 4/link from "expand" to "wildcard"-->
<tr>
<td> <a href="#" title="A word or words from the annotation's title">Title:</a></td>
<td><input type=text name=title size=50 maxlength=255 />
<select name=title_mode>
<option value=0 selected>Any terms</option>
<option value=2>All terms</option>
<!--<option value=3>Boolean</option>
<option value=4>Wildcard</option>
<option value=1>=</option>-->
</select>
<!-- + content checkbox commented out 8/12/2012
&nbsp;+ content:
<input type=checkbox name=add_content />-->
</td>
</tr>

<!-- Tag section NB: on 8/12/2012 I changed 0/link - from "like" to "all terms", commented out =, changed 2/link - from "natural" to "all terms", left Boolean as is for the time being, and changed 4/link from "expand" to "wildcard-->
<tr>
<td><a href="#" title="One or more tags or keywords that categorize the annotation"> Tags:</a></td>
<td>
<input type=text name=tags size=50 maxlength=255 />
<select name="tags_mode" >
<option value="any" selected>Any</option>
<option value="all">All</option>
</select>
</td>
</tr>

<!-- Content section -->
<tr>
<td><a href="#" title="A word, phrase or longer extract from the annotation body">Content:</a></td>
<td><input type=text name=fcontent size=90 maxlength=255 />
<select name=fcontent_mode>
<option value=0 selected>Any terms</option>
<option value=2>All terms</option>
<!--<option value=3>Boolean</option>
<option value=4>Wildcard</option>
<option value=1>=</option>-->

</select>
</td>
</tr>


<!-- Creator section -->
<tr>
<td ><a href="#" title="The user who created the annotation">Creator:</a></td>
<td><input type=text name=creator_user_id size=90 maxlength=255 /></td>
</tr>


<!-- Modifier section -->
<tr>
<td><a href="#" title="A user who edited or otherwise modified the annotation">Modifier:</a></td>
<td><input type=text name=modifier_user_id size=90 maxlength=255 /></td>
</tr>

<!-- Template section -->
<tr>
<td><a href="#" title="An identifying art form - when you choose a template type, additional template fields will appear">Template:</a></td>
<td>
<?php
echo
select_template($template_code), '</td>
</tr>';
template_form($template_code, null);
?>
</td>
</tr>

<!--Language section -->

<tr>
<td><a href="#" title="The language chosen by the annotation's creator"> Language:</a></td>
<!--
This is the code for the box with all four languages included (not pulldown menu). Changed to pulldown menu on 08/11/2012
<td><?php annotation_language_code(null, true); ?></td>
</tr>
</table>
<table>
<tr>-->
<td>
<select name=language_code>
<option value='' selected></option>
<option value=eng>English</option>
<option value=fre>French</option>
<option value=ger>German</option>
<option value=spa>Spanish</option>
</select>
</td>
</tr>

<!-- Sort by Section -->

<tr>
<td>Sort:</td>
<td>
<select name=order>
<option value=''></option>
<!--<option value=annotation_id>id</option>
<option value=version>version</option>-->
<option value=title>Title</option>
<!--<option value=user>Username</option>-->
<option value=creator_user_id>Creator</option>
<option value=created>Date created</option>
<option value=modifier_user_id>Modifier</option>
<option value=modified>Date modified</option>
<option value=template_code>Template</option>
<option value=folder>Project</option>
<option value=group>Group</option>
</select>
&nbsp; by order: 
Ascending
<input type=checkbox />
Descending
<input type=checkbox name=desc />
</td>
</tr>

<!-- State section: Published and Deleted states commented out on 08/11/2012-->

<tr>
<td>State: </td>
<td>
<select name=draft>
<option value="" selected></option>
<option value='Y'>Draft</option>
<option value='S'>Saved</option>
<!--<option value='N'>Published</option>
<option value='X'>Deleted</option> -->
</select>
</td>
</tr>
</table>

<br />
<!-- Commented out date section because it's broken
<table>
<tr>

<td><a href="#" title="Use YYYY-MM-DD format in date field">Date created:</a></td>
<td><input type="text" name="min_created" size="15" maxlength="255" />
&nbsp;&le;&nbsp;Date&nbsp;&le;&nbsp;
<input type="text" name="max_created" size="15" maxlength="255" />
</td>
</tr>

 Modified date section also broken

<tr>
<td><a href="#" title="Use YYYY-MM-DD format in date field">Date modified:</a></td>
<td><input type="text" name="min_modified" size="15" maxlength="255" />
&nbsp;&le;&nbsp;Date&nbsp;&le;&nbsp;
<input type="text" name="max_modified" size="15" maxlength="255" />

</td>
</tr>
</table>
-->

<table>
<tr>
<td align=right>Annotation id:</td>
<td><input type=text name=min_annotation_id size=10 maxlength=10 /></td>
</tr>
</table>
<!-- Commented out range and state 
&nbsp;&le;&nbsp;<input type=text name=max_annotation_id size=10 maxlength=10 />
&nbsp;State: <select name=draft>
<option value="" selected></option>
<option value='Y'>Draft</option>
<option value='S'>Saved</option>
<option value='N'>Published</option>
<option value='X'>Deleted</option>
</select>
-->
<?php
if (isset($setAnnotations)) {
?>
&nbsp;<select name=setMode>
<option value='' selected></option>
<option value=and>and</option>
<option value=or>or</option>
</select>
&nbsp;Selected
<?php
}
?>
</td>
</tr>
</table>


<!-- Version section 
<table>
<tr>
<td align=right>Version:</td>
<td>
<input type=text name=min_version size=10 maxlength=10 />
&nbsp;&le;&nbsp;Number&nbsp;&le;&nbsp;
<input type=text name=max_version size=10 maxlength=10 />
<select name=version_mode>
<option value=last selected>last</option>
<option value=any>any</option>
<option value=first>first</option>
</select>
</td>
</tr>
</table>
-->

<!-- Image URL and HTML URL sections commented out on 8/11/2012
<table>
<tr>
<td>Image URL:</td>
<td><input type=text name=image_url size=90 maxlength=255 />
<select name=image_url_mode>
<option value='' selected></option>
<option value=1>ends</option>
<option value=2>contains</option>
<option value=3>starts</option>
</select> 
</td>
</tr>

<tr>
<td>HTML URL:</td>
<td><input type=text name=html_url size=90 maxlength=255 />

<select name=html_url_mode>
<option value='' selected></option>
<option value=1>ends</option>
<option value=2>contains</option>
<option value=2>starts</option>
</select> 
</td>
</tr>
</table>
-->

<!-- Folder section commented out 8/11/2012
<table>
<tr>
<td align=right>Folder:</td>
<td><input type=text name=folder size=90 maxlength=255 />
<select name=folder_mode>
<option value=0 selected>Under</option>
<option value=1>Reachable</option>
<option value=2>In</option>
</select>
</td>
</tr>
</table>
-->

<br />
<!-- Relevant language section 
<table>
<tr>
<td><a href="#" title="Any or all languages included in the body of the annotation">Relevant<br/>to<br/>Languages:</a></td>
<td><?php select_multiple_languages(null, 6); ?></td>
</tr>
</table>
-->
<br />

<!-- Advanced search fields #1, #2 & #3 will be added when I can figure out how to pull each field plus "and/or" function
<table>
<tr>
<td>
<select>
<option value='' selected>Any</option>
<option value=title>Title</option>
<option value=fcontent>Content</option>
<option value=imageMAT_user_id>Username</option>
<option value=creator_user_id>Creator</option>
<option value=tags>Tags</option>
<option value=template_code>Template</option>
<option value=''>Project</option>
<option value=''>Group</option>
</select>
</td>
<td>
<select>
<option value='' selected>contains</option>
<option value=''>is exact</option>
<option value=''>starts with</option>
<option value=''>ends with</option>
</select>
</td>
<td><input type=text name=category size=50 maxlength=255 />
</td>
<td>
<select>
<option value=''>and</option>
<option value=''>or</option>
</select>
</td>
</tr>

<tr>
<td>
<select name=category>
<option value='' selected>Any</option>
<option value=title>Title</option>
<option value=fcontent>Content</option>
<option value=imageMAT_user_id>Username</option>
<option value=creator_user_id>Creator</option>
<option value=tags>Tags</option>
<option value=template_code>Template</option>
<option value=''>Project</option>
<option value=''>Group</option>
</select>
</td>
<td>
<select>
<option value='' selected>contains</option>
<option value=''>is exact</option>
<option value=''>starts with</option>
<option value=''>ends with</option>
</select>
</td>
<td><input type=text name=category size=50 maxlength=255 />
</td>
<td>
<select>
<option value=''>and</option>
<option value=''>or</option>
</select>
</td>
</tr>

<tr>
<td>
<select name=category>
<option value='' selected>Any</option>
<option value=title>Title</option>
<option value=fcontent>Content</option>
<option value=imageMAT_user_id>Username</option>
<option value=creator_user_id>Creator</option>
<option value=tags>Tags</option>
<option value=template_code>Template</option>
<option value=''>Project</option>
<option value=''>Group</option>
</select>
</td>
<td>
<select>
<option value='' selected>contains</option>
<option value=''>is exact</option>
<option value=''>starts with</option>
<option value=''>ends with</option>
</select>
</td>
<td><input type=text name=category size=50 maxlength=255 />
</td>
<td>
<select>
<option value=''>and</option>
<option value=''>or</option>
</select>
</td>
</tr>
</table>
-->



<!-- Page Size commented out 8/11/2012
<tr>
<td align=right>Page Size:</td>
<td>
<input type=text name=page size=3 maxwidth=3 />
</td>
</tr>
-->

<!-- Submission/Reset -->
<table>
<tr>
<td></td>
<td><input type=submit name=send value=Search /><input type=reset />
<?php
if (isset($addAnnotate)) {
  echo '
<input type=button value="Cancel" onclick="cancelAddAnnotate()" />
<input type=button id="fullWidthButton" style="display:none" value="Full Frame" title="Annotation frame expands across interface" onclick="toggle_frames()" />';
}
?>
</td>
</tr>
</table>
<br />
</form>
<?php
done:
?>
</body>
</html>
