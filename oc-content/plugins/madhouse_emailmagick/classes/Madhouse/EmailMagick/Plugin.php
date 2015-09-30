<?php

/**
 * Madhouse EmailMagick class.
 * @since  1.01
 */
class Madhouse_EmailMagick_Plugin
{
	public static function install()
	{
		// TODO.
	}

	public static function uninstall()
	{
		// TODO.
	}

	/**
	 * Upgrades the model & preferences to 1.01.
	 * - Fix for 1.00 where version does not exist.
	 * @return void
	 * @since  1.01
	 */
	public static function upgrade101()
	{
		if(mdh_get_preference("version") === "") {
			osc_set_preference("version", "1.01", mdh_current_preferences_section(), "STRING");
		}
	}
}

?>