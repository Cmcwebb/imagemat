<!DOCTYPE HTML>
<?php

$gPHPscript = __FILE__;
$dir = dirname(__FILE__);
require_once($dir . '/../include/boilerplate.php');
require_once($dir . '/../include/util.php');

function getMarked()
{
  return null;
}

if (mustlogon()) {
  return;
}

require_once($dir . '/../include/db.php');
require_once($dir . '/../include/reachable.php');

htmlHeader('Search Folders');
srcStylesheet(
  '../css/style.css',
  '../css/tooltip.css',
  '../css/sortable.css'
);
srcJavascript(
  '../js/base64.js',
  '../js/tooltip.js',
  '../js/sortable.js'
);
enterJavascript(); ?>
function setFolders_state_changed()
{
  var button;
  var setFolders_lth = top.setFolders_lth;

  button  = document.getElementById('clearall_button');
  if (button != undefined) {
    button.disabled = ((setFolders_lth == 0) ? true : undefined);
  }
  button  = document.getElementById('clear_button');
  if (button != undefined) {
    button.disabled = ((window.checkboxes_set_cnt == 0) ? true : undefined);
  }
  button  = document.getElementById('set_button');
  if (button != undefined) {
    button.disabled = (window.checkboxes_set_cnt == window.checkboxes_cnt) ? true : undefined;
  }
}

function setFolders_changed1(checkbox)
{
  var setFolders = top.setFolders;
  var lth  = top.setFolders_lth;
  var id   = parseInt(checkbox.value);
  var i;

  if (checkbox.checked) {
    if (setFolders == null) {
	  top.setFolders = setFolders = new Array();
    } else {
	  for (i = lth; 0 <= --i; ) {
        if (setFolders[i] == id) {
          return;
    } } }
    setFolders[top.setFolders_lth++] = id;
	++window.checkboxes_set_cnt;
  } else {
    for (i = lth; ; ) {
      if (--i < 0) {
	    return;
      }
      if (setFolders[i] == id) {
		break;
	} }
    setFolders[i] = setFolders[--top.setFolders_lth];
    --window.checkboxes_set_cnt;
  }
  top.setFoldersChanged();
}

function show_setFolders(prefix)
{
/*
  var setFolders = top.setFolders;
  var lth  = ((setFolders == null) ? -1 : setFolders.length);
  var msg;
  var i;

  msg = prefix + '(' + top.setAnnotations_lth + '/' + lth + ')';
  for (i = 0; i < lth; ++i) {
    msg += ' ' + setFolders[i];
  }
  alert(msg);
*/
}

function clearall_setFolders()
{
  var checkboxes = document.getElementsByName('edit');
  var i;

  for (i = checkboxes.length; 0 <= --i; ) {
	checkboxes[i].checked = false;
  }
  if (top.setFolders_lth > 0) {
    top.setFolders_lth = 0;
    top.setFoldersChanged();
  }
  window.checkboxes_set_cnt = 0;
  setFolders_state_changed();
  //show_setFolders('clearall ');
}

function setFolders_changed(checkbox)
{
  setFolders_changed1(checkbox);
  setFolders_state_changed();
  //show_setFolders('setFolders_changed ');
}

function clear_setFolders()
{
  var checkboxes = document.getElementsByName('edit');
  var checkbox;
  var i;

  for (i = checkboxes.length; 0 <= --i; ) {
    checkbox = checkboxes[i];
	if (checkbox.checked) {
      checkbox.checked = false;
      setFolders_changed1(checkbox);
  } }
  setFolders_state_changed();
  //show_setFolders('clear_setFolders ');
}

function set_setFolders()
{
  var checkboxes = document.getElementsByName('edit');
  var checkbox;
  var i;

  for (i = 0; i < checkboxes.length; ++i) {
    checkbox = checkboxes[i];
	if (!checkbox.checked) {
      checkbox.checked = true;
      setFolders_changed1(checkbox);
  } }
  setFolders_state_changed();
  //show_setFolders('set setFolders ');
}

function init_checkboxes()
{
  var checkboxes = document.getElementsByName('edit');
  var setFolders       = top.setFolders;
  var i, j, id;

  for (i = 0; i < checkboxes.length; ++i) {
    checkbox = checkboxes[i];
    id       = parseInt(checkbox.value);
    for (j = top.setFolders_lth; 0 <= --j; ) {
      if (setFolders[j] == id) {
        checkbox.checked = true;
		++window.checkboxes_set_cnt;
        break;
  } } }
  setFolders_state_changed();
}

function loaded()
{
  init_checkboxes();
}

<?php exitJavascript(); ?>
</head>
<body onload="loaded()" id="background">
<?php

$show = getparameter('show');

$from_id = getparameter('from_id');
if (!isset($from_id)) {
  $from_id = 1;
}
$mode = getpost('mode');
if (isset($mode)) {

  if (!DBconnect()) {
    goto done;
  }

  $constraints = array();

  $query =
'select folders.folder_id, folders.name, folders.description,
       folders.creator_user_id, folders.created
  from folders';

  $under = getpost('under');
  $restrict = getpost('restrict');
  if (!isset($restrict) && $under != 0) {
    switch ($under) {
    case 1:
	  $restrict = child($from_id, $show);
	  break;
	case 2:
	  $restrict = descendants($from_id, $show);
	  break;
	case 3:
	  $restrict = reachable($from_id, $show);
	  break;
    case 4:
      $restrict = not_descendants($from_id, $show);
      break;
    case 5:
	  $restrict = self($from_id, $show);
    }
    if (!$restrict) {
	  goto close;
	}
    $cnt = count($restrict);
    $restrict = implode(',', $restrict);
    if ($cnt == 1) {
      $restrict = 'folder_id = ' . $restrict;
    } else {
      $restrict = 'folder_id in (' . $restrict . ')';
    }
  }
  if (isset($restrict)) {
    $constraints[] = $restrict;
  }

  $name = getpost('name');
  if (isset($name)) {
    $nmode = getpost('nmode');
    if (!isset($nmode)) {
      $nmode = 0;
    }
    switch ($nmode) {
    case 0:
      $term = 'name like \'%' . DBnumber($name) . '%\'';
      break;
    case 1:
      $term = 'name = ' . DBstring($name);
      break;
    }
    $constraints[] = $term;
  }

  $description = getpost('description');
  if (isset($description)) {
    $dmode = getpost('dmode');
    if (!isset($dmode)) {
      $dmode = 0;
    }
    switch ($dmode) {
    case 0:
      $term = 'description like \'%' . DBnumber($description) . '%\'';
      break;
    case 1:
      $term = 'description = ' . DBstring($description);
      break;
    default:
      $query .= ', folderfulltexts';
      $constraints[] = 'folders.folder_id = folderfulltexts.folder_id';
      $term = 'match (fdescription) against (' . DBstring($description);
      switch ($content_mode) {
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

  $folders = getpost('folders');
  if (isset($folders)) {
    $listSetFolders = getpost('listSetFolders');
    if (!isset($listSetFolders)) {
      $listSetFolders  = getSetFolders();
	  if (!issset($listSetFolders)) {
	    $listSetFolders = -1;
	  } else {
        $listSetFolders = implode(',', $listSetFolders);
	} }
    if ($folders == 'and') {
      $constraints[] = 'folder_id in (' . $listSetFolders . ')';
  } }

  $annotations = getpost('annotations');
  if (isset($annotations)) {
	if (!isset($listSetAnnotations)) {
	  $listSetAnnotations = getSetAnnotations();
	  if (!isset($listSetAnnotations)) {
		$listSetAnnotations = -1;
	  }
	} else {
	  $listSetAnnotations = implode(',', $listSetAnnotations);
	}

	$query .= ', foldersannotations';

	$constraints[] = 'folders.folder_id = foldersannotations.folder_id';
    $constraints[] = 'annotation_id in  (' . $listSetAnnotations . ')';
  }

  $url       = getpost('url');
  if (isset($url)) {
    $umode = getpost('umode');
    if (!isset($umode)) {
      $umode = 0;
    }
	$query .= ',urls, foldersurls';

	$constraints[] = 'folders.folder_id = foldersurls.folder_id';
	$constraints[] = 'foldersurls.url_id = urls.url_id';
    switch ($umode) {
    case 0:
      $term = 'urls.url like \'%' . DBnumber($url) . '%\'';
      break;
    case 1:
      $term = 'urls.url = ' . DBstring($url);
      break;
    }
    $constraints[] = $term;
  }

  $creator_user_id = getpost('creator_user_id');
  if (isset($creator_user_id)) {
	$constraints[] = 'folders.creator_user_id = ' . DBstring($creator_user_id);
  }

  $min_created   = getpost('min_created');
  $max_created   = getpost('max_created');
  if (isset($min_created) || isset($max_created)) {
    if (!isset($max_created)) {
      $constraints[] = DBdate($min_created) . '<= folders.created';
    } else if (!isset($min_created)) {
      $constraints[] = 'folders.created <= ' . DBdate($max_created);
    } else if ($min_created == $max_created) {
      $constraints[] = 'folders.created = ' . DBdate($max_created);
    } else {
      $constraints[] = 'folders.created between ' . DBdate($min_created) . ' and ' . DBdate($max_created);
  } }

  $min_modified  = getpost('min_modified');
  $max_modified  = getpost('max_modified');
  if (isset($min_modified) || isset($max_modified)) {
    if (!isset($max_modified)) {
      $constraints[] = DBdate($min_created) . '<= folders.modified';
    } else if (!isset($min_modified)) {
      $constraints[] = 'folders.modified <= ' . DBdate($max_modified);
    } else if ($min_modified == $max_modified) {
      $constraints[] = 'folders.modified = ' . DBdate($max_modified);
    } else {
      $constraints[] = 'folders.modified between ' . DBdate($min_modified) . ' and ' . DBdate($max_modified);
  } }

  $tags = getpost('tags');
  if (isset($tags)) {
    $tags_mode = getpost('tags_mode');
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
      (select distinct folder_id
         from foldertags
        where ' . $tags1 . ') 
       ) tags';
          $constraints[] = 'folders.folder_id = foldertags.folder_id';
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
       (select folder_id
          from foldertags, ' . $tags1 . '
         where tags.name  = tags1.n
           and tags.value = tags1.v
         group by folder_id
        having count(*) = ' . DBnumber($cnt1) . '
       ) folders1';
          $constraints[] = 'folders.folder_id = folders1.folder_id';
        }
        if (isset($tags2)) {
          $tags2 .= '
             ) tags2';
          $query .= ',
      (select folder_id
        from (select distinct folder_id, name
                from foldertags
             ) tags, ' . $tags2 . '
         where tags.name = tags2.n
         group by folder_id
        having count(*) = ' . DBnumber($cnt2) . '
       ) folders2';
          $constraints[] = 'folders.folder_id = folders2.folder_id';
        }
        if (isset($tags3)) {
          $tags3 .= '
             ) tags3';
          $query .= ',
      (select folder_id
        from (select distinct folder_id, value
                from foldertags
             ) tags, ' . $tags3 . '
        where tags.value = tags3.v
        group by folder_id
       having count(*) = ' . DBnumber($cnt3) . '
      ) folders3';
          $constraints[] = 'folders.folder_id = folders3.folder_id';
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
  if (isset($folders)) {
    if ($folders == 'or') {
      $query .= $or . 'folder_id in (' . $listSetFolders . ')';
  } }

  $totalHits = getPost('totalHits');
  if (!isset($totalHits)) {
    $ret = DBquery(
'select count(*) as totalHits
  from 
(
' . $query . '
) hits');
    if (!$ret) {
      goto close;
    }
    $row = DBfetch($ret);
    $totalHits = $row['totalHits'];
  }


  $order = getpost('order');
  if (isset($order)) {
    $query .= '
 order by ' . $order;
    $desc = getpost('desc');
    if (isset($desc)) {
      $query .= ' desc';
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

  require_once($dir . '/../include/folders/permissions.php');
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
<form id=page name=page action="searchFolders.php" method="post" target=_self>
<input id=cludge type=hidden name=mode value=y />';
  hidden('from_id');
  hidden('show');
  hidden('under');
  hidden('restrict');
  hidden('name');
  hidden('nmode');
  hidden('description');
  hidden('dmode');
  hidden('folders');
  hidden('listSetFolders');
  hidden('annotations');
  hidden('listSetAnnotations');
  hidden('url');
  hidden('umode');
  hidden('creator_user_id');
  hidden('min_created');
  hidden('max_created');
  hidden('min_modified');
  hidden('max_modified');
  hidden('order');
  hidden('desc');
  hidden('page');
  hidden('at');
  hidden('totalHits');

  echo '
<table id="results" class=sortable cellpadding="0" cellspacing="4">
<tr><th class=unsortable></th><th class=startsort>Id</th><th width="40%">Name &amp; Description</th><th>Creator</th><th>Created</th></tr>';

  $cnt1 = 0;
  $checkboxes_cnt     = 0;
  for ($cnt = 0; $row = DBfetch($ret); ++$cnt) {
    $visible = may_see_folder($row);
    if (!isset($visible)) {
      goto close;
    }
    if (!$visible) {
      continue;
    }
    ++$cnt1;
	++$checkboxes_cnt;
    $id = $row['folder_id'];
	echo '
<tr>
<td><input type=checkbox name="edit" value=', $id, ' onclick="setFolders_changed(this)"/></td>
<td align=right>', $id,'</td>
<td>';
	if (isset($row['description'])) {
	  echo '<span class="foldername" onmouseover="tooltip.base64(\'',
base64_encode($row['description']), '\',null,\'tt\');" onmouseout="tooltip.hide();">',
htmlspecialchars($row['name']),'</span>';
	} else {
	  echo htmlspecialchars($row['name']);
	}
	echo '</td>
<td>', htmlspecialchars($row['creator_user_id']), '</td>
<td>', htmlspecialchars($row['created']), '</td>
</tr>';
  }
  echo '
</table>
<input type=button id="clearall_button" value="clear all" onclick="clearall_setFolders()" />';
  if ($checkboxes_cnt != 0) {
    echo '
<input type=button id=clear_button value=clear onclick="clear_setFolders()" />
<input type=button id=set_button value=set onclick="set_setFolders()" />';
  }
  if ($cnt >= $page) {
    echo '
<input type=submit name=next value=next />';
  }
  if ($at != 0) {
    echo '
<input type=submit name=prev value=prev />';
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
<p>', $cnt+$at, ' folders';
  if ($cnt != $cnt1) {
    echo '(', $cnt1, ' shown)';
  }
  goto close;
}

if ($from_id == 1) {
  $from_name = '/';
} else {
  if (!DBconnect()) {
	goto close;
  }
  $query = 
'select name 
  from folders
 where folder_id = ' . DBnumber($from_id);
  $ret = DBquery($query);
  if (!$ret) {
    goto close;
  }
  $row = DBfetch($ret);
  if (!$row) {
    echo 'Folder ', $from_id, ' not found';
    goto close;
  }
  $from_name = $row['name'];
}

?>
<h3>Please enter the folder search criteria</h3>
<p>

<form id=form name=form action="searchFolders.php" method="post">
<input type=hidden name=mode value=y />
<?php
hidden('from_id');
hidden('show');
?>
<table>
<tr>
<td align=right>Rooted at:</td>
<td><?php echo  htmlspecialchars($from_name); ?></td>
</tr>

<tr>
<td align=right>Type:</td>
<td>
<select name=under>
<option value=0 selected></option>
<option value=3>Reachable</option>
<option value=2>Descendant</option>
<option value=4>Not Descendant</option>
<option value=1>Child</option>
<option value=5>Self</option>
</select>
</td>
</tr>

<tr>
<td align=right>Name:</td>
<td><input type=text name=name size=32  />
<select name=nmode>
<option value=0 selected>like</option>
<option value=1>=</option>
</select>
</td>
</tr>

<tr>
<td align=right>Description:</td>
<td><input type=text name=description size=32  />
<select name=dmode>
<option value=0 selected>like</option>
<option value=1>=</option>
<option value=2>natural</option>
<option value=3>boolean</option>
<option value=4>expand</option>
</select>
</td>
</tr>

<tr>
<td align=right>Tags:</td>
<td>
<input type=text name=tags size=32 maxlength=255 />
<select name="tags_mode" >
<option value="any" selected>Any</option>
<option value="all">All</option>
</select>
</td>
</tr>

<tr>
<td align=right>URLS:</td>
<td><input type=text name=url size=32  />
<select name=umode>
<option value=0 selected>like</option>
<option value=1>=</option>
</select>
</td>
</tr>

<tr>
<td align=right>Creator:</td>
<td><input type=text name=creator_user_id size=32 maxlength=255 /></td>
</tr>

<tr>
<td align=right>Created:</td>
<td><input type=text name=min_created size=15 maxlength=255 />
&nbsp;&le;&nbsp;YYYY-MM-DD&nbsp;&le;&nbsp;
<input type=text name=max_created size=15 maxlength=255 />
</td>
</tr>

<tr>
<td align=right>Modified:</td>
<td><input type=text name=min_modified size=15 maxlength=255 />
&nbsp;&le;&nbsp;YYYY-MM-DD&nbsp;&le;&nbsp;
<input type=text name=max_modified size=15 maxlength=255 />
</td>
</tr>

<tr>
<td align=right>Folders:</td>
<td><?php
$set = getSetFolders();
if (isset($set)) {
?>
<select name=folders>
<option value='' selected></option>
<option value='and'>and</option>
<option value='or'>or</option>
</select>&nbsp;<?php echo count($set); ?> selected
<?php
} else {
  echo '<i>None selected</i>';
}
?></td>
</tr>

<tr>
<td align=right>Annotations:</td>
<td><?php
$set = getSetAnnotations();
if (isset($set)) {
?>
<select name=annotations>
<option value='' selected></option>
<option value='some'>parent of some</option>
</select>
<?php
  echo '&nbsp;of ', count($set), ' selected';
} else {
  echo '<i>None selected</i>';
}
?></td>
</tr>

<tr>
<td align=right>Sort:</td>
<td>
<select name=order>
<option value=''></option>
<option value=folder_id>id</option>
<option value=name>name</option>
<option value=description>description</option>
<option value=creator_user_id>creator</option>
<option value=created>created</option>
</select>
&nbsp;Desc
<input type=checkbox name=desc></input>
</td>
</tr>
<tr>
<td align=right>Page Size:</td>
<td>
<input type=text name=page size=3 maxwidth=3></input>
</td>
</tr>
<tr>
<td></td>
<td><input type=submit name=send value=Search /><input type=reset /></td>
</tr>
</table>
</form>

<?php
close:
DBClose();
done:
?>
<p>
</body>
</html>
