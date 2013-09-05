<!DOCTYPE HTML>
<?php

/* http://www.backroadtravelers.com/trainpics.html works */
/*add 'sb[flickr_apikey']='foobar123456789';for flickr support*/
/*NOTE: these values are hard-coded into the bookmarklet and cannot be refreshed without a bookmarklet reinstallation
 */

require_once('../include/boilerplate.php');

function base_path()
{
  return implode('/',array_slice(explode('/',$_SERVER['PHP_SELF']),0,-2));
}

function base_url() {
  $prot = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']) ? 'https://' : 'http:
//');     
  $host = $_SERVER['SERVER_NAME'];
  $path = base_path();
  return $prot.$host.$path;   
}       

htmlHeader('Installing ImageMAT bookmarklet');
srcStylesheet('../css/style.css');
?>
<body>
<?php bodyHeader(); ?>

<p>
Install the bookmarklet below by dragging it into your bookmarks toolbar.</p>
[<a href="javascript:/*BOOKMARKLET:<?php echo $_SERVER['SERVER_NAME']?>*/(function(host,bookmarklet_url,user_url){ 
var b=document.body;
var sb=window.SherdBookmarkletOptions;
if (!sb) {
    sb = window.SherdBookmarkletOptions = {};
    sb['action']='jump';
    sb['form_api']='imagemat';
}
sb['host_url']=host+'/annotate/annotate.php';
sb['login_url']=host+'/register/logon.php';
sb['tab_label']='Analyze in ImageMat';
sb['not_logged_in_message']='You are not logged in to ImageMat';
sb['login_to_course_message']='login';
sb['link_text_for_existing_asset']='Link in ImageMat';
var r4=function(){return '?nocache='+Number(new Date());};
var t='text/javascript';
if(b){
    var x=document.createElement('script'); x.type=t; x.src=host+user_url+r4();
    b.appendChild(x);
    var z=document.createElement('script'); z.type=t; z.src=host+bookmarklet_url+r4();
    b.appendChild(z);
    if (typeof jQuery=='undefined') {
        var y=document.createElement('script');
        y.type=t;
        y.src=host+'/js/sherdjs/lib/jquery.min.js';
        var onload = (/MSIE/.test(navigator.userAgent))?'onreadystatechange':'onload';        
        y[onload]=function(){
            var jQ = sb.jQuery = jQuery.noConflict(true);
            if (sb && sb.onJQuery) {
                sb.onJQuery(jQ);
            }
        };
        b.appendChild(y);
    }
}

})('<?php echo base_url(); ?>',
   '/js/sherdjs/src/bookmarklets/sherd.js',
   '/register/logged_in.php')"><?php
$path = base_path();
if ($path == '/imagemat') {
  echo 'ImageMAT ';
} else {
  echo substr($path,1); 
}
?> Bookmarklet</a>]
</p>
<p>
If you need to uninstall this bookmarklet, once in the toolbar right click on
it and select <i>delete</i>.
</p>

<p>
See <a href="http://mat.uwaterloo.ca/helpwiki/index.php/About_the_imageMAT_Bookmarklet">about Bookmarklet</a> for an explanation of this bookmarklet.</p>
<?php bodyFooter(); ?>
</body>
</html>
