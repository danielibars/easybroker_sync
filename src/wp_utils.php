<?php

function slugify($string){
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE',$string))));
    return $slug;
}

function location_ids($location_string){

    error_log("UBICACION: $location_string");
    $locations = explode(", ", $location_string);
    $the_parent=0;
    $ids = array();
    foreach(array_reverse($locations) as $location){
        error_log("beforetrim: $location");

       

        $parent = (array) wp_insert_term(
            $location,   // the term 
            'property_location', // the taxonomy
            array(
                'description' => $location,
                'slug'        => slugify($location),
                'parent'      => $the_parent,
            )
        );
        if (array_key_exists('term_id',$parent)){
            error_log("el term fue creado con el id: ".$parent['term_id']);
            $the_parent=$parent['term_id'];
        }elseif(isset($parent['error_data']['term_exists'])){
            error_log("el term ya existía con el id: ".$parent['error_data']['term_exists']);
            $the_parent=$parent['error_data']['term_exists'];
        }
        error_log("parent: ".print_r($parent['term_id'], true));
        error_log("the_parent: ".print_r($the_parent, true));
        $ids[]=$the_parent;
    }
    error_log("ids: ".print_r($ids, true));
    return $ids;

}


function find_property_by_meta_field($post_type, $meta_key, $meta_value){
    $search_args = array(
        'numberposts'=>1,
        'post_type'=>$post_type,
        'meta_key'=>$meta_key,
        'meta_value'=>$meta_value
    );
    $posts = get_posts($search_args);
    return $posts;
}