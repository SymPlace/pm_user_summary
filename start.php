<?php
/**
* Profile Manager User Summary Feature
*
* @package pm_user_summary
* @author oseg
* @copyright (C) ColdTrick 2009-2014, (C) Arttic 2018, (C) oseg 2018
*/

/**
 * Initialization of plugin
 *
 * @return void
 */
function pm_user_summary_init() {
	// Register Page handler
	elgg_register_page_handler('pm_user_summary', '\oseg\PmUserSummary\PageHandlers::userSummary');
	
	// hook for extending menus
	elgg_register_plugin_hook_handler('register', 'menu:entity', '\oseg\PmUserSummary\Users::registerEntityMenu', 600);
	
	// menu hooks
	elgg_register_plugin_hook_handler('register', 'menu:page', '\oseg\PmUserSummary\Menus::registerAdmin');
}

// elgg initialization events
elgg_register_event_handler('init', 'system', 'pm_user_summary_init');

// actions
elgg_register_action("pm_user_summary/user_summary_control/save", dirname(__FILE__) . "/actions/user_summary_control/save.php", "admin");
