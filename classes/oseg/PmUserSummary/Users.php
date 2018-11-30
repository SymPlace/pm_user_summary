<?php

namespace oseg\PmUserSummary;

/**
 * Users
 */
class Users {

	/**
	 * Used to extend the entity menu when user_summary_control is enabled
	 *
	 * @param string  $hook_name    name of the hook
	 * @param string  $entity_type  type of the hook
	 * @param unknown $return_value return value
	 * @param unknown $params       hook parameters
	 *
	 * @return array
	 */
	public static function registerEntityMenu($hook_name, $entity_type, $return_value, $params) {
	
		if (empty($return_value)) {
			$return_value = [];
		}
	
		// if it is not an array, someone is doing something strange with this hook
		if (!is_array($return_value)) {
			return $return_value;
		}
	
		// cleanup existing menu items (location is added in core/lib/users.php)
		// @todo is this fix still needed?
		if (!empty($return_value)) {
			foreach ($return_value as $key => $menu_item) {
				
				if (!($menu_item instanceof \ElggMenuItem)) {
					continue;
				}
				
				
				if ($menu_item->getName() !== "location") {
					continue;
				}
				
				$location = $params['entity']->location;
				if (empty($location)) {
					continue;
				}
				
				// remove location added by core
				if (elgg_get_plugin_setting('user_summary_control', 'profile_manager') == 'yes') {
					unset($return_value[$key]);
					continue;
				}
				
				// add the new and improved version that supports 'old' location as tags field
				
				if (is_array($location)) {
					$location = implode(',', $location);
				}
				$options = array(
					'name' => 'location',
					'text' => "<span>$location</span>",
					'href' => false,
					'priority' => 150,
				);
				$location_menu = \ElggMenuItem::factory($options);
				$return_value[$key] = $location_menu;
			}
		}

		if (elgg_in_context('widgets')) {
			return $return_value;
		}
		
		$user = elgg_extract('entity', $params);
		if (!elgg_instanceof($user, 'user')) {
			return $return_value;
		}
		
		if (elgg_get_plugin_setting('user_summary_control', 'profile_manager') !== 'yes') {
			return $return_value;
		}
		
		// add optional custom profile field data
		$current_config = elgg_get_plugin_setting('user_summary_config', 'profile_manager');
		if (empty($current_config)) {
			return $return_value;
		}
		
		$profile_fields = elgg_get_config('profile_fields');
		if (empty($profile_fields)) {
			return $return_value;
		}
		
		$current_config = json_decode($current_config, true);
		if (empty($current_config) || !is_array($current_config)) {
			return $return_value;
		}

		$fields = elgg_extract('entity_menu', $current_config);
		if (empty($fields)) {
			return $return_value;
		}
		
		$spacer_allowed = true;
		$spacer_result = '';
		$menu_content = '';

		foreach ($fields as $field) {
			$field_result = '';

			switch ($field) {
				case 'spacer_dash':
					if ($spacer_allowed) {
						$spacer_result = ' - ';
					}
					$spacer_allowed = false;
					break;
				case 'spacer_space':
					if ($spacer_allowed) {
						$spacer_result = ' ';
					}
					$spacer_allowed = false;
					break;
				case 'spacer_new_line':
					$spacer_allowed = true;
					$field_result = '<br />';
					break;
				default:
					if (array_key_exists($field, $profile_fields)) {
						$spacer_allowed = true;
						$field_result = elgg_view('output/' . $profile_fields[$field], ['value' => $user->$field, 'inline' => true]);
					}
					break;
			}

			if (!empty($field_result)) {
				$menu_content .= $spacer_result . $field_result;
			}
		}
			
		if (!empty($menu_content)) {
			$return_value[] = \ElggMenuItem::factory([
				'name' => 'profile_manager_user_summary_control_entity_menu',
				'text' => elgg_format_element('span', [], $menu_content),
				'href' => false,
				'priority' => 150,
			]);
		}
		
		return $return_value;
	}
}