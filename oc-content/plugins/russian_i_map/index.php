<?php
/*
Plugin Name: Russian Interactive Map
Plugin URI: https://osclass-pro.com
Description: Russian Interactive Map
Version: 1.0
Author: DIS
Author URI: https://osclass-pro.com
Short Name: russian_i_map
*/

	function russian_i_map() {
		require 'map_ru.php';
	}
	
	function map_ru_help() {
        osc_admin_render_plugin(osc_plugin_path(dirname(__FILE__)) . '/help.php') ;
    }
	
// This is needed in order to be able to activate the plugin
osc_register_plugin(osc_plugin_path(__FILE__), '');
// This is a hack to show a Uninstall link at plugins table (you could also use some other hook to show a custom option panel)
osc_add_hook(osc_plugin_path(__FILE__) . "_uninstall", '');
// This is a hack to show a Configure link at plugins table (you could also use some other hook to show a custom option panel)
osc_add_hook(osc_plugin_path(__FILE__) . '_configure', 'map_ru_help');
?>