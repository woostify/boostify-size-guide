<?php
/**
 * Class Size Guide Elementor
 *
 * Main Plugin class
 * @since 1.2.0
 */

namespace Boostify_Size_Guide;

class Elementor_Sg {
	/**
	 * Instance
	 *
	 * @since 1.2.0
	 * @access private
	 * @static
	 *
	 * @var Plugin The single instance of the class.
	 */
	private static $instance = null;


	private $modules_manager;
	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 1.2.0
	 * @access public
	 *
	 * @return Plugin An instance of the class.
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Register custom widget categories.
	 */
	public function add_elementor_widget_categories( $elements_manager ) {
		$elements_manager->add_category(
			'ht_hf_builder',
			array(
				'title' => esc_html__( 'Boostify Size Guide', 'boostify' ),
			)
		);
	}


	/**
	 * Widget Class
	 */
	public function get_widgets() {
		$widgets = array(
			'Image_Retina',
            'Size_Guide',
		);

		return $widgets;
	}

	/**
	 * widget_scripts
	 *
	 * Load required plugin core files.
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function widget_scripts() {
        // Size Guide
        wp_register_script(
            'boostify-hf-size-guide',
            BOOSTIFY_SIZE_GUIDE_URL . 'assets/js/size-guide' . boostify_size_guide_suffix() . '.js',
            array( 'jquery' ),
            BOOSTIFY_SIZE_GUIDE_VER,
            true
        );
	}
	/**
	 * Include Widgets files
	 *
	 * Load widgets files
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function autoload_widgets() {
		$widgets = $this->get_widgets();
		foreach ( $widgets as $widget ) {
			$filename = strtolower( $widget );
			$filename = str_replace( '_', '-', $filename );
			$filename = BOOSTIFY_SIZE_GUIDE_PATH . 'inc/elementor/widgets/class-' . $filename . '.php';

			if ( is_readable( $filename ) ) {
				include $filename;
			}
		}
	}

	/**
	 * Register Widgets
	 *
	 * Register new Elementor widgets.
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function init_widgets() {
		$this->autoload_widgets();
		// Its is now safe to include Widgets files
		$widget_manager = \Elementor\Plugin::instance()->widgets_manager;
		foreach ( $this->get_widgets() as $widget ) {
			$class_name = 'Boostify_Size_Guide\Widgets\\' . $widget;

			$widget_manager->register_widget_type( new $class_name() );
		}
	}

	public function init() {

		$this->modules_manager = \Boostify_Size_Guide\Module\Sticky::instance();

		$elementor = \Elementor\Plugin::$instance;

		do_action( 'elementor_controls/init' ); // phpcs:ignore
	}

	private function setup_hooks() {
		// Register Module.
		add_action( 'elementor/init', array( $this, 'init' ) );
		add_action( 'elementor/init', array( $this, 'register_abstract' ) );
		// Register custom widget categories.
		add_action( 'elementor/elements/categories_registered', array( $this, 'add_elementor_widget_categories' ) );
		// Register widget scripts
		add_action( 'elementor/frontend/after_register_scripts', array( $this, 'widget_scripts' ) );
		// Register widgets
		add_action( 'elementor/widgets/widgets_registered', array( $this, 'init_widgets' ) );
	}

	public function register_abstract() {
		require BOOSTIFY_SIZE_GUIDE_PATH . 'inc/elementor/abstract/class-base-widget.php';
	}
}
// Instantiate Boostify_Size_Guide\Elementor Class
Elementor_Sg::instance();

