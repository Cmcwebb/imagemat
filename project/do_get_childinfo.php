<?php

$gPHPscript = __FILE__;
$dir = dirname(__FILE__);
require_once($dir . '/../include/boilerplate.php');

$id     = getpost('id');
if (!isset($id)) {
  echo 'Mising id';
  return;
}

$indent = getpost('indent');
if (!isset($indent)) {
  echo 'Missing indent';
  return;
}

$show = getpost('show');
if (!isset($show)) {
  $show = 0;
}

require_once($dir . '/../include/db.php');
require_once($dir . '/../include/emit_children.php');

if (DBconnect()) {

  $query =
'select 0 as isSymlink, parent_folder_id, folder_id, name
   from folders
  where parent_folder_id = ' . DBnumber($id);
  if ($id == 1) {
    if ($show != 2) {
      $query .= '
    and creator_user_id  = ' . DBstring($gUserid);
      if ($show == 1) {
        $query .= '
  union all
 select 1 as isSymlink, 1 as parent_folder_id, folder_id, concat(favouritefolders.name, \' => \', folders.name) as name
   from favouritefolders, folders
  where favouritefolders.user_id          = ' . DBstring($gUserid) . '
    and favouritefolders.target_folder_id = folders.folder_id';
    } }
  } else {
    $query .= '
  union all
 select 1 as isSymlink, symlinks.parent_folder_id, folder_id, concat(symlinks.name, \' => \', folders.name) as name
   from symlinks, folders
  where symlinks.parent_folder_id = ' . DBnumber($id) . '
    and target_folder_id = folder_id';
  }

  emit_children($indent, $id, $query);

  DBclose();

}
?>
