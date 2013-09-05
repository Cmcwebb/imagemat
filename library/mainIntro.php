<!DOCTYPE HTML>
<?php 
$gPHPscript = __FILE__;
$dir = dirname(__FILE__);

require_once($dir . '/../include/boilerplate.php');
require_once($dir . '/../include/util.php');
require_once($dir . '/../include/db.php');
require_once($dir . '/../include/simple_view.php');
require_once($dir . '/../include/annotations.php');
require_once($dir . '/../include/urls.php');

if(!isset($_SESSION)) {
  session_start();
}

srcStylesheet(
  '../css/library.css'
);

srcJavascript('../js/alert.js'
	      ,'../js/util.js'
	      ,'../js/annotate.js'
	      ,'../js/enterkey.js'
	      ,'../js/fullwidth.js'
	      ,'../js/edit.js'
  	      ,'../js/images.js'
	      ,'../../tools/ckeditor/ckeditor.js'
		  ,'../js/library.js'
//            , '../js/newlib.js'
);

?>


<body>
<div class="mainIntro">
<h3>Welcome to your personal library space on imageMAT!</h3> 
<hr>

<p>This is your "dashboard" from which you can view all your personal projects and annotations as well as the work on which you are collaborating with other imageMAT users in projects and groups.</p>
<img src="../images/033v.jpg" align="right" width="150px" border="1" hspace="5px" vspace="5px">
<p>You can access all of your work from the directory panel on the left. When you click on a project or group name the information about that project or group will appear in this center panel; you will also be able to view or edit the annotations belonging to that project or group from this center panel. Related information about permissions and activity can be viewed in the panel on the right.</p>

<p>If you're new to imageMAT or are curious about how to make the most of your membership, refer to one of the following information sections:</p>

<ul>
<li><a href="#" onClick="openNewWindowAnnotation()">About Annotations</a></li>
<li><a href="#" onClick="openNewWindowProject()">About Projects</a></li>
<li><a href="#" onClick="openNewWindowBookmarklet()">About the Bookmarklet</a></li>
</ul>

<p>For general help, please refer to our <a href="http://mat.uwaterloo.ca/MAT/?page_id=64" target="_blank">support forum.</a></p>

<p>We hope you find your experiences working with imageMAT to be productive and look forward to your feedback. <a href="mailto:admin@imagemat.org">~ the imageMAT team</a></p>
</div>

</body>
</html>