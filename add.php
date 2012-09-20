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

// Insert an extra row into the database
// Numeric default values are directly set in MySQL on module install
// If you want to alter them in this place remember to complete the database
// query as well!

$heading 	= 'Gallery';
//$show_heading = '0';
$picdir 	= '';
//$subdirs 	= '0';
$thumbdir 	= 'thumbs';
//$maxpics 	= '9';
//$thumbsize	= '150';
//$appearance	= '0';
//$nolink	= '0';
//$random_order = '0';
//$show_filenames = '0';
//$html5 	= '0';



$database->query("INSERT INTO `".TABLE_PREFIX."mod_yaig_settings` (
	`page_id`, 
	`section_id`,
	`heading`,
	`picdir`,
	`thumbdir`
	
	) VALUES (
	
	'$page_id',
	'$section_id',
	'$heading',
	'$picdir',
	'$thumbdir')");

?>