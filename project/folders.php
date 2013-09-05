<!DOCTYPE HTML>
<?php

$gPHPscript = __FILE__;
$dir = dirname(__FILE__);
require_once($dir . '/../include/boilerplate.php');

if (mustlogon()) {
  /* This can happen if the frame is left idle a long time */
  return;
}

$show = getparameter('show');

htmlHeader('Manipulate folders');

require_once($dir . '/../include/db.php');
require_once($dir . '/../include/emit_children.php');

srcJavascript(
  '../js/browser.js',
  '../js/util.js',
  '../js/alert.js',
  '../js/ajax.js'
);
enterJavascript(); 


if (!isset($show)) {
  $show = 0;
}

echo 'var start_show = ', $show, ';
';

?>

var gMenu  = null;
var gState = null;

function hideMenu(event)
{
  if (!gMenu) {
	return;
  }
  if (!event) {
	event = window.event;
  }

  if (event.type == 'mouseout') {

/*
	http://www.quirksmode.org/js/events_mouse.html
	Another show stopper is that when you move the mouse into the layer,
	and then onto a link, browsers register a mouseout event on the layer!
	It doesn't make much sense to me (the mouse is still in the layer), but
	all browsers agree on this one.
*/

	var over = event.relatedTarget;
	if (!over) {
	  over = event.toTarget;
	}
	for (; over; over = over.parentNode) {
	  if (over.tagName == 'BODY') {
		break;
	  }
	  if (over == gMenu) {
		return;
  	} }
  }
    
  gMenu.style.display = 'none';
  gMenu = null;
}

function getInfo(a)
{
  var table, summary, info;
  var type, parent, id;

  for (table = a; ; table = table.parentNode) {
    if (!table) {
      alert('Can\'t locate table for item');
      return null;
    }
    if (table.tagName == 'TABLE') {
      break;
  } }
  summary = table.summary;
  if (!summary) {
    alert('Missing summary');
    return null;
  }
  info = summary.split(',');
  if (info.length != 3) {
    alert('Invalid info');
  }
  return { type:info[0], parent:info[1], id:info[2], a:a, table:table };
}

function getParentInfo(info)
{
  if (info.id == 1) {
    return null;
  }
  var table = info.table;
  var div   = table.parentNode;

  if (div == null || div.tagName != 'DIV') {
	alert('Missing DIV above table');
    return null;
  }
  table = div.previousSibling;
  if (!table || table.tagName != 'TABLE') {
    alert('Missing TABLE before DIV');
    return null;
  }
  return getInfo(table);
}

function isUnder(source, id)
{
  var info;

  for (info = source; info != null; info = getParentInfo(info)) {
	if (info.id == id && info.type == 'f') {
	  return true;
  } }
  return false;
}

function getNameAnchor(element)
{
  var ret;

  for (ret = element; ret != null; ret = ret.parentNode) {
    switch (ret.tagName) {
    case 'DIV':
      ret = null;
    case 'TR':
    case 'TABLE':
      break;
    default:
      continue;
    }
    break;
  }
  for (;; ret = ret.lastChild) {
    if (ret == null) {
      alert('Missing name anchor');
      break;
    }
    if (ret.tagName == 'A') {
      break;
  } }
  return ret;
}

// Children are denoted by a DIV following a table
// No div implies for a folder children are unknown

function addChildren()
{
  if (this.readyState == 4 && this.status == 200) {
    var table    = this.imagematState;
    var parent   = table.parentNode;
	var nxt      = table.nextSibling;
    var div      = document.createElement('div');
	if (!nxt) {
	  parent.appendChild(div);
    } else {
	  parent.insertBefore(div, nxt);
 	  if (nxt.tagName == 'DIV') {
      	alert('Table unexpectedly has children');
		parent.removeChild(nxt);
	  }
	}
    var response = this.responseText;
    if (response != '') {
      div.innerHTML = response;
    }
    // alert(response);
  }
}

function clickN(a) /* Click on Node */
{
  var tr       = null;
  var node_img = null;
  var indent   = 0;
  var cnt      = 0;
  var show     = 0;
  var info, id;
  var div, table, td, a, img, src;
  var node_img, node_prefix, node_open, node_suffix;
  var folder_img, folder_prefix, folder_open, folder_suffix;
  var lth;

  info = getInfo(a);
  id   = info.id;

  //alert('ClickOnNode(' + id + ')');

  for (table = a; ; table = table.parentNode) {
    if (!table) {
      alert('Can\'t find table for node ' + id);
      return;
    }
    if (table.tagName == 'TABLE') {
      break;
  } }
  if (table.id == 'f1') {
    show = start_show;
  }

  for (tr = table.firstChild; ; tr = tr.firstChild) {
    if (!tr) {
      alert('Missing TR');
      return;
    }
    if (tr.tagName == 'TR') {
	  break;
  } }

  for (td = tr.firstChild; ; td = td.nextSibling) {
    if (!td) {
      alert('Missing Icon TD');
      return;
    }
    if (td.tagName != 'TD') {
      alert('Missing TD');
      return;
    }
    ++cnt;
    a = td.firstChild;
    if (!a || a.tagName != 'A') {
      continue;
    }
    img = a.firstChild;
    if (!img || img.tagName != 'IMG') {
      continue;
    }
    src = img.src;
    lth = src.length;
    if (node_img == null) {
	  if (9 < lth && src.substr(lth-8) == 'node.gif') {
        node_prefix = src.substr(0, lth-10);
        node_suffix = src.substr(lth-9);
        node_open   = src[lth-10];
        node_img    = img;
        indent      = cnt;
        continue;
    } }
    if (lth < 11) {
   	  alert('Folder img src=' + src);
      return;
    }
    folder_suffix = src.substr(lth-10);
    if (folder_suffix != 'folder.gif' &&
        folder_suffix != 'folder.png' &&
        folder_suffix != 'symlnk.gif' &&
        folder_suffix != 'symlnk.png' &&
		folder_suffix != 'rootdr.png' &&
		folder_suffix != 'homedr.png') {
   	  alert('Folder img src=' + src);
      return;
    }
    folder_prefix = src.substr(0, lth - 11);
    folder_open   = src[lth-11];
    folder_img    = img;
    break;
  }

  div = table.nextSibling;
  switch (folder_open) {
  case 'o':
    /* Was open */
    if (node_img != null) {
      if (node_open != 'm') {
        alert('Node open not m ' + node_img.src);
        return;
      }
      node_img.src = node_prefix + 'p' + node_suffix;
    }
    folder_img.src = folder_prefix + 'c' + folder_suffix;
    if (div && div.tagName == 'DIV') {
      div.style.display ='none';
	}
    return;
  case 'c':
	/* Was closed */
    if (node_img != null) {
      if (node_open != 'p') {
        alert('Node open not p ' + node_img.src);
        return;
      }
      node_img.src = node_prefix + 'm' + node_suffix;
    }
    folder_img.src = folder_prefix + 'o' + folder_suffix;
    if (div && div.tagName == 'DIV') {
      div.style.display = '';
      return;
    }
	break;
  default:
    alert('Invalid folder gif=' + folder_img.src);
    return;
  }

  do_ajax(table, 'do_get_childinfo.php', 'id=' + id + '&show=' + show + '&indent=' + indent, addChildren);
}

function do_refresh_table(table)
{
  var div  = table.nextSibling;
  var open = false;
  if (div && div.tagName == 'DIV') {
	if (div.style.display != 'none') {
	  open = true;
	}
    table.parentNode.removeChild(div);
  }
  if (open) {
    clickN(table);
    clickN(table);
} }

// The same node can appear multiple times because of links

function do_refresh_tree(table, type, parent, id)
{
  var info = getInfo(table);
  if (info) {
    switch (info.type) {
    case 'f':
      if (type == 'f' && info.id == id) {
	    do_refresh_table(table);
      }
      break;
    case 's':
      if (type == 's' && info.parent == parent && info.id == id) {
		do_refresh_table(table);
	  }
	  break;
    default:
	  return;
	}
    var div  = table.nextSibling;

    if (div && div.tagName == 'DIV') {
	  for (var child = div.firstChild; child; child = child.nextSibling) {
        if (child.tagName == 'TABLE') {
		  do_refresh_tree(child, type, parent, id);
} } } } }

function do_refresh_id(type, parent, id)
{
  var table = document.getElementById('f1');
  if (table) {
    do_refresh_tree(table, type, parent, id);
} }

function do_refresh_folder(id)
{
  do_refresh_id('f', null, id);
}

function do_refresh1(info)
{
  do_refresh_id(info.type, info.parent, info.id);
}

function do_refresh()
{
  do_refresh1(gState);
}

function done_node()
{
  if (this.readyState == 4 && this.status == 200) {
    var response = this.responseText;
    if (response != '') {
      alert(response);
    }
  }
}

// Refresh tree rooted at clicked on folder

function done_tree()
{
  if (this.readyState == 4 && this.status == 200) {
    var response = this.responseText;
    if (response != '') {
      alert(response);
      return;
    }
    do_refresh1(this.imagematState);
  }
}

function erase_table(table)
{
  var div = table.parentNode;
  if (!div || div.tagName != 'DIV') {
    alert ('Can\'t find div for table being erased');
    return;
  }
  var children = table.nextSibling;
  if (children && children.tagName == 'DIV') {
    // Remove children of node
    div.removeChild(children);
  }
  // Remove node itself
  div.removeChild(table);
}

function erase_referencing_symlinks(id)
{
  var tables = document.getElementsByTagName('TABLE');
  var table, i, length;

  length = tables.length;
  for (i = 0; i < length; )  {
    table = tables[i];
    if (table.summary) {
      info = getInfo(table);
      if (info.type == 's' && info.id == id) {
        erase_table(table);
        if (tables.length < length) {
          length = tables.length;
          continue;
    } } }
    ++i;
} }

function done_delete_member()
{
  if (this.readyState == 4 && this.status == 200) {
    var response = this.responseText;
    if (response != '') {
      alert(response);
      return;
    }
    var info  = this.imagematState;

    if (info.type == 'f') {
      erase_referencing_symlinks(info.id);
    }
    erase_table(info.table);
} }

function done_movecopy()
{
  if (this.readyState == 4) {
	if (this.status == 200) {
      var response = this.responseText;
      if (response != '') {
        alert(response);
    } }
	do_refresh1(this.imagematState);
} }

function showRightFrame(link)
{
  if (self != top) {
    var right = top.document.getElementById('annotateFrame')
    if (right) {
      right.src = link;
} } }

function done_add_folder(parent_id)
{
  do_refresh_folder(parent_id);
}
  
function do_add_folder()
{
  showRightFrame('create_folder.php?parent_id=' + gState.id);
}

function done_delete_folder(parent_id, folder_id)
{
  do_refresh_folder(parent_id);
}

function do_delete_folder()
{
  showRightFrame('delete_folder.php?folder_id=' + gState.id + '&parent_id=' + gState.parent);
}

function do_rename_tree(table, type, parent_id, id, name)
{
  var info = getInfo(table);
  if (info) {
    if (info.type == type && info.id == id && (type == 'f' || info.parent == parent_id)) {
      var a = getNameAnchor(table);
      if (a) {
        a.textContent = name;
    } }
    var div  = table.nextSibling;

    if (div && div.tagName == 'DIV') {
	  for (var child = div.firstChild; child; child = child.nextSibling) {
        if (child.tagName == 'TABLE') {
		  do_rename_tree(child, type, parent_id, id, name);
} } } } }

function done_update_folder(folder_id, name)
{
  var table = document.getElementById('f1');
  if (table) {
    do_rename_tree(table, 'f', null, folder_id, name);
} }

function do_update_folder()
{
  showRightFrame('update_folder.php?folder_id=' + gState.id);
}

function done_update_link(parent_id)
{
  do_refresh_folder(parent_id);
}

function do_update_link()
{
  showRightFrame('update_link.php?parent_id=' + gState.parent + '&target_id=' + gState.id);
}

function do_show_folder()
{
  showRightFrame('show_folder.php?folder_id=' + gState.id);
}

function done_add_links(parent_id)
{
  do_refresh_folder(parent_id);
}
  
function do_add_links()
{
  top.folderIdsToServer();
  showRightFrame('create_links.php?parent_id=' + gState.id);
}

function do_add_annotations()
{
  showRightFrame('../annotate/search1.php?folder_info=' + gState.id + '/' + gState.parent + '/' + gState.type );
}

function add_annotations_callback(encoded)
{
  var a      = encoded.split('/');
  var id     = parseInt(a[0]);
  var parent = parseInt(a[1]);
  var type   = a[2];
  var info   = {id:id,parent:parent,type:type};
  do_ajax(info, 'do_add_annotations.php', 'id=' + info.id, done_tree);
}

function do_add_url()
{
  var url = prompt('URL', '');
  if (url == null ) {
    return;
  }
  url = trim(url);
  if (url == '') {
    return;
  }
  do_ajax(gState, 'do_add_url.php', 'id=' + gState.id + '&url=' + encodeURIComponent(url),  done_tree);
}


function change_name_class(element, className)
{
  var a = getNameAnchor(element);
  
  if (a) {
    a.className= className;
} }

function do_clear_table(table)
{
  var div;

  change_name_class(table, null);

  div = table.nextSibling;
  if (div && div.tagName == 'DIV') {
    for (table = div.firstChild; table; table = table.nextSibling) {
      if (table.tagName == 'TABLE') {
        do_clear_table(table);
} } } }

function do_clear_ancestors(element)
{
  for (;;) {
    if (!element) {
      alert('Missed foldertree div');
      return;
    }
    switch (element.tagName) {
    case 'TABLE':
      change_name_class(element, null);
      break;
    case 'DIV':
      if (element.id == 'folderTree') {
        return;
      }
      if (element.previousSibling) {
        element = element.previousSibling;
        continue;
      }
      break;
    }
    element = element.parentNode;
} }

function do_clear()
{
  do_clear_table(gState.table);
}

function do_mark_node()
{
  var table = gState.table;

  do_clear_table(table);
  do_clear_ancestors(table);
  change_name_class(table, 'mark');
}

function do_annotate()
{
  var target_info = gState;
  var id          = target_info.id;
  var type        = target_info.type;
  var transmit    = [];
  var length, i;

  switch (type) {
  case 's':
    alert('Can\'t annotate a symlink');
	return;
  case 'f':
    if (id == 1) {
	  alert('Can\'t annotate the root');
	  return;
    }
    var as     = document.getElementsByTagName('a');
    var a, source_info, info;

    length = (as ? as.length : -1);
    for (i = 0; i < length; ++i) {
      a = as[i];
      if (a.className != 'mark') {
		continue;
	  }
      source_info = getInfo(a);
      type = source_info.type;
	  switch(type) {
	  case 'f':
	  case 's':
	    // Don't yet handle
	   	continue;
	  }
	  if (!isUnder(source_info, id)) {
		continue;
	  }
	  transmit.push(
	    {t:type, i:source_info.id } );
	  a.className = null;
	}
    if (transmit.length == 0) {
      customAlert(
	    { title:'No marked items',
		  icon:'warn.png',
		  body:'You have no marked items to add to the annotation under this folder',
		  width:'600px'
	    });
      return;
    }
	break;
  default:
    transmit.push( {t:type, i:id} );
  }

  var image_ids    = null;
  var html_ids     = null;
  var citation_ids = null;

  length = transmit.length;
  for (i = 0; i < length; ++i) {
	type = transmit[i].t;
	id   = transmit[i].i;
	switch (type) {
    case 'a':	// Annotation
	  if (!citation_ids) {
		citation_ids = [];
	  }
	  citation_ids.push(id);
	  break;
	case 'u':	// Html URL
	  if (!html_ids) {
	    html_ids = [];
	  }
	  html_ids.push(id);
	  break;
	case 'i':
	  if (!image_ids) {
		image_ids = [];
	  }
	  image_ids.push(id);
	  break;
	default:
	  alert('Unexpected type of ' + type);
  } }

  var parameters = {};
  if (image_ids) {
	parameters['image_ids'] = image_ids;
  }
  if (html_ids) {
	parameters['html_ids']  = html_ids;
  }
  if (citation_ids) {
	parameters['citation_ids'] = citation_ids;
  }

  submitPost(
    { action:'../annotate/annotate.php',
	  parameters:parameters,
      target:'_top'
    } );
  return;
}


function do_movecopy(target_info, move)
{
  if (target_info.id == 1) {
	alert('Can\'t move or copy anything under the root');
	return;
  }

  var as       = document.getElementsByTagName('a');
  var transmit = [];
  var moved    = [];
  var id       = target_info.id;
  var info, type, parent, source_id;
  var noop = false;

  if (as) {
    var a, source_info, info, i, length;

    length = as.length;
    for (i = 0; i < length; ++i) {
      a    = as[i];
      if (a.className == 'mark') {
        source_info = getInfo(a);
        parent      = source_info.parent;
        if (parent == id) {
		  noop = true;
		} else {
    	  // otherwise source already under target - noop so ignore
          type      = source_info.type;
    	  source_id = source_info.id;
  		  if (type == 'f') {
			if (source_info.parent < 2 && move) {
			    customAlert(
			  	  { title:'Illegal move',
				    icon:'error.png',
				    body:'You may not move home folders or the root',
				    width:'600px'
				  });
			    return;
			}
	        // Can't move a source folder to itself or any folder under itself
			if (isUnder(target_info, source_id)) {
			  customAlert(
			    { title:'Illegal copy',
			      icon:'error.png',
				  body:'You may not move a folder under itself',
				  width:'600px'
				});
			  return;
		  } }
		  if (move) {
			moved.push(source_info.table);
		  }
		  transmit.push( {t:type, p:parent, i:source_id } );
		}
		a.className = null;
  } } }

  if (transmit.length == 0) {
    var msg = (move ? 'move' : 'copy');

    msg = (noop ? 'This ' + msg + ' would change nothing' : 'Nothing marked to ' + msg);
    customAlert(
	  { title:'Null operation',
		icon:'warn.png',
		body:msg,
		width:'600px'
	  });
    return;
  }

  var encoded = encodeURIComponent(JSON.stringify(transmit));

  if (move) {
	var table, div, parent;

	length = moved.length;
	for (i = 0; i < length; ++i) {
	  table  = moved[i];
	  parent = table.parentNode;
	  div    = table.nextSibling;
	  if (div && div.tagName == 'DIV') {
		parent.removeChild(div);
	  }
	  parent.removeChild(table);
	}
    do_ajax(target_info, 'do_movecopy.php', 'id=' + id + '&move=y&json=' + encoded, done_movecopy );
    return;
  }
  do_ajax(target_info, 'do_movecopy.php', 'id=' + id + '&json=' + encoded, done_movecopy );
}

function do_search()
{
  var show = 0;

  if (gState.id == 'f1') {
    show = start_show;
  }
  top.folderIdsToServer();
  showRightFrame('searchFolders.php?from_id='+gState.id+'&show='+show);
}

function do_move()
{
  do_movecopy(gState, true);
}

function do_copy()
{
  do_movecopy(gState, false);
}

function do_help_folder()
{
  customAlert(
    { title:'Manipulate folders',
      icon:false,
      body:'To insert URL provide full URL<p>To insert LINK use unix path format<p>To insert annotations select them first',
      width:'600px',
      html:true
    });
  return true;
}

function do_delete_symlink()
{
  do_ajax(gState, 'do_delete_symlink.php', 'parent=' + gState.parent + '&id=' + gState.id, done_delete_member);
}

function do_help_symlink()
{
  alert('help with symlink operations on ' + gState.parent + '->' + gState.id);
}

function showMenu(event, menu)
{
  gMenu = document.getElementById(menu);
  var a = event.target;

  gState              = getInfo(a);
  gMenu.style.left    = (event.pageX-10) + 'px';
  gMenu.style.top     = (event.pageY-10) + 'px';
  gMenu.style.display = '';
}

function clickF(event)	/* Click on Folder icon */
{
  var a = event.target;
  var menu;

  gState = getInfo(a);
  if (gState.type == 's') {
	menu = 'symlinkMenu';
  } else if (gState.id == 1) {
	menu = 'rootMenu';
  } else {
	menu = 'folderMenu';
  }
  showMenu(event, menu);
} 

function clickD(event)	/* Click on directory name */
{
  if (event.altKey || event.ctrlKey || event.shiftKey) {
	clickF(event);
	return;
  }
  var a = event.target;
  var menu;

  gState = getInfo(a);
  if (gState.type == 's') {
  	if (gState.id == 1) {
	  menu = 'rootMenu';
	} else {
	  menu = 'folderMenu';
    }
    showMenu(event, menu);
	return;
  }
  do_show_folder();
}

function do_remove_annotation()
{
  do_ajax(gState, 'do_remove_annotation.php', 'parent=' + gState.parent + '&id=' + gState.id, done_delete_member);
}

function do_help_annotation()
{
  alert('help with annotation operations on ' + gState.parent + '->' + gState.id);
}
  
function clickA(event)
{
  showMenu(event, 'annotationMenu');
}

function show_annotation()
{
  showRightFrame('show_annotation.php?annotation_id=' + gState.id );
}

function clickT(event)	/* Annotation title */
{
  if (event.altKey || event.cntrlKey || event.shiftKey) {
	clickA(event);
	return;
  }
  
  var a    = event.target;
  gState   = getInfo(a);
  show_annotation();
}

function do_remove_url()
{
  do_ajax(gState, 'do_remove_url.php', 'parent=' + gState.parent + '&id=' + gState.id, done_delete_member);
}

function do_help_url()
{
  alert('help with url operations on ' + gState.parent + '->' + gState.id);
}
  
function do_remove_image()
{
  do_ajax(gState, 'do_remove_image.php', 'parent=' + gState.parent + '&id=' + gState.id, done_delete_member);
}

function do_help_image()
{
  alert('help with image operations on ' + gState.parent + '->' + gState.id);
}
  
function do_show_url()
{
  var a = gState.a;
  var td;

  for (td = a; td = td.parentNode; ) {
	if (td.tagName == 'TD') {
	  if (td.nextSibling) {
		td = td.nextSibling;
		a  = td.firstChild;
		break;
  } } }
  showRightFrame(a.textContent);
}

function clickU(event)	/* Click on URL icon */
{
  showMenu(event, 'urlMenu');
}

function clickL(event)	/* Click on URL Link */
{
  if (event.altKey || event.ctrlKey || event.shiftKey) {
	clickU(event);
	return;
  }
  gState = getInfo(event.target);
  do_show_url();
}

function clickI(event)	/* Click on image URL */
{
  showMenu(event, 'imageMenu');
}

function clickJ(event)	/* Click on image JPEG Link */
{
  if (event.altKey || event.ctrlKey || event.shiftKey) {
	clickI(event);
	return;
  }
  gState = getInfo(event.target);
  do_show_url();
}

function unload()
{
  annotationIdsToServer();
}

function load()
{
  window.onbeforeunload = function () { unload(); }
}

<?php 
exitJavascript();
srcStylesheet(
  '../css/style.css',
  '../css/alert.css',
  '../css/folders.css'
);
?>
</head>
<body id="background">
<div id=folderTree>
<div style="display:block;">
<table id=f1 summary="f,0,1" cellspacing=0 cellpadding=0 border=0 width="100%"><tbody><tr><td><!--<a onclick="clickF(event)"><img class=foldericon src="../images/project/orootdr.png" /></a>--></td><td width="100%" valign="middle"><a onclick="clickD(event)"></a></td></tr></tbody></table><div><?php

if (!DBconnect()) {
  goto done;
}

$query =
'select 0 as isSymlink, 1 as parent_folder_id, folder_id, name
   from folders
  where parent_folder_id = 1';

if ($show != 2) {
  $query .= '
    and creator_user_id = ' . DBstring($gUserid);
  if ($show == 1) {
    $query .= '
 union all
select 1 as isSymlink, 1 as parent_folder, target_folder_id as folder_id, concat(favouritefolders.name, \' => \', folders.name) as name
  from favouritefolders, folders
 where favouritefolders.user_id = ' . DBstring($gUserid) . '
   and favouritefolders.target_folder_id = folders.folder_id';
} }

emit_children(0, 1, $query);
?></div></div></div>

<!-- Removes pulldown menu over root folder
<div id=rootMenu class="projectMenu" style="display:none">
<table onclick="hideMenu(event)" onmouseout="hideMenu(event)" >
<thead><tr><th>Root</th></tr></thead >
<tbody>
<tr><td onclick="do_show_folder()">Show</td></tr>
<tr><td onclick="do_add_links()">Add Links</td></tr>
<tr><td onclick="do_clear()">Clear</td></tr>
<tr><td onclick="do_search()">Search</td></tr>
<tr><td onclick="do_refresh()">Refresh</td></tr>
</tbody>
</table>
</div>
-->

<!-- Removes several functions in pulldown menu over project folder-->

<div id=folderMenu class="projectMenu" style="display:none">
<table onclick="hideMenu(event)" onmouseout="hideMenu(event)" >
<thead><tr><th>Folder</th></tr></thead >
<tbody>
<!--<tr><td onclick="do_show_folder()">Show</td></tr>-->
<tr><td onclick="do_add_folder()">Add Project</td></tr>
<!--<tr><td onclick="do_add_links()">Add Links</td></tr>-->
<tr><td onclick="do_add_annotations()">Add Annotation</td></tr>
<!--<tr><td onclick="do_add_url()">Add URL</td></tr>
<tr><td onclick="do_annotate()">Annotate</td></tr>
<tr><td onclick="do_search()">Search</td></tr>
<tr><td onclick="do_update_folder()">Update</td></tr>
<tr><td onclick="do_delete_folder()">Delete</td></tr>-->
<tr><td onclick="do_mark_node()">Mark</td></tr>
<tr><td onclick="do_move()">Move</td></tr>
<tr><td onclick="do_copy()">Copy</td></tr>
<!--<tr><td onclick="do_clear()">Clear</td></tr>
<tr><td onclick="do_refresh()">Refresh</td></tr>
<tr><td onclick="do_help_folder()">Help</td></tr>-->
</tbody>
</table>
</div>

<!-- removes symlink pulldown menu 
<div id=symlinkMenu class="projectMenu" style="display:none">
<table onclick="hideMenu(event)" onmouseout="hideMenu(event)" >
<thead><tr><th>Symlink</th></tr></thead >
<tbody>
<tr><td onclick="do_update_link()">Update</td></tr>
<tr><td onclick="do_delete_symlink()">Delete</td></tr>
<tr><td onclick="do_mark_node()">Mark</td></tr>
<tr><td onclick="do_clear()">Clear</td></tr>
<tr><td onclick="do_search()">Search</td></tr>
<tr><td onclick="do_refresh()">Refresh</td></tr>
<tr><td onclick="do_help_symlink()">Help</td></tr>
</tbody>
</table>
</div>
-->

<!-- removes annotation pulldown menu
<div id=annotationMenu class="projectMenu" style="display:none">
<table onclick="hideMenu(event)" onmouseout="hideMenu(event)" >
<thead><tr><th>Annotation</th></tr></thead >
<tbody>
<tr><td onclick="do_move()">Move</td></tr>
<tr><td onclick="do_copy()">Copy</td></tr>
<tr><td onclick="show_annotation()">Show</td></tr>
<tr><td onclick="do_remove_annotation()">Remove</td></tr>
<tr><td onclick="do_annotate()">Annotate</td></tr>
<tr><td onclick="do_mark_node()">Mark</td></tr>
<tr><td onclick="do_clear()">Clear</td></tr>
<tr><td onclick="do_help_annotation()">Help</td></tr>
</tbody>
</table>
</div>
-->

<!-- removes pulldown for URL? - not sure
<div id=urlMenu class="projectMenu" style="display:none">
<table onclick="hideMenu(event)" onmouseout="hideMenu(event)" >
<thead><tr><th>URL</th></tr></thead >
<tbody>
<tr><td onclick="do_show_url()">Show</td></tr>
<tr><td onclick="do_remove_url()">Remove</td></tr>
<tr><td onclick="do_annotate()">Annotate</td></tr>
<tr><td onclick="do_mark_node()">Mark</td></tr>
<tr><td onclick="do_clear()">Clear</td></tr>
<tr><td onclick="do_help_url()">Help</td></tr>
</tbody>
</table>
</div>
-->

<!-- removes pulldown for Image? - not sure
<div id=imageMenu class="projectMenu" style="display:none">
<table onclick="hideMenu(event)" onmouseout="hideMenu(event)" >
<thead><tr><th>Image</th></tr></thead >
<tbody>
<tr><td onclick="do_show_url()">Show</td></tr>
<tr><td onclick="do_remove_image()">Remove</td></tr>
<tr><td onclick="do_annotate()">Annotate</td></tr>
<tr><td onclick="do_mark_node()">Mark</td></tr>
<tr><td onclick="do_clear()">Clear</td></tr>
<tr><td onclick="do_help_image()">Help</td></tr>
</tbody>
</table>
</div>
-->
<?php

close:
DBclose();
done:
bodyFooterFilename();
?>
</body>
</html>
