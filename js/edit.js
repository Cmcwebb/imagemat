// checkVersion('edit', 1);

function pageLoaded()
{
  disableEnterKey('form');
}

/* http://ckeditor.com */

/* Have annotation content editor take up free space */
function editorReady(editor1)
{
/*
  if (self != top) {
    var myMaxheight, myIframeOffset, myIframeHeight, myFooterHeight, mySpare, e;
    var d        = top.document;
    var myIframe = self.frameElement;
    if (!myIframe) {
      return;
    }
    mySpare = windowInnerHeight(top);
    mySpare -= myIframe.clientHeight;
    if (mySpare > 0) {
      for (e = myIframe; e && !isNaN(e.offsetTop); e = e.parentNode) {
        mySpare -= e.offsetTop;
      }
      if (mySpare > 0) {
        var myFooter = d.getElementById('footer');
        if (myFooter) {
          mySpare -= myFooter.clientHeight;
          if (mySpare > 0) {
            myIframe.style.height = myIframe.clientHeight + mySpare;
    } } } }
    var myForm = document.getElementById('form');
	if (myForm) {
      mySpare = myIframe.clientHeight;
      for (e = myForm; e && !isNaN(e.offsetTop); e = e.parentNode) {
        mySpare -= e.offsetTop;
      }
      mySpare -= myForm.clientHeight;
      if (mySpare > 0) {
        e = editor1.getResizable(true);
        e = e.$;
        editor1.resize('100%', e.clientHeight+mySpare, false, false);
      }
  } }
*/
}

function createEditor(elementId, startExpanded, language, readonly )
{
  var myEditor = CKEDITOR.instances[elementId];
  if ( myEditor ) {
    myEditor.destroy();
  }

  // Replace the <textarea id="editor"> with an CKEditor
  // instance, using default configurations.
  myEditor = CKEDITOR.replace( elementId,
			 { language : language,
			   toolbarStartupExpanded : startExpanded,

			   on :
			     { instanceReady : function()
				   {
				     // Wait for the editor to be ready to set
				     // the language combo.
					 var languages = document.getElementById( 'languages' );
					 if (languages) {
					   languages.value = this.langCode;
					   languages.disabled = false;
					 }
					 if (readonly) {
						myEditor.setReadOnly(true);
					 }
                     editorReady(myEditor);
				   }
				 },
			   toolbar : 
			     [
                   { name: 'document',
                     items : [ 'Source','-','Save','Print' ]
                   },
                   { name: 'basicstyles',
                     items : [ 'Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat' ]
                   },
                   { name: 'insert',
                     items : [ 'Image','Table','SpecialChar' ]
                   },
                   { name: 'colors', 
					 items : [ 'TextColor','BGColor' ]
				   },
                   { name: 'paragraph',
                     items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock' ]
                   },
                   { name: 'clipboard',
                     items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] 
                   },
                   { name: 'links', items : [ 'Link','Unlink','Anchor' ]
                   },
                   { name: 'editing',
                     items : [ 'Find','Replace','-','SelectAll','-','SpellChecker' ] 
                   },
                   { name: 'styles',
                     items : [ 'Styles','Format','Font','FontSize' ]
                   }
			     ]
			   }
             );
  return myEditor;
}

function getFormData(form_id, data)
{
  var form = document.getElementById(form_id);
  if (form) {
    var elements = form.elements;
    var i, node;

    for (i = 0; i < elements.length; ++i) {
      node = elements[i];
      switch (node.nodeName) {
      case 'INPUT':
        if (node.hidden != true) {
          if (node.type == 'text') {
            data[node.name] = trim(node.value);
          } else if (node.type == 'checkbox') {
            data[node.name] = node.checked;
        } }
        break;
      case 'SELECT':
        if (node.name == undefined) {
          alert('Unnamed select');
          break;
        }
        var options = node.options;
        var selected = '';
        var bar = '';
        var j;

        for (j = 0; j < options.length; ++j) {
          option = options[j];
          if (option.selected) {
            selected += bar + option.text;
            bar = '|';
        } }
        data[node.name] = selected;
        break;
      case 'TEXTAREA':
        data[node.name] = trim(node.value);
        break;
} } } }
