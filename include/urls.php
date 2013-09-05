<?php

/* http://snippets.dzone.com/posts/show/187
   This function transforms an HTTP request path (ie, $_SERVER['PATH_INFO'])
   into its canonical form, removing empty path elements and relative path
   elements (//, /./, /../). It assumes that there is a trailing slash on the
   path if it's a directory.
 */

function canonical_url($url)
{
  if (isset($url)) {
    $parse = parse_url($url);
    if ($parse !== false) {
      if (isset($parse['scheme'])) {
        $parse['scheme'] = strtolower($parse['scheme']);
        switch ($parse['scheme']) {
        case 'http':
          unset($parse['scheme']);
          if (isset($parse['port']) && $parse['port'] == 80) {
            unset($parse['port']);
          }
          break;
        case 'https':
          if (isset($parse['port']) && $parse['port'] == 443) {
            unset($parse['port']);
          }
      } }
      if (isset($parse['path'])) {
        $part1 = preg_replace('/\\\/','/', $parse['path']);
        $b = explode('/', $part1);
        foreach($b as $i => $folder) {
          $folder = trim($folder);
          
          if ($folder == '.') {
            unset($b[$i]);
          } else if ($folder == '..') {
            unset($b[$i]);
            while (--$i >= 0) {
              if (isset($b[$i])) {
                $prior = $b[$i];
                if ($prior != '' && strpos($prior, ':') === false) {
                  unset($b[$i]);
                }
                break;
            } }
          } else {
            $b[$i] = $folder;
        } }
        $parse['path'] = implode('/', $b);
      }
      $ret = '';
      if (isset($parse['scheme'])) {
        $ret = $parse['scheme'] . ':';
      }
      if (isset($parse['user']) || isset($parse['password']) || isset($parse['host']) || isset($parse['port'])) {
        $ret .= '//';
        if (isset($parse['user']) || isset($parse['password'])) {
          if (isset($parse['user'])) {
            $ret .= $parse['user'];
          }
          if (isset($parse['password'])) {
            $ret .= ':' . $parse['password'];
          }
          $ret .= '@';
        }
      }
      if (isset($parse['host'])) {
        $ret .= strtolower(trim($parse['host']));
      }
      if (isset($parse['port'])) {
        $ret .= ':' . $parse['port'];
      }
      if (isset($parse['path'])) {
        $ret .= $parse['path'];
      }
      if (isset($parse['query'])) {
        parse_str($parse['query'], $b);
        ksort($b); 
        $ret .= '?';
        $part1 = '';
        foreach($b as $i => $folder) {
          $lth   = strlen($folder);
          $quote = null;
          if ($lth > 1) {
            switch ($folder[0]) {
            case '\'':
            case '"':
              if ($folder[$lth-1] == $folder[0]) {
                $folder = substr($folder, 1, $lth-2);
          } } }
          if (strpos($folder, '\'') !== false) {
             $quote = '"';
          } else if (strpos($folder, '"') != false) {
             $quote = '\'';
          } else if (strpbrk($folder, '?&;+%') != false) {
             $quote = '\'';
          }
          if (isset($quote)) {
            $folder = $quote . $folder . $quote;
          }
          $ret .= $part1 . $i . '=' . $folder;
          $part1 = '&';
        }
      }
      if (isset($parse['fragment'])) {
        $ret .= '#' . trim($parse['fragment']);
      }
      return $ret;
  } }
  return $url;
}

function get_url_string($id)
{
  $query =
'select url
  from urls
 where url_id = ' . DBnumber($id);

  $ret = DBquery($query);
  if (!$ret) {
	return false;
  }
  $row = DBfetch($ret);
  return $row['url'];
}

function get_url_id($url, $force)
{
  $canonical = canonical_url($url);

  $query = 
'select url_id
  from urls
 where url = ' . DBstring($canonical);
  $ret = DBquery($query);

  if (!$ret) {
    return -1;
  }
  $row = DBfetch($ret);
  if (isset($row)) { 
    return $row['url_id'];
  }
  if (!$force) {
	return 0;
  }    
  $query1 =
'insert into urls(url)
values (' . DBstring($canonical) . ')';
  $ret = DBquery($query1);
  if (!$ret) {
    return -1;
  }
  $id = DBid();
  if ($id > 0) {
    return $id;
  }

  $ret = DBquery($query);

  if (!$ret) {
    return -1;
  }
  $row = DBfetch($ret);
  if (isset($row)) { 
    return $row['url_id'];
  }
  echo '
<p>Can\'t obtain url_id for ', htmlspecialchars($url),'
<p>Canonical url = ', htmlspecialchars($canonical);
  return -1;
}

function build_image_data($annotation_id, $version1, $archive, $empty, $html0, $urls, $htmls)
{
  if (!$empty) {
	$modified   = null;
	if ($archive == 'Y') {
	  $query =
'select modified, archived
  from annotations_history
 where annotation_id = ' . DBnumber($annotation_id) . '
   and version       = ' . DBnumber($version1);
      $ret = DBquery($query);
      if (!$ret) {
		return false;
	  }
	  $row = DBfetch($ret);
	  if ($row) {
		$modified   = $row['modified'];
		$archived   = $row['archived'];
	} }
	
	if (isset($archived)) {
	  // Find latest markup existing at time of this version
	  $query =
'select markup_id, version, tab, image_url, url as html_url, citation_id, natural_width, natural_height, title, description, modified, archived, history
  from (select markup_id, version, tab, url as image_url, html_url_id, citation_id, natural_width, natural_height, title, description, modified, archived, history
          from annotationsofurls_all a0 left join urls u1
            on a0.image_url_id = u1.url_id
         where a0.annotation_id = ' . DBnumber($annotation_id) . '
           and a0.version       =
              (select max(version)
                 from annotationsofurls_all a2
                where a2.markup_id = a0.markup_id
                  and (a2.archived is null or
                       a2.archived >= ' . DBstring($modified) . ')
                  and a2.modified <= ' . DBstring($archived) . '
                group by markup_id
              )
        ) a1 left join urls u2
    on html_url_id = u2.url_id
 order by tab';
	} else {
      $query =
'select markup_id, version, tab, image_url, url as html_url, citation_id, natural_width, natural_height, title, description, modified, 0 history
  from (select markup_id, version, tab, url as image_url, html_url_id, citation_id, natural_width, natural_height, title, description, modified
          from annotationsofurls a0 left join urls u1
            on a0.image_url_id = u1.url_id
         where a0.annotation_id = ' . DBnumber($annotation_id) . '
        ) a1 left join urls u2
    on html_url_id = u2.url_id
 order by tab';
	}

	$ret = DBquery($query);
	if (!$ret) {
      return false;
  }	}

  enterJavascript();
  $id = 0;
  echo '
top.image_data = 
{ '; 
  if (isset($annotation_id)) {
	echo 'annotation_id:', $annotation_id, ',
  ';
  }
  if (isset($version1)) {
    echo 'version:', $version1, ',
  ';
  }
  echo 'archive:', (($archive == 'Y') ? 'true' : 'false'), ',
  images:
  [';
  $connector = '';
  if (isset($html0)) {
    echo '
    { id:',$id, ',
      html_url:',json_encode($html0), ',
      markups:[]
    }';
    $connector = ',
';
    $id = 1;
  }
  if (!$empty) {
    for (; $row = DBfetch($ret); $connector = ',
') {
      $isImage   = false;
	  $markup_id = $row['markup_id'];
	  $version   = $row['version'];
      $history   = ($row['history'] != 0);
      $update    = !$history;
      echo $connector, '
    { id:', $id, ',
      markup_id:', $markup_id, ',
      version:', $version;
	  ++$id;
      if ($history) {
		echo ',
      history:true';
	  } else {
		$value = $row['tab'];
		echo ',
      maxversion:', $version;
		if ($update) {
		  echo ',
      orig_tab:', $value;
		}
	  }
      if (isset($row['image_url'])) {
        $isImage = true;
		$value = json_encode($row['image_url']);
		echo ',
      image_url:',$value;
		if ($update) {
		  echo ',
      orig_image_url:',$value;
      } }
      if (isset($row['html_url'])) {
		$value = json_encode($row['html_url']);
		echo ',
      html_url:',$value;
		if ($update) {
		  echo ',
      orig_html_url:',$value;
      } }
      if (isset($row['citation_id'])) {
		$value = $row['citation_id'];
		echo ',
      citation_id:',$value;
		if ($update) {
		  echo ',
      orig_citation_id:',$value;
      } }
	  if (isset($row['natural_width'])) {
		$value = $row['natural_width'];
		echo ',
      naturalWidth:',$value;
	  	if ($update) {
		  echo ',
      orig_naturalWidth:', $value;
	  } }
	  if (isset($row['natural_height'])) {
		$value = $row['natural_height'];
	    echo ',
      naturalHeight:',$value;
		if ($update) {
		  echo ',
      orig_naturalHeight:', $value;
	  } }
      if (isset($row['title'])) {
		$value = json_encode($row['title']);
		echo ',
      title:', $value;
		if ($update) {
		  echo ',
      orig_title:', $value;
	  } }
      if (isset($row['description'])) {
		$value = json_encode($row['description']);
		echo ',
      description:', $value;
		if ($update) {
		  echo ',
      orig_description:', $value;
	  } }
	  if (isset($row['modified'])) {
		echo ',
      modified: ', json_encode($row['modified']);
	  }
	  if (isset($row['archived'])) {
		echo ',
      archived: ', json_encode($row['archived']);
      }
      if ($isImage) {
	    if ($history) {
		  $query = 
'select layer, title, description, svg
  from markuplayers_history
 where markup_id = ' . DBnumber($markup_id) . '
   and version   = ' . DBnumber($version) . '
 order by layer';
	    } else {
	  	  $query = 
'select layer, title, description, svg
  from markuplayers
 where markup_id = ' . DBnumber($markup_id) . '
 order by layer';
	    }
	    $ret1 = DBquery($query);
	    if (!$ret1) {
	  	  return false;
	    }
        echo ',
      markups:
      [';
	    for ($connector1 = ''; $row = DBfetch($ret1); $connector1 = ',
       ') {
		  $value = $row['layer'];
		  echo $connector1, '{ layer:', $value;
		  if ($update) {
		    echo ',
         orig_layer:', $value;
		  }
		  if (isset($row['title'])) {
		    $value = json_encode($row['title']);
		    echo ',
         title:', $value;
		    if ($update) {
		  	  echo ',
         orig_title:', $value;
	      } }
          if (isset($row['description'])) {
		    $value = json_encode($row['description']);
		    echo ',
         description:', $value;
		    if ($update) {
			  echo ',
         orig_description:', $value;
	      } }
		  if (isset($row['svg'])) {
		    $value = json_encode($row['svg']);
		    echo ',
         svg:', $value;
		    if ($update) {
			  echo ',
         orig_svg:', $value;
	      } }
		  echo '
	   }';
	    }
	    echo '
      ]';
	  }
      echo '
    }';
	}
  }
  if (isset($urls)) {
	foreach ($urls as $index => $image_url) {
      if (isset($htmls[$index])) {
		$html_url = $htmls[$index];
	  } else {
		$html_url = null;
	  }
	  echo $connector, '
    { id:', $id;
	  ++$id;
	  if (isset($image_url)) {
		echo ',
      image_url:',json_encode($value);
	  }
	  if (isset($html_url)) {
		echo ',
      html_url:',json_encode($html_url);
	  }
	  echo '
    }';
	  $connector = ',
';
  } }
  echo '
  ]
};';
  exitJavascript();
  return true;
}

function emit_zoom()
{
?>
<span id="zoom_panel" class="toolset">
<label>
<span id="zoomLabel" class="zoom_tool icon_label">
<img src="../images/annotate/zoom.png" />
</span>
<input id="inputZoom" class="spin-button" size="3" value="" type="text" autocomplete="off" />
</label>
<span id="zoom_dropdown" class="dropdown dropup">
<button>
<svg class="svg_icon" viewBox="0 0 24 24"
     xmlns="http://www.w3.org/2000/svg"
     xmlns:xlink="http://www.w3.org/1999/xlink"
     width="7" height="7" >
<svg viewBox="0 0 50 40">
<path transform="rotate(90, 26, 13)" d="m14,-12l0,50l25,-25l-25,-25z" fill="#000000" stroke="#000000" />
</svg>
</svg>
</button>
<ul class="hideZoomList">
<li>1000%</li>
<li>400%</li>
<li>200%</li>
<li id="fitToFrame"  title="frame">Fit to frame</li>
<li id="fitToMarkup" title="markup">Fit to markup</li>
<li id="fullMarkup"  title="fullMarkup">Full Screen Markup</li>
<li id="fullImage"   title="fullImage">Full Screen Image</li>
</ul>
</span>
</span>
<?php
}
?>
