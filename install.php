<?php

/**
 *  @module         yaig
 *  @version        see info.php of this module
 *  @author         Daniel Wacker, Matthias Gallas, Rob Smith, Manfred Fuenkner, Frank Berke
 *  @copyright      2004-2011, Ryan Djurovich, Daniel Wacker, Matthias Gallas, Rob Smith, Manfred Fuenkner, Frank Berke 
 *  @license        GNU General Public License
 *  @license terms  see info.php of this module
 *  @platform       see info.php of this module
 *  @requirements   PHP 5.1.x and higher
 */


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

require_once(WB_PATH.'/framework/functions.php');

// see if LibraryAdmin is listed in 'addons' table
$la_installed = ($query_addons = $database->query("SELECT `version` FROM `".TABLE_PREFIX."addons` WHERE `directory` = 'libraryadmin'"));
if (!$la_installed) {
	echo "LibraryAdmin not detected.";
} else {
	$la_settings = $query_addons->fetchRow();
}

// see if jQuery Initial Library is listed in 'addons' table
if (!($query_addons = $database->query("SELECT `version` FROM `".TABLE_PREFIX."addons` WHERE `directory` = 'lib_jquery'"))) {
	echo "jQuery Initial Library not detected.";
} else {
	$lj_settings = $query_addons->fetchRow();

	if (($lj_settings['version'] >= 1.2) && $la_installed) {
		// copy *.jquery presets to /modules/lib_jquery/presets
		$src = WB_PATH.'/modules/yaig/presets';
		$dst = WB_PATH.'/modules/lib_jquery/presets';
		
		$dir = opendir($src);
		
		while(false !== ( $file = readdir($dir)) ) {
			if (( $file != '.' ) && ( $file != '..' ) && (!is_dir($src . '/' . $file))) {
				copy($src . '/' . $file, $dst . '/' . $file);
			}
		}
		closedir($dir);
	} else {
		echo "jQuery Initial Library too old and/or LibraryAdmin not installed!";
	}
}
	
// Create table
$database->query("DROP TABLE IF EXISTS `".TABLE_PREFIX."mod_yaig_settings`");
$mod_yaig = 'CREATE TABLE `'.TABLE_PREFIX.'mod_yaig_settings` ('
		. ' `section_id` INT NOT NULL DEFAULT \'0\','
		. ' `page_id` INT NOT NULL DEFAULT \'0\','
		. ' `heading` TEXT NOT NULL,'
		. ' `show_heading` TINYINT NOT NULL DEFAULT \'0\','
		. ' `picdir` VARCHAR(255) NOT NULL,'
		. ' `subdirs` TINYINT NOT NULL DEFAULT \'0\','
		. ' `thumbdir` VARCHAR(25) NOT NULL,'
		. ' `maxpics` SMALLINT NOT NULL DEFAULT \'9\','
		. ' `thumbsize` SMALLINT NOT NULL DEFAULT \'150\','
		. ' `appearance` TINYINT NOT NULL DEFAULT \'0\','
		. ' `nolink` TINYINT NOT NULL DEFAULT \'0\','
		. ' `random_order` TINYINT NOT NULL DEFAULT \'0\','
		. ' `show_filenames` TINYINT NOT NULL DEFAULT \'0\','
		. ' `lightbox_effect` VARCHAR(25) NOT NULL,'
		. ' `html5` TINYINT NOT NULL DEFAULT \'0\','
		. 'PRIMARY KEY (`section_id`)'
		. ')';
$database->query($mod_yaig);

?>