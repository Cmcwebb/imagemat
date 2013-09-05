function annotationIdsToServer()
{
  if (window.setAnnotations_changed) {
    var lth   = window.setAnnotations_lth;
    var parms = 'setAnnotations=[';
    var http;
  
    if (lth > 0) {
      var setAnnotations_array  = window.setAnnotations;
  
      setAnnotations.length = window.setAnnotations_lth;
      setAnnotations_array.sort(sortNumber);
      parms += setAnnotations_array.toString();
    }
    parms += ']';
  
    //alert('Parms=' + parms);
    http  = getXMLHttpRequest();
  
    // Must be synchronous
    do_synchronous_ajax(http, "../annotate/do_setAnnotations.php", parms);
	if (http.responseText != '') {
      alert(http.responseText);
	}
    setAnnotations_changed = false;
  }
}
