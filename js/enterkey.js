// checkVersion('enterkey', 1);

function handleEnter(event)
{
  var keyCode = event.keyCode ? event.keyCode :
                event.which ? event.which :
                event.charCode;
  if (keyCode == 13) {
    var field    = event.target;
    var form     = field.form;
    var elements = form.elements;
    var next;
    var i,j;

    for (i = elements.length; 0 <= --i ; ) {
      if (elements[i] == field) {
        for (j = i; ++j != i;) {
          if (j == elements.length) {
            j = 0;
          }
          next = elements[j];
          if (next.nodeName != 'INPUT' || next.type == 'text' || next.type == 'password') {
            if (next.id == 'editor1') {
              next = CKEDITOR.instances['editor1'];
            }
            break;
        } }
        next.focus();
        return false;
  } } }
  return true;
}

function disableEnterKey(formId)
{
  var form     =  document.getElementById(formId);

  if (!form) {
    return;
  }
  var elements = form.elements;
  var fun;
  var i, field;

  for (i = elements.length; 0 <= --i ; ) {
    field = elements[i];
    switch (field.nodeName) {
    case 'INPUT':
      if (field.type != 'text' && field.type != 'password') {
        break;
      }
    case 'SELECT':
      field.onkeypress = handleEnter;
      break;
} } }

