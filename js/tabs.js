// checkVersion('tabs', 1);

// Hacked from http://www.elated.com/articles/javascript-tabs

function getTabId( url )
{
  var hashPos = url.lastIndexOf ( '#' );
  return url.substring( hashPos + 5 );	// Skip tab_
}

var selectedImage  = null;
var maxTab         = -1;
var tabLeftButton  = null;
var tabRightButton = null;
 
function setLeftRightButtons()
{
  if (tabLeftButton != null) {
	tabLeftButton.style.display = ((selectedImage.pos == 0) ? 'none' : '');
  }
  if (tabRightButton != null) {
	tabRightButton.style.display = ((selectedImage.pos == maxTab) ? 'none' : '');
  }
}

function documentHeight(document)
{
  var height = 0;

  try {
	// This will fail if document is in a cross-site iframe
    var body   = document.body;

    if (body) {
      height = body.scrollHeight;
      if (body.offsetHeight > height) {
  	  height = body.offsetHeight;
      }
      if (body.clientHeight > height) {
  	  height = body.clientHeight;
    } }
  } catch (e) {
  }

  try {
    var html   = document.documentElement;
    if (html) {
  	if (html.scrollHeight > height) {
  	  height = html.scrollHeight;
      }
      if (html.clientHeight > height) {
  	  height = html.clientHeight;
      }
      if (html.offsetHeight > height) {
  	  height = html.offsetHeight;
    } }
  } catch (e) {
  }
  return height;
}

function sizeHtmlIframe1(element)
{
  var height;

  if (element.offsetParent) {
	height  = sizeHtmlIframe1(element.offsetParent);
	height -= element.offsetTop;
	element.style.height = height + 'px';
  }
  return windowInnerHeight(top);
}

function sizeHtmlIframe(iframe)
{
  var content = iframe.contentDocument;
  var height  = 0;

  if (content) {
    height = documentHeight(content);
	if (height) {
	  // Allow for 2px border
	  iframe.style.overflow = 'hidden';
	  //iframe.style.overflow = 'visible';
      // iframe.style.border   = 0;
      iframe.style.height   = (height+4) + "px";
      return;
  } }
  sizeHtmlIframe1(iframe);
}

function loadedHTMLIframe(event)
{
  var iframe = event.target;
  sizeHtmlIframe(iframe);
}

function showTab(id)
{
  var images = top.image_data.images;
  var length = images.length;
  var i, image, a, div;

  // Highlight the selected tab, and dim all others.
  // Also show the selected content div, and hide all others.

  selectedImage = null;
  for (i = 0; i < length; ++i) {
    image = images[i];
	div   = image.div;
    if (div) {	// Deleted images have no div
      a = image.a;
      if (image.id == id) {
		selectedImage = image;
		a.className   = 'selected';
		div.className = (image.isImage ? 'tabImage' : 'tabHtml');

		// Avoid scrolling of image area
		var imageArea = div.parentNode;
		var height    = div.offsetTop + div.clientHeight;
		var rect;

		// Force body to have real height
		// Absolutely positioned tabbed items have no footprint in container
		imageArea.offsetLeft   = 0;
		imageArea.style.height = height + "px";

		if (image.isImage) {
		  // Make image.div same size as image.svgroot
		  var svgroot = image.svgroot;
		  if (svgroot) {
			var rect   = svgroot.getBoundingClientRect();
			height = parseInt(rect.bottom + 1);
			div.style.height = height + "px";
		  }
		} else {
	      var iframes = imageArea.ownerDocument.getElementsByTagName('IFRAME');
		  var length1 = (iframes ? iframes.length : 0);
		  var j, iframe;

		  for (j = 0; j < length1; ++j) {
		    iframe = iframes[j];
		    if (iframe.parentNode == div) {
			  sizeHtmlIframe(iframe);
			  break;
		} } }
	
		setLeftRightButtons();
		tabChanged();
      } else {
		a.className   = '';
		div.className = (image.isImage ? 'tabImage hide' : 'tabHtml hide');
} } } }

function add_tab(image)
{
  var id     = image.id;
  var image_url = image.image_url;
  var images = top.image_data.images; 
  var d      = document;
  var tabs   = d.getElementById('tabs');
  var li     = d.createElement('LI');
  var div, parent, child, img, i, pos1;
  var haveSvg = false;

  image.pos = ++maxTab;
  li.setAttribute('id', 'li_' + id);

  var a     = d.createElement('A');
  var tab   = 'tab_' + id;

  a.setAttribute('href', '#' + tab);
  a.onclick = clickTab;
  a.onfocus = function() { this.blur() };
  a.textContent = id;

  li.appendChild(a);
  tabs.appendChild(li);

  image.li = li;
  image.a  = a;

  div   = d.createElement('div');
  div.setAttribute('id', 'tab_' + id);
  div.setAttribute('class', (image_url ? 'tabImage' : 'tabHtml') + ' hide');

  parent = tabs.parentNode;
  parent.appendChild(div);

  image.div = div;

  if (image_url) {

	image.isImage = true;

	if (image.naturalWidth && image.naturalHeight) {
	  addSvgRoot(image);
	  haveSvg = true;
	} 

	/* Utter stupidity -- This really sucks.. 
	 * SVG img won't fire on the onload attribute and
	 * when we force it to do so by using:
	 * img.addEventListener('load',function(event) { imageLoaded(event)},false);
	 * no natural width and height are available in the svg img object
	 * Because it seems important to know natural width & height trick
	 * html into providing it for us. The dummy image will never be
	 * visible to the user.
	 */
	var dummy = d.createElement('img');
  	dummy.setAttribute('id', 'dummy_' + id);
    dummy.setAttribute('src', image_url);
	dummy.setAttribute('onabort', 'image_error(' + id + ');');
	dummy.setAttribute('onerror', 'image_error(' + id + ');');
    dummy.setAttribute('onload', 'image_loaded(this, ' + id + ');');
	if (haveSvg) {
		dummy.setAttribute('style', 'display:none');
	}
	dummy.setAttribute('width', '100%');
	//dummy.setAttribute('height', '100%');
	div.appendChild(dummy);
	image.dummy = dummy;
  } else {
	var html_url  = image.html_url;
	image.isImage = false;

	div.setAttribute('class', 'tabHtml hide');
    img = d.createElement('iframe');
	img.setAttribute('class', 'tabHtmlIframe');
    img.setAttribute('src', html_url);
	img.setAttribute('scrolling', 'no');
	img.setAttribute('onload', 'loadedHTMLIframe(event)');
/*
	img.setAttribute('vspace', 0);
	img.setAttribute('hspace', 0);
	img.setAttribute('marginwidth', 0);
	img.setAttribute('marginheight', 0);
*/
	img.setAttribute('mozallowfullscreen',true);
	img.setAttribute('webkitallowfullscreen',true);

  	div.appendChild(img);
    image.img  = img;
  }

  tabs.style.display = ((maxTab < 1 && !image.title && !image.description) ? 'none' : '');
}

function delete_tab()
{
  if (selectedImage != null) {

	var images  = top.image_data.images;
	var length  = images.length;
	var pos     = selectedImage.pos;
    var id      = selectedImage.id;
    var element = selectedImage.li;
    var tabs    = element.parentNode;
    var show    = null;
    var i, length, image, pos1;
    
    tabs.removeChild(element);
	selectedImage.li     = null;

    element = selectedImage.div;
    element.parentNode.removeChild(element);

	selectedImage.div    = null;
	selectedImage.img    = null;
	selectedImage.svgcontent = null;
	selectedImage.delete = 'Y';
	selectedImage.pos    = -1;
    selectedImage        = null;
	maxTab             = -1;
    for (i = 0; i < length; ++i) {
      image = images[i];
	  if (image.delete == 'Y') {
        continue;
      }
	  pos1 = image.pos;
	  if (pos < pos1) {
		image.pos = --pos1;
	  }
	  if (maxTab < pos1) {
		maxTab = pos1;
	  }
	  if (!show) {
		show = image;
	} }
	if (show) {
      showTab(show.id);
      tabs.style.display = ((maxTab < 1 && !show.title && !show.description) ? 'none' : '');
    } else {
   	  tabs.style.display = 'none';
	}
    return id;
  }
  return null;
}

function clickTab()
{
  var selectedId = getTabId( this.getAttribute('href') );
 
  showTab(selectedId);
  return false;
}
 
function moveUp(item)
{
  var prev = item.previousSibling;

  if (prev) {
	var parentNode = item.parentNode;

    parentNode.removeChild(item);
	parentNode.insertBefore(item, prev);
  }
}


function tabShiftLeft(image)
{
  var pos = image.pos;

  if (image != null && pos > 0) {

	var images = top.image_data.images;
    var length = images.length;
	var i, image1;

    moveUp(image.li);
	moveUp(image.div);
	
	--pos;
	for (i = 0; i < length; ++i) {
	  image1 = images[i];
	  if (image1.pos == pos) {
		image1.pos = pos+1;
		break;
	} }
	image.pos = pos;
	setLeftRightButtons();
} }

function tabLeft()
{
  tabShiftLeft(selectedImage);
}

function tabRight()
{
  var images = top.image_data.images;
  var length = images.length;
  var pos    = selectedImage.pos + 1;
  var i, image;

  for (i = 0; i < length; ++i) {
	image = images[i];
	if (image.pos == pos) {
	  tabShiftLeft(image);
      return;
  } }
}
