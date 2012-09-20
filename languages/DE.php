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

//Modulbeschreibung
//$module_description = '';

//Variablen für Frontend Texte
$MOD_IMAGEGALLERY['SHOWALL'] = 'Alle anzeigen';
$MOD_IMAGEGALLERY['NORMAL_VIEW'] = 'Normale Ansicht';

//Variablen für Backend Texte
$MOD_IMAGEGALLERY['MAIN_SETTINGS'] = 'Bildergalerie Einstellungen';

$MOD_IMAGEGALLERY['TITLE'] = 'Name der Galerie';
$MOD_IMAGEGALLERY['HEADING'] = 'Überschrift festlegen';
$MOD_IMAGEGALLERY['SHOW_HEADING'] = 'Überschrift anzeigen';


$MOD_IMAGEGALLERY['ORIGINAL_PICS'] = 'Originalbilder';
$MOD_IMAGEGALLERY['PICDIR'] = 'Media-Verzeichnis mit Originalbildern';
$MOD_IMAGEGALLERY['INCLUDE_SUBDIRS'] = 'Unterverzeichnisse einschließen';

$MOD_IMAGEGALLERY['THUMBS'] = 'Vorschaubilder';
$MOD_IMAGEGALLERY['THUMBDIR'] = 'Name für Vorschaubildverzeichnis';
$MOD_IMAGEGALLERY['MAXPICS'] = 'Anzahl der Vorschaubilder pro Seite';
$MOD_IMAGEGALLERY['THUMBSIZE'] = 'Größe der Vorschaubilder';
$MOD_IMAGEGALLERY['KEEPRATIO'] = 'Vorschau wie Original';
$MOD_IMAGEGALLERY['LIMITHEIGHT'] = 'Gleiche Höhe für Vorschaubilder';
$MOD_IMAGEGALLERY['LIMITWIDTH'] = 'Gleiche Breite für Vorschaubilder';
$MOD_IMAGEGALLERY['SQUARETHUMB'] = 'Quadratische Vorschaubilder';
$MOD_IMAGEGALLERY['THUMB4TO3'] = 'Vorschaubilder im Verhältnis 4:3';
$MOD_IMAGEGALLERY['THUMB16TO9'] = 'Vorschaubilder im Verhältnis 16:9';
$MOD_IMAGEGALLERY['NOLINK'] = 'Nur Thumbs (ohne Link aufs Original)';
$MOD_IMAGEGALLERY['RANDOM_ORDER'] = 'Zufällige Reihenfolge';
$MOD_IMAGEGALLERY['SHOW_FILE_NAMES'] = 'Dateinamen anzeigen';

$MOD_IMAGEGALLERY['GALLERY'] = 'Galerie';
$MOD_IMAGEGALLERY['LIGHTBOX_EFFECT'] = 'LightBox-Effekt auswählen (benötigt LibraryAdmin und jQuery Initial Library!)';
$MOD_IMAGEGALLERY['SHOWAS_HTML5'] = 'Bilder als HTML5 Modal anzeigen (kein JS)'; 

//Variablen für Fehlermeldungen
$MOD_IMAGEGALLERY['words']['error'] = 'Fehler';
$MOD_IMAGEGALLERY['words']['php_error'] = 'PHP Version >= 4.1 wid benötigt.';
$MOD_IMAGEGALLERY['words']['gd_error'] = 'GD Library wird benötigt. Siehe http://www.boutell.com/gd/.';
$MOD_IMAGEGALLERY['words']['jpg_error'] = 'JPEG-Software wird benötigt. Siehe ftp://ftp.uu.net/graphics/jpeg/.';
$MOD_IMAGEGALLERY['words']['mkdir_error'] = 'Für dieses Verzeichnis wird Schreibberechtigung benötigt.';
$MOD_IMAGEGALLERY['words']['opendir_error'] = 'Das Verzeichnis "%1" kann nicht gelesen werden.';
$MOD_IMAGEGALLERY['words']['readfile_error'] = 'Die Datei "%1" kann nicht gelesen werden.';

?>