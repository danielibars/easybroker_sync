<?php

function slugify($string){
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE',$string))));
    return $slug;
}

function location_ids($location_string){   
    $locations = explode(", ", $location_string);
    $the_parent=0;
    $ids = array();

    foreach(array_reverse($locations) as $location){
        error_log("LOCATION: $location");
        $parent = (array) wp_insert_term(
            $location,   // the term 
            'property_location', // the taxonomy
            array(
                'description' => $location,
                // 'slug'        => slugify($location),
                'parent'      => $the_parent
            )
        );
        if (isset($parent['term_id'])){
            error_log("el term -$location- fue creado con el id: ".$parent['term_id']);
            $the_parent=$parent['term_id'];
        }elseif(isset($parent['error_data']['term_exists'])){
            error_log("el term -$location- ya existía con el id: ".$parent['error_data']['term_exists']);
            $the_parent=$parent['error_data']['term_exists'];
        }
        error_log("parent: ".print_r($parent, true));
        error_log("the_parent: ".print_r($the_parent, true));
        $ids[]=$the_parent;
    }
    
    error_log("ids: ".print_r($ids, true));
    return $ids;

}

function attach_term($term, $category){
    error_log("$term: $category");
    $id = (array) wp_insert_term(
        $term,
        $category,
        array(
            "description"=>$term,
            "slug"=>slugify($term)
        )

    );
    error_log("el ID:".print_r($id,true));
    if (isset($id['term_id'])){
        error_log("el term fue creado con el id: ".$id['term_id']);
        $the_id=$id['term_id'];
    }elseif(isset($id['error_data']['term_exists'])){
        error_log("el term ya existía con el id: ".$id['error_data']['term_exists']);
        $the_id=$id['error_data']['term_exists'];
    }
    error_log(print_r($the_id,true));
    return $the_id;
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