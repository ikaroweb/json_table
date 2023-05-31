<?php
/*
Plugin Name: Raketech Json Plugin
Description: Get info from a Json, insert the shortcode [json_table] anywhere to show the list
Version: 1.0
Author: Ikaroweb
*/

// Function to fetch the data from the JSON and generate the table
function json_table_plugin_shortcode() {

    //execute the curl to get json data from api
    $curl = curl_init();
    
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'http://tema.intouchdesign.it/api/data',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $json_data,
        CURLOPT_HTTPHEADER => array(
        'authorization: key-Ayrn&64352nfhZ!urm',
        'Content-Type: application/json',
        ),
    ));
    
    $json_data = curl_exec($curl);
    
    curl_close($curl);

    $data = json_decode( $json_data, true );
    
    // Check if JSON decoding was successful
    if (!$data) {
        return '<p>Error decoding JSON file.</p>';
    }
    
    //get reviews only for 575 key
    $data_filter = $data['toplists']['575'];
    
    //sort array with the rating value in descending mode
    array_multisort(array_map(function($element) {
          return $element['info']['rating'];
      }, $data_filter), SORT_DESC, $data_filter);
      
    
    //list head
    $html .= "<div class=\"d-none d-sm-block heading text-center\">";
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

    //iterate items
    foreach ($data_filter as $item) {
        print_r("Rating: {$item['info']['rating']} <br>");
        print_r($item['info']['features']);
        print_r("<br/> Brand id: {$item['brand_id']} <br/>");
        print_r("Logo url: {$item['logo']}<br/>");
        print_r("Play url: {$item['play_url']}<br/>");
        print_r("Terms and conditions: {$item['terms_and_conditions']}<br/><br/>");

    }
    return $html;
}

add_shortcode('json_table', 'json_table_plugin_shortcode');

//import boostrap and font-awesome css
function wpbootstrap_enqueue_styles() {
    wp_enqueue_style( 'bootstrap', 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/css/bootstrap.min.css' );
    wp_enqueue_style('fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css');

}

?>