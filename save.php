<?php

require('../../config.php');

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


require(WB_PATH.'/modules/admin.php');


//Get settings
if (isset($_POST['heading'])) {
    $heading = $_POST['heading'];
} else {
    $heading = 'Gallery';
}

if (isset($_POST['show_heading'])) {
    $show_heading = $_POST['show_heading'];
} else {
    $show_heading = '0';
}

if (isset($_POST['picdir'])) {
    $picdir = addslashes($_POST['picdir']);
} else {
    $picdir = '';
}

if (isset($_POST['subdirs'])) {
    $subdirs = $_POST['subdirs'];
} else {
    $subdirs = '0';
}

if (isset($_POST['thumbdir'])) {
    $thumbdir = $_POST['thumbdir'];
} else {
    $thumbdir = 'thumbs';
}

if (isset($_POST['maxpics'])) {
    $maxpics = $_POST['maxpics'];
} else {
    $maxpics = '9';
}

if (isset($_POST['thumbsize'])) {
    $thumbsize = $_POST['thumbsize'];
} else {
    $thumbsize = '150';
}

if (isset($_POST['appearance'])) {
    $appearance = $_POST['appearance'];
} else {
    $appearance = '0';
}

if (isset($_POST['nolink'])) {
    $nolink = $_POST['nolink'];
} else {
    $nolink = '0';
}

if (isset($_POST['random_order'])) {
    $random_order = $_POST['random_order'];
} else {
    $random_order = '0';
}

if (isset($_POST['show_filenames'])) {
    $show_filenames = $_POST['show_filenames'];
} else {
    $show_filenames = '0';
}

if (isset($_POST['lightbox_effect'])) {
    $lightbox_effect = addslashes($_POST['lightbox_effect']);
} else {
    $lightbox_effect = '';
}

if (isset($_POST['html5'])) {
    $html5 = $_POST['html5'];
} else {
    $html5 = '0';
}


//Write to database	
$query = "UPDATE `".TABLE_PREFIX."mod_yaig_settings` SET "
		. " `heading` = '$heading',"			
		. " `show_heading` = '$show_heading',"
		. " `picdir` = '$picdir',"
		. " `subdirs` = '$subdirs',"
		. " `thumbdir` = '$thumbdir',"
		. " `maxpics` = '$maxpics',"
		. " `thumbsize` = '$thumbsize',"
		. " `appearance` = '$appearance',"
		. " `nolink` = '$nolink',"
		. " `random_order` = '$random_order',"
		. " `show_filenames` = '$show_filenames',"
		. " `lightbox_effect` = '$lightbox_effect',"
		. " `html5` = '$html5'";
$query .=  " WHERE `section_id` = '$section_id'";

$database->query($query);

// Check if there is a database error, otherwise say successful
if($database->is_error()) {
	$admin->print_error($database->get_error(), $js_back);
} else {
	$admin->print_success($MESSAGE['PAGES']['SAVED'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
}

// Print admin footer
$admin->print_footer();

?>