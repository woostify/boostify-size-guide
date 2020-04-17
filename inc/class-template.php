<?php
namespace Boostify_Size_Guide;
/**
 * Comments
 *
 * Handle comments (reviews and order notes).
 *
 * @package Boostify_Size_Guide_Template
 *
 * Written by pcd
 */

defined( 'ABSPATH' ) || exit;
/**
 * Boostify Size Guide Template Class.
 */

class Template {

	private static $instance;

	/**
	 * Post ID
	 *
	 * @var int
	 */
	public $post_id;

	/**
	 * Post type
	 *
	 * @var String
	 */
	public $post_type;

	/**
	 *  Initiator
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Hook in methods.
	 */
	public function __construct() {
		add_filter( 'single_template', array( $this, 'single_template' ) );
	}


	public function single_template( $single_template ) {
		if ( 'btfsg_builder' == get_post_type() ) { // phpcs:ignore
			$single_template = BOOSTIFY_SIZE_GUIDE_PATH . 'templates/sg.php';
		}
        
		return $single_template;
	}
}

Template::instance();

