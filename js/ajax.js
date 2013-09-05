// checkVersion('ajax', 1);

function getXMLHttpRequest()
{
  // return ActiveXObject for IE5, and IE6
  return (window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP"));
}

function do_ajax(state, target, parms, handler)
{
  var http  = getXMLHttpRequest();
  http.onreadystatechange = handler;
  http.open("POST",target,true);

  // Send the proper header information along with the request
  http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  http.setRequestHeader("Content-length", parms.length);
  http.setRequestHeader("Connection", "close");
  http.imagematState = state;
  http.send(parms);
}

function do_synchronous_ajax(http, target, parms)
{
  http.open("POST",target,false);

  // Send the proper header information along with the request
  http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  http.setRequestHeader("Content-length", parms.length);
  http.setRequestHeader("Connection", "close");
  http.send(parms);
  if (http.readyState != 4 || http.status != 200) {
    alert('ajax readyState=' + http.readyState + ' status=' + http.status);
  }
}

