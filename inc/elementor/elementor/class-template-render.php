<?php
/**
 * Entry point for the plugin. Checks if Elementor is installed and activated and loads it's own files and actions.
 *
 * @package Boostify Size Guide Builder
 */
namespace Boostify_Size_Guide;
/**
 * Class boostify_sg_Template_Render
 */
class Template_Render {

	/**
	 * Current theme template
	 *
	 * @var String
	 */
	public $template;

	/**
	 * Instance of Elemenntor Frontend class.
	 *
	 * @var \Elementor\Frontend()
	 */
	private static $elementor_instance;
	/**
	 * Constructor
	 */
	public function __construct() {

		$this->template = get_template();

		if ( defined( 'ELEMENTOR_VERSION' ) && is_callable( 'Elementor\Plugin::instance' ) ) {

			self::$elementor_instance = \Elementor\Plugin::instance();

			// Scripts and styles.
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
			add_shortcode( 'bsg', array( $this, 'render_template' ) );
		}

	}


	/**
	 * Prints the admin notics when Elementor is not installed or activated.
	 */
	protected function elementor_not_available() {

		if ( file_exists( WP_PLUGIN_DIR . '/elementor/elementor.php' ) ) {
			$url = network_admin_url() . 'plugins.php?s=elementor';
		} else {
			$url = network_admin_url() . 'plugin-install.php?s=elementor';
		}
	}

	/**
	 * Enqueue styles and scripts.
	 */
	public function enqueue_scripts() {
		$builder_template = $this->builder_template();
		if ( $builder_template ) {
			if ( class_exists( '\Elementor\Plugin' ) ) {
				$elementor = \Elementor\Plugin::instance();
				$elementor->frontend->enqueue_styles();
				if ( class_exists( '\Elementor\Core\Files\CSS\Post' ) ) {
					$css_file = new \Elementor\Core\Files\CSS\Post( $builder_template );
				} elseif ( class_exists( '\Elementor\Post_CSS_File' ) ) {
					$css_file = new \Elementor\Post_CSS_File( $builder_template );
				}
				$css_file->enqueue();
			}
		}
	}


	/**
	 * Callback to shortcode.
	 *
	 * @param array $atts attributes for shortcode.
	 */
	public function render_template( $atts ) {

		$atts = shortcode_atts(
			array(
				'id'   => '',
				'type' => '',
			),
			$atts,
			'bsg'
		);

		$id   = ! empty( $atts['id'] ) ? intval( $atts['id'] ) : '';
		$type = ! empty( $atts['type'] ) ? $atts['type'] : '';

		if ( empty( $id ) ) {
			return '';
		}

		if ( class_exists( '\Elementor\Core\Files\CSS\Post' ) ) {
			$css_file = new \Elementor\Core\Files\CSS\Post( $id );
		} elseif ( class_exists( '\Elementor\Post_CSS_File' ) ) {
			$css_file = new \Elementor\Post_CSS_File( $id );
		}
		$css_file->enqueue();

		return self::$elementor_instance->frontend->get_builder_content_for_display( $id );
	}

	/**
	 * Callback to shortcode.
	 *
	 * @param array $atts attributes for shortcode.
	 */
	protected function builder_template() {
		$args = array(
			'post_type'           => 'btf_builder',
			'posts_per_page'      => -1,
			'ignore_sticky_posts' => 1,
		);

		$template = new \WP_Query( $args );

		if ( $template->have_posts() ) {
			while ( $template->have_posts() ) {
				$template->the_post();
				return get_the_ID();
			}
			wp_reset_postdata();
		} else {
			return false;
		}
	}

	/**
	 * Header Template.
	 *
	 * @return Header Template.
	 */
	public static function get_header_template() {
		$id = boostify_header_template_id();
		return self::$elementor_instance->frontend->get_builder_content_for_display( $id );
	}

	/**
	 * Footer Template.
	 *
	 * @return Footer Template.
	 */
	public static function get_footer_template() {
		$id = boostify_footer_template_id();
		return self::$elementor_instance->frontend->get_builder_content_for_display( $id );
	}
}
