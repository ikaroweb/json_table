<?php
/*
Plugin Name: Raketech Json Plugin
Description: Get info from a Json, insert the shortcode [json_table] anywhere to show the table
Version: 1.0
Author: Ikaroweb
*/

// Function to fetch the data from the JSON and generate the table
function json_table_plugin_shortcode() {
       
}

add_shortcode('json_table', 'json_table_plugin_shortcode');


?>