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
//require_once __DIR__ . '/src/wp_utils.php';
require_once __DIR__ . '/src/activation.php';
require_once __DIR__ . '/src/settings_menu.php';
require_once __DIR__ . '/src/easybroker_api.php';
require_once __DIR__ . '/src/properties_cpt.php';




// Acción personalizada
add_action( 'easybroker_sync_cron_hook', 'easybroker_sync_process' );
function easybroker_sync_process() {

	$properties = get_all_properties();


    // Insertar Actualizar
    foreach($properties as $property ){
        //Buscar el post_id desde public_id
        $search_args = array(
            'numberposts'=>1,
            'post_type'=>'property',
            'meta_key'=>'_custom-text',
            'meta_value'=>$property['public_id']
        );
        $posts = get_posts($search_args);

        $my_post = array(
            'post_title'=>ucwords(strtolower(preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $property['title']))),
            'post_type'=>'property',
            'post_status'=>'publish',
            'post_author'   => get_option('easybroker_sync_author_id')
        );
        if (count($posts) > 0){ //Si lo encuentro hago update
            $post = $posts[0];
            $my_post['ID'] = $post->ID;
            error_log("UPDATE: ".$property['public_id']." - ".$post->ID);
        } else{ //Si no lo encuentro hago insert
            error_log("INSERT: ".$property['public_id']);
        }
        $post_id = wp_insert_post($my_post);


        /// Manejo del precio USD and MXN
        $precio_dolar = 20;
        $amount = $property['operations'][0]['amount'];
        $formatted_amount = $property['operations'][0]['formatted_amount'];
        $currency = $property['operations'][0]['currency'];

        if ($currency == 'USD'){
            $amount = $amount * $precio_dolar;
        }elseif ($currency == 'MXN'){
            $formatted_amount = $currency.$formatted_amount;
        }


        $my_meta = array(
            "public_id"=>$property['public_id'],
            "title_image_full"=>$property['title_image_full'],
            "title_image_thumb"=>$property['title_image_thumb'],
            "location"=>$property['location'],
            "operation_type"=>$property['operations'][0]['type'],
            "operation_amount"=>$amount,
            "operation_currency"=>$currency,
            "operation_formatted_amount"=>$formatted_amount,
            "bedrooms"=>$property['bedrooms'],
            "bathrooms"=>$property['bathrooms'],
            "parking_spaces"=>$property['parking_spaces'],
            "property_type"=>$property['property_type'],
            "lot_size"=>$property['lot_size'],
            "construction_size"=>$property['construction_size'],
            "agent"=>$property['agent'],
            "show_prices"=>$property['show_prices']

            
        );
        foreach($my_meta as $meta_key => $meta_value){
            if ( ! add_post_meta( $post_id, $meta_key, $meta_value, true ) ) { 
                update_post_meta ( $post_id, $meta_key, $meta_value );
             }
        }

   
        

    }

    // Eliminar propiedades que ya no estan en EasyBroker

    


}


//Registro de intervalos 
add_filter( 'cron_schedules', 'ibars_my_custom_schedule');
function ibars_my_custom_schedule( $schedules ) {
     $schedules['EasyBrokerSync'] = array(
        'interval' => 43200,
        'display' =>__('Dos veces por día','ibars_lang_domain')
     );
     return $schedules;
}

