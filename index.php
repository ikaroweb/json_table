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
        //get rating and generate stars with font-awesome
        $stars = "";
        
        for($i = 0; $i < $item['info']['rating']; $i++){            
            $stars .= "<i class=\"fa-solid fa-star\" style=\"color: #ffd500;\"></i>";
        }
        
        //iterate features and create the list
        $features = "";
        foreach($item['info']['features'] as $feature) {
             $features .= "<li class=\"list-group-item features\"><i class=\"fa-solid fa-check\"></i> $feature </li>";  
        } 
        
        $html .= "<div class=\"row mb-3 mt-3\">";

        //1st column show logo e review link
        $html .= "<div class=\"col-lg-3 col-md-6 col-12 text-center mb-3\">";
        $html .= '<a target="_blank" rel="noopener noreferrer" href="https://site_url/'.$item['brand_id'] .'"><div><img src="' . $item['logo'] . '" class="easeload" onload="this.style.opacity=1"></div></a><br/><a target="_blank" rel="noopener noreferrer"  class="review_link" href="https://site_url/' . $item['brand_id'] . '"/>Review</a>';
        $html .= "</div>";
        
        //2nd column show bonus info
        $html .= "<div class=\"col-lg-3 col-md-6 col-6 text-center\">";
        $html .= $stars .'<br/><p class="bonus">' . $item['info']['bonus'] . '</p>';
        $html .= "</div>";
        
        //3rd column show features list
        $html .= "<div class=\"col-lg-3 col-md-6 col-6\">";
        $html .= $features;
        $html .= "</div>";
        
        //4th column show play button and T&C
        $html .= "<div class=\"col-lg-3 col-md-6 col-12 text-center\">";
        $html .= '<a target="_blank" rel="noopener noreferrer" href="' . $item['play_url'] . '" class="btn btn-success btn_play btn-lg">PLAY NOW</a><br/><br/>'. str_replace("<a ", '<a target="_blank rel="noopener noreferrer" ' , $item['terms_and_conditions']);
        $html .= "</div>";
        
        $html .= "</div>";
        
        // line separator
        $html .= "<hr style=\"height:3px;background-color:#000;\">";
        //end iterate
    } //end foreach
    
    //close main div
    $html .= "</div>";
    
    return $html;
}

add_shortcode('json_table', 'json_table_plugin_shortcode');

//import boostrap and font-awesome css
function wpbootstrap_enqueue_styles() {
    wp_enqueue_style( 'bootstrap', 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/css/bootstrap.min.css' );
    wp_enqueue_style('fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css');

}

add_action( 'wp_enqueue_scripts', 'wpbootstrap_enqueue_styles' );

//import the style.css file
function callback_for_setting_up_scripts() {
    wp_register_style( 'raketech_json', plugins_url( 'css/style.css' , __FILE__ ) );    
    wp_enqueue_style( 'raketech_json' );
}

add_action( 'wp_enqueue_scripts', 'callback_for_setting_up_scripts' );

?>