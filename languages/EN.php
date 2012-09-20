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

//Modul Description
//$module_description = '';

//Variablen für Frontend Texte
$MOD_IMAGEGALLERY['SHOWALL'] = 'Show All';
$MOD_IMAGEGALLERY['NORMAL_VIEW'] = 'Normal View';

//Variables for the Backend
$MOD_IMAGEGALLERY['MAIN_SETTINGS'] = 'Imagegallery Settings';

$MOD_IMAGEGALLERY['TITLE'] = 'Gallery Name';
$MOD_IMAGEGALLERY['HEADING'] = 'Input Heading';
$MOD_IMAGEGALLERY['SHOW_HEADING'] = 'Show Heading';

$MOD_IMAGEGALLERY['ORIGINAL_PICS'] = 'Images';
$MOD_IMAGEGALLERY['PICDIR'] = 'Image Source';
$MOD_IMAGEGALLERY['INCLUDE_SUBDIRS'] = 'Include Subdirectories';

$MOD_IMAGEGALLERY['THUMBS'] = 'Thumbnails';
$MOD_IMAGEGALLERY['THUMBDIR'] = 'Choose Name for Thumbdir';
$MOD_IMAGEGALLERY['MAXPICS'] = 'Number of Thumbs per page';
$MOD_IMAGEGALLERY['THUMBSIZE'] = 'Thumbnail Size';
$MOD_IMAGEGALLERY['KEEPRATIO'] = 'Thumb is like Original';
$MOD_IMAGEGALLERY['LIMITHEIGHT'] = 'Same Height for Thumbs';
$MOD_IMAGEGALLERY['LIMITWIDTH'] = 'Same Width for Thumbs';
$MOD_IMAGEGALLERY['SQUARETHUMB'] = 'Square-shaped Thumbs';
$MOD_IMAGEGALLERY['THUMB4TO3'] = 'Thumbnail Ratio 4:3';
$MOD_IMAGEGALLERY['THUMB16TO9'] = 'Thumbnail Ratio 16:9';
$MOD_IMAGEGALLERY['NOLINK'] = 'Just Thumbs (w/o link to original image)';
$MOD_IMAGEGALLERY['RANDOM_ORDER'] = 'Random Order';
$MOD_IMAGEGALLERY['SHOW_FILE_NAMES'] = 'Show Filenames';

$MOD_IMAGEGALLERY['GALLERY'] = 'Gallery';
$MOD_IMAGEGALLERY['LIGHTBOX_EFFECT'] = 'Choose LightBox effect (LibraryAdmin and jQuery Initial Library required!)';
$MOD_IMAGEGALLERY['SHOWAS_HTML5'] = 'Display Images as HTML5 Modal (no JS)'; 

//Variables for Error messages
$MOD_IMAGEGALLERY['words']['error'] = 'Error';
$MOD_IMAGEGALLERY['words']['php_error'] = 'PHP >= 4.1 is required.';
$MOD_IMAGEGALLERY['words']['gd_error'] = 'GD Library is required. See http://www.boutell.com/gd/.';
$MOD_IMAGEGALLERY['words']['jpg_error'] = 'JPEG software is required. See ftp://ftp.uu.net/graphics/jpeg/.';
$MOD_IMAGEGALLERY['words']['mkdir_error'] = 'Write permission is required in this folder.';
$MOD_IMAGEGALLERY['words']['opendir_error'] = 'The directory "%1" can not be read.';

?>