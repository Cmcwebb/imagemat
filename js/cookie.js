// checkVersion('cookie', 1);

function readCookie(name)
{
  var cookie = document.cookie;
  if (cookie) {
    var ca   = cookie.split(';');
    var lth  = ca.length;
    var lth1 = name.length;
    var c, i, j;

    for (i = 0; i < lth; ++i) {
	  c = ca[i];
	  j = c.indexOf('=');
      if (lth1 <= j) {
        if (lth1 < j && (c[j - lth1 - 1] != ' ')) {
		  continue;
		}
		if (c.substring(j-lth1,j) == name) {
		  return c.substring(j+1);
  } } } }
  return null;
}

function createCookie(name, value, secs)
{
  var expires;
  if (secs) {
    var date = new Date();
	date.setTime(date.getTime() + (secs * 1000));
	expires = "; expires=" + date.toGMTString();
  } else {
	expires = "";
  }
  document.cookie = name + '=' + value + expires + '; path=/';
}

function deleteCookie(name)
{
  createCookie(name, '', -1);
}

