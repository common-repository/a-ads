<?php

//Simple security measure
defined('ABSPATH') or die("You can't access this file");
/*

Maybe I remember to add something here.

*/
/**
 * Adds aads_ads_widget widget.
 */
class aads_ads_widget extends wp_widget {
 
    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
            'aads_ads_widget', // Base ID
            'AADS widget', // Name
            array( 'description' => __( 'Banner ads widget', 'ads_domain' ), ) // Args
           
        );
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */

     
    public function widget( $args, $instance ) {
        extract( $args );
        $title = apply_filters( 'AADS Widget', $instance['title'] );
        
        echo $before_widget;
        if ( ! empty( $title ) ) {
         //   echo $before_title . $title . $after_title;
        }

        // Widget Output
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
        if (isset($ad_sizes[$instance['ad_unit_size']])) {
            $width = $ad_sizes[$instance['ad_unit_size']]['width'];
            $height = $ad_sizes[$instance['ad_unit_size']]['height'];
        } else {
            // Default width and height if ad unit size is not found
            $width = "100px";
            $height = "100px";
        }


        // Choose between adaptive/non-adaptive ad unit.
        if ($instance['ad_unit_size'] == 'adaptive') {
            echo '<iframe
            data-aa='.$instance['ad_unit_ID'].'
            src="//acceptable.a-ads.com/'.$instance['ad_unit_ID'].'"
            scrolling="no"
            style="border:0px;
            padding:0;
            width:100%;
            height:100%;
            overflow:hidden"
            allowtransparency="true">
            </iframe>';
         }
       else {
          echo ('<iframe
          data-aa='.$instance['ad_unit_ID'].'
          src="//ad.a-ads.com/'.$instance['ad_unit_ID'].'?size='.$instance['ad_unit_size'].'"
          scrolling="no"
          style="width:'.$width.';
          height:'.$height.';
          border:0px;
          padding:0;
          overflow:hidden"
          allowtransparency="true">
          </iframe>');
        }
       
       // echo __( 'adaptive' );

        echo $after_widget;
    }
 

 /**
 * Back-end widget form.
 *
 * @see WP_Widget::form()
 *
 * @param array $instance Previously saved values from database.
 */
public function form( $instance ) {
    if ( isset( $instance['title'] ) ) {
        $title = $instance['title'];
    } else {
        $title = __( 'AADS', 'ads_domain' );
    }

    // If $instance['ad_unit_ID'] is set
    if ( isset( $instance['ad_unit_ID'] ) ) {
        $ad_unit_ID = $instance['ad_unit_ID'];
    } else {
        $ad_unit_ID = 1; // Default ad unit ID
    }

    // If $instance['ad_unit_size'] is set
    if ( isset( $instance['ad_unit_size'] ) ) {
        $ad_unit_size = $instance['ad_unit_size'];
    } else {
        $ad_unit_size = 'Adaptive'; // Default ad unit size
    }
/*    
    // Make API request to get ad unit details
    $api_response = wp_remote_get( 'https://a-ads.com/api/v1/ad_units/' . $ad_unit_ID );
    
    // Check if API request was successful
    if ( ! is_wp_error( $api_response ) && wp_remote_retrieve_response_code( $api_response ) === 200 ) {
        $api_data = json_decode( wp_remote_retrieve_body( $api_response ), true );
        
        // Extract size from API response
        if ( isset( $api_data['data']['attributes']['size'] ) ) {
            $ad_unit_size = $api_data['data']['attributes']['size'];
        } else {
            $ad_unit_size = 'default'; // Default size if not found in API response
        }
    } else {
        $ad_unit_size = 'default'; // Default size if API request fails
    }
    */
    ?>

    <p>
        <label for="<?php echo $this->get_field_name( 'title' ); ?>">
            <?php _e( 'Title:' ); ?>
        </label>
        <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
    </p>

    <p>
        <label for="<?php echo $this->get_field_name( 'ad_unit_ID' ); ?>">
            <?php _e( 'Ad Unit ID:' ); ?>
        </label>
        <input class="widefat" id="<?php echo $this->get_field_id( 'ad_unit_ID' ); ?>" name="<?php echo $this->get_field_name( 'ad_unit_ID' ); ?>" type="number" value="<?php echo esc_attr( $ad_unit_ID ); ?>" />
    </p>

    <input type="hidden" name="<?php echo $this->get_field_name( 'ad_unit_size' ); ?>" value="<?php echo esc_attr( $ad_unit_size ); ?>" />


    <?php
    }
 
    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( !empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        $instance['ad_unit_ID'] = ( !empty( $new_instance['ad_unit_ID'] ) ) ? strip_tags( $new_instance['ad_unit_ID'] ) : '';
        
        // Make API request to get ad unit details
        $api_response = wp_remote_get( 'https://a-ads.com/api/v1/ad_units/' . $instance['ad_unit_ID'] );
    
        // Check if API request was successful
        if ( ! is_wp_error( $api_response ) && wp_remote_retrieve_response_code( $api_response ) === 200 ) {
            $api_data = json_decode( wp_remote_retrieve_body( $api_response ), true );
            
            if (isset($api_data['data']['attributes']['size'])) {
                $instance['ad_unit_size'] = $api_data['data']['attributes']['size'];
            } else {
                // Handle case where the key doesn't exist in the API response
            }
            
        } else {
            // Handle API request error
            // For example:
            // $instance['ad_unit_size'] = 'Error: API request failed';
        }

        return $instance; // Return the updated instance array
    }
    
 

} // class aads_ads_widget
 
?>