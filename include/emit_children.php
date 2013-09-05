<?php

function emit_children($indent, $parent, $query)
{
  $ret = DBquery($query);
  if (!$ret) {
    goto done;
  }

  for ($next = DBfetch($ret); $row = $next; ) {
    if ($row['isSymlink'] == 1) {
      $type = 's';
      $gif  = 'symlnk.png';
	} else {
	  $type = 'f';
	  if ($parent == 1) {
		$gif = 'homedr.png';
	  } else {
	    $gif  = 'folder.png';
	} }
    $parent = $row['parent_folder_id'];
    $id     = $row['folder_id'];
    $name   = htmlspecialchars($row['name']);
    $next   = DBfetch($ret);
    echo '<table summary="',
$type, ',', $parent, ',', $id,
'" cellspacing=0 cellpadding=0 border=0 width="100%"><tbody><tr>';
    for ($i = 0; $i < $indent; ++$i) {
      echo '<td valign=top><img class=folderbar src="../images/project/vertline.gif"></td>';
    }
    echo '<td background="../images/project/vertline.gif valign=top"><a onclick="clickN(this)"><img class=folderbar src="../images/project/p',
($next ? 'n' : 'l'),
'node.gif" /></a></td><td valign=top><a onclick="clickF(event)"><img class=foldericon src="../images/project/c',$gif,
'" /></a></td><td width="100%" valign="middle"><a onclick="clickD(event)">',
$name,
'</a></td></tr></tbody></table>';
  }

  $query = 
'select annotations.annotation_id, title
  from foldersannotations, annotations
 where folder_id = ' . DBnumber($parent) . '
   and foldersannotations.annotation_id = annotations.annotation_id
   and annotations.annotation_deleted is null';

  $ret = DBquery($query);
  if (!$ret) {
    goto done;
  }

  while ($row = DBfetch($ret)) {
    $id = $row['annotation_id'];
    echo '<table summary="a,',$parent,',',$id,
'" cellspacing=0 cellpadding=0 border=0 width="100%"><tbody><tr>';
    for ($i = 0; $i <= $indent; ++$i) {
      echo '<td valign=top><img class=folderbar src="../images/project/vertline.gif"></td>';
    }
    echo '<td valign=top><a onclick="clickA(event)"><img class=foldericon src="../images/project/annotation.png" /></a></td><td width="100%" valign="middle"><a onclick="clickT(event)">',
htmlspecialchars($row['title']),
'</a></td></tr></tbody></table>';
  }

  $query = 
'select urls.url_id, url
  from foldersurls, urls
 where folder_id = ' . DBnumber($parent) . '
   and foldersurls.url_id = urls.url_id';

  $ret = DBquery($query);
  if (!$ret) {
    goto done;
  }

  while ($row = DBfetch($ret)) {
    $id = $row['url_id'];
    echo '<table summary="u,',$parent,',',$id,
'" cellspacing=0 cellpadding=0 border=0 width="100%"><tbody><tr>';
    for ($i = 0; $i <= $indent; ++$i) {
      echo '<td valign=top><img class=folderbar src="../images/project/vertline.gif"></td>';
    }
    echo '<td valign=top><a onclick="clickU(event)"><img class=foldericon src="../images/project/url.png" /></a></td><td width="100%" valign="middle"><a onclick="clickL(event)">',
htmlspecialchars($row['url']),
'</a></td></tr></tbody></table>';
  }
  $ret = true;

  $query = 
'select urls.url_id, url
  from foldersimages, urls
 where folder_id = ' . DBnumber($parent) . '
   and foldersimages.url_id = urls.url_id';

  $ret = DBquery($query);
  if (!$ret) {
    goto done;
  }

  while ($row = DBfetch($ret)) {
    $id = $row['url_id'];
    echo '<table summary="i,',$parent,',',$id,
'" cellspacing=0 cellpadding=0 border=0 width="100%"><tbody><tr>';
    for ($i = 0; $i <= $indent; ++$i) {
      echo '<td valign=top><img class=folderbar src="../images/project/vertline.gif"></td>';
    }
    echo '<td valign=top><a onclick="clickI(event)"><img class=foldericon src="../images/project/image.png" /></a></td><td width="100%" valign="middle"><a onclick="clickJ(event)">',
htmlspecialchars($row['url']),
'</a></td></tr></tbody></table>';
  }
  $ret = true;
done:
  return $ret;
}
?>
