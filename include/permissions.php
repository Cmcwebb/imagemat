<?php

function bit_checked($value, $bit)
{
  if ($value & (1 << $bit)) {
    return ' checked';
  }
  return '';
}

define("CAN_ANNOTATE",    0x01);
define("CAN_SUBFOLDER",   0x02);
define("CAN_DOCUMENT",    0x04);
define("VETTER",          0x08);
define("MANAGER",         0x10);
define("CAN_OWN",         0x20);
define("INHERITS_MEMBERS",0x40);

$folder_options = array(
'can_annotate'      => 'Can annotate',
'can_subfolder'     => 'Can subfolder',
'can_document'      => 'Can document',
'vetter'            => 'Is Vetter',
'manager'           => 'Is Manager',
'can_own'           => 'Can own'
);

define("MONITOR_SELF", 0x01);
define("MONITOR_COMMENTS", 0x02);
define("MONITOR_OTHERS",   0x04);
define("MONITOR_OTHERS_COMMENTS",   0x08);
define("MONITOR_NEW", 0x10);
define("MONITOR_SUBFOLDERS", 0x20);
define("MONITOR_OTHERS_SUBFOLDERS", 0x40);
define("MONITOR_PERMISSIONS", 0x80);

$monitor_options = array(
'monitor_self'              => 'Monitor self',
'monitor_comments'          => 'Monitor comments on my work made by others',
'monitor_others'            => 'Monitor the work of other users in a shared project',
'monitor_others_comments'   => 'Monitor comments on other users\'s work in a shared project',
'monitor_new'               => 'Monitor new',
'monitor_subfolders'        => 'Monitor subfolders',
'monitor_others_subfolders' => 'Monitor others subfolders',
'monitor_permissions'       => 'Monitor permissions'
);

$annotate_options = array(
'owns'              => 'Owns',
'may_see'           => 'May see',
'may_read'          => 'May read',
'may_post'          => 'May post',
'may_copy'          => 'May copy',
'may_update'        => 'May update',
'may_delete'        => 'May delete',
'may_read_comments' => 'May read comments',
'may_comment'       => 'May comment',
'manage_comments'   => 'Manage comments',
'may_x_post'        => 'May cross post' );

$annotate_defaults = array(
'owns'              => 7,
'may_see'           => 31,
'may_read'          => 31,
'may_post'          => 17,
'may_copy'          => 1,
'may_update'        => 17,
'may_delete'        => 1,
'may_read_comments' => 17,
'may_comment'       => 17,
'manage_comments'   => 1,
'may_x_post'        => 1 );

define("NEW_USER_INHERITS",0);
define("FOLDER_NOT_VISIBLE", 1);
define("NEW_USER_NO", 2);
define("NEW_USER_MAYBE", 3);
define("NEW_USER_ASK_OWNER", 4);
define("NEW_USER_ASK_MANAGER", 5);
define("NEW_USER_ASK", 6);
define("NEW_USER_ANYONE", 7);

$new_user_options = array(
 0                => 'Inherit rule',
 1                => 'Not visible',
 2                => 'No',
 3                => 'Maybe',
 4                => 'Ask owner',
 5                => 'Ask manager',
 6                => 'Ask',
 7                => 'Anyone' );

define("GRANT_CREATOR", 0x01);
define("GRANT_SYSTEM",  0x02);
define("GRANT_FOLDER",  0x04);
define("GRANT_MANAGER", 0x08);
define("GRANT_VETTER",  0x10);
define("GRANT_MEMBER",  0x20);
define("GRANT_FOLLOWER",0x40);
define("GRANT_PUBLIC",  0x80);

?>
