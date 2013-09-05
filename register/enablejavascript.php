<!DOCTYPE HTML>
<?php

$gPHPscript = __FILE__;
$dir = dirname(__FILE__);
require_once($dir . '/../include/boilerplate.php');

header('window-target: _top');
htmlHeader('Enabling Javascript');
srcStylesheet('../css/style.css');
?>
<meta http-equiv="window-target" content="_top">
</head>
<body>
<?php
bodyHeader();
?>
<!--[if lt IE 9]>
imageMAT works on Internet Explorer 9+. Please update your browser.
<![endif]-->
<?php enterJavascript(); ?>
document.write('<h1><font color=blue>Javascript is enabled</font></h1>');
<?php exitJavascript(); ?>

<h1>How to enable Javascript in your browser</h1>
<p>
The mechanism to enable Javascript will differ both for different browsers,
and potentially for different versions of any given browser.  The following
instructions thus provide only general guidelines.  Further instruction can
readily be obtained from the web.
<p>

<strong>This Web site uses scripting to enhance your browsing experience. Once scripting is successfully enabled, this page should advise you of that fact
</strong>.
<p>
<h3>To allow all Web sites in the Internet zone to run scripts, use the steps
that apply to your browser:</h3>
<p>
<h3>Windows Internet Explorer</h3>
(all versions except <u>Pocket Internet Explorer</u>)
<p>
<strong>Note</strong>
To allow scripting on this Web site only, and to leave scripting disabled in
the Internet zone, <u>add this Web site to the <strong>Trusted sites</strong>
zone.</u>
<OL>
<LI>On the <strong>Tools</strong> menu, click
<strong>Internet Options</strong>, and then click the
<strong>Security</strong> tab. </li>
<li>Click the <strong>Internet</strong> zone.  </li>
<li>If you do not have to customize your Internet security settings, click
<strong>Default Level</strong>.
Then do step 4 </li>
<blockquote>If you have to customize your Internet security settings, follow
these steps:
<br>a.   Click <strong>Custom Level</strong>.
<br>b.    In the <strong>Security Settings Internet Zone</strong>
dialog box, click <strong>Enable</strong> for <strong>Active Scripting</strong>
in the <strong>Scripting</strong> section.</blockquote>
<li>Click the <strong>Back</strong> button to return to the previous page, and
then click the <strong>Refresh</strong> button to run scripts.</li>
</ol>
<h3>Mozilla Corporation's Firefox version 2</h3>
<ol>
<li>On the <strong>Tools</strong> menu, click <strong>Options</strong>. </li>
<li>On the <strong>Content</strong> tab, click to select the
<strong>Enable JavaScript</strong> check box.</li>
<li>Click the <strong>Go back one page</strong> button to return to the
previous page, and then click the <strong>Reload current page</strong> button
to run scripts.</li>
</ol>
<h3>Opera Software's Opera version 9</h3>
<ol>
<li>On the <strong>Tools</strong> menu, click <strong>Preferences</strong>.
</li>
<li>On the <strong>Advanced</strong> tab, click <strong>Content</strong>.</li>
<li>Click to select the <strong>Enable JavaScript</strong> check box, and then
click <strong>OK</strong>.</li>
<li>Click the <strong>Back</strong> button to return to the previous page, and
then click the <strong>Reload</strong> button to run scripts.</li>
</ol>
<h3>Netscape browsers</h3>
<P></P>
<ol>
<li>Select <strong>Edit</strong>, <strong>Preferences</strong>,
<strong>Advanced</strong></li>
<li>Click to select  <strong>Enable JavaScript</strong> option.</li>
</ol>

<?

bodyFooter();
?>

</body>
</html>
