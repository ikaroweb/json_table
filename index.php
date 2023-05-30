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
      
    
    //list head
    $html .= "<div class=\"d-none d-sm-block heading text-center \" >";
    $html .= "<div class=\"row\">";
    $html .= "<div class=\"col\">";
    $html .= "<p>Casino</p>";
    $html .= "</div>";
    $html .= "<div class=\"col\">";
    $html .= "<p>Bonus</p>";
    $html .= "</div>";
    $html .= "<div class=\"col\">";
    $html .= "<p>Features</p>";
    $html .= "</div>";
    $html .= "<div class=\"col\">";
    $html .= "<p>Play</p>";
    $html .= "</div>";
    $html .= "</div>";
    $html .= "</div>";
    //end list head
    
    return $html;
}

add_shortcode('json_table', 'json_table_plugin_shortcode');

//import boostrap and font-awesome css
function wpbootstrap_enqueue_styles() {
    wp_enqueue_style( 'bootstrap', 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/css/bootstrap.min.css' );
    wp_enqueue_style('fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css');

}

?>