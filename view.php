<?php

// include class.secure.php to protect this file and the whole CMS!
if (defined('WB_PATH')) {	
	include(WB_PATH.'/framework/class.secure.php'); 
} elseif (file_exists($_SERVER['DOCUMENT_ROOT'].'/framework/class.secure.php')) {
	include($_SERVER['DOCUMENT_ROOT'].'/framework/class.secure.php'); 
} else {
	$subs = explode('/', dirname($_SERVER['SCRIPT_NAME']));	$dir = $_SERVER['DOCUMENT_ROOT'];
	$inc = false;
	foreach ($subs as $sub) {
		if (empty($sub)) continue; $dir .= '/'.$sub;
		if (file_exists($dir.'/framework/class.secure.php')) { 
			include($dir.'/framework/class.secure.php'); $inc = true;	break; 
		} 
	}
	if (!$inc) trigger_error(sprintf("[ <b>%s</b> ] Can't include class.secure.php!", $_SERVER['SCRIPT_NAME']), E_USER_ERROR);
}
// end include class.secure.php


// check if module language file exists for the language set by the user (e.g. DE, EN)
if(!file_exists(WB_PATH .'/modules/yaig/languages/'.LANGUAGE .'.php')) {
	// no module language file exists for the language set by the user, include default module language file EN.php
	require_once(WB_PATH .'/modules/yaig/languages/EN.php');
} else {
	// a module language file exists for the language defined by the user, load it
	require_once(WB_PATH .'/modules/yaig/languages/'.LANGUAGE .'.php');
}

// check if frontend.css file needs to be included into the <body></body> of view.php
if((!function_exists('register_frontend_modfiles') || !defined('MOD_FRONTEND_CSS_REGISTERED')) &&  file_exists(WB_PATH .'/modules/yaig/frontend.css')) {
   echo '<style type="text/css">';
   include(WB_PATH .'/modules/yaig/frontend.css');
   echo "\n</style>\n";
} 

// Get settings
$query_settings = $database->query("SELECT
	*
	FROM `".TABLE_PREFIX."mod_yaig_settings` WHERE `section_id` = '$section_id'");

$settings = $query_settings->fetchRow();

$delim = DIRECTORY_SEPARATOR;

$heading = $settings['heading'];
$show_heading = ($settings['show_heading'] == 1);
$picdir = $settings['picdir'];
$subdirs = ($settings['subdirs'] == 1);
$thumbdir = $settings['thumbdir'];
$maxpics = $settings['maxpics'];
$thumbsize = $settings['thumbsize']; // longest side (either width or height) of thumbnail
$limitheight = ($settings['appearance'] == 1); // thumbs have same height, but differ in width
$limitwidth = ($settings['appearance'] == 2); // thumbs have same width, but differ in height
$squarethumb = ($settings['appearance'] == 3); // thumbs are square-shaped
$thumb4to3 = ($settings['appearance'] == 4);
$thumb16to9 = ($settings['appearance'] == 5);
$nolink = ($settings['nolink'] == 1);
$random_order = ($settings['random_order'] == 1);
$show_filenames = ($settings['show_filenames'] == 1);
$lightbox_effect = $settings['lightbox_effect'];
$html5 = ($settings['html5'] == 1);

$words = $MOD_IMAGEGALLERY['words'];


if (function_exists('ini_set')) {
	@ini_set('memory_limit', -1);
	ini_set( 'arg_separator.output', '&amp;');
}

$jpg = '\.jpg$|\.jpeg$';
$gif = '\.gif$';
$png = '\.png$';
$txt = '\.txt$';
$fontsize = 2;

// This function looks whether YAIG is installed on WB or Lepton.
// Both use different section ID prefixes ('wb_' and 'lep_'), so
// YAIG needs to know which fragment identifier to use.
function fragmentID () {
global $database;
	$sql = "SELECT * FROM ".TABLE_PREFIX."settings";
	$db = $database->query ($sql);
	
	if(!$db) echo $database->get_error();
 	
  	if ($db->numRows() > 0) {
  		while ($ret = $db->fetchRow()) {
  			//print_r ($ret);
			if ($ret['name'] == 'lepton_version') return '#lep_';
			if ($ret['name'] == 'wb_version') return '#wb_';
		}
	}
}

// make sure function is only called once to keep DB queries low
if (!isset ($secIDprefix)) $secIDprefix = fragmentID ();

if(!function_exists('html')) {
	function html ($param) {
		return htmlentities($param, ENT_COMPAT, DEFAULT_CHARSET);
	}
}

if(!function_exists('word')) {
	function word ($param, $words) {
		return $words[$param];
	}
}

if(!function_exists('error')) {
	function error ($param, $arg, $words) {
	if ($arg) {
		return '<p class="error">Error: '.html(str_replace('%1', $arg, word($param, $words))).'</p>';
	} else {
		return '<p class="error">Error: '.html(word($param, $words)).'</p>';
	}
	}
}
if(!function_exists('isEmptyDir')) {
	function isEmptyDir($dir) {
		return (($files = @scandir($dir)) && count($files) <= 2);
	}
}

if(!function_exists('html5modal')) {
	function html5modal($id, $image, $title) {
		$output = '<aside id="'.$id.'" class="modal">'."\n";
		$output .= '<div>'."\n";
		if ($title != "") { $output .= '<h2>'.$title.'</h2>'."\n"; }
		$output .= '<img src="'.$image.'" alt="'.$title.'" title="'.$title.'" />'."\n";
		$output .= '<a href="#close" title="Close"></a>'."\n";
		$output .= '</div>'."\n";
		$output .= '</aside>'."\n";
		
		return $output;
	}
}

echo "\n".'<!-- start image gallery -->'."\n";

// echo droptlet code, so the user doesn't need to do this
if ($lightbox_effect != "") {
	echo "[[LibInclude?lib=lib_jquery&preset=yaig_".$lightbox_effect."]]";
}

if (array_key_exists('dir'.$section_id, $_GET) && $subdirs) {
	$dir = str_replace('../', '', $_GET['dir'.$section_id]);
} else {
	$dir = '';
}

$dirname = WB_PATH.MEDIA_DIRECTORY.$picdir.str_replace (WB_PATH.MEDIA_DIRECTORY.$picdir, '', $dir);
$dirnamehttp = WB_URL.MEDIA_DIRECTORY.$picdir.str_replace (WB_PATH.MEDIA_DIRECTORY.$picdir, '', $dir);
$realdir = $dirname;
$relpath = str_replace (array ('http://www.', 'http://', 'https://', $_SERVER['HTTP_HOST']), '', $dirnamehttp);


if (substr($dirnamehttp, 0, 2) == './') {
	$dirnamehttp = substr($dirnamehttp, 2);
}

if (empty($dirnamehttp)) {
	$dirnamehttp = '.';
}

if (($d = @opendir($realdir)) === false) {
	echo error('opendir_error', $realdir, $words);
	exit;
}

if ($show_heading) {
	echo '<h1>'.$heading.'</h1>'."\n";
}

$dirs = $pics = $pic_titles = array();

$query = $jpg;
$query .= '|'.$gif;
$query .= '|'.$png;


// Read the picture directory:
while (($filename = readdir($d)) !== false) {
	if ($filename == $thumbdir || ($filename == '..' && $dirname == '') || ($filename != '..' && substr($filename, 0, 1) == '.')) {
		continue;
	}
	$file = $realdir . $delim . $filename;
	if (is_dir($file)) {
		$dirs[] = $filename;
	} elseif (eregi($query, $file)) { 
		$pics[] = $filename;
	} elseif (eregi($txt, $file) && (!$random_order)) {
		// read list with picture titles
		$fp = fopen ($file, "r");
		if ($fp) {
			while (!feof($fp)) {
				$pic_titles[] = html (fgets ($fp));
			}
		}
		fclose ($fp);
	}
}
closedir($d);
sort($dirs);
($random_order) ? shuffle($pics) : sort($pics);


// Check for Subdirectories and list them: 
if (sizeof($dirs) > 0 && $subdirs) {
	echo '<!-- start directories -->'."\n";
	echo '<ul class="yaig_directories">'."\n";
	foreach ($dirs as $filename) {
		
		$target = substr($dir.$delim.$filename, strlen(isset($root)));
		
		if ($delim == '\\') {
			$target = strtr($target, '\\', '/');
		}
		if ($target == '') {
			$url = ereg_replace('^([^?]+).*$', '\1', $_SERVER['REQUEST_URI']);
		} else {
			$url = '?dir'.$section_id.'='.urlencode($target);
		}
		$predir = str_replace (WB_PATH.MEDIA_DIRECTORY.$picdir, '', $dirname);
		$target2 = str_replace($dir, '', $target);
		if ($target2 == '/..' && trim($predir) == '') {
			// nothing to do :-/
		} else {
			if ($target2 == '/..') {
				$urlsearch = array('%2F..', '%2F', '+');
				$urlreplace = array('', '/', ' ');
				$url = str_replace($urlsearch, $urlreplace, $url);
				$urllist = explode('/', $url);
				$urlcount = count($urllist);
				$urlpre = $urllist[$urlcount-1];
				$url = str_replace('/'.$urlpre, '', $url);
			}
			echo '<li><a href="'.html($url).'">'.html($filename).'</a></li>'."\n";
		}
	}
	echo '</ul>'."\n";
	echo '<!-- end directories -->'."\n";
}

if (($num = sizeof($pics)) > 0) {
	
	// display all preview images...
	$showall = (array_key_exists('showall'.$section_id, $_GET));
	if ($showall) {
		$maxpics = $num;
		$showalltrigger = 'offset'.$section_id.'=0';
		$showalltext = $MOD_IMAGEGALLERY['NORMAL_VIEW'];
	} else {
		$showalltrigger = 'showall'.$section_id;
		$showalltext = $MOD_IMAGEGALLERY['SHOWALL'];
	}
	// ... or just the offset?
	if (array_key_exists('offset'.$section_id, $_GET)) {
		$offset = $_GET['offset'.$section_id];
	} else { 
		$offset = 0;
	}
	if ($num >= $maxpics) {
		
		//generate pagenumbers
		echo '<!-- start pagenumbers -->'."\n";
		echo '<ul class="yaig_pagenumbers">'."\n";
		for ($i = 0; $i < $num; $i += $maxpics) {
			$e = $i + $maxpics - 1;
			if ($e > $num - 1) {
				$e = $num - 1;
			}
			if ($i != $e) {
				$b = ($i + 1).'-'.($e + 1);
			} else {
				$b = $i + 1;
			}
			if ($i == $offset) {
				echo '<li><span>'.$b.'</span></li>';
			} else {
				$predir = str_replace (WB_PATH.MEDIA_DIRECTORY.$picdir, '', $dirname);
				$url = ($predir  == '') ? '?' : '?dir'.$section_id.'='.urlencode($predir).'&amp;';
				echo '<li><a href="'.$url.'offset'.$section_id.'='.$i.$secIDprefix.$section_id.'">'.$b.'</a></li>';
			}
			echo "\n" ;
		}
		//display showall to show all thumbs of this gallery and get a better slideshow experience
		$predir = str_replace (WB_PATH.MEDIA_DIRECTORY.$picdir, '', $dirname);
		$url = ($predir  == '') ? '?' : '?dir'.$section_id.'=' . urlencode($predir).'&amp;';
		echo '<li><a href="'.$url.$showalltrigger.$secIDprefix.$section_id.'">'.$showalltext.'</a></li>';
			
		echo '</ul>'."\n";
		echo '<!-- end pagenumbers -->'."\n";
	}	
	
	echo '<!-- start preview images -->'."\n";
	
	// make shure thumbdir exists
	if (!is_dir($realdir . $delim . $thumbdir)) {
		$u = umask(0);
		if (!@mkdir($realdir . $delim . $thumbdir, 0777)) {
			echo error('mkdir_error', '', $words);
			break;
		}
		umask($u);
	}
	
	// get size of thumbnails if they exist
	// and trigger re-creation if size has changed
	$thumb = $realdir . $delim . $thumbdir . $delim . 'thumb_' . $pics[rand(0, $num-1)];
	if (is_file($thumb)) {
		list($width, $height, $type, $attr) = getimagesize($thumb);
		// It may happen that by chance you enter a new thumbsize which matches
		// either width or height in which case the thumbnails won't be changed.
		// Try to reload the web page, as on each reload another random thumbnail
		// is loaded an measured.
		// If this doesn't help, first change thumbnail size to an unusually small
		// value, reload the page (important!), then go back to the size you wish.
		$thumbsize_changed = (($width != $thumbsize) && ($height != $thumbsize));
	}
	
	
	//generate preview images
	if ((isEmptyDir($realdir . $delim . $thumbdir)) OR ($thumbsize_changed)) {
		
		for ($i = $offset; $i <= $num; $i++) {
			if ($i >= $num) {
			break;
		}
		
		$filename = $pics[$i];
		$file = $realdir . $delim . $filename;
		
		$thumb = $realdir . $delim . $thumbdir . $delim . 'thumb_' . $filename;
		
		if (eregi($jpg, $file)) {
			$original = @imagecreatefromjpeg($file);
		} elseif (eregi($gif, $file)) {
			$original = @imagecreatefromgif($file);
		} elseif (eregi($png, $file)) {
			$original = @imagecreatefrompng($file);
		} else {
			continue;
		}
		
		if ($original) {
			list($width, $height, $type, $attr) = getimagesize($file);
			
			$ofx = 0;
			$ofy = 0;
			$imagecropped = false;
			
			if ($limitwidth) {
				$smallwidth = $thumbsize;
				$smallheight = floor($height / $width * $thumbsize);
			}
			elseif ($limitheight) {
				$smallheight = $thumbsize;
				$smallwidth = floor($width / $height * $thumbsize);
			}
			elseif ($squarethumb) {
				if ($width >= $height && $width > $thumbsize) {
					$ofx = floor(($width - $height) / 2);
					$width = $height;
				}
				elseif ($width <= $height && $height > $thumbsize) {
					$ofy = floor(($height - $width) / 2);
					$height = $width;
				}
				$smallwidth = $thumbsize;
				$smallheight = $thumbsize;
				$imagecropped = true;
			}
			elseif (($thumb4to3) || ($thumb16to9)) {
				($thumb4to3) ? $ratio = 1.33 : $ratio = 1.78;
				$imageratio = $width / $height;
				$imageratio = round($imageratio, 2);
				if ($imageratio < $ratio) {
					$newheight = floor($width / $ratio);
					$ofy = floor(($height - $newheight) / 2);
					$height = $newheight;
				} elseif ($imageratio > $ratio) {
					$newwidth = floor($height * $ratio);
					$ofx = floor(($width - $newwidth) / 2);
					$width = $newwidth;
				}
				$smallwidth = $thumbsize;
				$smallheight = $thumbsize / $ratio;
				$imagecropped = true;
			} else {
				if ($width >= $height && $width > $thumbsize) {
					$smallwidth = $thumbsize;
					$smallheight = floor($height / $width * $thumbsize);
				}
				elseif ($width <= $height && $height > $thumbsize) {
					$smallheight = $thumbsize;
					$smallwidth = floor($width / $height * $thumbsize);
				} else {
					$smallheight = $height;
					$smallwidth = $width;
				}
			}
		
			if ($imagecropped) {
				$crop = ImageCreateTrueColor($width, $height); 
				imagecopy($crop, $original, 0, 0, $ofx, $ofy, $width, $height);
				$small = imagecreatetruecolor($smallwidth, $smallheight);
				imagecopyresampled($small, $crop, 0, 0, 0, 0, $smallwidth, $smallheight, $width, $height);
			} else {
				$small = imagecreatetruecolor($smallwidth, $smallheight);
				imagecopyresampled($small, $original, 0, 0, 0, 0, $smallwidth, $smallheight, $width, $height);
			}
		} else {
			// something went wrong with the original image
			// frankly speaking, I don't really understand this stuff
			$small = imagecreate($thumbsize, $thumbsize);
			$black = imagecolorallocate($small, 0, 0, 0);
			$fw = imagefontwidth($fontsize);
			$fh = imagefontheight($fontsize);
			$htw = ($fw * strlen($filename)) / 2;
			$hts = $thumbsize / 2;
			imagestring($small, $fontsize, $hts - $htw, $hts - ($fh / 2), $filename, $black);
			imagerectangle($small, $hts - $htw - $fw - 1, $hts - $fh, $hts + $htw + $fw - 1, $hts + $fh, $black);
		}
		imagejpeg($small, $thumb);
		
		// save memory... probably?!
		if ($original) { imagedestroy($original); }
		if ($imagecropped) { imagedestroy($crop); }
		imagedestroy($small);
		}
	}
	
	// set CSS classes
	$ul_classes = "yaig_picturelist";
	if ($limitwidth) { $ul_classes .= " same_width"; }
	if ($limitheight) { $ul_classes .= " same_height"; }
	if ($squarethumb) { $ul_classes .= " squarethumb"; }
	if ($thumb4to3) { $ul_classes .= " thumb4to3"; }
	if ($thumb16to9) { $ul_classes .= " thumb16to9"; }
	if ($nolink) { $ul_classes .= " nolink"; }
	
	// start list of preview images
	echo '<ul class="'.$ul_classes.'">'."\n";
	for ($i = $offset; $i < $offset + $maxpics; $i++) {
		if ($i >= $num) {
			break;
		}
		$filename = $pics[$i];
		if (isset($pic_titles[$i])) { $title_attr = $pic_titles[$i]; } else { $title_attr = ''; }
		$file = $realdir . $delim . $filename;
		if (!is_readable($file)) {
			echo error('readfile_error', $file, $words);
			continue;
		}
		
		if ($squarethumb) {
			$width = $thumbsize;
			$height = $thumbsize;
		} else {
			list($width, $height, $type, $attr) = getimagesize($realdir . $delim . $thumbdir . $delim. 'thumb_' . $filename);
		}
		
		if ($html5) { echo '<li>'; } else { echo '<li class="yaig_thumb">'; }
		if (!$nolink) {
			if ($html5) {
				echo '<a href="#image_'.$section_id.'_'.$i.'">';
			} else {
				($lightbox_effect == 'pirobox') ? $anchor_class = ' class="'.$lightbox_effect.'_'.$section_id.'"' : $anchor_class = '';
				$relation = 'rel="'.$lightbox_effect.'['.$section_id.']"';
				echo '<a href="'.html($relpath.'/'.$filename).'" '.$relation.' title="'.$title_attr.'"'.$anchor_class.'>';
			}
		}
		echo '<img src="'.html($relpath.'/'.$thumbdir.'/'.'thumb_'.$filename).'" alt="'.html($filename).'" title="'.$title_attr.'" width="'.$width.'" height="'.$height.'" />';
		if (!$nolink) {
			echo '</a>'."\n";
		}
		if ($show_filenames) { echo '<span>'.html($filename).'</span>'; }
		if ($html5) { echo html5modal('image_'.$section_id.'_'.$i, html($relpath.'/'.$filename), $title_attr); }
		echo '</li>'."\n";
	}
	
	echo '</ul>'."\n";
	echo '<!-- end preview images -->'."\n";
}

echo '<!-- end image gallery -->'."\n";

