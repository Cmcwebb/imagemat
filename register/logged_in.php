<?php
if(!isset($_SESSION)) {
  session_start();
}
$logged_in = (isset($_SESSION['imageMAT_user_id']) ? 'true' : 'false');
?>
(function() { 
    var status = {
      "logged_in":<?php echo $logged_in; ?>,
      "course_selected":true,
      "ready":<?php echo $logged_in; ?>
    };
    if (window.SherdBookmarklet) {
      window.SherdBookmarklet.update_user_status(status);
    } 
    if (!window.SherdBookmarkletOptions) {
      window.SherdBookmarkletOptions={};
    }
    window.SherdBookmarkletOptions.user_status = status;
  })();
