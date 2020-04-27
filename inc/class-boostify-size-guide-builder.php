<?php

defined( 'ABSPATH' ) || exit;

/**
 * Main Boostify Size Guide Builder
 *
 * @class Boostify_Size_Guide_Builder
 *
 * Written by pcd
 *
 */
if ( ! class_exists( 'Boostify_Size_Guide_Builder' ) ) {
	class Boostify_Size_Guide_Builder {

		private static $instance;

		public static function instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Boostify Size Guide Builder Constructor.
		 */
		public function __construct() {
			$this->includes();
			$this->hooks();
			$this->cpt();
		}

		public function includes() {
            include_once BOOSTIFY_SIZE_GUIDE_PATH . 'inc/admin/class-admin.php';
            include_once BOOSTIFY_SIZE_GUIDE_PATH . 'inc/admin/class-metabox.php';
            include_once BOOSTIFY_SIZE_GUIDE_PATH . 'inc/class-template.php';
            include_once BOOSTIFY_SIZE_GUIDE_PATH . 'inc/helper.php';
            include_once BOOSTIFY_SIZE_GUIDE_PATH . 'inc/elementor/class-elementor.php';
            include_once BOOSTIFY_SIZE_GUIDE_PATH . 'inc/elementor/class-template-sg-render.php';
		}

		public function hooks() {
			add_action( 'init', array( $this, 'post_types' ) );
			add_action( 'plugins_loaded', array( $this, 'init' ) );
			add_action( 'body_class', array( $this, 'body_ver' ) );
			add_action( 'elementor/controls/controls_registered', array( $this, 'modify_controls' ), 10, 1 );
			add_action( 'elementor/editor/wp_head', array( $this, 'enqueue_icon' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'style' ), 99 );
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_icon' ), 99 );
			add_action( 'admin_notices', array( $this, 'notice_plugin' ) );
			add_action( 'admin_notices', array( $this, 'notice_theme_support' ) );
            add_action( 'woocommerce_single_product_summary', 'bosstify_size_guide', 29 );
		}

		public function cpt() {
			add_post_type_support( 'btfsg_builder', 'elementor' );
		}

		public function body_ver( $classes ) {
			$classes[] = 'boostify-size-guide-' . BOOSTIFY_SIZE_GUIDE_VER;

			return $classes;
		}

		public function post_types() {
			register_post_type(
				'btfsg_builder',
				array(
					'supports'     => array( 'title', 'page-attributes' ),
					'hierarchical' => true,
					'rewrite'      => array( 'slug' => 'btfsg_builder' ),
					'has_archive'  => false,
					'public'       => true,
					'labels'       => array(
						'name'          => esc_html__( 'Boostify Size Guide', 'boostify' ),
						'add_new_item'  => esc_html__( 'Add New Size Guide', 'boostify' ),
						'edit_item'     => esc_html__( 'Edit Size Guide', 'boostify' ),
						'all_items'     => esc_html__( 'All Size Guide', 'boostify' ),
						'singular_name' => esc_html__( 'Elementor Builder', 'boostify' ),
					),
					'menu_icon'    => 'dashicons-welcome-add-page',
				)
			);
		}

		public function init() {
			new Boostify_Size_Guide\Metabox();
			new Boostify_Size_Guide\Template_Sg_Render();
		}

		public function test($value='') {
			new Boostify_Size_Guide\Theme_Support();
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
		 * Add ionicons.
		 *
		 */
		public function enqueue_icon() {
			wp_enqueue_style(
				'ionicons',
				BOOSTIFY_SIZE_GUIDE_URL . '/assets/css/ionicons.css',
				array(),
				BOOSTIFY_SIZE_GUIDE_VER
			);
		}


		public function style() {

			// FontAweSome 5 Free
			wp_enqueue_style(
				'fontawesome-5-free',
				BOOSTIFY_SIZE_GUIDE_URL . 'assets/css/fontawesome/fontawesome.css',
				array(),
				BOOSTIFY_SIZE_GUIDE_VER
			);

			// Style
			wp_enqueue_style(
				'boostify-sg-style',
				BOOSTIFY_SIZE_GUIDE_URL . 'assets/css/style.css',
				array(),
				BOOSTIFY_SIZE_GUIDE_VER
			);
		}

		/**
		 * Notice when do not install or active Elementor.
		 *
		 */
		public function notice_plugin() {
			if ( ! defined( 'ELEMENTOR_VERSION' ) || ! is_callable( 'Elementor\Plugin::instance' ) ) {

				if ( file_exists( WP_PLUGIN_DIR . '/elementor/elementor.php' ) ) {
					$url = network_admin_url() . 'plugins.php?s=elementor';
				} else {
					$url = network_admin_url() . 'plugin-install.php?s=elementor';
				}

				echo '<div class="notice notice-error">';
				/* Translators: URL to install or activate Elementor plugin. */
				echo '<p>' . sprintf( __( 'The <strong>Size Guide Elementor</strong> plugin requires <strong><a href="%s">Elementor</strong></a> plugin installed & activated.', 'size-guide-elementor' ) . '</p>', $url );// phpcs:ignore
				echo '</div>';
			}
		}

		/**
		 * Notice when do not theme Support.
		 *
		 */
		public function notice_theme_support() {
			if ( ! current_theme_supports( 'boostify-size-guide' ) ) {
				?>
				<div class="notice notice-error">
					<p><?php echo esc_html__( 'Your current theme is not supported Boostify Size Guide Plugin', 'boostify' ) ?></p>
				</div>
				<?php
			}
		}
	}

	Boostify_Size_Guide_Builder::instance();
}
