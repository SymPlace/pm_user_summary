<?php

namespace oseg\PmUserSummary;

class PageHandlers {
	
	/**
	 * Handle /profile_manager/user_summary_control url
	 *
	 * @param array $page URL segments
	 *
	 * @return bool
	 */
	public static function userSummary($page) {
		switch ($page[0]) {
			case "user_summary_control":
				include(elgg_get_plugins_path() . "pm_user_summary/pages/user_summary_control/preview.php");
				return true;
		}
	}
	
}
