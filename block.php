<?php

//Simple security measure
defined('ABSPATH') or die("You can't access this file");

class aads_ads_block_functions {

    // Cache for ad unit sizes
    private $ad_unit_sizes_cache = array();

    function __construct() {
        add_action('init', array($this, 'block_assets'));
    }

    // Enqueue Block Assets
    function block_assets() {
        wp_enqueue_script(
            'aadsblockscript', // Handle
            plugin_dir_url(__FILE__) . 'build/index.js', // Script URL
            array('wp-blocks', 'wp-element', 'wp-editor')//, // Dependencies
          //  true // Enqueue script in footer
        );
        
        register_block_type('aadsplugin/aads-block',array(
            'editor_script' => 'aadsblockscript',
            'render_callback' => array($this, 'render_aads_ads_block')
        ));
    }

    //Get ad unit size from API function
    function get_ad_unit_size($ad_unit_ID) {
        // Check if ad unit size is already cached
        if (isset($this->ad_unit_sizes_cache[$ad_unit_ID])) {
            return $this->ad_unit_sizes_cache[$ad_unit_ID];
        }

        // Make API request to get ad unit details
        $api_response = wp_remote_get( 'https://a-ads.com/api/v1/ad_units/' . $ad_unit_ID );
    
        // Check if API request was successful
        if ( ! is_wp_error( $api_response ) && wp_remote_retrieve_response_code( $api_response ) === 200 ) {
            $api_data = json_decode( wp_remote_retrieve_body( $api_response ), true );
            
            if (isset($api_data['data']['attributes']['size'])) {
                $ad_unit_size = $api_data['data']['attributes']['size'];

                // Cache the ad unit size if it's not the default value
                $this->ad_unit_sizes_cache[$ad_unit_ID] = $ad_unit_size;
            } else {
                // Handle case where the key doesn't exist in the API response
                $ad_unit_size = 'Adaptive';
            }
            
        } else {
            // Handle API request error
            // For example:
            // $ad_unit_size = 'Error: API request failed';
            $ad_unit_size = 'Adaptive';
        }

        return $ad_unit_size;
    }

    // Render Block
    function render_aads_ads_block( $attributes ) {
        $title = isset( $attributes['title'] ) ? $attributes['title'] : 'AADS';
        $ad_unit_ID = isset( $attributes['adUnitID'] ) ? $attributes['adUnitID'] : 1;
        $ad_unit_size = $this->get_ad_unit_size($ad_unit_ID);
    
        // Define ad unit sizes and their corresponding width and height
        $ad_sizes = array(
            '120x60' => array('width' => '120px', 'height' => '60px'),
            '120x600' => array('width' => '120px', 'height' => '600px'),
            '125x125' => array('width' => '125px', 'height' => '125px'),
            '160x600' => array('width' => '160px', 'height' => '600px'),
            '200x200' => array('width' => '200px', 'height' => '200px'),
            '240x400' => array('width' => '240px', 'height' => '400px'),
            '250x250' => array('width' => '250px', 'height' => '250px'),
            '300x250' => array('width' => '300px', 'height' => '250px'),
            '300x600' => array('width' => '300px', 'height' => '600px'),
            '320x50' => array('width' => '320px', 'height' => '50px'),
            '320x100' => array('width' => '320px', 'height' => '100px'),
            '336x280' => array('width' => '336px', 'height' => '280px'),
            '468x60' => array('width' => '468px', 'height' => '60px'),
            '728x90' => array('width' => '728px', 'height' => '90px'),
            '970x90' => array('width' => '970px', 'height' => '90px'),
            '970x250' => array('width' => '970px', 'height' => '250px')
        );
    
        // Set width and height based on ad unit size
        if (isset($ad_sizes[$ad_unit_size])) {
            $width = $ad_sizes[$ad_unit_size]['width'];
            $height = $ad_sizes[$ad_unit_size]['height'];
        } else {
            // Default width and height if ad unit size is not found
            $width = "300px";
            $height = "250px";
        }
    
        // Choose between adaptive/non-adaptive ad unit.
        $output = '';
        if ($ad_unit_size == 'Adaptive') {
            $output .= '<iframe
                data-aa="' . $ad_unit_ID . '"
                src="//acceptable.a-ads.com/' . $ad_unit_ID . '"
                scrolling="no"
                style="border:0px;
                padding:0;
                width:100%;
                height:100%;
                overflow:hidden"
                allowtransparency="true">
                </iframe>';
        } else {
            $output .= '<iframe
                data-aa="' . $ad_unit_ID . '"
                src="//ad.a-ads.com/' . $ad_unit_ID . '?size=' . $ad_unit_size . '"
                scrolling="no"
                style="width:' . $width . ';
                height:' . $height . ';
                border:0px;
                padding:0;
                overflow:hidden"
                allowtransparency="true">
                </iframe>';
        }
    
        $output = '<div style="text-align: center;">' . $output . '</div>';
        return $output;
    }

}

//Check if class exist, if yes create a new instance.
if (class_exists('aads_ads_block_functions')) 
{
$blockFunctions = new aads_ads_block_functions();
}


?>