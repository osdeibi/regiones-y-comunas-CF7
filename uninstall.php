<?php
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}
$option_name = 'dycdc_plugin';
$version = 'dycdc_plugin_version';
delete_option($option_name);
delete_option($version);
global $wpdb;
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}dycdc_departamentos");
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}dycdc_ciudades");