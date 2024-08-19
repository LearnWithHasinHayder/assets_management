<?php
/**
 * Plugin Name: Assets Academy
 * Description: This is a plugin for assets management examples
 * Version: 1.0.1
 * Author: weDevs Academy
 */
include_once(ABSPATH . 'wp-admin/includes/plugin.php');
class Assets_Academy {
    function __construct() {
        add_action('init', [$this, 'init']);
    }

    function init() {
        add_action("wp_enqueue_scripts", [$this, "load_assets"]);
        add_action("admin_enqueue_scripts", [$this, "load_admin_assets"]);
    }

    function load_admin_assets($hook) {
        // die($hook);
        $plugin_data = get_plugin_data(__FILE__);
        $plugin_version = $plugin_data['Version'];
        // $assets_path = plugin_dir_url( __FILE__)."assets/";
        $assets_path = plugins_url("assets/", __FILE__);
        $js_path = $assets_path . "js/";
        $css_path = $assets_path . "css/";

        wp_enqueue_style("aac-admin-style", $css_path . "admin-style.css", [], $plugin_version);
        wp_enqueue_script("aac-admin-script", $js_path . "admin-script.js", ["jquery"], $plugin_version, true);
        // if ($hook == "plugins.php" || $hook = "plugin-install.php") {
        //     wp_enqueue_script("aac-plugin-script", $js_path . "plugin-script.js", ["jquery"], $plugin_version, true);
        // }

        $pages = [
            "plugins.php",
            "plugin-install.php"
        ];

        if (in_array($hook,$pages)) {
            wp_enqueue_script("aac-plugin-script", $js_path . "plugin-script.js", ["jquery"], $plugin_version, true);
        }
    }

    function load_assets() {
        $plugin_data = get_plugin_data(__FILE__);
        $plugin_version = $plugin_data['Version'];
        // $assets_path = plugin_dir_url( __FILE__)."assets/";
        $assets_path = plugins_url("assets/", __FILE__);
        $js_path = $assets_path . "js/";

        $current_wp_version = get_bloginfo("version");

        wp_enqueue_script("aac-script1-js", $js_path . "script1.js", ["jquery", "aac-script3-js"], $plugin_version, true);
        wp_enqueue_script("aac-script2-js", $js_path . "script2.js", ["jquery"], $plugin_version, true);
        wp_enqueue_script("aac-script3-js", $js_path . "script3.js", ["jquery", "aac-script2-js"], $plugin_version, true);
        wp_enqueue_script_module("aac-module-js", $js_path . "module.js", [], $plugin_version);
        wp_enqueue_script("aac-script-defer-js", $js_path . "script-defer.js", [], $plugin_version, [
            "strategy" => 'defer', //or async
            "in_footer" => "true"
        ]);

        $data = [
            "site_url" => site_url(),
            "ajax_url" => admin_url("admin-ajax.php"),
            "nonce" => wp_create_nonce("aac_nonce"),
            "current_wp_version" => $current_wp_version
        ];

        wp_enqueue_script("aac-data-js", $js_path . "data.js", [], $plugin_version, true);
        wp_localize_script("aac-data-js","databank",$data);

        wp_enqueue_style("aac-style1-css", $assets_path . "css/frontend.css", [], $plugin_version);
    }

}

new Assets_Academy();