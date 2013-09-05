<!DOCTYPE html>
<?php
$gPHPscript = __FILE__;
$dir = dirname(__FILE__);
require_once($dir . '/../include/boilerplate.php');

if (mustlogon()) {
  return;
}

$image_url = getpost('image_url');

htmlHeader('Edit Markup');
?>
<meta http-equiv="X-UA-Compatible" content="chrome=1"/>
<link rel="icon" type="image/png" href="images/logo.png"/>
<?php
srcStylesheet(
  'jgraduate/css/jPicker-1.0.12.css',
  'jgraduate/css/jgraduate.css',
  'svg-editor.css',
  'spinbtn/JQuerySpinBtn.css'
);

/* Release version of script tags: */
srcJavascript(
  '../../tools/jquery-1.7.2.js',
  /* 'jquery.js', */
  'js-hotkeys/jquery.hotkeys.min.js',
  'jgraduate/jquery.jgraduate.min.js',
  'svgicons/jquery.svgicons.min.js',
  'jquerybbq/jquery.bbq.min.js',
  'spinbtn/JQuerySpinBtn.min.js',
  'svgcanvas.js',
  'svg-editor.js',
  'locale/locale.min.js',
  '../js/base64.js',
  '../js/util.js',
  '../js/fullwidth.js',
  '../js/edit.js',
  '../js/images.js',
  '../../tools/ckeditor/ckeditor.js'
);

/* you can load extensions here 
 * Doing this here permits them to be debugged 
*/

/* always minified scripts */
srcJavascript(
 'jquery-ui/jquery-ui-1.8.custom.min.js',
 'jgraduate/jpicker-1.0.12.min.js'
);
?>

<!-- feeds -->
<link rel="alternate" type="application/atom+xml" title="SVG-edit General Discussion" href="http://groups.google.com/group/svg-edit/feed/atom_v1_0_msgs.xml" />
<link rel="alternate" type="application/atom+xml" title="SVG-edit Updates (Issues/Fixes/Commits)" href="http://code.google.com/feeds/p/svg-edit/updates/basic" />

<!-- Add script with custom handlers here -->
<title>SVG Markup editor</title>
</head>
<body>
<div id="svg_editor">

<div id="workarea">
<style id="styleoverrides" type="text/css" media="screen" scoped></style>
<div id="svgcanvas">
	<svg id="svgroot"
		xmlns="http://www.w3.org/2000/svg"
		xmlns:xlink="http://www.w3.org/1999/xlink"
        xmlns:se="http://svg-edit.googlecode.com"
        xmlns:html="http://www.w3.org/1999/xhtml"
        xmlns:math="http://www.w3.org/1998/Math/MathML"
		overflow="visible">
		<defs>
			<filter id="canvashadow" filterUnits="objectBoundingBox">
				<feGaussianBlur in="SourceAlpha" stdDeviation="4" result="blur"/>
				<feOffset in="blur" dx="5" dy="5" result="offsetBlur"/>
				<feMerge>
					<feMergeNode in="offsetBlur"/>
					<feMergeNode in="SourceGraphic"/>
				</feMerge>
			</filter>
		</defs>
		<svg id="canvasBackground"
			x="0"
			y="0"
			overflow="visible"
			style="pointer-events:none">
			<image id="background_image"
				xlink:href="<?php echo htmlspecialchars($image_url); ?>"
				width="100%"
				height="100%"
				style="pointer-events:none" />
		</svg>
		<svg id="svgcontent"
			xmlns:se="http://svg-edit.googlecode.com"
			overflow="hidden" >
		</svg>
	</svg>
</div>
</div>

<div id="sidepanels">
	<div id="layerpanel" style="display:none" >
		<h3 id="layersLabel">Objects</h3>
		<fieldset id="layerbuttons">
			<div id="layer_new" class="layer_button"  title="New Object"></div>
			<div id="layer_delete" class="layer_button"  title="Delete Object"></div>
			<div id="layer_rename" class="layer_button"  title="Rename Object"></div>
			<div id="layer_up" class="layer_button"  title="Move Object Up"></div>
			<div id="layer_down" class="layer_button"  title="Move Object Down"></div>
		</fieldset>
		
		<table id="layerlist">
		  <tbody id="layerlistbody">
			<tr class="layer">
				<td class="layervis"></td>
				<td class="layername">Object 1</td>
			</tr>
		  </tbody>
		</table>
		<span id="selLayerLabel">Move elements to:</span>
		<select id="selLayerNames" title="Move selected elements to a different layer" disabled="disabled">
			<option selected="selected" value="layer1">Layer 1</option>
		</select>

        <span>
		<b id="layersComment">Comment</b>
		</span>
        <textarea id="layersText" name="layersText" cols="60" rows="6"></textarea>
	</div>
	<div id="sidepanel_handle" title="Drag left/right to resize side panel [X]">O b j e c t s</div>
</div>

<div id="main_button">
	<div id="main_icon" class="buttonup" title="Main Menu">
		<span></span>
		<div id="logo"></div>
		<div class="dropdown"></div>
	</div>
		
	<div id="main_menu"> 
	
		<!-- File-like buttons: New, Save, Source -->
		<ul>
			<li id="tool_clear">
				<div></div>
				New Markup [N]
			</li>
			
			<li id="tool_docprops">
				<div></div>
				Markup Properties [P]
			</li>

			<li id="tool_save" style="display:none;">
				<div></div>
				Save Markup [S]
			</li>

			<li id="tool_msave" style="display:none;">
				<div></div>
				Minor Save [M]
			</li>

			<li id="tool_return">
				<div></div>
				Return Markup [R]
			</li>

			<li id="tool_quit">
				<div></div>
				Quit Markup [Q]
			</li>
		</ul>
		
		<p>
			<a href="http://svg-edit.googlecode.com/" target="_blank">
				SVG-edit Home Page
			</a>
		</p>

	</div>
</div>



<div id="tools_top" class="tools_panel">
	
	<div id="editor_panel">
		<div class="push_button" id="tool_source" title="View Markup [U]"></div>
		<div class="tool_button" id="tool_wireframe" title="Wireframe Mode [F]"></div>
	</div>

    <!-- History buttons -->
	<div id="history_panel">
		<div class="tool_sep"></div>
		<div class="push_button tool_button_disabled" id="tool_undo" title="Undo [Z]"></div>
		<div class="push_button tool_button_disabled" id="tool_redo" title="Redo [Y]"></div>
	</div>
	
	<!-- Buttons when a single element is selected -->
	<div id="selected_panel">
		<div class="toolset">
			<div class="tool_sep"></div>
			<div class="push_button" id="tool_clone" title="Clone Element [C]"></div>
			<div class="push_button" id="tool_delete" title="Delete Element [Delete/Backspace]"></div>
			<div class="tool_sep"></div>
			<div class="push_button" id="tool_move_top" title="Move to Top [Shift+Up]"></div>
			<div class="push_button" id="tool_move_bottom" title="Move to Bottom [Shift+Down]"></div>
			<div class="push_button" id="tool_topath" title="Convert to Path"></div>
			<div class="push_button" id="tool_reorient" title="Reorient path"></div>
			<div class="tool_sep"></div>
			<label id="idLabel" title="Identify the element">
				<span>id:</span>
				<input id="elem_id" class="attr_changer" data-attr="id" size="10" type="text"/>
			</label>
		</div>

		<label id="tool_angle" title="Change rotation angle">
			<span id="angleLabel" class="icon_label"></span>
			<input id="angle" size="2" value="0" type="text"/>
		</label>
		
		<div class="toolset" id="tool_blur" title="Change gaussian blur value">
			<label>
				<span id="blurLabel" class="icon_label"></span>
				<input id="blur" size="2" value="0" type="text"/>
			</label>
			<div id="blur_dropdown" class="dropdown">
				<button></button>
				<ul>
					<li class="special"><div id="blur_slider"></div></li>
				</ul>
			</div>
		</div>
		
		<div class="dropdown toolset" id="tool_position" title="Align Element to Page">
				<div id="cur_position" class="icon_label"></div>
				<button></button>
		</div>		

		<div id="xy_panel" class="toolset">
			<label>
				x: <input id="selected_x" class="attr_changer" title="Change X coordinate" size="3" data-attr="x"/>
			</label>
			<label>
				y: <input id="selected_y" class="attr_changer" title="Change Y coordinate" size="3" data-attr="y"/>
			</label>
		</div>
	</div>

	<!-- Buttons when multiple elements are selected -->
	<div id="multiselected_panel">
		<div class="tool_sep"></div>
		<div class="push_button" id="tool_clone_multi" title="Clone Elements [C]"></div>
		<div class="push_button" id="tool_delete_multi" title="Delete Selected Elements [Delete/Backspace]"></div>
		<div class="tool_sep"></div>
		<div class="push_button" id="tool_group" title="Group Elements [G]"></div>
		<div class="push_button" id="tool_alignleft" title="Align Left"></div>
		<div class="push_button" id="tool_aligncenter" title="Align Center"></div>
		<div class="push_button" id="tool_alignright" title="Align Right"></div>
		<div class="push_button" id="tool_aligntop" title="Align Top"></div>
		<div class="push_button" id="tool_alignmiddle" title="Align Middle"></div>
		<div class="push_button" id="tool_alignbottom" title="Align Bottom"></div>
		<label id="tool_align_relative"> 
			<span id="relativeToLabel">relative to:</span>
			<select id="align_relative_to" title="Align relative to ...">
			<option id="selected_objects" value="selected">selected objects</option>
			<option id="largest_object" value="largest">largest object</option>
			<option id="smallest_object" value="smallest">smallest object</option>
			<option id="page" value="page">page</option>
			</select>
		</label>
		<div class="tool_sep"></div>

	</div>

	<div id="g_panel">
		<div class="tool_sep"></div>
		<div class="push_button" id="tool_ungroup" title="Ungroup Elements [G]"></div>
	</div>

	<div id="rect_panel">
		<div class="toolset">
			<label id="rect_width_tool" title="Change rectangle width">
				<span id="rwidthLabel" class="icon_label"></span>
				<input id="rect_width" class="attr_changer" size="3" data-attr="width"/>
			</label>
			<label id="rect_height_tool" title="Change rectangle height">
				<span id="rheightLabel" class="icon_label"></span>
				<input id="rect_height" class="attr_changer" size="3" data-attr="height"/>
			</label>
		</div>
		<label id="cornerRadiusLabel" title="Change Rectangle Corner Radius">
			<span class="icon_label"></span>
			<input id="rect_rx" size="3" value="0" type="text" data-attr="Corner Radius"/>
		</label>
	</div>

	<div id="image_panel">
	<div class="toolset">
		<label><span id="iwidthLabel" class="icon_label"></span>
		<input id="image_width" class="attr_changer" title="Change image width" size="3" data-attr="width"/>
		</label>
		<label><span id="iheightLabel" class="icon_label"></span>
		<input id="image_height" class="attr_changer" title="Change image height" size="3" data-attr="height"/>
		</label>
	</div>
	<div class="toolset">
		<label id="tool_image_url">url:
			<input id="image_url" type="text" title="Change URL" size="35"/>
		</label>
		<label id="tool_change_image">
			<button id="change_image_url" style="display:none;">Change Image</button>
			<span id="url_notice" title="NOTE: This image cannot be embedded. It will depend on this path to be displayed"></span>
		</label>
	</div>
  </div>

	<div id="circle_panel">
		<div class="toolset">
			<label id="tool_circle_cx">cx:
			<input id="circle_cx" class="attr_changer" title="Change circle's cx coordinate" size="3" data-attr="cx"/>
			</label>
			<label id="tool_circle_cy">cy:
			<input id="circle_cy" class="attr_changer" title="Change circle's cy coordinate" size="3" data-attr="cy"/>
			</label>
		</div>
		<div class="toolset">
			<label id="tool_circle_r">r:
			<input id="circle_r" class="attr_changer" title="Change circle's radius" size="3" data-attr="r"/>
			</label>
		</div>
	</div>

	<div id="ellipse_panel">
		<div class="toolset">
			<label id="tool_ellipse_cx">cx:
			<input id="ellipse_cx" class="attr_changer" title="Change ellipse's cx coordinate" size="3" data-attr="cx"/>
			</label>
			<label id="tool_ellipse_cy">cy:
			<input id="ellipse_cy" class="attr_changer" title="Change ellipse's cy coordinate" size="3" data-attr="cy"/>
			</label>
		</div>
		<div class="toolset">
			<label id="tool_ellipse_rx">rx:
			<input id="ellipse_rx" class="attr_changer" title="Change ellipse's x radius" size="3" data-attr="rx"/>
			</label>
			<label id="tool_ellipse_ry">ry:
			<input id="ellipse_ry" class="attr_changer" title="Change ellipse's y radius" size="3" data-attr="ry"/>
			</label>
		</div>
	</div>

	<div id="line_panel">
		<div class="toolset">
			<label id="tool_line_x1">x1:
			<input id="line_x1" class="attr_changer" title="Change line's starting x coordinate" size="3" data-attr="x1"/>
			</label>
			<label id="tool_line_y1">y1:
			<input id="line_y1" class="attr_changer" title="Change line's starting y coordinate" size="3" data-attr="y1"/>
			</label>
		</div>
		<div class="toolset">
			<label id="tool_line_x2">x2:
			<input id="line_x2" class="attr_changer" title="Change line's ending x coordinate" size="3" data-attr="x2"/>
			</label>
			<label id="tool_line_y2">y2:
			<input id="line_y2" class="attr_changer" title="Change line's ending y coordinate" size="3" data-attr="y2"/>
			</label>
		</div>
	</div>

	<div id="text_panel">
		<div class="toolset">
			<div class="tool_button" id="tool_bold" title="Bold Text [B]"><span></span>B</div>
			<div class="tool_button" id="tool_italic" title="Italic Text [I]"><span></span>i</div>
		</div>
		
		<div class="toolset" id="tool_font_family">
			<label>
				<!-- Font family -->
				<input id="font_family" type="text" title="Change Font Family" size="12"/>
			</label>
			<div id="font_family_dropdown" class="dropdown">
				<button></button>
				<ul>
					<li style="font-family:serif">Serif</li>
					<li style="font-family:sans-serif">Sans-serif</li>
					<li style="font-family:cursive">Cursive</li>
					<li style="font-family:fantasy">Fantasy</li>
					<li style="font-family:monospace">Monospace</li>
				</ul>
			</div>
		</div>

		<label id="tool_font_size" title="Change Font Size">
			<span id="font_sizeLabel" class="icon_label"></span>
			<input id="font_size" size="3" value="0" type="text"/>
		</label>
		
		<!-- Not visible, but still used -->
		<input id="text" type="text" size="35"/>
	</div>
	
	<div id="path_node_panel">
		<div class="tool_sep"></div>
		<div class="tool_button" id="tool_node_link" title="Link Control Points"></div>
		<div class="tool_sep"></div>
		<label id="tool_node_x">x:
			<input id="path_node_x" class="attr_changer" title="Change node's x coordinate" size="3" data-attr="x"/>
		</label>
		<label id="tool_node_y">y:
			<input id="path_node_y" class="attr_changer" title="Change node's y coordinate" size="3" data-attr="y"/>
		</label>
		
		<select id="seg_type" title="Change Segment type">
			<option id="straight_segments" selected="selected" value="4">Straight</option>
			<option id="curve_segments" value="6">Curve</option>
		</select>
		<div class="tool_button" id="tool_node_clone" title="Clone Node"></div>
		<div class="tool_button" id="tool_node_delete" title="Delete Node"></div>
		<div class="tool_button" id="tool_openclose_path" title="Open/close sub-path"></div>
		<div class="tool_button" id="tool_add_subpath" title="Add sub-path"></div>
	</div>
	
</div> <!-- tools_top -->

<div id="tools_left" class="tools_panel">
	<div class="tool_button" id="tool_select" title="Select Tool [1]"></div>
	<div class="tool_button" id="tool_fhpath" title="Pencil Tool [2]"></div>
	<div class="tool_button" id="tool_line" title="Line Tool [3]"></div>
	<div class="tool_button flyout_current" id="tools_rect_show" title="Square/Rect Tool [4/Shift+4]">
		<div class="flyout_arrow_horiz"></div>
	</div>
	<div class="tool_button flyout_current" id="tools_ellipse_show" title="Ellipse/Circle Tool [5/Shift+5]">
		<div class="flyout_arrow_horiz"></div>
	</div>
	<div class="tool_button" id="tool_path" title="Path Tool [6]"></div>
	<div class="tool_button" id="tool_text" title="Text Tool [7]"></div>
	<div class="tool_button" id="tool_image" title="Image Tool [8]"></div>
	<div class="tool_button" id="tool_zoom" title="Zoom Tool [Ctrl+Up/Down]"></div>
	
	<div style="display: none">
		<div id="tool_rect" title="Rectangle"></div>
		<div id="tool_square" title="Square"></div>
		<div id="tool_fhrect" title="Free-Hand Rectangle"></div>
		<div id="tool_ellipse" title="Ellipse"></div>
		<div id="tool_circle" title="Circle"></div>
		<div id="tool_fhellipse" title="Free-Hand Ellipse"></div>
	</div>
</div> <!-- tools_left -->

<div id="tools_bottom" class="tools_panel">

    <!-- Zoom buttons -->
	<div id="zoom_panel" class="toolset" title="Change zoom level">
		<label>
		<span id="zoomLabel" class="zoom_tool icon_label"></span>
		<input id="zoom" size="3" value="100" type="text" />
		</label>
		<div id="zoom_dropdown" class="dropdown">
			<button></button>
			<ul>
				<li>1000%</li>
				<li>400%</li>
				<li>200%</li>
				<li>100%</li>
				<li>50%</li>
				<li>25%</li>
				<li id="fit_to_canvas" data-val="canvas">Fit to canvas</li>
				<li id="clip_to_canvas" data-val="clip">Clip to canvas</li>
				<li id="fit_to_sel" data-val="selection">Fit to selection</li>
				<li id="fit_to_layer_content" data-val="layer">Fit to object content</li>
				<li id="fit_to_all" data-val="content">Fit to all content</li>
				<li>100%</li>
			</ul>
		</div>
		<div class="tool_sep"></div>
	</div>

	<div id="tools_bottom_2">
		<div id="color_tools">
			<div class="color_tool" id="tool_fill">
				<label class="icon_label" for="fill_color" title="Change fill color"></label>
				<div class="color_block">
					<div id="fill_bg"></div>
					<div id="fill_color" class="color_block"></div>
				</div>
			</div>
		
			<div class="color_tool" id="tool_stroke">
				<div class="color_block">
					<label class="icon_label" title="Change stroke color"></label>
				</div>
				<div class="color_block">
					<div id="stroke_bg"></div>
					<div id="stroke_color" class="color_block" title="Change stroke color"></div>
				</div>
				
				<label>
					<input id="stroke_width" title="Change stroke width by 1, shift-click to change by 0.1" size="2" value="5" type="text" data-attr="Stroke Width"/>
				</label>
				
				<label class="stroke_tool">
					<select id="stroke_style" title="Change stroke dash style">
						<option selected="selected" value="none">&mdash;</option>
						<option value="2,2">...</option>
						<option value="5,5">- -</option>
						<option value="5,2,2,2">- .</option>
						<option value="5,2,2,2,2,2">- ..</option>
					</select>
				</label>	

 				<div class="stroke_tool dropdown" id="stroke_linejoin">
 					<div>
						<div id="cur_linejoin" title="Linejoin: Miter"></div>
						<button></button>
					</div>
 				</div>
 				
 				<div class="stroke_tool dropdown" id="stroke_linecap">
 					<div>
						<div id="cur_linecap" title="Linecap: Butt"></div>
						<button></button>
					</div>
 				</div>
			
				<div id="toggle_stroke_tools" title="Show/hide more stroke tools">
					&gt;&gt;
				</div>
				
			</div>
		</div>
	
		<div class="toolset" id="tool_opacity" title="Change selected item opacity">
			<label>
				<span id="group_opacityLabel" class="icon_label"></span>
				<input id="group_opacity" size="3" value="100" type="text"/>
			</label>
			<div id="opacity_dropdown" class="dropdown">
				<button></button>
				<ul>
					<li>0%</li>
					<li>25%</li>
					<li>50%</li>
					<li>75%</li>
					<li>100%</li>
					<li class="special"><div id="opac_slider"></div></li>
				</ul>
			</div>
		</div>

	</div>

	<div id="tools_bottom_3">
		<div id="palette_holder"><div id="palette" title="Click to change fill color, shift-click to change stroke color"></div></div>
	</div>
    <div id="copyright">
 	<!--<input type="button" id="fullWidthButton" style="display:none" value="Full Frame" onclick="toggle_frames()" />-->
    <span id="copyrightLabel">Derived from</span> <a href="http://svg-edit.googlecode.com/" target="_blank">SVG-edit v2.5.1</a></div>
</div>

<div id="option_lists">
	<ul id="linejoin_opts">
		<li class="tool_button current" id="linejoin_miter" title="Linejoin: Miter"></li>
		<li class="tool_button" id="linejoin_round" title="Linejoin: Round"></li>
		<li class="tool_button" id="linejoin_bevel" title="Linejoin: Bevel"></li>
	</ul>
	
	<ul id="linecap_opts">
		<li class="tool_button current" id="linecap_butt" title="Linecap: Butt"></li>
		<li class="tool_button" id="linecap_square" title="Linecap: Square"></li>
		<li class="tool_button" id="linecap_round" title="Linecap: Round"></li>
	</ul>
	
	<ul id="position_opts" class="optcols3">
		<li class="push_button" id="tool_posleft" title="Align Left"></li>
		<li class="push_button" id="tool_poscenter" title="Align Center"></li>
		<li class="push_button" id="tool_posright" title="Align Right"></li>
		<li class="push_button" id="tool_postop" title="Align Top"></li>
		<li class="push_button" id="tool_posmiddle" title="Align Middle"></li>
		<li class="push_button" id="tool_posbottom" title="Align Bottom"></li>
	</ul>
</div>


<!-- hidden divs -->
<div id="color_picker"></div>

</div> <!-- svg_editor -->

<div id="svg_source_editor">
	<div id="svg_source_overlay"></div>
	<div id="svg_source_container">
		<div id="tool_source_back" class="toolbar_button">
			<button id="tool_source_cancel">Close</button>
		</div>
		<div id="svg_source_textarea">
		</div>
	</div>
</div>

<div id="svg_docprops">
	<div id="svg_docprops_overlay"></div>
	<div id="svg_docprops_container">
		<div id="tool_docprops_back" class="toolbar_button">
			<button id="tool_docprops_save">OK</button>
			<button id="tool_docprops_cancel">Cancel</button>
		</div>

		<fieldset id="svg_docprops_prefs">
			<legend id="svginfo_editor_prefs">Editor Preferences</legend>

			<label><span id="svginfo_lang">Language:</span>
				<!-- Source: http://en.wikipedia.org/wiki/Language_names -->
				<select id="lang_select">
				  <option id="lang_ar" value="ar">العربية</option>
					<option id="lang_cs" value="cs">Čeština</option>
					<option id="lang_de" value="de">Deutsch</option>
					<option id="lang_en" value="en" selected="selected">English</option>
					<option id="lang_es" value="es">Español</option>
					<option id="lang_fa" value="fa">فارسی</option>
					<option id="lang_fr" value="fr">Français</option>
					<option id="lang_fy" value="fy">Frysk</option>
					<option id="lang_hi" value="hi">&#2361;&#2367;&#2344;&#2381;&#2342;&#2368;, &#2361;&#2367;&#2306;&#2342;&#2368;</option>
					<option id="lang_ja" value="ja">日本語</option>
					<option id="lang_nl" value="nl">Nederlands</option>
					<option id="lang_pt-BR" value="pt-BR">Português (BR)</option>
					<option id="lang_ro" value="ro">Româneşte</option>
					<option id="lang_ru" value="ru">Русский</option>
					<option id="lang_sk" value="sk">Slovenčina</option>
					<option id="lang_zh-TW" value="zh-TW">繁體中文</option>
				</select>
			</label>

			<label><span id="svginfo_icons">Icon size:</span>
				<select id="iconsize">
					<option id="icon_small" value="s">Small</option>
					<option id="icon_medium" value="m" selected="selected">Medium</option>
					<option id="icon_large" value="l">Large</option>
					<option id="icon_xlarge" value="xl">Extra Large</option>
				</select>
			</label>
			
			<fieldset id="image_save_opts">
				<legend id="includedImages">Included Images</legend>
				<label><input type="radio" name="image_opt" value="embed" checked="checked"/> <span id="image_opt_embed">Embed data (local files)</span> </label>
				<label><input type="radio" name="image_opt" value="ref"/> <span id="image_opt_ref">Use file reference</span> </label>
			</fieldset>			
		</fieldset>

		<br>

		<fieldset>
			<legend id="svginfo_markup">Markup</legend>
			<label>
				<span id="svginfo_title">Title:</span>
				<input type="text" id="canvas_title" size="24"/>
			</label>			
			<label>
				<div id="svginfo_desc">Description:</div>
        		<textarea id="canvas_desc" name="canvas_desc" cols="80" rows="6"></textarea>
			</label>
			<label>
				<div id="canvas_size"></div>
			</label>
		</fieldset>
	</div>
</div>

<div id="dialog_box">
	<div id="dialog_box_overlay"></div>
	<div id="dialog_container">
		<div id="dialog_content"></div>
		<div id="dialog_buttons"></div>
	</div>
</div>

</body>
</html>
