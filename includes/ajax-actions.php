<?php
add_action( 'wp_ajax_dycdc_get_ciudades','dycdc_get_ciudades' );
add_action( 'wp_ajax_nopriv_dycdc_get_ciudades", "dycdc_get_ciudades' );
function dycdc_get_ciudades()
{
  check_ajax_referer( 'dycdc_ajax_nonce', 'nonce_ajax' );
  global $wpdb;
  if(isset($_POST["sid"]))
  {
    $sid=sanitize_text_field($_POST["sid"]);
  }
  $ciudades = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM ".$wpdb->base_prefix."dycdc_ciudades where departamento_id=%1s order by name asc", $sid));
  echo json_encode($ciudades);
  wp_die();
}
?>
