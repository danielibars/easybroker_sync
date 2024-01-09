<?php
/*
Plugin Name: EasyBroker Sync
Description: Crea un Custom Post Type y descarga las propiedades de EasyBroker
Version:     1.0
Author:      Daniel Ibars Guerrero
Author URI:  https://www.danielibars.com
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

require_once __DIR__ . '/src/settings_menu.php';
require_once __DIR__ . '/src/easybroker_api.php';
require_once __DIR__ . '/src/properties_cpt.php';
require_once __DIR__ . '/src/taxonomies.php';
require_once __DIR__ . '/src/wp_utils.php';

// Activación del Plugin
register_activation_hook(__FILE__, 'ibars_plugin_activation');
function ibars_plugin_activation()
{
    error_log("Activando cron");
    if (!wp_next_scheduled('easybroker_sync_cron_hook')) {
        wp_schedule_event(current_time('timestamp'), 'EasyBrokerSync', 'easybroker_sync_cron_hook');
    }
}

// Desactivación del plugin
register_deactivation_hook(__FILE__, 'ibars_plugin_desativation');
function ibars_plugin_desativation()
{
    error_log("Desactivando cron");
    wp_clear_scheduled_hook('easybroker_sync_cron_hook');
}


// Acción personalizada
add_action('easybroker_sync_cron_hook', 'easybroker_sync_process');
function easybroker_sync_process()
{
    error_log("getting all properties");
    $properties = get_all_properties();
    error_log("cantidad de propiedades " . count($properties));

    // Insertar Actualizar
    $all_public_ids = array();
    foreach ($properties as $property) {
        //Buscar el post_id desde public_id
        $all_public_ids[] = $property['public_id'];
        error_log(print_r($property, true));



        error_log("buscando propiedad: " . $property['public_id']);
        $posts = find_property_by_meta_field('property', 'public_id', $property['public_id']);


        $my_post = array(
            'post_title' => ucwords(strtolower(preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $property['title']))),
            'post_type' => 'property',
            'post_content' => $property['ebs_details']['description'],
            'post_status' => 'publish',
            'post_author'   => get_option('easybroker_sync_author_id')
        );
        if (count($posts) > 0) { //Si lo encuentro hago update
            $post = $posts[0];
            $my_post['ID'] = $post->ID;
            error_log("UPDATE: " . $property['public_id'] . " - " . $post->ID);
        } else { //Si no lo encuentro hago insert
            error_log("INSERT: " . $property['public_id']);
        }


        $post_id = wp_insert_post($my_post);

        // Manejo de la imagen destacada

        if (!has_post_thumbnail($post_id)) {
            include_once("src/wp_insert_attachment_from_url.php");

            if ($property['title_image_full'] !== null) {
                $imageurl = explode("?", $property['title_image_full'])[0];
                $attach_id = wp_insert_attachment_from_url($imageurl, $post_id);
                set_post_thumbnail($post_id, $attach_id);
            } else {
                error_log("No se encontro imagen destacada para: " . $property['public_id']);
            }
        }

        $location = location_ids($property['location']);
        wp_set_object_terms($post_id, $location, 'property_location');

        //Manejo de property_type
        $property_type = attach_term($property['property_type'], "property_type");
        error_log("Para $post_id se inserta el property_type: $property_type");
        $result_property_type = wp_set_object_terms($post_id, $property_type, 'property_type');
        //error_log("$result_property_type");


        // Manejo de tags
        $operation_type_tag = attach_term($property['operations'][0]['type'], "property_tag");
        $result_operation_type = wp_set_object_terms($post_id, $operation_type_tag, 'property_tag');

        /// Manejo del precio USD and MXN
        $precio_dolar = 20;
        $amount = $property['operations'][0]['amount'];
        $formatted_amount = $property['operations'][0]['formatted_amount'];
        $currency = $property['operations'][0]['currency'];

        if ($currency == 'USD') {
            $amount = $amount * $precio_dolar;
        } elseif ($currency == 'MXN') {
            $formatted_amount = $currency . $formatted_amount;
        }



        $my_meta = array(
            "age" => $property['ebs_details']['age'],
            "agent" => $property['agent'],
            "bathrooms" => $property['bathrooms'],
            "bedrooms" => $property['bedrooms'],
            "construction_size" => $property['construction_size'],
            "floors" => $property['ebs_details']['floors'],
            "half_bathrooms" => $property['ebs_details']['half_bathrooms'],
            "internal_id" => $property['ebs_details']['internal_id'],
            "latitude" => $property['ebs_details']['location']['latitude'],
            "loctation" => $property['location'],
            "longitude" => $property['ebs_details']['location']['longitude'],
            "lat_lng" => $property['ebs_details']['location']['latitude'] . "," . $property['ebs_details']['location']['longitude'],
            "property_images" => $property['ebs_details']['property_images'],
            "lot_length" => $property['ebs_details']['lot_length'],
            "lot_size" => $property['lot_size'],
            "lot_width" => $property['ebs_details']['lot_width'],
            "operation_amount" => $amount,
            "operation_currency" => $currency,
            "operation_formatted_amount" => $formatted_amount,
            "operation_type" => $property['operations'][0]['type'],
            "parking_spaces" => $property['parking_spaces'],
            "property_type" => $property['property_type'],
            "public_id" => $property['public_id'],
            "show_prices" => $property['show_prices'],
            "title_image_full" => $property['title_image_full'],
            "title_image_thumb" => $property['title_image_thumb'],
            "features" => json_encode($property['ebs_details']['features']),
            "property_images" => json_encode($property['ebs_details']['property_images'])
        );

        foreach ($my_meta as $meta_key => $meta_value) {
            if (!add_post_meta($post_id, $meta_key, $meta_value, true)) {
                update_post_meta($post_id, $meta_key, $meta_value);
            }
        }
    }

    // Eliminar propiedades que ya no estan en EasyBroker
    $args = array(
        'numberposts' => -1,
        'post_type'   => 'property'
    );
    $wp_properties = get_posts($args);
    foreach ($wp_properties as $wp_prop) {
        $pub_id = get_post_meta($wp_prop->ID, 'public_id', true);
        if (in_array($pub_id, $all_public_ids)) {
            error_log("post OK");
        } else {
            error_log("POST DELETED:", $wp_prop->ID);
            wp_delete_post($wp_prop->ID, true);
        }
    }
}


//Registro de intervalos 
add_filter('cron_schedules', 'ibars_my_custom_schedule');
function ibars_my_custom_schedule($schedules)
{
    $schedules['EasyBrokerSync'] = array(
        'interval' => 43200,
        'display' => __('Dos veces por día', 'ibars_lang_domain')
    );
    return $schedules;
}



//External image
// Add External Link to Featured Image with Custom Field

add_filter('post_thumbnail_html', 'add_external_link_on_page_post_thumbnail', 10);
function add_external_link_on_page_post_thumbnail($html)
{

    global $post;
    $name = get_post_meta($post->ID, 'title_image_full', true);
    if ($name) {
        $html = '<a href="' . ($name) . '" target="_blank" >' . $html . '</a>';
    }

    return $html;
}
