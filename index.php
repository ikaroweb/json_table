<?php
/*
Plugin Name: Raketech Json Plugin
Description: Get info from a Json, insert the shortcode [json_table] anywhere to show the list
Version: 1.0
Author: Ikaroweb
*/

// Function to fetch the data from the JSON and generate the table
function json_table_plugin_shortcode() {

    // Get data from url JSON file
    $json_data = file_get_contents('https://tema.intouchdesign.it/data.json');
    
    $data = json_decode( $json_data, true );
    
    // Check if JSON decoding was successful
    if (!$data) {
        return '<p>Errore nella decodifica del file JSON.</p>';
    }
    
    //get reviews only for 575 key
    $data_filter = $data['toplists']['575'];

    //get reviews only for 575 key
    $data_filter = $data['toplists']['575'];
    
    //sort array with the rating value in descending mode
    array_multisort(array_map(function($element) {
          return $element['info']['rating'];
      }, $data_filter), SORT_DESC, $data_filter);
      
    print_r($data_filter);
}

add_shortcode('json_table', 'json_table_plugin_shortcode');


?>