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
$POST_TYPE = "at_biz_dir";



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
            "_custom-text"=>$property['public_id'],
            "_edit_lock"=>"",
            "_directory_type"=>3,
            "_edit_last"=>get_option('easybroker_sync_author_id'),
            "_atbd_listing_pricing"=>"",
            "_price"=>$amount,
            "_custom-text-2"=>$formatted_amount,
            "_custom-select"=>$currency,
            "_image"=>$property['title_image_full'],
            "_price_range"=>"",
            "_tax_input[at_biz_dir-location][]"=>"",
            "_tax_input[at_biz_dir-tags][]"=>"",
            "_admin_category_select[]"=>"",
            "_atbdp_post_views_count"=>"",
            "_manual_lat"=>"",
            "_hide_map"=>"",
            "_manual_lng"=>"",
            "_map"=>"",
            "_listing_img"=>"",
            "_listing_prv_img"=>"",
            "_tagline"=>"",
            "_address"=>$property['location'],
            "_featured"=>"",
            "_never_expire"=>1,
            "_expiry_date"=>"",
            "_listing_status"=>"post_status"
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

