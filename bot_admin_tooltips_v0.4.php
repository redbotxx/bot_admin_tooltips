<?php

// This is a PLUGIN TEMPLATE.

// Copy this file to a new name like abc_myplugin.php.  Edit the code, then
// run this file at the command line to produce a plugin for distribution:
// $ php abc_myplugin.php > abc_myplugin-0.1.txt

// Plugin name is optional.  If unset, it will be extracted from the current
// file name. Plugin names should start with a three letter prefix which is
// unique and reserved for each plugin author ("abc" is just an example).
// Uncomment and edit this line to override:
$plugin['name'] = 'bot_admin_tooltips';

// Allow raw HTML help, as opposed to Textile.
// 0 = Plugin help is in Textile format, no raw HTML allowed (default).
// 1 = Plugin help is in raw HTML.  Not recommended.
# $plugin['allow_html_help'] = 1;

$plugin['version'] = '0.4';
$plugin['author'] = 'redbot';
$plugin['author_uri'] = 'http://www.redbot.it/txp';
$plugin['description'] = 'Create tooltips in admin interface.';

// Plugin load order:
// The default value of 5 would fit most plugins, while for instance comment
// spam evaluators or URL redirectors would probably want to run earlier
// (1...4) to prepare the environment for everything else that follows.
// Values 6...9 should be considered for plugins which would work late.
// This order is user-overrideable.
$plugin['order'] = '5';

// Plugin 'type' defines where the plugin is loaded
// 0 = public       : only on the public side of the website (default)
// 1 = public+admin : on both the public and admin side
// 2 = library      : only when include_plugin() or require_plugin() is called
// 3 = admin        : only on the admin side
$plugin['type'] = '3';

// Plugin "flags" signal the presence of optional capabilities to the core plugin loader.
// Use an appropriately OR-ed combination of these flags.
// The four high-order bits 0xf000 are available for this plugin's private use
if (!defined('PLUGIN_HAS_PREFS')) define('PLUGIN_HAS_PREFS', 0x0001); // This plugin wants to receive "plugin_prefs.{$plugin['name']}" events
if (!defined('PLUGIN_LIFECYCLE_NOTIFY')) define('PLUGIN_LIFECYCLE_NOTIFY', 0x0002); // This plugin wants to receive "plugin_lifecycle.{$plugin['name']}" events

$plugin['flags'] = '0';

if (!defined('txpinterface'))
        @include_once('zem_tpl.php');

# --- BEGIN PLUGIN CODE ---
//<?php

if(@txpinterface == 'admin') {
	add_privs('bot_admin_tooltips_tab', '1,2');
	register_tab('extensions', 'bot_admin_tooltips_tab', 'bot_admin_tooltips');
	register_callback('bot_admin_tooltips_tab', 'bot_admin_tooltips_tab');
	register_callback('bot_admin_tooltips', 'article');
	register_callback('bot_admin_tooltips', 'category');
	register_callback('bot_admin_tooltips', 'list');
	register_callback('bot_admin_tooltips', 'image');
	register_callback('bot_admin_tooltips', 'file');
	register_callback('bot_admin_tooltips', 'link');
	register_callback('bot_admin_tooltips', 'discuss');
	register_callback('bot_admin_tooltips', 'admin');
} 

global $bot_admin_tooltips, $bot_admin_tooltips_main_array; //set globals

if (getThings("Show tables like '".PFX."bot_admin_tooltips'")) { // if plugin is installed loads items and tips from db
	$r = safe_rows_start('item, tip', 'bot_admin_tooltips','1=1');
	if ($r) {
		$bot_admin_tooltips = array();
		while ($a = nextRow($r)) {
			$bot_admin_tooltips[$a['item']] = $a['tip'];
		}   
	}
}



// ===========================================================


 
function bot_in_main_array($item, $page, $hook, $type) { // helps build the main array
	global $bot_admin_tooltips_main_array;
	$bot_admin_tooltips_main_array[$item]['page'] = $page;
	$bot_admin_tooltips_main_array[$item]['hook'] = $hook; 
	$bot_admin_tooltips_main_array[$item]['type'] = $type; 
	return $bot_admin_tooltips_main_array;
}

bot_in_main_array('tab_write|advanced_options','article','advanced','link');
bot_in_main_array('tab_write|override_default_form','article','override-form','label');
bot_in_main_array('tab_write|keywords','article','keywords','label');
bot_in_main_array('tab_write|article_image','article','article-image','label');
bot_in_main_array('tab_write|URL-only title','article','url-title','label');
bot_in_main_array('tab_write|recent_articles','article','recent','link');
bot_in_main_array('tab_write|title','article','title','label');
bot_in_main_array('tab_write|body','article','body','label');
bot_in_main_array('tab_write|excerpt','article','excerpt','label');
bot_in_main_array('tab_write|status','article','write-status','legend');
bot_in_main_array('tab_write|sort_display','article','write-sort','legend');
bot_in_main_array('tab_write|category1','article','category-1','label');
bot_in_main_array('tab_write|category2','article','category-2','label');
bot_in_main_array('tab_write|section','article','section','label');
bot_in_main_array('tab_write|more','article','more','link');
bot_in_main_array('tab_write|timestamp','article','write-timestamp','legend');
bot_in_main_array('tab_write|expires','article','write-expires','legend');
bot_in_main_array('tab_organise|article_head','category','article_category','h3');
bot_in_main_array('tab_organise|link_head','category','link_category','h3');
bot_in_main_array('tab_organise|image_head','category','image_category','h3');
bot_in_main_array('tab_organise|file_head','category','file_category','h3');
bot_in_main_array('tab_organise|name','category','name','td');
// bot_in_main_array('tab_organise|title','category','title','td');
bot_in_main_array('tab_organise|parent','category','parent','td');
bot_in_main_array('tab_list|search','list','list-search','label');
bot_in_main_array('tab_list|with_selected','list','withselected','label');
bot_in_main_array('tab_image|upload_image','image','image-upload','label');
bot_in_main_array('tab_image|search','image','image-search','label');
bot_in_main_array('tab_image|with_selected','image','withselected','label');
bot_in_main_array('tab_image|replace_image','image','image-replace','label');
bot_in_main_array('tab_image|upload_thumbnail','image','upload-thumbnail','label');
bot_in_main_array('tab_image|image_category','image','image-category','label');
bot_in_main_array('tab_image|alt_text','image','alt-text','label');
bot_in_main_array('tab_image|caption','image','caption','label');
bot_in_main_array('tab_file|upload_file','file','file-upload','label');
bot_in_main_array('tab_file|search','file','file-search','label');
bot_in_main_array('tab_file|with_selected','file','withselected','label');
bot_in_main_array('tab_file|category','file','category','p');
bot_in_main_array('tab_file|description','file','description','p');
bot_in_main_array('tab_file|file_status','file','file-status','legend');
bot_in_main_array('tab_file|timestamp','file','file-created','legend');
bot_in_main_array('tab_link|title','link','link-title','label');
bot_in_main_array('tab_link|sort_value','link','link-sort','label');
bot_in_main_array('tab_link|url','link','link-url','label');
bot_in_main_array('tab_link|category','link','link-category','label');
bot_in_main_array('tab_link|description','link','link-description','label');
bot_in_main_array('tab_link|search','link','link-search','label');
bot_in_main_array('tab_link|with_selected','link','withselected','label');
bot_in_main_array('tab_comments|search','discuss','discuss-search','label');
bot_in_main_array('tab_comments|with_selected','discuss','withselected','label');
bot_in_main_array('tab_admin|login','admin','name','td');
bot_in_main_array('tab_admin|real_name','admin','RealName','td');
bot_in_main_array('tab_admin|email','admin','email','td');
bot_in_main_array('tab_admin|privileges','admin','privs','td');
bot_in_main_array('tab_admin|with_selected','admin','withselected','label');
bot_in_main_array('tab_admin|new_password','admin','new_pass','label');



// ===========================================================



// when first login onto txp, event is not specified. So if txp version < 4.08 $event = 'article'
// if txp version > 4.08 $event = '$default_event' in $prefs (cos 4.2 allows to choose default event)

function bot_check_event() {
    global $event, $prefs;
   	if (gps('event')) {
    	$event = gps('event');
    }
    else {
        $event=(($prefs['version']) < '4.2.0') ? 'article' : $prefs['default_event'];
    }
    return $event;
} 





// ===========================================================
//	jquery
// ===========================================================



function bot_admin_tooltips_js_rows() {
	//set function variables
	global $bot_admin_tooltips_per_page, $bot_admin_tooltips_main_array, $plugins;
	extract(bot_admin_tooltips_get_prefs());
	$bot_admin_tooltips_js_show_event = $bot_admin_tooltips_js_event ? 'mouseover' : 'click'; // set the '$bot_admin_tooltips_js_event' variable depending on the posted values
	$bot_admin_tooltips_js_hide_event = $bot_admin_tooltips_js_event ? 'mouseout' : 'unfocus'; 
	if (in_array('glz_custom_fields', $plugins)) {
	$bot_glz_version = safe_field("version","txp_plugin","name='glz_custom_fields'"); //  fetch glz version, if installed
	$bot_glz_version = str_replace(".", "", $bot_glz_version);
	};

    // when first login onto txp event is not specified. So if txp version < 4.08 $event = 'article'
	// if txp version > 4.08 $event =  $default_event in $prefs (4.2 allows to choose default event)
	$event = bot_check_event();
	
	foreach ($bot_admin_tooltips_per_page as $item => $tip) {
		if (strpos($item, 'custom_') !== false) { // if is a custom field use this values
			$hook = substr($item, strpos($item, 'custom_')); // gets the hook: removes the  part before "|"
			$hook = in_array('glz_custom_fields', $plugins) && $bot_glz_version < 122 ? $hook : str_replace('custom_', 'custom-', $hook); // if glz_custom_fields is NOT installed  OR is varsion 1.2.2. replaces "_" with "-"
			$page = 'article';
			$type = 'label';
			$position = 'topRight';
		}
		else {
			$hook = $bot_admin_tooltips_main_array[$item]['hook'];
			$page = $bot_admin_tooltips_main_array[$item]['page'];
			$type = $bot_admin_tooltips_main_array[$item]['type'];
		}; 
		$tip = addslashes($tip);
		switch($event){
			case 'article':
				switch($type){
					case 'label':
						$selector = "$(\"label[for='".$hook."']\")"; 
					break;
					case 'legend':
						$selector = "$(\"fieldset#".$hook." legend\")";
					break;
					case 'link':
						$selector = "$(\"a[href*='".$hook."']\")"; // for "more" and "recent articles"
					break;
				}
			break;				
			case 'category':
				switch($type){
					case 'h3':
						$selector = "$(\"h3:has(a[href*='".$hook."'])\")"; 
					break;
					case 'td': // for category "title", "parent" etc.
						$selector = "$(\"td:has(*[name='".$hook."'])\").prev()"; 
					break;
				}
			break;
			case 'list':
				$selector = "$(\"label[for='".$hook."']\")";
			break;
			case 'image':
				$selector = "$(\"label[for='".$hook."']\")";
			break;
			case 'file':
				switch($type){
					case 'label':
						$selector = "$(\"label[for='".$hook."']\")";
					break;
					case 'legend':
						$selector = "$(\"fieldset#".$hook." legend\")";
					break;	
					case 'p': // for file "description", "category" 
						$selector = "$(\"p:has(*[name='".$hook."'])\")";
					break;
				}
			break;
			case 'link':
				$selector = "$(\"label[for='".$hook."']\")";
			break;	
			case 'discuss':
				$selector = "$(\"label[for='".$hook."']\")";
			break;
			case 'admin':
				switch($type){
					case 'label':
						$selector = "$(\"label[for='".$hook."']\")";
					break;	
					case 'td': // for category "Privileges", "Login" etc.
						$selector = "$(\"td:has(*[name='".$hook."'])\").prev()";  
					break;
				}
			break;	
		}
		echo 
			$selector.'.append("<span class=\"bot_tip\" style=\"cursor:pointer;\">&nbsp;[ ? ]&nbsp;</span>");'.n. // append question mark
			$selector.'.qtip({  
				content: "'.preg_replace("/\r?\n/", "<br />", $tip).'", 
				show: { when: {event: "'.$bot_admin_tooltips_js_show_event.'", target: '.$selector.'.children("span.bot_tip")}, effect: { type: "fade", length: 0}, solo: true, delay: 0},
				hide: { when: {event: "'.$bot_admin_tooltips_js_hide_event.'", target: '.$selector.'.children("span.bot_tip")}, effect: { type: "fade", length: 200}, fixed: true},
				style:{border: {width: 6, radius: 8, color: "'.$bot_admin_tooltips_border_color.'"}, width: 200, background: "'.$bot_admin_tooltips_bg.'", color: "'.$bot_admin_tooltips_color.'", tip: "bottomLeft"},
				position: {target: '.$selector.'.children("span.bot_tip"), corner: {target: "topRight", tooltip: "bottomLeft"}, adjust: { screen: true }}
			});	';	
		
	};	
}



// ===========================================================



function bot_admin_tooltips(){

	global $bot_admin_tooltips, $bot_admin_tooltips_main_array;
	global $bot_admin_tooltips_per_page; // tips for the actual page (event)
	extract(bot_admin_tooltips_get_prefs());
	
	    // when first login onto txp event is not specified. So if txp version < 4.08 $event = 'article'
	// if txp version > 4.08 $event =  $default_event in $prefs (4.2 allows to choose default event)
	$event = bot_check_event();

	foreach ($bot_admin_tooltips as $item => $tip) { // builds the "tips per page" array
        if ((strpos($item, 'custom_') !== false) && $event =="article") { // if is a cf and event is "article"
			$bot_admin_tooltips_per_page[$item] = $tip; 
		}
		elseif(strpos($item, 'custom_') !== false){
        // do nothing
		}
		elseif ($bot_admin_tooltips_main_array[$item]['page'] == $event) {
			$bot_admin_tooltips_per_page[$item] = $tip; 
		};
	}
	
	if ($bot_admin_tooltips_per_page) { // prints js code only if there are tips
		echo
				'<script type="text/javascript" src= "'.$bot_admin_tooltips_path.'jquery.qtip-1.0.0-rc3.min.js"></script>'.n.
				'<script language="javascript" type="text/javascript">'.n.
				'/* <![CDATA[ */'.n.
				'	$(document).ready(function() {';
				bot_admin_tooltips_js_rows();
		echo
				'	});'.n.
				'/* ]]> */'.n.
				'</script>';
	}
	if ($bot_admin_tooltips_hide_pophelp == '1') {
		echo		
				'<script language="javascript" type="text/javascript">'.n.
				'/* <![CDATA[ */'.n.
				'	$(document).ready(function() {'.n.
				'	$("a.pophelp").hide()'.n.
				'	});'.n.
				'/* ]]> */'.n.
				'</script>';		
	}
}



// ===========================================================
//	Interface and db
// ===========================================================



function bot_admin_tooltips_gTxt($what) {

	global $language;
	
	$en_us = array(
		'install_message' => 'bot_admin_tooltips is not yet properly initialized.  Use the button below to create the preferences table.',
		'upgrade_message' => 'bot_admin_tooltips must be upgraded. Use the button below to add the new fields to the preferences table.',
		'uninstall' => 'Uninstall',
		'uninstall_message' => 'Using the button below will remove the bot_admin_tooltips preferences table (and all other entries made by the plugin).',
		'uninstall_confirm' => 'Are you sure you want to delete the preferences table?',
		);
	
	$lang = array(
		'en-us' => $en_us
		);
		
		$language = (isset($lang[$language])) ? $language : 'en-us';
		$msg = (isset($lang[$language][$what])) ? $lang[$language][$what] : $what;
		return $msg;
}



//===========================================



function bot_admin_tooltips_check_install() {

	// Check if the bot_admin_tooltips table already exists
	if (getThings("Show tables like '".PFX."bot_admin_tooltips'")) {
		return true;
	}
	return false;
}



//=========================================== 



function bot_admin_tooltips_install() {	
	
	// figure out what MySQL version we are using (from _update.php)
	$mysqlversion = mysql_get_server_info();
	$tabletype = (intval($mysqlversion[0]) >= 5 || preg_match('#^4\.(0\.[2-9]|(1[89]))|(1\.[2-9])#',$mysqlversion)) 
		? " ENGINE=MyISAM "
		: " TYPE=MyISAM ";
	if (isset($txpcfg['dbcharset']) && (intval($mysqlversion[0]) >= 5 || preg_match('#^4\.[1-9]#',$mysqlversion))) {
		$tabletype .= " CHARACTER SET = ". $txpcfg['dbcharset'] ." ";
	}
	
	// Create the bot_admin_tooltips table
	$bot_admin_tooltips_prefs_table = safe_query("CREATE TABLE `".PFX."bot_admin_tooltips` (
		`id` INT NOT NULL AUTO_INCREMENT , 
		`item` VARCHAR(255) NOT NULL, 
		`tip` TEXT NOT NULL,
		PRIMARY KEY (`id`)
		) $tabletype");
	
	set_pref ('bot_admin_tooltips_hide_pophelp','1', 'bot_tips_','2');
	set_pref ('bot_admin_tooltips_js_event','0', 'bot_tips_','2');
	set_pref ('bot_admin_tooltips_bg','#fdfdfd', 'bot_tips_','2');
	set_pref ('bot_admin_tooltips_color','#454545', 'bot_tips_','2');
	set_pref ('bot_admin_tooltips_border_color','#e0e0e0', 'bot_tips_','2');
	set_pref ('bot_admin_tooltips_path','../js/', 'bot_tips_','2');
}



//===========================================



function bot_get_tips() { // creates an array of values extracted from the db 

	$r = safe_rows_start('id, item, tip', 'bot_admin_tooltips','1=1');
	if ($r) {
		$out = array();
		while ($a = nextRow($r)) {
			$out[$a['item']]['item'] = $a['item'];
			$out[$a['item']]['id'] = $a['id'];
			$out[$a['item']]['tip'] = $a['tip'];
		}
		asort($out);
		return $out;
	}
	return false;
}



//===========================================



function bot_admin_tooltips_get_prefs() { // creates an array of values extracted from the prefs in db 
	
	$r = safe_rows_start('name, val', 'txp_prefs', 'event = "bot_tips_"');
	if ($r) {
		$public_prefs = array();
		while ($a = nextRow($r)) {
			$public_prefs[$a['name']] = $a['val'];
		}
		return $public_prefs;
	}
	return false;
}



//===========================================



function selectInput_values ($current_item = '') { // generates context-sensitive array for selectInput values
	global $bot_admin_tooltips_main_array, $arr_custom_fields;
	$prefs = bot_get_tips(); // values from the db 
    if ($arr_custom_fields) {
        $bot_admin_tooltips_main_array_with_cf = array_merge($bot_admin_tooltips_main_array, $arr_custom_fields); // mega array with existing cfs
    }
    else {
        $bot_admin_tooltips_main_array_with_cf = $bot_admin_tooltips_main_array; // if no cfs are set - var name should be changed but I'm lazy
    }
    $values_for_selectInput = array(); // creates a new array
	foreach ($bot_admin_tooltips_main_array_with_cf as $item => $values) { // filters values for txp selectInput function (behaves differently if cf or not)...
		if (!array_key_exists($item, $prefs) || $item == $current_item) { // ... insert in  "values_for_selectInput" only if not yet used or value is given as parameter "$current_item" 
			if (strpos($item, 'custom_') !== false) { // if $item IS a cf insert in this specific format...
				$values_for_selectInput[$item] = gTxt('tab_write').'|'.$values; // value is modified to translate and to allow alphabetical ordering
			}
			else { // ...if is not a cf insert in this format instead
				$split_items = explode('|', $item); // begin translation process > split item name
				for ($i = 0; $i < count($split_items); $i++) {
				$translated_value[] = gTxt($split_items[$i]); // translates each word and add to an array	
				}			
				$joint_items = implode('|', $translated_value); // joins together single words
				unset($translated_value); // so every time is empty
				$values_for_selectInput[$item] = $joint_items; // key => ugly name; $value => nice name
			}
		}
	}
	asort($values_for_selectInput);
	return $values_for_selectInput;
}



//===========================================



function bot_admin_tooltips_output_rows(){ // outputs the rows for the html table in the bot_admin_tooltips_tab

	global $bot_admin_tooltips_main_array;
	$prefs = bot_get_tips(); // array of values from the db 
	
	$rows = "";
	$values_for_selectInput = selectInput_values();
	
	if ($values_for_selectInput){ // if there are free values left for select input...
		$input_row = tr(td(selectInput('bot_new_item', $values_for_selectInput, '', '1' )).td('<textarea name="bot_new_tip" cols="40" rows="5" style="width:300px; height:70px"></textarea>').td()); // ...outputs the 'new tip' input
		$rows .= $input_row;
	}
	foreach ($prefs as $item => $values) {	// rows for values coming from the db
		$tip = $values['tip'];
		$id = $values['id'];
		$values_for_selectInput = selectInput_values($item);
		$single_row = tr(td(selectInput('bot_item[]', $values_for_selectInput, $item))
						.td('<textarea name="bot_saved_tip[]" cols="40" rows="5" style="width:300px; height:70px">'.$tip.'</textarea>')
						.td('<label for="bot_delete_id">Delete</label>'.checkbox('bot_delete_id[]', $id, '0'))) // TODO add translation
						.hInput('bot_id[]', $id);
		$rows .= $single_row;
	}	
	return $rows;
}



//===========================================



function bot_admin_tooltips_tab($event, $step) {

	global $bot_admin_tooltips_main_array, $plugins;
	
	if (isset($_POST['bot_item'])) { // if there are preferences
		$prefs = bot_get_tips(); // array of values from the db table
	};

	$r = safe_rows_start('name, val', 'txp_prefs','event = "custom" AND val != ""'); // creates an array of all cfs for selectInput in bot_admin_tooltips_tab
	if ($r) {
		global $arr_custom_fields;
		while ($a = nextRow($r)) { 
			$name = 'tab_write|'.str_replace('_set', '', $a['name']);
			$val = $a['val'];
			$arr_custom_fields[$name] = $val;
		}
	}
  
	pagetop('bot_admin_tooltips '.gTxt('preferences'), ($step == 'update' ? gTxt('preferences_saved') : ''));
	echo hed('bot | admin tooltips','2', ' style="text-align: center; margin:20px auto;   padding-bottom:10px;"');

	if ($step == 'install') {
		// Install the preferences table.
		bot_admin_tooltips_install();
	}
	
	if ($step == 'uninstall') {
		//remove table
		safe_query("DROP TABLE ".PFX."bot_admin_tooltips");
		safe_delete('txp_prefs', 'event = "bot_tips_"' );
	}
	
	if ($step == 'update') { 
	
		// set function variables
		$new_item = doslash(ps('bot_new_item'));
		$new_tip = doslash(ps('bot_new_tip'));
		$item = doslash(ps('bot_item'));
		$tip = doslash(ps('bot_saved_tip'));
		$tip_id = ps('bot_id');
		$delete_id = ps('bot_delete_id');
		$hide_pophelp = ps('bot_admin_tooltips_hide_pophelp');
		$js_event = ps('bot_admin_tooltips_js_event');
		$tips_bg = doslash(ps('bot_admin_tooltips_bg'));
		$tips_color = doslash(ps('bot_admin_tooltips_color'));
		$tips_border_color = doslash(ps('bot_admin_tooltips_border_color'));
		$js_path = doslash(ps('bot_admin_tooltips_path'));

        if ($delete_id) { // checks if there is something to delete
			foreach ($delete_id as $id) {
				safe_delete('bot_admin_tooltips', 'id ="'.$id.'"' );
			}
		};
		
		safe_update('txp_prefs', 'val= "'.$hide_pophelp.'"', 'name = "bot_admin_tooltips_hide_pophelp"' ); // updates pophelp prefs
		safe_update('txp_prefs', 'val= "'.$js_event.'"', 'name = "bot_admin_tooltips_js_event"' ); // updates click/hover prefs
		safe_update('txp_prefs', 'val= "'.$tips_bg.'"', 'name = "bot_admin_tooltips_bg"' ); // updates bg prefs
		safe_update('txp_prefs', 'val= "'.$tips_color.'"', 'name = "bot_admin_tooltips_color"' ); // updates color prefs
		safe_update('txp_prefs', 'val= "'.$tips_border_color.'"', 'name = "bot_admin_tooltips_border_color"' ); // updates border color prefs
		safe_update('txp_prefs', 'val= "'.$js_path.'"', 'name = "bot_admin_tooltips_path"' ); // updates path prefs
				
		if (($item != '') && ($tip != '')) { // when tips are set			
			for($i = 0; $i < count($item); $i++) { // creates the "posted_variables" array containing item, tip, tip_id
				$posted_variables[$item[$i]]['item'] = $item[$i];
				$posted_variables[$item[$i]]['tip'] = $tip[$i];
				$posted_variables[$item[$i]]['id'] = $tip_id[$i]; 
			}
			foreach($posted_variables as $item => $values) { // for each posted variable (item, tip, tip_id) updates the db
				$tip = $values['tip'];
				$id = $values['id'];
				if (($item != '') && ($tip != '')) { // if there is item AND tip
					safe_update('bot_admin_tooltips', 'item = "'.$item.'", tip = "'.$tip.'"', 'id = "'.$id.'"');
				}
				elseif ($tip == ''){ // if there is no tip, tip is deleted from db
				safe_delete('bot_admin_tooltips', 'item ="'.$item.'"' );
				}
			}
			if(($new_item != '') && ($new_tip != '')) { // if there is a new tip is inserted in db
				safe_insert('bot_admin_tooltips', 'item = "'.$new_item.'", tip = "'.$new_tip.'"');
			}
		}
		elseif(($new_item != '') && ($new_tip != '')) { // if no tips are set yet deals only with new tip
			safe_insert('bot_admin_tooltips', 'item = "'.$new_item.'", tip = "'.$new_tip.'"');
		}
 	}
	
	
	if (bot_admin_tooltips_check_install()) {		
		extract(bot_admin_tooltips_get_prefs());
		
		// beginning of the form 
		echo form( 
		
		// preferences
		'<div style="text-align:center; background:#f2f2f2; margin:20px auto 40px; padding:10px; border-bottom:solid #ccc 1px; border-top:solid #ccc 1px; ">'
		.n.'<label for="bot_admin_tooltips_bg">Background color </label>'.finput('text', 'bot_admin_tooltips_bg', $bot_admin_tooltips_bg) 
		.n.'&nbsp; &nbsp;'
		.n.'<label for="bot_admin_tooltips_color">Text color </label>'.finput('text', 'bot_admin_tooltips_color', $bot_admin_tooltips_color) 
		.n.'&nbsp; &nbsp;'
		.n.'<label for="bot_admin_tooltips_border_color">Border color </label>'.finput('text', 'bot_admin_tooltips_border_color', $bot_admin_tooltips_border_color) 
		.n.'&nbsp; &nbsp;'
		.n.'<label for="bot_admin_tooltips_js_event">Show tip on hover </label>'.checkbox2('bot_admin_tooltips_js_event', $bot_admin_tooltips_js_event) 
		.n.'&nbsp; &nbsp;'
		.n.'<label for="bot_admin_tooltips_hide_pophelp">Hide txp pophelps </label>'.checkbox2('bot_admin_tooltips_hide_pophelp', $bot_admin_tooltips_hide_pophelp) 
		.n.'&nbsp; &nbsp;'
		.n.'<label for="bot_admin_tooltips_path">Path to js </label>'.finput('text', 'bot_admin_tooltips_path', $bot_admin_tooltips_path) 
		.n.'</div>'

		// update button
		.'<div style="margin: 0 auto 20px; width:580px; padding:0 10px;">'
		.n.eInput('bot_admin_tooltips_tab')
		.n.sInput('update')
		.n.fInput('submit', 'update', 'Update', 'publish')
		.'</div>'
		
		// beginning of the tips table
		.n.startTable('list') 
		.n.tr(td(strong('Item')).td(strong('Tip'))) // table header
		.n.bot_admin_tooltips_output_rows() // html rows generated by "bot_admin_tooltips_output_rows()"
		.n.endTable() // end of the tips table
		
		// second update button
		.'<div style="margin: 20px auto; width:580px; padding:0 10px;">'
		.n.eInput('bot_admin_tooltips_tab')
		.n.sInput('update')
		.n.fInput('submit', 'update', 'Update', 'publish')
		.'</div>');
		
		// uninstall button
		echo n.t.'<div style="margin: 20px auto 0; width:580px; border-top:dashed #ccc 1px; margin-top:40px; padding:10px 10px 0;">'.
		n.t.t.graf(bot_admin_tooltips_gTxt('uninstall_message')).
		n.hed(bot_admin_tooltips_gTxt('uninstall'), '1').
		n.n.form(
		n.eInput('bot_admin_tooltips_tab').
		n.sInput('uninstall').
		n.n.fInput('submit', 'uninstall', 'Uninstall ', 'smallerbox'),"","confirm('".bot_admin_tooltips_gTxt('uninstall_confirm')."')"
		)
		.'</div>'; 
	} 
	
	else { 
		// install message
		echo n.t.'<div style="margin: auto; width:40%;">'.
			n.t.t.hed('bot_admin_tooltips '.gTxt('Preferences'), '1').
			n.graf(bot_admin_tooltips_gTxt('install_message')).
			n.n.form(
				n.eInput('bot_admin_tooltips_tab').
				n.sInput('install').
				n.n.fInput('submit', 'install', 'Install ', 'publish')
				).
			'</div>';
	}
}
# --- END PLUGIN CODE ---
if (0) {
?>
<!--
# --- BEGIN PLUGIN HELP ---
<h2>bot_admin_tooltips</h2>
<p>This plugin allows to insert tooltips in txp admin using Craig Thompson's jquery plugin <a href="http://craigsworks.com/projects/qtip/">qtips</a>.  It can be useful to show customized instructions for your clients.</p>
<h3>Features</h3>
<ul>
  <li>Generated tooltips can be customized in background, border and text color. </li>
  <li>By default standard txp &quot;pophelps&quot; are hidden but can cohexist with the plugin </li>
  <li>Tooltips can be activated on click (default) or hover.</li>
  <li>Tooltips can be associated with almost every page element under the &quot;content&quot; tab </li>
  <li>Tooltips accept html and recognize line breaks (for  faster text insertion) </li>
  <li>Tooltips anchors have a class so can be customized via css </li>
  <li>glz_custom_fields compatible </li>
</ul>
<h3>Instructions</h3>
<p>Once inatalled and activated visit: &quot;extensions&quot; &gt; &quot;bot_tips&quot;. Here you can set preferences, enter tooltips text and associate it with a specific item. </p>
<h3>Preferences:</h3>
<p><strong>Background color:</strong><br />
Sets the tooltip background (default #fdfdfd) </p>
<p>
  <strong>Text color:</strong>  <br />
Sets the tooltip color (default #454545) </p>
<p>
  <strong>Border color:  </strong><br />
Sets the tooltip border color(default #e0e0e0) </p>
<p>
  <strong>Show tip on hover: </strong><br />
default is on click </p>
<p>
<strong>Hide txp pophelps: </strong><br />
Hides all native txp 'pophelps' </p>
<p>
  <strong>Path to js:</strong>
  <br />
  Path to  qtips script. Defaults to '../js/' </p>
<h3>Notes</h3>
<ol>
  <li> This plugin has been tested to work without conflicts with many other plugins but seems to have a slight incompatibility with hak_titnymce.<br />
    If you set &quot;Show editor toggle:&quot; to &quot;yes&quot; in its preferences then  tooltips won't show  up  under the &quot;advanced&quot; section of the &quot;write&quot; tab - all the rest will work ok though.</li>
  <li>If &quot;Hide txp pophelps&quot; is ticked standard Txp pophelps are hidden for all subtabs under the &quot;content&quot; tab and for the &quot;users&quot; subtab.<br />
    This will suffice most of the times but if you want to hide <strong>every</strong> native pophelp you can simply add this rule  at the end of your  textpattern.css file, which - depending on your txp version - can be found in /textpattern (pre 4.2) or in /textpattern/theme/yourtheme (v 4.2): <br />
    <code>a.pophelp {display:none;}</code></li>
</ol>
# --- END PLUGIN HELP ---
-->
<?php
}
?>