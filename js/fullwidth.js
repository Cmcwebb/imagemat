// checkVersion('fullwidth', 1);

/* Decide if there should be button to toggle between full and split frame
   mode and if so give it the correct value.  This has to be done onload
   because page can be reloaded in either state, and php doesn't know
   what state this page will be in. Eg. [duplicate] reloads in either
   state as does [template].
 */

function isFullFrame()
{
  if (self != top) {
	var my = self.frameElement;
	if (my.style.width != '100%') {
	  return false;
  } }
  return true;
}

function label_frame_button()
{
  if (self != top) {
    var fullWidthButton = document.getElementById("fullWidthButton");
    if (fullWidthButton) {
	  if (isFullFrame()) {
        fullWidthButton.value = 'Split Frame';
      } else {
        fullWidthButton.value = 'Full Frame';
      }
      fullWidthButton.style.display = '';
} } }

/* Switch between full frame and split frame view */
function toggle_frames()
{
  if (self != top) {
	var lth   = parent.frames.length;
	var my    = self.frameElement;
	var frame, right, other, extra, i;

	for (i = lth; ; ) {
	  if (--i < 0) {
		alert('Couldn\'t find my frame');
		return;
	  }
	  if (self == parent.frames[i]) {
		right = (i & 1);
		break;
	} }

	for (i = lth; ; ) {
	  if (--i < 0) {
		alert('Couldn\'t find other frame');
		return;
	  }
	  frame = parent.frames[i];
	  if (self == frame) {
		continue;
	  }
	  other = frame.frameElement;
	  if (i < 2 || other.style.height != '0px') {
	    break;
	} }
	
    if (other.style.display == 'none') {
      my.style.width      = '49.5%';
      if (right) {
        my.style.margin   = '0 0 0 50%'; // 50% on left
      } else {
        my.style.margin   = '0 50% 0 0'; // 50% on right
      }
	  other.style.display = '';
    } else {
      other.style.display = 'none';
      my.style.width      = '100%';
      my.style.margin     = '0 0 0 0';
    }
    label_frame_button();
  }
}

function full_frame()
{
  if (!isFullFrame()) {
    toggle_frames();
} }
