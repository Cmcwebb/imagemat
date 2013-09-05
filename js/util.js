// checkVersion('util', 1);

// Utilities
// http://www.howtocreate.co.uk/tutorials/javascript/browserwindow

function showHtmlLink(link)
{
  window.location = link;
}

/*
function checkVersion(name, version)
{
  if (typeof ignoreVersions == 'undefined') {
    if (typeof jsversions[name] == 'undefined') {
      alert(name + '.js has no current version');
	} else if (jsversions[name] != version) {
	  var cookie = name + 'Reload';
	  var flag   = readCookie(cookie);
      if (flag == null) {
		createCookie(cookie, '0', 60);
	    alert(name + '.js version ' + version + ' has been upgraded to version ' + jsversions[name] + '.\n\nReloading page.');
		window.location.reload(true);
        top.window.location.reload(true);
	    return;
      }
	  deleteCookie(cookie);
	  alert(name + '.js version ' + version + ' has been upgraded to version ' + jsversions[name] + '.\n\nPlease clear your browser cache.');
      top.showHtmlLink('../error/upgradeVersion.php');
} } }
*/

function dumbGetElementById(idVal)
{
  if (document.all != null) {
    return document.all[idVal];
  }
  alert("Problem getting element '" + idVal + "' by id");
  return null;
}

if (document.getElementById == null) {
  document.getElementById = dumbGetElementById;
}

function windowInnerWidth(target)
{
  return target.innerWidth || target.document.documentElement.clientWidth || target.document.body.clientWidth;
}

function windowInnerHeight(target)
{
  return target.innerHeight || target.document.documentElement.clientHeight || target.document.body.clientHeight;
}

function windowPageXOffset(target)
{
  return target.pageXOffset || target.document.body.scrollLeft;
}

function windowPageYOffset(target)
{
  return target.pageYOffset || target.document.body.scrollTop;
}

function escapeHTML(unsafe)
{
  if (typeof unsafe == 'string') {
    return unsafe.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#39;");
  }
  return unsafe;

} 

function trim(s)
{
  if (s == null) {
    return s;
  }
  return s.replace(/^\s+|\s+$/g,"");
}

function addhidden1(form, name, value)
{
  if ((typeof form[name] === 'undefined') || 
      (typeof form[name].type == 'undefined') ||
	  (form[name].type != 'hidden')) {
    var input = document.createElement('input');
    input.type  = 'hidden';	// ie8 insist this be done before appendChild
    input.name  = name;
    input.value = value;
    form.appendChild(input);
  } else {
    form[name].value = value;
  }
}

function addhidden(form, name, value)
{
  if (value != null && typeof value == 'object') {
	for (item in value) {
	  addhidden1(form, name + '[' + item + ']', value[item]);
    }
  } else {
	addhidden1(form, name, value);
} }

function submitPost(options)
{
  var d,body, form, input, parameters, parameter;

  d    = document;
  body = d.getElementsByTagName("body")[0];
  form = body.appendChild(d.createElement('form'));

  if (options.parameters) {
    parameters = options.parameters;
    for (parameter in parameters) {
      addhidden(form, parameter, parameters[parameter]);
  } }
  form.action = options.action;
  form.method = 'post';
  if (options.target) {
    form.target = options.target;
  }
  form.submit();
}

