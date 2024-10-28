<?php
/**
 * @package AADS
 */

/**
 * Plugin Name:       AADS
 * Plugin URI:        https://aads.com
 * Description:       Simple advertising blocks integration.
 * Version:           2.1
 * Requires at least: 5.2
 * Requires PHP:      5.2
 * Author:            AADS
 * Author URI:        https://aads.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       code_integration
 * Domain Path:       /AADS
 * Developed By:      Micheal George
 */
 
/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
Copyright (C) 2024 AADS.
*/

//Simple security measure
defined('ABSPATH') or die("You can't access this file");

//Include widget.php
require_once(plugin_dir_path(__FILE__).'/widget.php' );

//Include block.php
require_once(plugin_dir_path(__FILE__).'/block.php' );

//Plugin Core
class aads_core_functions
{
    function __construct() {
        add_action( 'widgets_init', array($this,'register_ads_widget')); 
    }

   function activate() {
        //Run on activation of plugin
        flush_rewrite_rules();
    }

    function deactivate() {
        //Run on deactivation of plugin
        //unregister_widget('ads_widget');
        flush_rewrite_rules();
    }

    function uninstall() {
        //Run on unstallation of plugin
        flush_rewrite_rules();
    }

    //Register wiget
    function register_ads_widget() { 
        register_widget( 'aads_ads_widget' ); 
    }

    //Add Extra Scripts
    function extra_code() { 
        //
        }
    
 }

// Instantiate the class
if (class_exists('aads_core_functions')) {
    $CoreFunctions = new aads_core_functions();

    //activation() hook
    register_activation_hook( __FILE__, array($CoreFunctions, 'activate'));

    //deactivate() hook
    register_deactivation_hook( __FILE__, array($CoreFunctions, 'deactivate'));
}
//uninstall() hook
register_uninstall_hook( __FILE__, 'aads_uninstall_function');

function aads_uninstall_function() {
    $core_functions = new aads_core_functions();
    $core_functions->uninstall();
}

?>
