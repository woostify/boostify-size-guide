<?php
/**
 * Plugin Name: Boostify Size Guide Builder
 * Plugin URI: https://boostifythemes.com
 * Description: Create Size Guide for your site using Elementor Page Builder.
 * Version: 1.0.0
 * Author: Woostify
 * Author URI: https://woostify.com
 */

define( 'BOOSTIFY_SIZE_GUIDE_PATH', plugin_dir_path( __FILE__ ) );
define( 'BOOSTIFY_SIZE_GUIDE_URL', plugin_dir_url( __FILE__ ) );
define( 'BOOSTIFY_SIZE_GUIDE_VER', '1.0.0' );

require_once BOOSTIFY_SIZE_GUIDE_PATH . 'inc/class-boostify-size-guide-builder.php';