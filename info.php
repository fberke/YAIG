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

?>