<?php
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

namespace Boostify_Size_Guide;
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

}

Template::instance();

