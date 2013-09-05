// checkVersion('images', 1);

function text2xml(sXML)
{
  var ua   = window.navigator.userAgent.toLowerCase();
  var out;
  
  if (ua.indexOf('msie') == -1) {
  	try{
		var dXML = new DOMParser();
		dXML.async = false;
		out = dXML.parseFromString(sXML, "text/xml");
	} catch(e){ 
		throw new Error("Error parsing XML string");
	}
	return out;
  }
  // Special handling for IE
  try{
    var dXML = new ActiveXObject("Microsoft.XMLDOM");
    dXML.async = false;
	out = dXML.loadXML(sXML) ? dXML : false;
  } catch(e){ 
	throw new Error("IE Error parsing XML string"); 
  }
  return out;
}

function setupVersions(image)
{
  var imageVersions = document.getElementById('imageVersions');

  if (imageVersions) {
    var child, version, maxversion, i, option;
	while ((child = imageVersions.firstChild)) {
		imageVersions.removeChild(child);
	}
	if (maxversion = image.maxversion) {
	  version = image.version;
	  for (i = 1; i <= maxversion; ++i) {
		option         = document.createElement('OPTION');
		option.value   = i;
		if (i == version) {
			option.selected = true;
		}
		option.textContent = '' + i;
		imageVersions.appendChild(option);
} }	} }

function doneClickVersion()
{
  if (this.readyState == 4 && this.status == 200) {
    var response = this.responseText;
    if (response != '') {
	  if (response.charAt(0) != '{' || response.charAt(response.length-1) != '}') {
        alert(response);
		return;
	  }
	  var result;
	  try {
		result = JSON.parse(response);
		if (!result) {
		  alert('JSON.parse returned null on ' + response);
		  return;
		}
	  } catch (e) {
		alert('JSON.parse error ' + e + ' on ' + response);
		return;
	  }
	  var image   = this.imagematState;
	  var dummy   = image.dummy;
	  var svgroot = image.svgroot;
	  var setup   = false;

	  image.html_url      = result.html_url;
	  image.modified      = result.modified;
	  image.archived      = result.archived;
	  image.naturalWidth  = result.naturalWidth;
	  image.naturalHeight = result.naturalHeight;
	  image.version       = result.version;
	  image.markups       = result.markups;

	  if (result.version == image.maxversion && result.history) {
		image.version    = result.version;
		image.maxversion = result.version + 1;
		setup            = true;
	  } else if (image.version != result.version) {
		image.version    = result.version;
	    setup            = true;
	  }
	  if (setup) {
		setupVersions(image);
	  }
	  if (svgroot != null) {
	    var div = image.div;
		if (div.firstChild == svgroot) {
		  div.removeChild(svgroot);
		}
		image.svgroot = null;
	  }
	  if (image.naturalWidth && image.naturalHeight) {
		addSvgRoot(image);
		dummy.setAttribute('style', 'display:none');
	  } else {
		dummy.setAttribute('style', 'display:');
	  }
    }
  }
}

function clickVersion(select)
{
  var selectedIndex = select.selectedIndex;

  if (selectedIndex < 0) {
	return;
  }
  var image   = window.selectedImage;
  if (!image) {
	return;
  }
  var markup_id = image.markup_id;
  if (!markup_id) {
	return;
  }
  var option  = select.options[selectedIndex]
  var version = option.value;

  do_ajax(image, '../annotate/do_get_image_version.php', 'markup_id=' + markup_id + '&version=' + version, doneClickVersion);
}

var gLastShowInfo = null;

function tabChanged()
{
  var image        = window.selectedImage;
  var d            = document;
  var markupButton = d.getElementById('markup');


  if (gLastShowInfo) {
	removeCustomAlert('alertBox' + gLastShowInfo, false);
	gLastShowInfo = null;
  }

  if (markupButton) {
	markupButton.style.display = (image.isImage ? '' : 'none');
  }

  setupVersions(image);

  attachZoomToImage();
}

function addSvgRoot(image)
{
  if (image.svgroot != null && (image.div.firstChild == image.svgroot)) {
	// Already loaded svg earlier
	return;
  }

  var id            = image.id;
  var naturalWidth  = image.naturalWidth;
  var naturalHeight = image.naturalHeight;
  var markups       = image.markups;
  var lth           = (markups ? markups.length : -1);
  var markup, i;

  // Even if no markup load svg for zoom purposes

  xml = 
'<svg xmlns="http://www.w3.org/2000/svg"\
      xmlns:xlink="http://www.w3.org/1999/xlink"\
      xmlns:html="http://www.w3.org/1999/xhtml"\
      xmlns:math="http://www.w3.org/1998/Math/MathML"\
      xmlns:se="http://svg-edit.googlecode.com"\
      width="100%"\
      style="position:absolute;left:0px;top:0px:overflow:visible"\
      viewBox="0 0 ' + naturalWidth + ' ' + naturalHeight + '"\
><defs><filter id="canvasshadow" filterUnits="objectBoundingBox"\
><feGaussianBlur in="SourceAlpha" stdDeviation="4" result="blur"\
/><feOffset in="blur" dx="5" dy="5" result="offsetBlur"\
/><feMerge><feMergeNode in="offsetBlur" /><feMergeNode in="SourceGraphic"\
/></feMerge></filter></defs\
><svg id="svgimage_' + id + '"\
      style="pointer-events:none"\
	   x="0" y="0" width="' + naturalWidth + '" height="' + naturalHeight + '"\
><image id="img_' + id + '"\
       xlink:title="image to annotate"\
       style="pointer-events:none"\
       width="100%" height="100%"\
/></svg>';

  var xml1 = '';

  for (i = 0; i < lth; ++i) {
    markup = markups[i];
    
    if (markup.svg) {
	  xml1 += '<g>' + markup.svg + '</g>';
  }	}
  if (xml1 != '') {
	xml +=
'<svg id="svgcontent_' + id + '"\
      overflow="hidden"\
	  x="0" y="0" width="' + naturalWidth + '" height="' + naturalHeight + '"\
>' + xml1 + '</svg>';
  }

  xml += '</svg>';

  var d = text2xml(xml);
  if (!d || !d.documentElement) {
	alert('addSvgRoot: Can\'t form svgroot');
	return;
  }

  var div           = image.div;
  var child, img;
 
  if (!div) {
	alert('div null');
  }
/*  Breaks full-screen mode --- no idea why
  while ((child = div.firstChild)) {
	// Remove the image if added to determine image size
	div.removeChild(child);
  }
*/

  child = d.documentElement;

  div.appendChild(child);
  img = document.getElementById('img_' + id);
  img.setAttributeNS('http://www.w3.org/1999/xlink','href',image.image_url);
  image.img        = img;
  image.svgroot    = child;
  image.svgimage   = document.getElementById('svgimage_' + id);
  image.svgcontent = child = document.getElementById('svgcontent_' + id);

  if (child) {
	child = child.firstChild;
	for (i = 0; i < lth; ++i) {
	  markup = markups[i];
	  if (markup.svg) {
		add_tooltip(child, markup.title, markup.description);
		child = child.nextSibling;
  } } }

  attachZoomToImage();
}

function image_loaded(element, index)
{
  var image         = top.image_data.images[index];
  var naturalWidth  = element.naturalWidth;
  var naturalHeight = element.naturalHeight;

  if (!naturalWidth || !naturalHeight) {
	alert(image.image_url + ' size ' + naturalWidth + 'x' + naturalHeight);
	return true;
  }
  if (image.naturalWidth != naturalWidth || image.naturalHeight != naturalHeight) {
	if (image.naturalWidth || image.naturalHeight) {
	  alert(image.image_url + ' size changed from ' + image.naturalWidth + 'x' + image.naturalHeight + ' to ' + naturalWidth + 'x' + naturalHeight);
	}
	image.naturalWidth  = naturalWidth;
	image.naturalHeight = naturalHeight;

	addSvgRoot(image);
  }

  return true;
}

function add_tooltip(element, title, description)
{
  var tip = '';

  if (title) {
	tip = '<strong>' + escapeHTML(title) + '</strong>';
  }
  if (description) {
	tip += description;
  }
  if (tip == '') {
	element.setAttribute('onmouseover', null);
	element.setAttribute('onmouseout',  null);
  } else { 
	tip = 'tooltip.base64(\'' + base64_encode(tip) + '\',null,\'markuptt\',this);';
	element.setAttribute('onmouseover', tip);
	element.setAttribute('onmouseout', 'tooltip.hide();');
} }

function add_url_info(image, index, silent)
{
  var d           = document;
  var citation_id = image.citation_id;
  var path, url, html;

  if (citation_id) {
    path = window.location.pathname.split('/');
	image.html_url = window.location.protocol + '//' +
                     window.location.host + '/' +
                     path[1] +
                     '/annotate/simple_view.php?annotation_id='+citation_id;
  } else {
    url  = trim(image.image_url);
    html = trim(image.html_url);

	if (url == '') {
	  url = null;
	}
	image.image_url = url;

	if (html == '') {
      html = null;
	} else if (!silent && typeof html == 'string') { 
      if (html[0] != '.') {
		if (html.indexOf(':') < 0) {
		  if (html[0] != '/') {
			html = '//' + html;
	  } } }
	}
    image.html_url = html;

    if (!url && !html) {
      return null;
  } }

  if (maxTab < 0) {
    var deleteButton   = d.getElementById('delete');
    var markupButton   = d.getElementById('markup');
    var showButton     = d.getElementById('show');
	var imageControls2 = d.getElementById('imageControls2');

	if (deleteButton != null) {
      deleteButton.style.display = '';
    }
    if (markupButton != null) {
	  markupButton.style.display = '';
    }
	if (showButton != null) {
      showButton.style.display = '';
    }
	if (imageControls2 != null) {
	  imageControls2.style.display = '';
  } }

  // Add the tab tooltip
  add_tab(image);
  add_tooltip(image.li, image.title, image.description);
  if (!silent) {
    showTab(image.id);
  }
}

function add_tabs()
{
  var image_data = top.image_data;
  var images = image_data.images;
  var lth    = (images ? images.length : -1);
  var i, image;

  for (i = 0; i < lth; ++i) {
	image = images[i];
    add_url_info(image, i, true);
  }
}

function showFirstTab()
{
  var image_data = top.image_data;
  var images = image_data.images;
  var lth    = (images ? images.length : -1);
  var start_tab = (image_data.start_tab ? (image_data.start_tab - 1) : null);
  var first  = null;
  var i, image;

  for (i = 0; i < lth; ++i) {
	image = images[i];
    if (!first || image.pos == start_tab ) {
      first = image;
    }
  }
  if (first) {
    showTab(first.id);
  }
}

function doneClickInfo(state)
{
  var image    = window.selectedImage;

  if (image == null) {
    alert('doneClickInfo: No selected tab');
    return;
  }
  if (top.image_data.readonly) {
	return;
  }
  var layers = state.layers;
  var i, value, input, markups, markup, dirtyTooltip, svgcontent, child;
  var tip, li;

  input = document.getElementById('showHtmlUrl');
  if (input) {
	image.html_url = input.value;
  }

  markups = image.markups;
  markup  = null;
  svgcontent = null;
  for (i = 0; i <= layers; ++i) {
	dirtyTooltip = false;
    input = document.getElementById('showTitles[' + i + ']');
	if (input) {
	  value = input.value;
	  if (markup) {
		if (value != markup.title) {
		  markup.title = value;
		  image.dirtyLayers = true;
		  dirtyTooltip = true;
		}
	  } else {
		if (image.title != value) {
		  image.title = value;
		  dirtyTooltip = true;
	} }	}
	  
    editor = CKEDITOR.instances['showDescs[' + i + ']'];
    if (editor && editor.checkDirty()) {
	  value = editor.getData();
	  if (markup) {
		if (value != markup.description) {
		  markup.description = value;
		  image.dirtyLayers = true;
		  dirtyTooltip = true;
		}
	  } else {
		if (image.description != value) {
		  image.description   = value;
		  dirtyTooltip = true;
	} } }
	if (!i) {
	  if (dirtyTooltip) {
	    add_tooltip(image.li, image.title, image.description);
	  }
  	  svgcontent = image.svgcontent;
	  if (!svgcontent) {
	  	child      = null;
	  } else {
	    for (child = svgcontent.firstChild; child; child = child.nextSibling) {
	  	  if (child.nodeType == 1 && child.tagName == 'g') {
			break;
	  } } }
	} else {
	  if (dirtyTooltip) {
		if (!child) {
		  alert('doneClickInfo:missing child');
		} else {
		  add_tooltip(child, markup.title, markup.description);
	  } }
      if (child) {
		while ((child = child.nextSibling)) {
	  	  if (child.nodeType == 1 && child.tagName == 'g') {
			break;
	} } } }
	
	markup = markups[i];
} }

function clickAllNoImg(a)
{
  var icon  = a.firstChild;
  var image = window.selectedImage;
  var img   = image.img;

  switch (icon.alt) {
  case 'blur':
	img.style.display = '';
	img.style.opacity = 0.2;
	icon.src = '../images/annotate/none.png';
    icon.alt = 'none';
	break;
  case 'none':
	img.style.display = 'none';
	icon.src = '../images/annotate/picture.png';
	icon.alt = 'image';
	break;
  default:
	img.style.display = '';
	img.style.opacity = 1.0;
	icon.src = '../images/annotate/picture-blur.png';
	icon.alt = 'blur';
  }
}

var currentLayer = -1;
var hideOtherSvg = false; /* Don't hide other svg when this svg shown */

function getSvgLayer()
{
  var image      = window.selectedImage;
  var svgcontent = image.svgcontent;

  if (svgcontent) {
    var child = svgcontent.firstChild,
		i     = 0;

	for (; child; child = child.nextSibling ) {
	  if (child.nodeType == 1 && child.tagName == 'g') {
	    if (i == currentLayer) {
		  return child;
	    }
		++i;
  } } }
  alert('getSvgLayer: Failed to find layer');
  return null;
}

function displayAllSvgLayers(value)
{
  var image      = window.selectedImage;
  var svgcontent = image.svgcontent;

  if (svgcontent) {
    var child;

	for (child = svgcontent.firstChild; child; child = child.nextSibling ) {
	  if (child.nodeType == 1 && child.tagName == 'g') {
		child.style.display = value;
} } } } 

function isAllSvgShowing()
{
  var image      = window.selectedImage;
  var svgcontent = image.svgcontent;

  if (svgcontent) {
    var child;

	for (child = svgcontent.firstChild; child; child = child.nextSibling ) {
	  if (child.nodeType == 1 && child.tagName == 'g') {
		if (child.style.display == 'none') {
		  return false;
  } } } }
  return true;
}

function clickAllNoSvg(a)
{
  var icon = a.firstChild;

  if (icon.alt == 'none') {
	displayAllSvgLayers('none');
	icon.src = '../images/annotate/all.png';
	icon.alt = 'all';
  } else {
	displayAllSvgLayers('');
	icon.src = '../images/annotate/none.png';
	icon.alt = 'none';
} }

function clickSvgEye(a)
{
  var icon = a.firstChild;
  var visible;

  if (currentLayer < 0) {
  	var image = window.selectedImage;
	if (image.li.hideThisTooltip) {
	  image.li.hideThisTooltip = false;
	  visible = true;
	} else {
	  image.li.hideThisTooltip = true;
	  visible = false;
	}
  } else {
    var svg   = getSvgLayer();
	var hide  = false;
	var selected;

	if (svg.style.display == 'none') {
	  if (hideOtherSvg) {
		displayAllSvgLayers('none');
		hide = true;
	  }
	  svg.style.display = '';
	  visible = true;
	  selected = true;
	} else {
	  svg.style.display = 'none';
	  visible = false;
	  selected = false;
	}
  }
  if (visible) {
	icon.src = '../images/annotate/eye-close.png';
	icon.alt = 'hide';
  } else {
	icon.src = '../images/annotate/eye.png';
	icon.alt = 'show';
} }

function clickSingleMulti(a)
{
  var icon = a.firstChild;

  if (icon.alt == 'single') {
	icon.src = '../images/annotate/multi.png';
	icon.alt = 'multi';
	hideOtherSvg = true;
  } else {
	icon.src = '../images/annotate/layer.png';
	icon.alt = 'single';
	hideOtherSvg = false;
} }

function clickChangeLayer(select)
{
  var image   = window.selectedImage;
  var options = select.options;
  var lth     = options.length;
  var i, option, title, desc, hide, icon, svg, visible;

  icon = document.getElementById('imgSvgEye');
  for (i = 0; i < options.length; ++i) {
    option = options[i];
	title  = document.getElementById('showTitles[' + i + ']');
    desc   = document.getElementById('cke_showDescs[' + i + ']');
	if (option.selected) {
	  hide = '';
	  currentLayer = i - 1;
	  if (icon) {
	    if (i == 0) {
		  visible = (image.li.hideThisTooltip ? false : true);
		} else {
		  svg     = getSvgLayer();
          visible = (svg && svg.style.display == 'none' ? false : true);
		}
		if (visible) {
		  icon.src = '../images/annotate/eye-close.png';
		  icon.alt = 'hide';
		} else {
	  	  icon.src = '../images/annotate/eye.png';
		  icon.alt = 'show';
	  } }
	} else {
	  hide = 'none';
	}
    for (;title.tagName != 'TR'; title = title.parentElement);
	for (;desc.tagName  != 'TR'; desc  = desc.parentElement);
	title.style.display = hide;		
	desc.style.display  = hide;
  }
}

function clickHelpInfo()
{
  customAlert( { title:'Help on Image Visualisation', icon:'help.png', body:
'This dialog box permits one to visualise and change the text associated with the tab and image markup.\
<br>Use the buttons at the top to:\n\
<br>1. Set the image visible | blurred | hidden (Image)\n\
<br>2. Set all image markup visible | hidden (Markup)\n\
<br>3. Set the selected layer visible | hidden (Layer)\n\
<br>4. Show single | multiple layers of markup (Multi)' , html:true }
 );
}

function clickInfo()
{
  if (alertStillActive(gLastShowInfo, self)) {
	return;
  }

  var image  = window.selectedImage;

  if (image == null) {
    alert('clickInfo: No selected tab');
    return;
  }
  var img       = image.img;
  var width     = Math.floor((windowInnerWidth(self) * 4)/5);
  var width1    = width - 80;
  var style     = ' style="width:' + width1 + 'px" ';
  var image_url = (image.image_url ? trim(image.image_url) : '');
  var html_url  = (image.html_url  ? trim(image.html_url) : '');
  var title     = image.title;
  var description = image.description;
  var markups   = image.markups;
  var lth1      = (markups ? markups.length : 0);
  var j, markup, svg, selected;
  var readonly  = (top.image_data.readonly ? ' readonly' : '');

  msg = '<form>\n<table>\n<tbody>\n<col style="width:50px;" />\n';

  msg += '<tr><td></td><td>';
  if (image_url != '' && img) {
	msg += 'Image: <a href="#" onclick="clickAllNoImg(this);"><img src="../images/annotate/';
	if (img.display == 'none') {
	  msg += 'picture.png" alt="image"';
	} else if (img.style.opacity == 0.2) {
	  msg += 'none.png" alt="none"';
	} else {
	  msg += 'picture-blur.png" alt="blur"';
	}
	msg += '></img></a>';
  }
  if (lth1 > 0) {
	msg += '&nbsp;Markup: <a href="#" onclick="clickAllNoSvg(this);"><img src="../images/annotate/';
	if (isAllSvgShowing()) {
	  msg += 'none.png" alt="none"';
	} else {
	  msg += 'all.png" alt="all"';
	}
	msg += '></img></a>';

    msg += '&nbsp;Layer: <a href="#" onclick="clickSvgEye(this);"><img id="imgSvgEye" src="../images/annotate/';
	if (image.li.hideThisTooltip) {
	  msg += 'eye.png" alt="show"';
	} else {
	  msg += 'eye-close.png" alt="hide"';
	}
	msg += '></img></a>';

	msg += '&nbsp;Multi: <a href="#" onclick="clickSingleMulti(this)"><img src="../images/annotate/';
	if (hideOtherSvg) {
      msg += 'multi.png" alt="multi"';
	} else {
      msg += 'layer.png" alt="single"';
	}
	msg += '></img></a>';
  }

  msg += '&nbsp;Help: <a href="#" onclick="clickHelpInfo()"><img src="../images/annotate/question.png"></img></a></td></tr>\n';

  if (image_url == '' && html_url == '') {
	msg += '<tr><td>*Unknown*</td></tr>\n';
  } else {
    if (image_url != '') {
	  msg += '<tr><td align=right>IMG:</td><td>' + escapeHTML(image_url) + '</td></tr>\n';
	  msg += '<tr><td align=right>HTML:</td><td><input' + readonly + ' id=showHtmlUrl type="text"' + style + 'value="' + escapeHTML(html_url) + '" /></td>';
	} else {
	  msg += '<tr><td align=right>HTML:</td><td>' + escapeHTML(html_url) + '</td>';
	}
	if (image.citation_id) {
	  msg += '<tr><td align=right>Ann#:</td><td>' + escapeHTML(image.citation_id) + '</td>';
  } }
  msg += '</tr>\n';
  if (lth1 > 0) {
    msg += '<tr><td>Layer:</td><td><select onchange="clickChangeLayer(this)" >\n';
    selected = ' selected';
    for (j = 0; ; ++j) {
	  msg +='<option value=' + j + selected + '>' + (title ? escapeHTML(title) : '') + '</option>\n';
	  if (lth1 <= j) {
		break;
	  }
	  selected = '';
	  title = markups[j].title;
    }
	currentLayer = -1;
	msg += '</select>\n</td></tr>\n';
  }
  title = image.title;
  if (!title) {
	title = '';
  }
  msg += '<tr><td align=right>Title:</td><td><input' + readonly + ' id="showTitles[0]" type=text' + style + 'value="' + escapeHTML(title) + '" /></td></tr>\n';
  if (!description) {
	description = '';
  }
  msg += '<tr><td align=right>Desc:</td><td><textarea' + readonly + ' id="showDescs[0]" name=edit' + style + 'rows=6>' + escapeHTML(description) + '</textarea></td></tr>\n';

  for (j = 0; j < lth1;) {
	markup = markups[j++];
	title  = markup.title;
	svg = markup.svg;
    if (!title) {
	  title = '';
	}
	msg += '<tr style="display:none" ><td align=right>Title ' + j + ':</td><td><input' + readonly + ' id="showTitles['+j+']"' + style + 'value="' + escapeHTML(title) + '" /></td></tr>\n';
	description = markup.description;
	if (!description) {
	  description = '';
	}
	msg += '<tr style="display:none" ><td align=right>Desc ' + j + ':</td><td><textarea' + readonly + ' id="showDescs['+j+']" name=edit' + style + 'rows=6>' + escapeHTML(description) + '</textarea></td></tr>\n';
	if (svg && svg != '') {
	  msg += '<tr style="display:none" ><td align=right>SVG ' + j + ':</td><td>' + escapeHTML(svg) + '</td></tr>\n';
  } }
  msg += '<tr><td align=right>Size:</td><td>' + image.naturalWidth + 'x' + image.naturalHeight + '</td></tr>\n';
  if (image.modified) {
    msg += '<tr><td align=right>Modified:</td><td>' + image.modified + '</td></tr>\n';
  }
  if (image.archived) {
    msg += '<tr><td align=right>Archived:</td><td>' + image.archived + '</td></tr>\n';
  }
  msg += '</tbody>\n</table>\n</form>';

  var options = { title:'Image Info', icon:false, body:msg, width:width, html:true, state:{layers:lth1}, editor:false };
  if (readonly != '') {
	options.readonly=true;
  } else {
	options.buttons = { Update:doneClickInfo };
  }
  gLastShowInfo = customAlert( options );
}

function sendMarkup(image)
{
  var image1     = null;
  var markup_id  = image.markup_id;
  var image_data = top.image_data;
  var annotation_id = image_data.annotation_id;
  var force   = !annotation_id || !markup_id;
  var markups = image.markups;
  var lth     = (markups ? markups.length : -1);
  var dirtySummarys = force;
  var dirtyLayers = force || image.dirtyLayers;
  var title, description, old, svg, markup, i;

  title       = (image.title ? trim(image.title) : '');
  description = (image.description ? trim(image.description) : '');
  if (!dirtySummarys) {
  	old   = (image.orig_title ? image.orig_title : '');
    if (title != old) {
	  dirtySummarys = true;
    } else {
 	  old = (image.orig_description ? image.orig_description : '');
      if (description != old) {
		dirtySummarys = true;
  } } }
  if (dirtySummarys) {
	image.orig_title       = title;
   	image.orig_description = description;
	image.dirtySummarys    = false;
    image1       = {};
	image1.title = title;
	image1.description = description;
  }
				
  if (!dirtyLayers && markups) {
	dirtyLayers = true;
	for (i = 0; ; ) {
	  if (i == lth) {
		dirtyLayers = false;
		break;
	  }
	  markup = markups[i++];
	  title = (markup.title ? markup.title : '');
	  old   = (markup.orig_title ? markup.orig_title : '');
	  if (title != old) {
		break;
	  }
	  description = (markup.description ? markup.description : '');
	  old         = (markup.orig_description ? markup.orig_description : '');
	  if (description != old) {
		break;
	  }
	  svg = (markup.svg ? markup.svg : '');
	  old = (markup.orig_svg ? markup.orig_svg : '');
	  if (svg != old) {
		break;
  } } }
  if (dirtyLayers) {
	var markups1, markup1;
	image.dirtyLayers = false;
	if (image1 == null) {
	  image1 = {};
	}
	image1.markups = markups1 = [];
	for (i = 0; i < lth; ++i) {
	  markup      = markups[i];
	  markups1[i] = markup1 = {};
	  markup1.title       = markup.title;
	  markup1.description = markup.description;
	  markup1.svg         = markup.svg;
	  markup.orig_title   = markup.title;
	  markup.orig_description = markup.description;
	  markup.orig_svg         = markup.svg;
  }	}

  if (dirtySummarys || dirtyLayers) {
	var naturalWidth  = image.naturalWidth;
	var naturalHeight = image.naturalHeight;

	image1.markup_id     = markup_id;
	image1.maxversion    = image.maxversion;
    if (naturalWidth  != image.orig_naturalWidth ||
      naturalHeight != image.orig_naturalHeight) {
	  image1.naturalWidth      = naturalWidth;
	  image1.naturalHeight     = naturalHeight;
	  image.orig_naturalWidth  = naturalWidth;
	  image.orig_naturalHeight = naturalHeight;
	}
	return image1;
  }
  return null;
}

function add_new_url_info(image_url, html_url)
{
  var image_data = top.image_data;
  var images     = image_data.images;
  var lth        = images.length;
  var image      = { id:lth, image_url:image_url, html_url:html_url, markups:[] };

  images.push(image);
  add_url_info(image, lth, false);
}

function addAnnotations()
{
  var refs           = top.refAnnotations_lth;
  if (refs) {
    var refAnnotations = top.refAnnotations;
	var image_data     = top.image_data;
	var images         = image_data.images;
	var lth            = (images ? images.length : 0);
	var image, i, citation_id;

	for (i = 0; i < refs; ++i) {
	  citation_id = refAnnotations[i];
	  image = { id:lth, citation_id:citation_id };
	  images.push(image);
      add_url_info(image, lth, false);
	  ++lth;
} } }

function doClickAddAnnotations()
{
  var left  = top.frames[0];
  var right = top.frames[1];
  var extra = top.frames[2];

  left  = left.frameElement;
  right = right.frameElement;
  extra = extra.frameElement;
  extra.style.height = left.height + 'px';
  if (right.style.display == 'none') {
	extra.style.width  = '100%';
	extra.style.margin = '0 0 0 0';
  } else {
    extra.style.width  = "49.5%";
	extra.style.margin = '0 50% 0 0';
  }
  extra.src = '../annotate/search1.php?addAnnotate=y';
  left.style.display  = 'none';
  extra.style.display = '';
}

function doClickAddUrl()
{
  var d       = document;
  var urlbox  = d.getElementById('urlbox');
  if (!urlbox) {
    alert('Missing urlbox');
    return false;
  }
  var htmlbox = d.getElementById('htmlbox');
  if (!htmlbox) {
    alert('Missing htmlbox');
    return false;
  }
  add_new_url_info(urlbox.value, htmlbox.value);
}

function clickAddUrl()
{
  customAlert(
	{ title:"Paste URL into space below",
	  icon:false,
	  html:true,
      body:"\
<table>\
<tbody>\
<tr>\
<td>Image URL:</td>\
<td><input id=urlbox type='text' name='url' size='60' value='' /></td>\
</tr>\
<tr>\
<td>HTML URL:</td>\
<td><input id=htmlbox type='text' name='html' size='60' value='' /></td>\
</tr>\
</tbody>\
</table>",
	  width: 500,
	  editor:false,
	  buttons:{ "Add Url":doClickAddUrl, /*"Add Annotations":doClickAddAnnotations */}
	}
  );

  return false;
}

function markup_url()
{
  var image = window.selectedImage;

  if (image == null) {
    alert('No selected tab to markup');
    return;
  }

  top.markup_background = {
    image:image,
	lang:top.mylanguage
  };
  // full_frame();
  submitPost({ action:'../markup/svg-editor.php',
               parameters:{ image_url:image.image_url, html_url:image.html_url}
             });
}

function clickDeleteUrl()
{
  delete_tab();

  if (maxTab < 0) {
    var d              = document;
    var deleteButton   = d.getElementById('delete');
    var markupButton   = d.getElementById('markup');
    var showButton     = d.getElementById('show');
	var imageControls2 = d.getElementById('imageControls2');

    if (deleteButton) {
      deleteButton.style.display   = 'none';
	}
	if (markupButton) {
      markupButton.style.display   = 'none';
	}
    if (showButton) {
      showButton.style.display     = 'none';
    } 
    if (imageControls2) {
	  imageControls2.style.display = 'none';
  } }
}

function image_error(index)
{
  var image = top.image_data.images[index];

  selectedImage = image;
  if (!image.markup_id) {
    clickDeleteUrl();
  }
  customAlert(
	{
	  title:'Image not found',
	  icon:'error.png',
	  body:'Unable to load image "' + image.image_url + '"'
	}
  );
}

function clickUploadUrl()
{
  var url  = 'http://mat.uwaterloo.ca/dev/jpg/landscape-10.jpg';
  var html = 'http://mat.uwaterloo.ca';
  add_new_url_info( url, html);
}

function clickResizeImage()
{
  toggle_frames();
  setCurrentZoom();
}

function image_page_loaded()
{
  label_frame_button();
  tabLeftButton  = document.getElementById('tabLeftBtn');
  tabRightButton = document.getElementById('tabRightBtn');
  setupZoomButton();
  add_tabs();
  showFirstTab();
}

