<?php

function location_ids($location_string){
    $locations = str_split($location_string);
    $parent='';
    $ids = array();
    foreach(array_reverse($locations) as $location){
        $location = trim($location);
        $tx = array(
            "taxonomy"=>"at_biz_dir-location",
            "cat_name" => $location,
            'category_parent' => $parent
        );
        $parent = wp_insert_category($tx);
        $ids[]=$parent;
    }
    return $ids;

}