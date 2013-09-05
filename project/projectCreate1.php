<!DOCTYPE HTML>
<?php

$gPHPscript = __FILE__;
$dir = dirname(__FILE__);
global $user_folder_id ;

require_once($dir . '/../include/boilerplate.php');
require_once($dir . '/../include/util.php');
require_once($dir . '/../include/db.php');
require_once($dir . '/../include/simple_view.php');
require_once($dir . '/../include/annotations.php');
require_once($dir . '/../include/urls.php');

if (mustlogon()) {
  /* This can happen if the frame is left idle a long time */
  return;
}

if(!isset($_SESSION)) {
  session_start();
}
srcStylesheet(
  '../css/project.css'
);
?>

<?php

$description = getparameter('parent_id');
?>

<body>
<div class="projectText">

<form id=form name=form action="create_folder.php" method="post" >
<h2>Create New Project</h2>
<br>
<h3>Give your project a title:</h3>
<br>

<input type="text" id="name" name="name" size="50" maxlength="255" value=""/> 
<?php // echo htmlspecialchars($name); ?> 

<br>
<td align=right>Tags:</td>
<br>

<input type="text" name="tags" size="50" maxlength="255" value=" "?>   

 
<br>

<td colspan=2>Description:</td>
<br>

<textarea id="folderdesc" name="folderdesc" width="100%" rows="6">
</textarea>
<br>
 <?php //echo htmlspecialchars($description);
 ?> 

<h3>What type of project is this?</h3>
<p><select name="Project Type">
  <option value="Private" selected="selected">Private: Only you can view and work in this project</option>
  <option value="Public">Shared: You can share this project with other imageMAT users</option>
</select>
</p>

<h3>Add a key image for this project:</h3>
<p>[add image file form]
</p>

<h3>Add annotations:</h3>
<p>Would you like to add annotations to this project now? You can add more annotations to this project at any time from the project panel in the library.<br />
[add annotations form]</p>

<h3>Add collaborators:</h3>
<p>Would you like to add imageMAT users as collaborators in this project? (You can share this project with more imageMAT users at any time by acessing the "Add users" function from the library.)<br />
[add users form]</p>
</div>

<input type="submit" value="Create" />
<input type=reset value="Restart" onclick="reset_form()" />

</form>

<?php
close:
DBclose();
done:
bodyFooterFilename();
?>

</body>
</html>
