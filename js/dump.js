// checkVersion('dump', 1);

// http://www.openjs.com/scripts/others/dump_function_php_print_r.php
/**
 * Function : dump()
 * Arguments: The data - array,hash(associative array),object
 *    The level - OPTIONAL
 * Returns  : The textual representation of the array.
 * This function was inspired by the print_r function of PHP.
 * This will accept some data as the argument and return a
 * text that will be a more readable version of the
 * array/hash/object that is given.
 * Docs: http://www.openjs.com/scripts/others/dump_function_php_print_r.php
 */

function dump(arr)
{
  var ret, type, item, value;
	
  if (arr == null) {
    return 'null';
  }
  type = typeof(arr);
  switch (type) {
  case 'boolean':
    if (arr) {
      return 'true';
    }
    return 'false';
  case 'number':
    return arr;
  case 'string':
    return "'" + escapeHTML(arr) + "'";
  case 'object':
    ret = '<table rules=all border=3>';
    for(item in arr) {
      value = arr[item];
      ret += '<tr>\n<td align=right>\n' + escapeHTML(item) + '\n</td>\n<td>' + dump(value) + '\n</td>\n</tr>';
    }
    return ret + '\n</table>';
  }
  return type;
}
