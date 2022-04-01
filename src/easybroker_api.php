<?php

function get_all_properties()
{
    // get an option
    $apikey = get_option('easybroker_sync_api_key');
    error_log("getting API KEY");

    // Create a stream
    $opts = array(
        'http' => array(
            'method' => "GET",
            'header' => "accept: application/json\r\n" .
                "X-Authorization: " . $apikey . "\r\n"
        )
    );
    $context = stream_context_create($opts);
    error_log("setup HTTP REQUEST");

    // Open the file using the HTTP headers set above
    error_log("start");
    $ebs_url = 'https://api.easybroker.com/v1/properties';
    $page = "?page=1";
    $results = array();
    $pagination_url = "$ebs_url$page";
    error_log("initial PAGINATION_URL: -" . $pagination_url . "-");
    do {
        $file = json_decode(file_get_contents($pagination_url, false, $context), true);
        $pagination_url = $file['pagination']['next_page'];
        error_log("next PAGINATION_URL: -" . $pagination_url . "-");
        $results = array_merge($results, $file['content']);
    } while (!is_null($pagination_url));
    error_log("Terminando paginacion");
    error_log("iniciando propiedad por propiedad");
    $las_propiedades = array();
    foreach ($results as $property) {

        error_log($property['public_id']);
        $property_url = $ebs_url . "/" . $property['public_id'];
        error_log("PROPERTY_URL: -" . $property_url . "-");
        $p = json_decode(file_get_contents($property_url, false, $context), true);
        //error_log("THIS_ARE_THE_TAGS: " . print_r($p['tags'], true));
        if (get_option('easybroker_sync_tag_filter') && get_option('easybroker_sync_tag_filter') != '' && in_array(get_option('easybroker_sync_tag_filter'), $p['tags'])) {
            $property['ebs_details'] = $p;
            $las_propiedades[] =  $property;
        }
    }
    return $las_propiedades;
}
