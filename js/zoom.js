/*
offsetWidth: width on page including border+padding but not margin
scrollWidth: total width of element from left to right
clientWidth: visible portion of width due to existance of scrollbar
			 not including borders, margins or scrollbar
*/

// Call whenever the window size changes but the image doesnt
var setCurrentZoom;
// Call whenever the image on a tab becomes visible
var attachZoomToImage;

var setupZoomButton = function() 
{
	var myroot        = null;
	var zoom_panel    = null;
	var ul            = null;
	var button        = null;
	var inputZoom     = null;

	var image;
	var div;
	var svgroot;
	var svgcontent;
	var current_zoom;
	var naturalWidth;	// Actual width  in pixels
	var naturalHeight;	// Actual height in pixels

	var disableDefault = function(evt)
	{
		if (evt.stopPropagation) {
			evt.stopPropagation();
		} else {
			evt.cancelBubble = true;
		}
	}

	var showSvgZoom = function()
	{
	 	var rect = svgroot.getBoundingClientRect();
		if (!rect) {
			image.zoom = current_zoom = null;
			inputZoom.value = '';
		} else {
			image.zoom = current_zoom = rect.width / naturalWidth;
			inputZoom.value = Math.round(current_zoom*100);
	}	}

	var zoomToSvgFrame = function()
	{
		svgroot.setAttribute('width', '100%');
		svgroot.removeAttribute('height');
		div.scrollLeft = 0;
		div.scrollTop  = 0;
		showSvgZoom();
	}

	var setHtmlZoom = function(zoom)
	{
		if (zoom <= 0) {
			return;
		}
		var img   = image.img;
		var scale = (zoom != 1 ? 'scale(' + zoom + ')' : null);
		var top   = (scale != null ? '0 0' : null);

/*
		img.style['zoom']                     = zoom;
		img.style['-moz-transform']           = scale;
		img.style['-moz-transform-origin']    = top;
		img.style['-o-transform']             = scale;
		img.style['-o-transform-origin']      = top;
		img.style['-webkit-transform']        = scale;
		img.style['-webkit-transform-origin'] = top;
		img.style['-ms-transform']            = scale;
		img.style['-ms-transform-origin']     = top;
*/

		img.style.MozTransform = scale;
		img.style.MozTransformOrigin = top;
		img.style.WebkitTransform = scale;
		img.style.WebkitTransformOrigin = top;
		if (zoom < 1) {
			img.width = (100/zoom) + '%';
		} else {
			img.width = (zoom * 100) + '%';
		}

		current_zoom    = zoom;
		inputZoom.value = Math.round(current_zoom*100);
	}

    var wasFullFrame;

	var fullScreen = function(img)
	{
		// Stupid behaviour in chrome if try to fullScreen right frame
		wasFullFrame = isFullFrame();
		if (!wasFullFrame) {
			toggle_frames();
		}

        if (img.requestFullScreen) {
            img.requestFullScreen();
		} else if (img.mozRequestFullScreen) {   
			img.mozRequestFullScreen();   
			// document.mozCancelFullScreen
		} else if (img.webkitRequestFullScreen) {   
			img.webkitRequestFullScreen();   
			// document.webkitCancelFullScreen
	}	}

	var imageControls;
	var imageTabs;
	var imageDummy;

	var zoomToFullImage = function()
	{
		var img;

		if (image.isImage) {
			imageControls = document.getElementById('imageControls');
			imageTabs     = document.getElementById('tabs');
			imageDummy    = image.dummy;

			svgroot.style.display       = 'none';
			imageDummy.style.display    = '';
			imageControls.style.display = 'none';
			if (imageTabs.style.display == '') {
				imageTabs.style.display = 'none';
			} else {
				imageTabs = null;
			}
			img = image.div;
		} else {
			img = image.img;
		}
		fullScreen(img);
	}

	var zoomToFullMarkup = function()
	{
		var img;

		imageControls = document.getElementById('imageControls');
		imageTabs     = document.getElementById('tabs');

		imageControls.style.display = 'none';
		if (imageTabs.style.display == '') {
			imageTabs.style.display = 'none';
		} else {
			imageTabs = null;
		}
		fullScreen(image.div);
	}

	var fullScreenChange = function()
	{
		if (!document.mozFullScreenElement && !document.webkitIsFullScreen && !document.fullscreen) {
			if (!wasFullFrame) {
				toggle_frames();
			}
			if (imageDummy) {
				imageDummy.style.display = 'none';
				svgroot.style.display    = '';
				imageDummy               = null;
			}
			if (imageControls) {
				imageControls.style.display = '';
				imageControls = null;
			}
			if (imageTabs) {
				imageTabs.style.display = '';
				imageTabs = null;
	}	}	}

	setCurrentZoom = function()
	{
		if (image.isImage) {
	 		var rect = svgroot.getBoundingClientRect();

			if (rect && rect.width < div.clientWidth) {
				zoomToSvgFrame();
			} else {
				showSvgZoom();
			}
		}
	}

	// Setup as if the user had screen grabbed this region
	// Coordinates are in screen pixels, not natural pixels

	var setVisibleRegion = function(x, y, width, height, inverse)
	{
		/* N.B div.clientHeight is not reliable -- goes to end of page */
		var clientWidth  = div.clientWidth;
		var clientHeight = clientWidth * naturalHeight / naturalWidth;
		var increase, zoomlevel;

		increase  = (inverse ? (width/clientWidth) : (clientWidth / width));
		zoomlevel = current_zoom * increase;
		width     = zoomlevel * naturalWidth;

		if (width < clientWidth) {
			zoomToSvgFrame();
			return;
		}

		height    = zoomlevel * naturalHeight;	
		x        *= increase;
		y        *= increase;

		if (x + width < clientWidth) {
			// Remove blank space on right
			x = clientWidth - width;
		}
		if (x < 0) {
			x = 0;
		}
		if (y + height < clientHeight) {
			y = clientHeight - height;
		}
		if (y < 0) {
			y = 0;
		}
		svgroot.setAttribute('width',  width);
		svgroot.setAttribute('height', height);
		div.scrollLeft   = x;
		div.scrollTop    = y;

		setCurrentZoom();
	}

	var getStrokedBBox = function() 
	{
		var layers = [];
		var child, bb;
		for (child = svgcontent.firstChild; child; child = child.nextSibling) {
			if (child.tagName == 'g') {
				if (child.display != 'none') {
					try {
						bb = child.getBBox();
						layers[layers.length] = bb;
					} catch (e) {
		}	}	}	}

		var lth = layers.length;

		if (lth == 0) {
			return null;
		}
		var full_bb = layers[0];
		if (lth == 1) {
			return full_bb;
		}

		var max_x   = full_bb.x + full_bb.width;
		var max_y   = full_bb.y + full_bb.height;
		var min_x   = full_bb.x;
		var min_y   = full_bb.y;
		var cur_bb, i;

		for (i = 1; i < lth; ++i) {
			cur_bb = layers[i];
	
			min_x = Math.min(min_x, cur_bb.x);
			min_y = Math.min(min_y, cur_bb.y);
		}
	
		full_bb.x = min_x;
		full_bb.y = min_y;
	
		for (i = 1; i < lth; ++i) {
			cur_bb = layers[i];
			max_x  = Math.max(max_x, cur_bb.x + cur_bb.width);
			max_y = Math.max(max_y, cur_bb.y + cur_bb.height);
		}
	
		full_bb.width  = max_x - min_x;
		full_bb.height = max_y - min_y;
		return full_bb;
	}

	var zoomToMarkup = function(val)
	{
		var bb = getStrokedBBox();
		if (bb) {
			var rect    = svgroot.getBoundingClientRect();
			var scale_x = rect.width  / svgroot.viewBox.baseVal.width;
			var scale_y = rect.height / svgroot.viewBox.baseVal.height;
			setVisibleRegion(bb.x * scale_x, bb.y * scale_y, bb.width * scale_x, bb.height * scale_y, false);
	}	}

	var changeZoomPercent = function(percent)
	{
		if (percent <= 0 || isNaN(percent)) {
			return;
		}
			
		if (image.isImage) {
			var width  = naturalWidth * percent / 100;

			if (width < div.clientWidth) {
				zoomToSvgFrame();
				return;
			}

			var height = width * naturalHeight / naturalWidth;


			svgroot.setAttribute('width',  width);
			svgroot.setAttribute('height', height);
			setCurrentZoom();
			return;
		}

		setHtmlZoom(percent / 100);
	}
			
	var mousedownZoomButton = function()
	{
		if (button.className != 'down') {
			button.className = 'down';
			ul.className = 'showZoomList';
		} else {
			button.className = null;
			ul.className = 'hideZoomList';
		}
	};

	var clickZoomLabel = function()
	{
		mousedownZoomButton();
	}

	var zoom_panel = document.getElementById('zoom_panel');
	var node   = document.getElementById('zoom_dropdown');
	var input  = null;
	var child;

	for (child = node.firstChild; child; child = child.nextSibling) {
		switch (child.tagName) {
		case 'BUTTON':
			button = child;
			button.addEventListener(
				'mousedown',
				mousedownZoomButton,
				false
			);
			button.addEventListener(
				'mouseup',
				disableDefault,
				false
			);
			break;
		case 'UL':
			ul = child;
			break;
	}	} 
	
	for (child = ul.firstChild; child; child = child.nextSibling) {
		if (child.tagName == 'LI') {
			var title = child.title;
			if (title) {
				switch (title) {
				case 'frame':
					child.addEventListener(
						'mouseup',
						zoomToSvgFrame,
						false);
					break;
				case 'fullImage':
					child.addEventListener(
						'mouseup',
						zoomToFullImage,
						false);
					break;
				case 'fullMarkup':
					child.addEventListener(
						'mouseup',
						zoomToFullMarkup,
						false);
					break;
				case 'markup':
					child.addEventListener(
						'mouseup',
						zoomToMarkup,
						false);
					break;
				}
			} else {
				child.addEventListener(
					'mouseup',
					function()
					{
						changeZoomPercent(parseInt(this.textContent));
					},
					false);
	}	}	}

	window.addEventListener(
		'mouseup',
		function()
		{
			button.className = '';
			ul.className = 'hideZoomList';
		},
		false
	);

	document.addEventListener(
		'mozfullscreenchange',
		fullScreenChange,
		false);

    document.addEventListener(
		'webkitfullscreenchange', 
		fullScreenChange,
        false);
				
    document.addEventListener(
		'fullscreenchange', 
		fullScreenChange,
        false);
				
	node = document.getElementById('zoomLabel');
	node.addEventListener(
		'click',
		clickZoomLabel,
		false
	);

	inputZoom = document.getElementById('inputZoom');

	inputZoom.addEventListener(
		'keypress',
		function(e)
		{
  			var keyCode = e.keyCode ? e.keyCode : e.which ? e.which : e.charCode;
			if (keyCode == 13) {
				changeZoomPercent(e.target.value);
			}
		},
		false
	);

	attachZoomToImage = function()
	{
		if (!selectedImage || (selectedImage.isImage && !selectedImage.svgroot)) {
			return;	// Do later
		}
		var setupRubberBand = function()
		{
			var rubberBandBox = null;
			var	root_sctm;
			var drag, start_x, start_y, shiftKey;
			var scale_x, scale_y;
		
			var mousedownRubberBand = function(evt)
			{
				// disableDefault(evt);
				if (drag) {
					return;
				}

				var rect = svgroot.getBoundingClientRect();
				
				scale_x  = svgroot.viewBox.baseVal.width  / rect.width;
				scale_y  = svgroot.viewBox.baseVal.height / rect.height;
				start_x  = evt.layerX;
				start_y  = evt.layerY;
				shiftKey = evt.shiftKey;

				drag    = true;
				
				rubberBandBox.setAttribute('x', start_x * scale_x);
				rubberBandBox.setAttribute('y', start_y * scale_y);
				rubberBandBox.setAttribute('width', 0);
				rubberBandBox.setAttribute('height', 0);
				rubberBandBox.setAttribute('display', 'inline');
			}
		
			var mousemoveRubberBand = function(evt)
			{
				if (!drag) {
					return;
				}

				var	x  = evt.layerX,
					y  = evt.layerY;
			
				// disableDefault(evt);
				
				rubberBandBox.setAttribute('x',      Math.min(start_x, x) * scale_x);
				rubberBandBox.setAttribute('y',      Math.min(start_y, y) * scale_y);
				rubberBandBox.setAttribute('width',  Math.abs(start_x - x) * scale_x);
				rubberBandBox.setAttribute('height', Math.abs(start_y - y) * scale_y);
			}
		
			var mouseupRubberBand = function(evt)
			{
				if (!drag) {
					return;
				}

				rubberBandBox.setAttribute('display', 'none');
				drag = false;
				var	x      = evt.layerX,
					y      = evt.layerY,
					width,
					height,
					half;

				if (x == start_x || y == start_y) {
	 				var rect         = svgroot.getBoundingClientRect();
					var clientWidth  = div.clientWidth;
					var clientHeight = clientWidth * naturalHeight / naturalWidth;
					// Assume a pan -- this is to be the centre point
					x      = (start_x + x - clientWidth)  / 2;
					y      = (start_y + y - clientHeight) / 2;
					width  = rect.width;
					height = rect.height;
					if (x + width < clientWidth) {
						// Remove blank space on right
						x = clientWidth - width;
					}
					if (x < 0) {
						x = 0;
					}
					if (y + height < clientHeight) {
						y = clientHeight - height;
					}
					if (y < 0) {
						y = 0;
					}
					div.scrollLeft   = x;
					div.scrollTop    = y;
					return;
				} 
				width  = Math.abs(x - start_x);
				height = Math.abs(y - start_y);
				x      = Math.min(start_x, x);
				y      = Math.min(start_y, y);

				setVisibleRegion(x, y, width, height, shiftKey);
			}
	
			// This is a static variable because it is a member variable
			// Use cascade rather than bubble so nothing can stop
			// propagation to us.
			attachZoomToImage.detachRubberBand = function()
			{
				rubberBandBox.setAttribute('display', 'none');
				svgroot.removeEventListener(
					'mousedown',
					mousedownRubberBand,
					false
				);
				svgroot.removeEventListener(
					'mousemove',
					mousemoveRubberBand,
					false
				);
				svgroot.removeEventListener(
					'mouseup',
					mouseupRubberBand,
					false
				);
				rubberBandBox.parentNode.removeChild(rubberBandBox);
			}
	
			// This is a static variable because it is a member variable
			attachZoomToImage.attachRubberBand = function()
			{
				if (!rubberBandBox) {
					rubberBandBox = document.createElementNS(
										'http://www.w3.org/2000/svg', 'rect');
					rubberBandBox.setAttribute('fill', '#22C');
					rubberBandBox.setAttribute('fill-opacity', 0.15);
					rubberBandBox.setAttribute('stroke','#22C');
					rubberBandBox.setAttribute('stroke-width', 0.5);
					rubberBandBox.setAttribute('display', 'none');
					rubberBandBox.setAttribute('style', 'pointer-events:none');
				}
				svgroot.appendChild(rubberBandBox);
				svgroot.addEventListener(
					'mouseup',
					mouseupRubberBand,
					false
				);
				svgroot.addEventListener(
					'mousemove',
					mousemoveRubberBand,
					false
				);
				svgroot.addEventListener(
					'mousedown',
					mousedownRubberBand,
					false
				);
			}
		}

		if (svgroot) {
			attachZoomToImage.detachRubberBand();
			svgroot = null;
		}
		image = selectedImage;
		if (image.zoom != 'BAD') {
			var fitToMarkup = document.getElementById('fitToMarkup');
			var fitToFrame  = document.getElementById('fitToFrame');
			var fullMarkup  = document.getElementById('fullMarkup');
			var fullImage   = document.getElementById('fullImage');
			var img;

			fullMarkup.style.display  = 'none';
			fullImage.style.display   = 'none';
			fitToMarkup.style.display = 'none';
			current_zoom  = image.zoom;
			div           = image.div;
			zoom_panel.style   ='pointer-events: auto;cursor: auto';
			inputZoom.readonly = null;
			if (image.isImage) {
				svgroot       = image.svgroot;
				svgcontent    = image.svgcontent;

        		if (div.requestFullScreen || div.mozRequestFullScreen || div.webkitRequestFullScreen) {
					if (svgcontent) {
						fullMarkup.style.display  = '';
						fitToMarkup.style.display = '';
					}
					fullImage.style.display = '';
				}
				fitToFrame.style.display  = '';
				naturalWidth  = image.naturalWidth;
				naturalHeight = image.naturalHeight;
				if (!current_zoom) {
					setCurrentZoom(image);
			
					if (!myroot) {
						var bodys = document.getElementsByTagName('BODY');
						switch (bodys.length) {
						case 0:
							alert('Can\'t find body');
							break;
						case 1:
							myroot = bodys[0];
							myroot.addEventListener(
								"resize",
								setCurrentZoom,
								false
							);
							break;
						default:
							alert('Saw ' + bodys.length + ' body elements');
					}	}
				}
				setCurrentZoom();
		
				if (!attachZoomToImage.attachRubberBand) {
					setupRubberBand();
				}
				attachZoomToImage.attachRubberBand();
				return;
			}
			var img = image.img;
        	if (img.requestFullScreen || img.mozRequestFullScreen || img.webkitRequestFullScreen) {
				fullImage.style.display = '';
			}
				
			// Otherwise this is a web page
			fitToFrame.style.display  = 'none';
			// Can't obtain natural width of an iframe
			// Cross-domain restrictions
			// So don't know how to fit unknown width to iframe
			inputZoom.value = (image.zoom ? image.zoom : '');
			return;
		}
		zoom_panel.style   ='pointer-events: none;cursor: default';
		inputZoom.readonly = 'readonly';
		div             = null;
		svgroot         = null;
		svgcontent      = null;
		current_zoom    = null;
		naturalWidth    = null;
		naturalHeight   = null;
		inputZoom.value = 'N/A';
	}
}

