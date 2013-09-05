// checkVersion('annotate', 1);

function checkEdit()
{
  var title = document.getElementById('title');
  var content = CKEDITOR.instances['editor1'].getData();
  var msg;

  title = trim(title.value);
  if (title == '') {
    title = null;
  }
  content = trim(content);
  if (content == '') {
    content = null;
  }

  if (title == null || content == null) {
    msg = { title:'Missing data', icon:'warn.png'};
    if (content != null) {
      msg.body = 'An annotation needs a title';
    } else if (title != null) {
      msg.body = 'An annotation needs content';
    } else {
      msg.body = 'An annotation needs a title and content';
    }
    customAlert(msg);
    return false;
  }

  var forceSaveMarkup = top.frames[0].forceSaveMarkup;

  if (forceSaveMarkup) {
	var cancelSaveMarkup = top.frames[0].cancelSaveMarkup;
    customAlert(
	  { title:'Image markup editing in progress',
		icon:'warn.png',
	    body:'You must save or discard current markup editing as part of saving the current annotation',
		buttons: { Save:forceSaveMarkup, Discard:cancelSaveMarkup, Cancel:null }
      }             
	);
	return false;
  }
  return true;
}

function showImages()
{
  if (parent != self) {
    var load_left = document.getElementById('load_left');
    if (load_left) {
      load_left.submit();
} } }

function sendImageData()
{
  var image_data  = top.image_data;
  var images      = image_data.images;
  var lth         = (images ? images.length : -1);
  var image_data1 = null;
  var i, tab, images1, image, image1, image_url, html_url, citation_id, orig;

  for (i = tab = 0; i < lth; ++i) {
	image     = images[i];
	if (!image.delete) {
	  citation_id = image.citation_id;
	  if (!citation_id) {
	    image_url = image.image_url;
	    if (image_url) {
	  	  image_url = trim(image_url);
	    } else {
		  image_url = '';
	    }
	    html_url = image.html_url;
	    if (html_url) {
		  html_url = trim(html_url);
	    } else {
		  html_url = '';
	    }
	    if (image_url == '' && html_url == '') {
		  image.delete = true;
	} } }
	if (image.delete) {
	  if (!image.markup_id) {
		continue;
	  }
	  image1 = { delete:true };
	} else {
	  if (citation_id) {
		orig = image.orig_citation_id;
		if (citation_id != orig) {
		  image1 = { citation_id:citation_id };
		}
	  } else {
		image1 = sendMarkup(image);
		orig = image.orig_image_url;
		if (!orig) {
		  orig = '';
		}
		if (image_url != orig) {
		  if (!image1) {
			image1 = {};
		  }
		  image1.image_url = image_url;
		}
		orig = image.orig_html_url;
		if (!orig) {
		  orig = '';
		}
		if (html_url != orig) {
		  if (!image1) {
			image1 = {};
		  }
		  image1.html_url = html_url;
	  }	}
	  ++tab;
	  if (image.orig_tab != tab) {
	    if (!image1) {
	      image1 = {};
		}
		image1.tab = tab;
	  } 
	  if (!image1) {
		continue;
	  }
	  image1.maxversion = image.maxversion;
	}
	image1.markup_id  = image.markup_id;
	if (!image_data1) {
	  image_data1 = {};
	  image_data1.annotation_id = image_data.annotation_id;
	  image_data1.images = images1 = [];
	}
	images1.push(image1);
  } 
  return image_data1;
}

function duplicateImages2(source, target)
{
  for (i in source) {
    target[i] = {};
    duplicateImages1(source[i], target[i]);
  }
}

function duplicateImages1(source, target)
{
  var item;

  for (i in source) {
	item = source[i];
	if (item != null) {
	  switch (i) {
	  case 'images':
	  case 'markups':
	    target[i] = [];
        duplicateImages2(item, target[i]);
	    break;
	  case 'citation_id':
	  case 'html_url':
	  case 'image_url':
	  case 'naturalWidth':
	  case 'naturalHeight':
	  case 'title':
	  case 'description':
	  case 'svg':
        target[i] = item;
		break;
} } } }

function duplicateImages()
{
  var target = {};
  duplicateImages1(top.image_data, target);
  top.image_data = target;
}

/* Force a rewriting of the page which will embed the template if any
   in the form
 */
function template_change()
{ 
  var mode = document.getElementById("mode");

  if (mode) {
    mode.value = 't';
  }
  var form = document.getElementById('form');
  form.submit();
}

function flipTemplateVisible()
{
  var button = document.getElementById('flipTemplate');
  var trs    = document.getElementsByName('template');
  var display;
  var i;

  if (button.value == 'Hide') {
    button.value = 'Show';
    display = 'none';
  } else {
    button.value = 'Hide';
    display = '';
  }

  for (i = 0; i < trs.length; ++i) {
    trs[i].style.display = display;
} }

function tr(cnt)
{
 if (cnt & 1) {
   return '<tr bgcolor="#fff">';
 }
 return '<tr bgcolor="#fff">';
}
  
function preview()
{
  var data, value, msg, cnt;

  cnt  = 0;
  data = [];
  getFormData('form', data);

  value = data['title'];
  if (value) {
    msg = '<h2>Title: ' + escapeHTML(value) + '</h2>\n';
  } else {
    msg = '';
  }
  msg += '<table class="alertTable">\n';
  if (annotation_id) {
    msg += tr(++cnt)+'<tr><td class="tdCat">Annotation</td><td class="tdInfo">' + escapeHTML('' + annotation_id);
  }
  value = data['version'];
  if (value) {
    msg += '/' + value;
  }
  msg += '</td></tr>\n';
  value = data['tags'];
  if (value) {
    msg += tr(++cnt)+'<td class="tdCat">Tags</td><td class="tdInfo">' + escapeHTML(value) + '</td></tr>\n';
  }
  value = data['template_code'];
  if (value) {
    msg += tr(++cnt)+'<td class="tdCat">Template</td><td class="tdInfo">' + escapeHTML(value) + '</td></tr>\n';
  }
  for (name in data) {
    if (name.substr(0, 9) == 'template[') {
      value = data[name];
      if (value) {
        c = name[9];
        msg += tr(++cnt)+'<td class="tdCat">' + c.toUpperCase() + name.substr(10, name.length-11) + '</td><td class="tdInfo">' + escapeHTML(value) + '</td></tr>\n';
  } } }
  msg += '</table>\n';

  value = CKEDITOR.instances['editor1'].getData();
  if (value) {
    msg += '<h2>Text:</h2> ' + value + '\n';
  }
  if (data['language_codes[]']) {
    msg += '<p>' + data['language_codes[]'];
  }
  customAlert(
    { target:top,
      html:true,
      width:400,
      title:'Preview Annotation',
      icon:false,
      body:msg,
      buttons:{ Draft:preview_draft,
				Save:preview_save,
				Publish:preview_publish,
				Cancel:null}
    });
}

