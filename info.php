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
 
$module_directory = 'yaig';
$module_name      = 'Yet Another Image Gallery';
$module_function  = 'page';
$module_version   = '0.8.7';
$module_platform  = '1.x';
$module_author    = 'Daniel Wacker, Matthias Gallas, Rob Smith, Manfred Fuenkner, Frank Berke';
$module_license   = 'GNU General Public License';
$module_license_terms  = '-';
$module_guid      = '120f5dae-0d0d-455c-85f8-c910a45c73e2';
$module_description = 'This module lets you create image galleries either using ColorBox for neat display (separate module plus jQuery required!) or on an HTML5 basis.';
$module_home      = 'https://github.com/fberke/YAIG';

/**
Changelog:
Version 0.8.7
	+ added function to check whether YAIG is installed in Lepton or WB to
	  ensure same functionality in both CMS
	- removed header block from each file because it's of no use
Version 0.8.6
	+ checks if 'Library Admin' and 'jQuery Initial Library' are installed
	  and copies *.jquery presets to /modules/lib_jquery/presets
	+ upgrade.php does the same as described above, but may overwrite
	  existing presets
	+ in backend, you can choose the preset (e.g. ColorBox, FancyBox, etc.),
	  and an adjusted Droplet Code is placed in the HTML output automatically
	  N.B.: this function requires the plugins to be installed!
	- removed bugs that prevented users from having more than one YAIG section
	  per page
	- removed other little bugs
Version 0.8.5
	+ added extra html classes to thumbnail <ul> to apply different
	  styles if needed (otherwise these classes don't hurt)
	  There are already dummy entries in the frontend.css file
	+ made pagination more accessible by using an unsorted list
	+ if in Showall mode it is now possible to revert to normal gallery view
	- fixed bug regarding HTTP parameter creation
	- removed some more unusued code in view.php
	- removed search.php as it isn't used
Version 0.8.4
	+ added preset for use with LibraryAdmin/LibjQuery and ColorBox Plugin
	  copy that preset to your /modules/lib_jquery/presets/ directory and add
	  the appropriate droplet code to every page where YAIG is used
	  the droplet most likely looks like:
	  [[LibInclude?lib=lib_jquery&preset=yaig-colorbox]]
	+ added backend option 'nolink' which displays just thumbs
	+ added backend option 'random_order': displays images in a random order;
	  if unchecked, they are sorted by filename
Version 0.8.3
	- removed language files for NL and SE as I'm unable to maintain them
	+ several files changed to meet Lepton's module standards
Version 0.8.2
	+ added HTML5 modals to display images with an overlay that does not
	  require JavaScript
	+ automatically re-create thumbs when thumbnail size has changed
	  (works in most cases - for further information see view.php)
Version 0.8.1
	+ added several options to display thumbnails
	: Same as original (longest side equals thumbnail size)
	: Same height
	: Same width
	: Square-sized thumbs (may display just a part of original image)
	: 4:3 ratio (may display just a part of original image)
	: 16:9 ratio (may display just a part of original image)
	+ image titles can be added through a separate file
	  upload a simple text file (*.txt) to you image directory (not thumbs!)
	  which contains a title text every line - YAIG sorts your images by
	  filename so be aware to keep the correct order!
	  N.B. when using 'random_order' (see 0.8.4) titles file is not being loaded
	+ use 'imagedestroy' to keep memory usage low (maybe useful on shared hosts)
	+ properly set width + height of thumbs in HTML to speed up page display
	+ use relative paths to keep html output as small as possible
	+ optimized CSS output for instant visual satisfaction ;-)
	+ optimized HTML output - HTML (almost) validates and is sematically meaningful
	+ optimized and rectified php code
	- removed unnecessary code in view.php
	- removed function for 'inline' display

Credits:
HTML5 modal is based on the work of Paul Hayes - http://www.paulrhayes.com
This module is based on 'Another Image Gallery', an Image Gallery module for Website Baker
This module is based on 'galerie.php' by Daniel Wacker - http://cker.name/galerie/
*/

?>