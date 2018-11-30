<?php

namespace oseg\PmUserSummary;

/**
 * Menus
 */
class Menus {

	/**
	 * Add admin menu items
	 *
	 * @param string $hook        hook name
	 * @param string $entity_type hook type
	 * @param array  $returnvalue current return value
	 * @param array  $params      parameters
	 *
	 * @return array
	 */
	public static function registerAdmin($hook, $entity_type, $returnvalue, $params) {
		if (!elgg_in_context('admin') || !elgg_is_admin_logged_in()) {
			return;
		}
		
		if (elgg_get_plugin_setting('user_summary_control', 'profile_manager') == 'yes') {
			$returnvalue[] = \ElggMenuItem::factory([
				'name' => 'appearance:user_summary_control',
				'text' => elgg_echo('admin:appearance:user_summary_control'),
				'href' => 'admin/appearance/user_summary_control',
				'context' => 'admin',
				'parent_name' => 'appearance',
				'section' => 'configure',
			]);
		}
		
		return $returnvalue;
	}
	
}