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

// include core functions of WB 2.7 to edit the optional module CSS files (frontend.css, backend.css)
@include_once(WB_PATH .'/framework/module.functions.php');

// check if module language file exists for the language set by the user (e.g. DE, EN)
if(!file_exists(WB_PATH .'/modules/yaig/languages/'.LANGUAGE .'.php')) {
	// no module language file exists for the language set by the user, include default module language file EN.php
	require_once(WB_PATH .'/modules/yaig/languages/EN.php');
} else {
	// a module language file exists for the language defined by the user, load it
	require_once(WB_PATH .'/modules/yaig/languages/'.LANGUAGE .'.php');
}

// check if backend.css file needs to be included into the <body></body> of modify.php
if(!method_exists($admin, 'register_backend_modfiles') && file_exists(WB_PATH ."/modules/yaig/backend.css")) {
	echo '<style type="text/css">';
	include(WB_PATH .'/modules/yaig/backend.css');
	echo "\n</style>\n";
}

require_once(WB_PATH.'/framework/functions.php');

// Get settings
$query_settings = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_yaig_settings` WHERE `section_id` = '$section_id'");
$settings = $query_settings->fetchRow();

?>
<h2><?php echo $MOD_IMAGEGALLERY['MAIN_SETTINGS']; ?></h2>
<?php
// include the button to edit the optional module CSS files
// Note: CSS styles for the button are defined in backend.css (div class="mod_moduledirectory_edit_css")
// Place this call outside of any <form></form> construct!!!
if(function_exists('edit_module_css')) {
	edit_module_css('imagegallery');
}
?>
<div class="gallery_box">
<form name="modify" action="<?php echo WB_URL; ?>/modules/yaig/save.php" method="post" >
<input type="hidden" name="section_id" value="<?php echo $section_id; ?>" />
<input type="hidden" name="page_id" value="<?php echo $page_id; ?>" />

<fieldset class="gallery">
	<legend><?php echo $MOD_IMAGEGALLERY['TITLE']; ?></legend>
	
	<div class="gallery_setting_name">
		<label for="heading"><?php echo $MOD_IMAGEGALLERY['HEADING']; ?>:</label>
	</div>
	<div class="gallery_setting_value">
		<input type="text" name="heading" id="heading" value="<?php echo $settings['heading']; ?>" />
	</div>
	
	<div class="gallery_setting_name">
		<label for="show_heading"><?php echo $MOD_IMAGEGALLERY['SHOW_HEADING']; ?>:</label>
	</div>
	<div class="gallery_setting_value"><?php ($settings['show_heading'] == '1') ? $checked = 'checked' : $checked = ''; ?>
		<input type="checkbox" value="1" name="show_heading" id="show_heading" <?php echo $checked; ?> />
	</div>
</fieldset>

<fieldset class="gallery">
	<legend><?php echo $MOD_IMAGEGALLERY['ORIGINAL_PICS']; ?></legend>

	<div class="gallery_setting_name">
		<label for="picdir"><?php echo $MOD_IMAGEGALLERY['PICDIR']; ?>:</label>
	</div>
	<div class="gallery_setting_value">
		<select name="picdir" id="picdir">
		<option value="<?php echo $settings['picdir']; ?>" selected><?php echo $settings['picdir']; ?></option>
		<?php
		$folder_list = directory_list(WB_PATH.MEDIA_DIRECTORY);
		array_push($folder_list, WB_PATH.MEDIA_DIRECTORY);
		sort($folder_list);
		// What ist this good for? Doesn't make sense to me...
		//echo"<pre>";print_r($folder_list);echo"</pre>";
		foreach($folder_list AS $foldername) {
			$thumb_count = substr_count($foldername, '/thumbs');
			if ($thumb_count == 0 and trim($foldername) != "") {
				echo "<option value='".str_replace(WB_PATH.MEDIA_DIRECTORY, '', $foldername)."'>".
				str_replace(WB_PATH.MEDIA_DIRECTORY, '', $foldername).
				"</option>\n";
			}
			$thumb_count="";	
		}	
		?>
		</select>
	</div>

	<div class="gallery_setting_name">
		<label for="subdirs"><?php echo $MOD_IMAGEGALLERY['INCLUDE_SUBDIRS']; ?>:</label>
	</div>
	<div class="gallery_setting_value"><?php ($settings['subdirs'] == '1') ? $checked = 'checked' : $checked = ''; ?>
		<input type="checkbox" value="1" name="subdirs" id="subdirs" <?php echo $checked; ?> /></div>
</fieldset>
<fieldset class="gallery">
	<legend><?php echo $MOD_IMAGEGALLERY['THUMBS']; ?></legend>

	<div class="gallery_setting_name">
		<label for="thumbdir"><?php echo $MOD_IMAGEGALLERY['THUMBDIR']; ?>:</label>
	</div>
	<div class="gallery_setting_value">
		<input type="text" name="thumbdir" id="thumbdir" value="<?php echo $settings['thumbdir']; ?>" />
	</div>

	<div class="gallery_setting_name">
		<label for="maxpics"><?php echo $MOD_IMAGEGALLERY['MAXPICS']; ?>:</label>
	</div>
	<div class="gallery_setting_value">
		<input type="text" name="maxpics" id="maxpics" value="<?php echo $settings['maxpics']; ?>" />
	</div>

	<div class="gallery_setting_name">
		<label for="thumbsize"><?php echo $MOD_IMAGEGALLERY['THUMBSIZE']; ?>:</label>
	</div>
	<div class="gallery_setting_value">
		<input type="text" name="thumbsize" id="thumbsize" value="<?php echo $settings['thumbsize']; ?>" /> px
	</div>
	
	<div class="gallery_setting_name">
		<label for="keepratio"><?php echo $MOD_IMAGEGALLERY['KEEPRATIO']; ?>:</label>
	</div>
	<div class="gallery_setting_value"><?php ($settings['appearance'] == '0') ? $checked = 'checked' : $checked = ''; ?>
		<input type="radio" value="0" name="appearance" id="keepratio" <?php echo $checked; ?> />
	</div>

	<div class="gallery_setting_name">
		<label for="limitheight"><?php echo $MOD_IMAGEGALLERY['LIMITHEIGHT']; ?>:</label>
	</div>
	<div class="gallery_setting_value"><?php ($settings['appearance'] == '1') ? $checked = 'checked' : $checked = ''; ?>
		<input type="radio" value="1" name="appearance" id="limitheight" <?php echo $checked; ?> />
	</div>

	<div class="gallery_setting_name">
		<label for="limitwidth"><?php echo $MOD_IMAGEGALLERY['LIMITWIDTH']; ?>:</label>
	</div>
	<div class="gallery_setting_value"><?php ($settings['appearance'] == '2') ? $checked = 'checked' : $checked = ''; ?>
		<input type="radio" value="2" name="appearance" id="limitwidth" <?php echo $checked; ?> />
	</div>
	
	<div class="gallery_setting_name">
		<label for="squarethumb"><?php echo $MOD_IMAGEGALLERY['SQUARETHUMB']; ?>:</label>
	</div>
	<div class="gallery_setting_value"><?php ($settings['appearance'] == '3') ? $checked = 'checked' : $checked = ''; ?>
		<input type="radio" value="3" name="appearance" id="squarethumb" <?php echo $checked; ?> />
	</div>

	<div class="gallery_setting_name">
		<label for="thumb4to3"><?php echo $MOD_IMAGEGALLERY['THUMB4TO3']; ?>:</label>
	</div>
	<div class="gallery_setting_value"><?php ($settings['appearance'] == '4') ? $checked = 'checked' : $checked = ''; ?>
		<input type="radio" value="4" name="appearance" id="thumb4to3" <?php echo $checked; ?> />
	</div>

	<div class="gallery_setting_name">
		<label for="thumb16to9"><?php echo $MOD_IMAGEGALLERY['THUMB16TO9']; ?>:</label>
	</div>
	<div class="gallery_setting_value"><?php ($settings['appearance'] == '5') ? $checked = 'checked' : $checked = ''; ?>
		<input type="radio" value="5" name="appearance" id="thumb16to9" <?php echo $checked; ?> />
	</div>
		
	<div class="gallery_setting_name">
		<label for="nolink"><?php echo $MOD_IMAGEGALLERY['NOLINK']; ?>:</label>
	</div>
	<div class="gallery_setting_value"><?php ($settings['nolink'] == '1') ? $checked = 'checked' : $checked = '';?>
		<input type="checkbox" value="1" name="nolink" id="nolink" <?php echo $checked; ?> />
	</div>

	<div class="gallery_setting_name">
		<label for="random_order"><?php echo $MOD_IMAGEGALLERY['RANDOM_ORDER']; ?>:</label>
	</div>
	<div class="gallery_setting_value"><?php ($settings['random_order'] == '1') ? $checked = 'checked' : $checked = '';?>
		<input type="checkbox" value="1" name="random_order" id="random_order" <?php echo $checked; ?> />
	</div>
	
	<div class="gallery_setting_name">
		<label for="show_filenames"><?php echo $MOD_IMAGEGALLERY['SHOW_FILE_NAMES']; ?>:</label>
	</div>
	<div class="gallery_setting_value"><?php ($settings['show_filenames'] == '1') ? $checked = 'checked' : $checked = '';?>
		<input type="checkbox" value="1" name="show_filenames" id="show_filenames" <?php echo $checked; ?> />
	</div>

</fieldset>
<fieldset class="gallery">
	<legend><?php echo $MOD_IMAGEGALLERY['GALLERY']; ?></legend>
	
	<div class="gallery_setting_name">
		<label for="lightbox_effect"><?php echo $MOD_IMAGEGALLERY['LIGHTBOX_EFFECT']; ?>:</label>
	</div>
	<div class="gallery_setting_value">
		<select name="lightbox_effect" id="lightbox_effect">
		<option value="<?php echo $settings['lightbox_effect']; ?>" selected><?php echo $settings['lightbox_effect']; ?></option>
		<?php
		// list of currentlx known LightBox-like LA-jQuery plugins
		// unsuitable plugins: floaty (Youtube only)
		$known_plugins = array(
			'ceebox',
			'colorbox',
			'fancybox',
			'lightbox',
			//'picbox',
			'pirobox',
			'prettyphoto',
			//'slimbox2',
			'zoomimage'
			);
		$options_list = array();
		
		$directory = WB_PATH.'/modules/lib_jquery/plugins';
		
		if (is_dir($directory))	{
			// Open directory
			$dir = dir($directory);
			if ($dir != NULL) {
				// loop through the directory
				while ($entry = $dir->read()) {
					if (($entry != '.') && ($entry != '..') && is_dir($directory."/".$entry) && in_array(strtolower($entry), $known_plugins)) {
						$options_list[] = strtolower($entry);
					}
				}
				$dir->close();
			}
		}
		sort($options_list);
		foreach($options_list as $entry) {
			echo "<option value='".$entry."'>".
			$entry.
			"</option>\n";
		}
		?>
		</select>
	</div>
	
	
	
	
	<div class="gallery_setting_name">
		<label for="html5"><?php echo $MOD_IMAGEGALLERY['SHOWAS_HTML5']; ?>:</label>
	</div>
	<div class="gallery_setting_value"><?php ($settings['html5'] == '1') ? $checked = 'checked' : $checked = '';?>
		<input type="checkbox" value="1" name="html5" id="html5" <?php echo $checked; ?> />
	</div>

</fieldset>

<table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tr>
		<td align="left">
			<input name="save" type="submit" value="<?php echo $TEXT['SAVE']; ?>" style="width: 100px; margin-top: 5px;" />
		</td>
		<td align="right">
			<input type="button" value="<?php echo $TEXT['CANCEL']; ?>" onclick="javascript: window.location = '<?php echo ADMIN_URL; ?>/pages/index.php'; return false;" style="width: 100px; margin-top: 5px;" />
		</td>
	</tr>
</table>
</form>
</div>