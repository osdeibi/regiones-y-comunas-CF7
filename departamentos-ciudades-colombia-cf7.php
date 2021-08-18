<?php
/*
Plugin Name: Regiones y comunas de Chile para CF7
Description: Integra dropdowns dinamicos segun la seleccion de Region para usarlo con Contact Form 7.
Version: 1.0
Author: Osdeibi Acurero
Author URI: https://osdeibi.dev/
License: GPL3
License URI: http://www.gnu.org/licenses/gpl.html
*/
// Block direct access to the main plugin file.
defined( 'ABSPATH' ) or die();
class DYCDC_Plugin {
	public function __construct(){
		add_action( 'plugins_loaded', array( $this, 'dycdc_load_plugin_textdomain' ) );
		if(class_exists('WPCF7')){
			$this->dycdc_plugin_constants();
			require_once DYCDC_PATH . 'includes/autoload.php';
		}else{
			add_action( 'admin_notices', array( $this, 'dycdc_admin_error_notice' ) );
		}
		if(!get_option('dycdc_plugin_version'))
		{
			global $wpdb;
			$charset_collate = $wpdb->get_charset_collate();
			$table_departamento = $wpdb->prefix . 'dycdc_departamentos';
			$table_ciudad = $wpdb->prefix . 'dycdc_ciudades';
			$departamento_create="CREATE TABLE IF NOT EXISTS $table_departamento (
				`id` mediumint(8) UNSIGNED NOT NULL,
				`name` varchar(255) NOT NULL
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;";
			$ciudad_create="CREATE TABLE IF NOT EXISTS $table_ciudad (
				`id` mediumint(8) UNSIGNED NOT NULL,
				`name` varchar(255) NOT NULL,
				`departamento_id` mediumint(8) UNSIGNED NOT NULL
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;";
			$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}departamento");
			$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}ciudad");
			include_once ('includes/departamentos-sql.php');
			include_once ('includes/ciudades-sql.php');
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta($departamento_create);
			dbDelta($ciudad_create);
			dbDelta($departamento_insert);
			dbDelta($ciudad_insert);
			update_option('dycdc_plugin_version','2.1');
		}
	}
	public function dycdc_admin_error_notice_database(){
		echo '<div class="notice update-nag ">Error</div>';
	}
	/*
	REGISTER ADMIN NOTICE IF CONTACT FORM 7 IS NOT ACTIVE.
	*/
	public function dycdc_admin_error_notice(){
		$message = sprintf( esc_html__( 'Para usar este plugin es necesario Contact Form 7', 'dycdc_' ),'<strong>', '</strong>');
		printf( '<div class="notice notice-error"><p>%1$s</p></div>', wp_kses_post( $message ) );
	}
	/*
	SET PLUGIN CONSTANTS
	*/
	public function dycdc_plugin_constants(){
		if ( ! defined( 'DYCDC_PATH' ) ) {
			define( 'DYCDC_PATH', plugin_dir_path( __FILE__ ) );
		}
		if ( ! defined( 'DYCDC_URL' ) ) {
			define( 'DYCDC_URL', plugin_dir_url( __FILE__ ) );
		}
	}
}

// INSTANTIATE THE PLUGIN CLASS.
$dycdc_plugin = new DYCDC_Plugin();
register_activation_hook( __FILE__, 'dycdc_create_db' );
function dycdc_create_db() {
	global $wpdb;
	$charset_collate = $wpdb->get_charset_collate();
	$table_departamento = $wpdb->prefix . 'dycdc_departamentos';
	$table_ciudad = $wpdb->prefix . 'dycdc_ciudades';
	$departamento_create="CREATE TABLE IF NOT EXISTS $table_departamento (
		`id` mediumint(8) UNSIGNED NOT NULL,
		`name` varchar(255) NOT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;";
	$ciudad_create="CREATE TABLE IF NOT EXISTS $table_ciudad (
		`id` mediumint(8) UNSIGNED NOT NULL,
		`name` varchar(255) NOT NULL,
		`departamento_id` mediumint(8) UNSIGNED NOT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT;";
	include_once ('includes/departamentos-sql.php');
	include_once ('includes/ciudades-sql.php');
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta($departamento_create);
	dbDelta($ciudad_create);
	if(!get_option('dycdc_plugin'))
	{
		update_option('dycdc_plugin','installed');
	}
	if(get_option('dycdc_plugin')=='installed')
	{
		dbDelta($departamento_insert);
		dbDelta($ciudad_insert);
	}
	update_option('dycdc_plugin','activated');
	update_option('dycdc_plugin_version','1.0');
}
?>
