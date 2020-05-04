<?php
/**
 * The Base Widget
 *
 * @package Boostify_Size_Guide
 */

namespace Boostify_Size_Guide;

use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Base Widget
 */
abstract class Base_Widget extends Widget_Base {
	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'ht_bfsg_builder' );
	}
}
