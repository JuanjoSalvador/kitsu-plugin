<?php

/*
Plugin Name: Kitsu Shortcode
Plugin URI: http://github.com/JuanjoSalvador/kitsu-wp-shortcode
Description: Provides a shortcode to insert Kitsu links on your posts
Version: 0.1
Author: Juanjo Salvador
Author URI:	http://juanjosalvador.me
License: GPLv3
*/

add_action('init', 'kitsu_shortcode');
add_action('wp_enqueue_scripts', 'kitsu_shortcode_stylesheet');
add_shortcode('kitsu', 'shortcode');

function kitsu_shortcode() {
    function shortcode($atts) {
        $uri    = $atts['url'];
        $search = explode("/", $uri);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Accept: application/vnd.api+json',
            'Content-Type: application/vnd.api+json'));
        curl_setopt($curl, CURLOPT_URL, 'https://kitsu.io/api/edge/' . $search[3] . '?filter[slug]=' . $search[4]);

        $data = curl_exec($curl);
        $resArr = array();
        $resArr = json_decode($data, true);

        $attributes = $resArr['data'][0]['attributes'];
        $type       = $resArr['data'][0]['type'];

        $image_uri = $attributes['posterImage']['tiny'];
        $title     = $attributes['titles']['en_jp'];
        $slug      = $attributes['slug'];

        if ($uri) {
            echo "<div id='kitsu_block'>
                    <img src='$image_uri' align='left' style='margin-right: 1em;' />
                    <h2>$title</h2>
                    <a href='$uri'>
                        View on Kitsu
                    </a>
                </div>";
        }
    }
}

function kitsu_shortcode_stylesheet() {
    wp_register_style('kitsu-shortcode-style', plugins_url('css/kitsu_shortcode_stylesheet.css', __FILE__), array());
    wp_enqueue_style('kitsu-shortcode-style');
}
?>