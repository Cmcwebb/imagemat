// checkVersion('base64', 1);

// This code was written by Tyler Akins and has been placed in the
// public domain.  It would be nice if you left this header intact.
// Base64 code from Tyler Akins -- http://rumkin.com

function custom_base64_encode(input)
{
  var output = new Array( Math.floor( (input.length + 2) / 3 ) * 4 );
  var chr1, chr2, chr3;
  var enc1, enc2, enc3, enc4;
  var i = 0, p = 0;
	
  do {
	chr1 = input.charCodeAt(i++);
	chr2 = input.charCodeAt(i++);
	chr3 = input.charCodeAt(i++);
	
	enc1 = chr1 >> 2;
	enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
	enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
	enc4 = chr3 & 63;
	
	if (isNaN(chr2)) {
	  enc3 = enc4 = 64;
	} else if (isNaN(chr3)) {
	  enc4 = 64;
	}
	
	output[p++] = _keyStr.charAt(enc1);
	output[p++] = _keyStr.charAt(enc2);
	output[p++] = _keyStr.charAt(enc3);
	output[p++] = _keyStr.charAt(enc4);
  } while (i < input.length);
	
  return output.join('');
}

function custom_base64_decode(input)
{
  var output = "";
  var chr1, chr2, chr3 = "";
  var enc1, enc2, enc3, enc4 = "";
  var i = 0;
		
  // remove all characters that are not A-Z, a-z, 0-9, +, /, or =
  input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");
		
  do {
	enc1 = _keyStr.indexOf(input.charAt(i++));
	enc2 = _keyStr.indexOf(input.charAt(i++));
	enc3 = _keyStr.indexOf(input.charAt(i++));
	enc4 = _keyStr.indexOf(input.charAt(i++));
		
	chr1 = (enc1 << 2) | (enc2 >> 4);
	chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
	chr3 = ((enc3 & 3) << 6) | enc4;
		
	output += String.fromCharCode(chr1);
		
	if (enc3 != 64) {
	   output += String.fromCharCode(chr2);
	}
	if (enc4 != 64) {
	   output += String.fromCharCode(chr3);
	}
		
  } while (i < input.length);
  return output;
}

function base64_encode(input)
{
  if (window.btoa) {
    return window.btoa(input);
  }
  return custom_base64_encode(input);
}

function base64_decode(input)
{
  if(window.atob) {
	return window.atob(input);
  }
  return custom_base64_decode(input);
}
