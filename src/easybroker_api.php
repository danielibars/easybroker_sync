<?php

function get_all_properties(){
    // get an option
    $apikey = get_option('easybroker_sync_api_key');
    

    // Create a stream
    $opts = array(
        'http'=>array(
        'method'=>"GET",
        'header'=>"accept: application/json\r\n" .
                    "X-Authorization: ".$apikey."\r\n"
        )
    );
  
    $context = stream_context_create($opts);
  
    // Open the file using the HTTP headers set above
    $ebs_url = 'https://api.easybroker.com/v1/properties?page=1';
    $next = "next";
    $results = array();
    do{
        error_log($ebs_url);
        $file = json_decode(file_get_contents($ebs_url, false, $context), true);
        $ebs_url = $file['pagination']['next_page'];
        
        $results = array_merge($results, $file['content'] );
    }while (!is_null($ebs_url));
    
    
    return $results;    
}