// checkVersion('top', 1);

/* Doesn't work IJD */

function showInTopWindow()
{
  if (window.top == window.self) {
    alert('In top window');
  } else {
    var href=window.document.URL + '?top=yes';
    alert(href + ' not in top window');
    // href='https://mat.uwaterloo.ca/ijdavis/annotate/index.html';
    //window.top.location = href;
    //location = href;
} }
