<?php
/*
Plugin Name: Madhouse EmailMagick
Short Name: madhouse_emailmagick
Description: A useful tool to properly managing osclass emails.
Plugin URI: http://wearemadhouse.wordpress.com/portfolio/madhouse-emailmagick/
Plugin update URI: emailmagick
Version: 1.01
Author: Madhouse
Author URI: http://wearemadhouse.wordpress.com/
*/

/*
 * ==========================================================================
 *  LOADING
 * ==========================================================================
 */

require __DIR__ . "/vendor/composer_components/madhouse/autoloader/autoload.php";

/**
 * Makes this plugin the first to be loaded.
 * - Bumps this plugin at the top of the active_plugins stack.
 */
function mdh_emailmagick_bump_me()
{
    if(OC_ADMIN) {
        // @legacy : ALWAYS remove this if active.
        if(osc_plugin_is_enabled("madhouse_utils/index.php")) {
            Plugins::deactivate("madhouse_utils/index.php");
        }

        // Sanitize & get the {PLUGIN_NAME}/index.php.
        $path = str_replace(osc_plugins_path(), '', osc_plugin_path(__FILE__));

        if(osc_plugin_is_installed($path)) {
            // Get the active plugins.
            $plugins_list = unserialize(osc_active_plugins());
            if(!is_array($plugins_list)) {
                return false;
            }

            // Remove $path from the active plugins list
            foreach($plugins_list as $k => $v) {
                if($v == $path) {
                    unset($plugins_list[$k]);
                }
            }

            // Re-add the $path at the beginning of the active plugins.
            array_unshift($plugins_list, $path);

            // Serialize the new active_plugins list.
            osc_set_preference('active_plugins', serialize($plugins_list));

            if(Params::getParam("page") === "plugins" && Params::getParam("action") === "enable" && Params::getParam("plugin") === $path) {
                //osc_redirect_to(osc_admin_base_url(true) . "?page=plugins");
            } else {
                osc_redirect_to(osc_admin_base_url(true) . "?" . http_build_query(Params::getParamsAsArray("get")));
            }
        }
    }
}

if(!function_exists("mdh_utils") || (function_exists("mdh_utils") && (mdh_utils() === true || strnatcmp(mdh_utils(), "1.20") === -1))) {
    mdh_emailmagick_bump_me();
} else {
    /*
     * ==========================================================================
     *  INSTALL / UNINSTALL
     * ==========================================================================
     */

    /**
     * (hook: install) Make installation operations
     * 		It creates the database schema and sets some preferences.
     * @returns void.
     */
    function mdh_emailmagick_install()
    {
        osc_set_preference(
            "email_template",
            file_get_contents(mdh_current_plugin_path("assets/model/template.html", false)),
            mdh_current_preferences_section(),
            'STRING'
        );
        osc_set_preference(
            "email_datas",
            file_get_contents(mdh_current_plugin_path("assets/model/data.json", false)),
            mdh_current_preferences_section(),
            'STRING'
        );

        Madhouse_EmailMagick_Plugin::upgrade101();
    }
    osc_register_plugin(osc_plugin_path(__FILE__), 'mdh_emailmagick_install');

    if(osc_plugin_is_installed(mdh_current_plugin_name(true)) && osc_get_preference("email_datas", mdh_current_preferences_section()) === "") {
        mdh_emailmagick_install();
    } else if(osc_get_preference("version", mdh_current_preferences_section()) === "") {
        Madhouse_EmailMagick_Plugin::upgrade101();
    }

    /**
     * (hook: uninstall) Make un-installation operations
     * 		It destroys the database schema and unsets some preferences.
     * @returns void.
     */
    function mdh_emailmagick_uninstall()
    {
        mdh_delete_preferences(mdh_current_preferences_section());
    }
    osc_add_hook(osc_plugin_path(__FILE__) . '_uninstall', 'mdh_emailmagick_uninstall');

    /*
     * ==========================================================================
     *  ROUTES
     * ==========================================================================
     */

    function mdh_emailmagick_admin_controller()
    {
        if(preg_match('/^' . mdh_current_plugin_name() . '.*$/', Params::getParam("route"))) {
            osc_add_hook("admin_header", function() {
                osc_enqueue_script(mdh_current_plugin_name() . "_admin");
                osc_enqueue_style(mdh_current_plugin_name() . "_admin", mdh_current_plugin_url("assets/css/admin.css"));
            });

            $filter =  function($string) {
                return __("Madhouse EmailMagick", mdh_current_plugin_name());
            };

            // Page title (in <head />)
            osc_add_filter("admin_title", $filter);

            // Page title (in <h1 />)
            osc_add_filter("custom_plugin_title", $filter);

            osc_add_filter("admin_body_class", function($classes) {
                array_push($classes, "madhouse");
                return $classes;
            });

            // Add a .row-offset to wrapping <div /> element.
            osc_add_filter("render-wrapper", function($string) {
                return "row-offset";
            });

            $do = new Madhouse_EmailMagick_Controllers_Admin();
            $do->doModel();
        }
    }
    osc_add_hook("renderplugin_controller", "mdh_emailmagick_admin_controller");

    osc_add_route(
        mdh_current_plugin_name(),
        'emailmagick/?',
        'emailmagick/',
        mdh_current_plugin_name() . '/views/admin/settings.php'
    );

    osc_add_route(
        mdh_current_plugin_name() . "_do",
        'emailmagick/do/?',
        'emailmagick/do/',
        mdh_current_plugin_name() . '/views/admin/settings.php'
    );

    osc_add_route(
        mdh_current_plugin_name() . "_init",
        'emailmagick/init?',
        'emailmagick/init/',
        mdh_current_plugin_name() . '/views/admin/init.php'
    );

    /*
     * ==========================================================================
     *  REGISTER & ENQUEUE
     * ==========================================================================
     */
    if(OC_ADMIN && preg_match('/^' . mdh_current_plugin_name() . '.*$/', Params::getParam("route"))) {
        osc_register_script("jquery", mdh_current_plugin_url("vendor/bower_components/jquery/dist/jquery.js"));
    }

    osc_register_script(mdh_current_plugin_name() . "_admin", mdh_current_plugin_url("assets/js/min/admin-min.js"));

    madhouse_register_mustache(mdh_current_plugin_name() . "_edit_email", mdh_current_plugin_path("assets/mustache/email-edit.mustache", false));
    madhouse_register_mustache(mdh_current_plugin_name() . "_edit_footer", mdh_current_plugin_path("assets/mustache/edit-footer.mustache", false));

    /*
     * ==========================================================================
     *  USER AND ADMIN MENU
     * ==========================================================================
     */

    /**
     * Adds a submenu to the Madhouse main admin menu.
     * (hook: admin_menu_init)
     */
    function mdh_emailmagick_admin_init() {
        osc_add_admin_submenu_divider('madhouse', "EmailMagick", mdh_current_plugin_name(), 'administrator');
        osc_add_admin_submenu_page('madhouse', __('Manage your emails', mdh_current_plugin_name()), mdh_emailmagick_url(), mdh_current_plugin_name(), 'administrator');
    }
    osc_add_hook('admin_menu_init', 'mdh_emailmagick_admin_init');
}

?>