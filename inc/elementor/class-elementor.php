<?php
/**
 * Class Size Guide Elementor
 *
 * Main Plugin class
 * @since 1.2.0
 */

namespace Boostify_Size_Guide;

class Elementor {
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
			'ht_bfsg_builder',
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
            'boostify-sg-size-guide',
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
     * Add icon for elementor.
     */
    public function modify_controls( $controls_registry ) {
        // Get existing icons
        $icons = $controls_registry->get_control( 'icon' )->get_settings( 'options' );
        // Append new icons
        $new_icons = array_merge(
            array(
                'ion-android-arrow-dropdown'  => 'Ion Dropdown',
                'ion-android-arrow-dropright' => 'Ion Dropright',
                'ion-android-arrow-forward'   => 'Ion Forward',
                'ion-chevron-right'           => 'Ion Right',
                'ion-chevron-down'            => 'Ion Downr',
                'ion-ios-arrow-down'          => 'Ion Ios Down',
                'ion-ios-arrow-forward'       => 'Ion Ios Forward',
                'ion-ios-arrow-thin-right'    => 'Thin Right',
                'ion-navicon'                 => 'Ion Navicon',
                'ion-navicon-round'           => 'Navicon Round',
                'ion-android-menu'            => 'Menu',
                'ion-ios-search'              => 'Search',
                'ion-ios-search-strong'       => 'Search Strong',
            ),
            $icons
        );
        // Then we set a new list of icons as the options of the icon control
        $controls_registry->get_control( 'icon' )->set_settings( 'options', $new_icons );
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

	private function setup_hooks() {
		// Register Module.
		add_action( 'elementor/init', array( $this, 'register_abstract' ) );
		// Register custom widget categories.
		add_action( 'elementor/elements/categories_registered', array( $this, 'add_elementor_widget_categories' ) );
		// Register widget scripts
		add_action( 'elementor/frontend/after_register_scripts', array( $this, 'widget_scripts' ) );
		// Register widgets
		add_action( 'elementor/widgets/widgets_registered', array( $this, 'init_widgets' ) );
        add_action( 'elementor/controls/controls_registered', array( $this, 'modify_controls' ), 10, 1 );
	}

	public function register_abstract() {
		require BOOSTIFY_SIZE_GUIDE_PATH . 'inc/elementor/abstract/class-base-widget.php';
	}

    /**
     *  Plugin class constructor
     *
     * Register plugin action hooks and filters
     *
     * @since 1.2.0
     * @access public
     */
    public function __construct() {
        $this->setup_hooks();
    }
}
// Instantiate Boostify_Size_Guide\Elementor Class
Elementor::instance();

