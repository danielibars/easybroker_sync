<?php 
/*
Plugin Name: EasyBroker Sync
Description: Plugin para mostrar cómo funciona el cron de WordPress
Version:     1.0
Author:      Daniel Ibars Guerrero
Author URI:  https://www.crowdbarkers.com
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/
require_once __DIR__ . '/src/settings_menu.php';







// Activación del Plugin
register_activation_hook( __FILE__, 'ibars_plugin_activation' );
function ibars_plugin_activation() {
    if( ! wp_next_scheduled( 'ibars_my_cron_hook' ) ) {
        wp_schedule_event( current_time( 'timestamp' ), 'EasyBrokerSync', 'ibars_my_cron_hook' );
    }
}

// Desactivación del plugin
register_deactivation_hook( __FILE__, 'ibars_plugin_desativation' );
function ibars_plugin_desativation() {
    wp_clear_scheduled_hook( 'ibars_my_cron_hook' );
}

// Acción personalizada
add_action( 'ibars_my_cron_hook', 'ibars_my_process' );
function ibars_my_process() {
	// Create a stream
    $opts = array(
        'http'=>array(
        'method'=>"GET",
        'header'=>"accept: application/json\r\n" .
                    "X-Authorization: APIKEY\r\n"
        )
    );
  
    $context = stream_context_create($opts);
  
    // Open the file using the HTTP headers set above
    $file = json_decode(file_get_contents('https://api.easybroker.com/v1/properties', false, $context), true);
       

    foreach($file['content'] as $property ){
        error_log($property['title']);
        $my_post = array(
            'post_title'=>$property['title'],
            'post_type'=>'post',
            'post_status'=>'publish',
            'post_author'   => 1
        );
        wp_insert_post($my_post);
    }

}


//Registro de intervalos 
add_filter( 'cron_schedules', 'ibars_my_custom_schedule');
function ibars_my_custom_schedule( $schedules ) {
     $schedules['EasyBrokerSync'] = array(
        'interval' => 3600,
        'display' =>__('1 hora','ibars_lang_domain')
     );
     return $schedules;
}

