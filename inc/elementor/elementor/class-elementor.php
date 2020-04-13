<?php
/**
 * Class Header Footer Elementor
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

		// Add element category in panel
		$elementor->elements_manager->add_category(
			'boostify-sticky',
			array(
				'title' => __( 'Header Sticky', 'boostify' ),
				'icon'  => 'font',
			),
			1
		);

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
		add_filter( 'add_to_cart_fragments', array( $this, 'add_to_cart_fragment' ) );
	}

	public function register_abstract() {
		require BOOSTIFY_SIZE_GUIDE_PATH . 'inc/elementor/abstract/class-base-widget.php';
		require BOOSTIFY_SIZE_GUIDE_PATH . 'inc/elementor/abstract/class-nav-menu.php';
	}

	public function includes() {
		require BOOSTIFY_SIZE_GUIDE_PATH . 'inc/elementor/module/class-sticky.php';
	}

	public function add_to_cart_fragment( $fragments ) {
		global $woocommerce;
		ob_start();
		?>
			<span class="boostify-count-product"><?php echo esc_html( WC()->cart->get_cart_contents_count() ); ?></span>

		<?php
		$fragments['span.boostify-count-product'] = ob_get_clean();//a.cartplus-contents,a.cart-button
		ob_end_clean();
		return $fragments;

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
		$this->includes();
		$this->setup_hooks();
	}
}
// Instantiate Boostify_Size_Guide\Elementor Class
Elementor::instance();

