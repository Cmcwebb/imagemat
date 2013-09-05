// checkVersion('alert', 1);

// http://www.brainjar.com/dhtml/drag/default.asp
// Drag functionality

function AlertBrowser()
{
  var ua, i;

  this.isIE    = false;

  // Determine if IE
  ua = navigator.userAgent;
  if ((i = ua.indexOf('MSIE')) >= 0) {
    this.isIE = true;
  }
}

var gAlertBrowser = null;

function dragAlertStop(event)
{
  if (gAlertBrowser.isIE) {
    document.detachEvent("onmousemove", dragAlertGo);
    document.detachEvent("onmouseup",   dragAlertStop);
  } else {
    document.removeEventListener("mousemove", dragAlertGo,   true);
    document.removeEventListener("mouseup",   dragAlertStop, true);
  }
}

function dragAlertGo(event)
{
  var e, node;
  var x, y;

  if (!event) {
    e = window.event;
  } else {
    e = event;
  }
  if (e.target) {
    node = e.target;
  } else if (e.srcElement) { // IE
    node = e.srcElement;
  } else {
    dragAlertStop(null);
    return;
  }
  for (;; node = node.parentNode) {
    if (!node) {
      dragAlertStop(null);
      return;
    }
    if (node.className && node.className == 'alertBox') {
      break;
  } }

  // Get cursor position with respect to the page.

  if (gAlertBrowser.isIE) {
    x = e.clientX + document.documentElement.scrollLeft + document.body.scrollLeft;
    y = e.clientY + document.documentElement.scrollTop  + document.body.scrollTop;
    // Move drag element by the same amount the cursor has moved.
    node.style.left = (node.startLeft + x - node.cursorStartX) + "px";
    node.style.top  = (node.startTop  + y - node.cursorStartY) + "px";
    e.cancelBubble = true;
    e.returnValue = false;
  } else {
    x = e.clientX + window.scrollX;
    y = e.clientY + window.scrollY;
    // Move drag element by the same amount the cursor has moved.
    node.style.left = (node.startLeft + x - node.cursorStartX) + "px";
    node.style.top  = (node.startTop  + y - node.cursorStartY) + "px";
    event.preventDefault();
  }
}

function dragAlertStart(event)
{
  var e, node;
  var x, y;

  //alert('dragAlertStart');
  if (gAlertBrowser == null) {
    gAlertBrowser = new AlertBrowser();
  }
  if (!event) {
    e = window.event;
  } else {
    e = event;
  }
  if (e.target) {
    node = e.target;
  } else if (e.srcElement) { // IE
    node = e.srcElement;
  } else {
    return;
  }
  for (;; node = node.parentNode) {
    if (!node) {
      return;
    }
    if (node.className && node.className == 'alertBox') {
      break;
  } }

  // Get cursor position with respect to the page.

  if (gAlertBrowser.isIE) { // IE
    x = e.clientX + document.documentElement.scrollLeft + document.body.scrollLeft;
    y = e.clientY + document.documentElement.scrollTop  + document.body.scrollTop;
  } else {
    x = e.clientX + window.scrollX;
    y = e.clientY + window.scrollY;
  }

  node.cursorStartX = x;
  node.cursorStartY = y;
  x  = parseInt(node.style.left, 10);
  if (isNaN(x)) x = 0;
  node.startLeft  = x;
  y  = parseInt(node.style.top,  10);
  if (isNaN(y)) y = 0;
  node.startTop   = y;

  // Capture mousemove and mouseup events on the page.

  if (gAlertBrowser.isIE) {
    document.attachEvent("onmousemove", dragAlertGo);
    document.attachEvent("onmouseup",   dragAlertStop);
    window.event.cancelBubble = true;
    window.event.returnValue = false;
  } else {
    document.addEventListener("mousemove", dragAlertGo,   true);
    document.addEventListener("mouseup",   dragAlertStop, true);
    event.preventDefault();
  }
}

// http://javascript.internet.com/miscellaneous/custom-alert-box.html
// This script and many more are available free online at
// The JavaScript Source!! http://javascript.internet.com
// Created by: Steve Chipman | http://slayeroffice.com/ */

// over-ride the alert method only if this a newer browser.
// Older browser will see standard alerts
/*
if(document.getElementById) {
  window.alert = function(txt) {
    customAlert( { body:txt } );
  }
}
*/

function alertStillActive(id, target)
{
  if (id != null) {
    d = target.document;
    alert = d.getElementById('alertBox' + id);
    return (alert != null);
  }
  return false;
}

var gAlertId      = 0;
var gAlertCounter = -1;
var gAlerts       = 0;

function customAlert(options)
{
  var target, body, d, alertObj, h1, p, img, button, div, btn, fun;
  var title, icon, body, buttons, state;
  var left, shift, top1, maxheight, height, columns, col;
  var table, tbody, tr, td;
  var alertCounter = ++gAlertCounter;
  var id = 'alertBox' + (++gAlertId);
  var editor = null;

  ++gAlerts;

  if (options == undefined) {
    options = {};
  }

  if (options.editor != undefined) {
	editor = options.editor;
  }
  // shortcut reference to the document object
  if (options.target) {
    target = options.target;
  } else {
    target = self;
  }
  d = target.document;

  // create the modalContainer div as a child of the BODY element
  alertObj = d.body.appendChild(d.createElement("div"));
  alertObj.id = id;
  if (options.width) {
    alertObj.style.width = options.width + "px";
  } else {
    alertObj.style.width = "300px";
  }

  shift = alertCounter*10;
  if (options.fifo == true) {
    alertObj.style.zIndex = 10000 - alertCounter;
    shift = 0 - shift;
  } else {
    alertObj.style.zIndex = 9000 + alertCounter;
  }
  alertObj.className  = "alertBox";

  if (options.title == undefined) {
    title = 'Alert!';
  } else {
    title = options.title;
    if (title == false) {
      title = '&nbsp;';
  } }

  if (options.icon == undefined) {
    icon = "default.png";
  } else {
    icon = options.icon;
  }
  if (icon == false) {
    icon = '';
  } else {
    icon = 'http://mat.uwaterloo.ca/imagemat/images/alert/' + icon; 
  }

  if (options.body == undefined) {
    body = '';
  } else {
    body = options.body;
  }

  // create an H1 element as the title bar
  h1 = alertObj.appendChild(d.createElement("h1"));
  h1.appendChild(d.createTextNode(title));
  img = h1.appendChild(d.createElement('img'));
  img.src = 'http://mat.uwaterloo.ca/imagemat/images/alert/close.png';
  img.align='right';
  img.onclick = function() { target.removeCustomAlert(id, editor); return false; }

  h1.onmousedown= function(event) { target.dragAlertStart(event); }

  // create a paragraph element to contain the txt argument
  if (body != '' || icon != '') {
    p = alertObj.appendChild(d.createElement('p'));
    if (options.html != true) {
      img = p.appendChild(d.createElement('img'));
      img.src = icon;
      img.className = 'alertImg';
      p.appendChild(d.createTextNode(body));
    } else {
      if (icon != '') {
        icon = '<img src="' + icon + '" class="alertImg"></img>'; 
      }
      p.innerHTML = icon + body;
      if (editor != null) {
		var textareas = d.getElementsByName('edit');
        var lth       = (textareas ? textareas.length : -1);
		var textarea, i, parent;
		var readonly  = (options.readonly ? true : false);
		for (i = 0; i < lth; ++i) {
		  textarea = textareas[i];
		  for (parent = textarea.parentNode; parent; parent = parent.parentNode) {
			if (parent == p) {
  				createEditor(textarea.id, editor, top.mylanguage, readonly);
				break;
	  }	} } }
  } }
  

  button = null;
  if (options.buttons != undefined) {
	buttons = options.buttons;
	if (buttons != null && (typeof buttons == 'object')) {
	  for (button in buttons) {
		break;
  } } }
	
  if (button == null) {
    buttons = { Close:null };
  }

  div = alertObj.appendChild(d.createElement("div"));
  div.className = "alertBtns";

  if (options.state == undefined) {
    state = null;
  } else {
    state = options.state;
  }

  if (options.columns == undefined) {
    columns = 0;
  } else {
    columns = options.columns;
  }
  if (columns >  0) {
    table = div.appendChild(d.createElement("table"));
    // table.rules='all';
    tbody = table.appendChild(d.createElement("tbody"));
    tr    = null;
  }
  col = columns;
  for (button in buttons) {
    if (columns > 0) {
      if (tr == null) {
        tr = tbody.appendChild(d.createElement("tr"));
      }
      td = tr.appendChild(d.createElement("td"));
      if (--col == 0) {
        tr  = null;
        col = columns;
      }
      btn = td.appendChild(d.createElement("a"));
    } else {
      btn = div.appendChild(d.createElement("a"));
    }
    btn.className = "alertBtn";
    btn.appendChild(d.createTextNode(button));
    btn.href = "#";
    // set up the onclick event to remove the alert when the anchor is clicked
    // We must force new fun1 variables else the fun called when the button
    // is clicked is the current value of buttons[button] or fun or whatever
    // not the value it had when we did the onclick assignment
    fun = buttons[button];
    if (fun == null) {
      btn.onclick = function() { target.removeCustomAlert(id,editor); return false; }
    } else {
      ( function(btn, fun, state, editor)
        {
          var fun1 = fun; 
		  if (editor == null) {
            btn.onclick = function() { target.removeCustomAlert(id,editor); fun1(state); return false; }
		  } else {
            btn.onclick = function() { fun1(state); target.removeCustomAlert(id,editor); return false; }
        } }
      )(btn, fun, state, editor);
  } }


  // center the alert box
  left  = (d.documentElement.scrollWidth - alertObj.offsetWidth)/2;
  maxheight = windowInnerHeight(target);
  height    = alertObj.offsetHeight;
  if (height > maxheight - 50) {
    height  = maxheight - 50;
    alertObj.style.height = height+"px";
  }
  top1      = 200 + shift;
  alertObj.style.top  = (windowPageYOffset(target) + top1) + "px";
  alertObj.style.left = (windowPageXOffset(target) + left + shift) + "px";
  alertObj.style.overflow = 'auto';

  if (height + top1 > maxheight) {
    top1 = maxheight - height - 25;
    alertObj.style.top = (windowPageYOffset(target) + top1) + "px";
  }
  return gAlertId;
}

// removes the custom alert from the DOM
function removeCustomAlert(id, editor)
{
  var alert = document.getElementById(id);

  if (alert) {
    if (editor != null) {
	  var textareas = document.getElementsByName('edit');
      var lth       = (textareas ? textareas.length : -1);
	  var textarea, i, parent, nyEditor;

	  for (i = 0; i < lth; ++i) {
		textarea = textareas[i];
		for (parent = textarea.parentNode; parent; parent = parent.parentNode) {
		  if (parent == alert) {
			myEditor = CKEDITOR.instances[textarea.id];
			if ( myEditor ) {
			  myEditor.destroy();
			}
			break;
	} }	} }
	alert.parentNode.removeChild(alert);
    if (!--gAlerts) {
      gAlertCounter = -1;
  } }
}


