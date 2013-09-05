<?

function insert_folder($parent_id, $name, $description)
{
  global $gUserid;

  $query =
'insert into folders
       (parent_folder_id, name, description, new_users, inherits, 
        default_folder_permissions, default_mask_owns, default_mask_may_see,
        default_mask_may_read, default_mask_may_post, default_mask_may_copy,
        default_mask_may_update, default_mask_may_delete,
        default_mask_may_comment,
        default_mask_may_read_comments, default_mask_manage_comments,
        default_mask_may_x_post, creator_user_id, created)
 select folder_id, ' . DBstringC($name) . DBstringC($description) . '0, 127,
        default_folder_permissions, default_mask_owns, default_mask_may_see,
        default_mask_may_read, default_mask_may_post, default_mask_may_copy,
        default_mask_may_update, default_mask_may_delete,
        default_mask_may_comment,
        default_mask_may_read_comments, default_mask_manage_comments,
        default_mask_may_x_post,' . DBstringC($gUserid) . 'utc_timestamp()
   from folders
  where folder_id = ' . DBnumber($parent_id);

  $ret = DBquery($query);
  if (!$ret) {
    return -1;
  }
  return DBid();
}

?>

