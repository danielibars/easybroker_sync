<?php

function slugify($string){
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string)));
    return $slug;
}

function location_ids($location_string){

    error_log("UBICACION: $location_string");
    $locations = explode(", ", $location_string);
    $parent=0;
    $ids = array();
    foreach(array_reverse($locations) as $location){
        error_log("beforetrim: $location");
        $parent = wp_insert_term(
            $location,   // the term 
            'property_location', // the taxonomy
            array(
                'description' => $location,
                'slug'        => slugify($location),
                'parent'      => $parent,
            )
        );
        error_log("parentid: $parent");
        $ids[]=$parent;
    }
    error_log("ids: ".print_r($ids));
    return $ids;

}