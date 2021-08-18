<?php
/* Include all js and css files */
function dycdc_embedCssJs() {
	wp_enqueue_script( 'dycdc_auto-script', DYCDC_URL . 'assets/js/script.js', array( 'jquery' ) );
	wp_localize_script( 'dycdc_auto-script', 'dycdc_ajax', array( 'ajax_url' => admin_url('admin-ajax.php'),'nonce'=>wp_create_nonce('dycdc_ajax_nonce')) );
	}
add_action( 'wp_enqueue_scripts', 'dycdc_embedCssJs' );
