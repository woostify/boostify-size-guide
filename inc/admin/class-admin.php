<?php

namespace Boostify_Size_Guide;

defined( 'ABSPATH' ) || exit;

/**
 * Main Boostify Size Guide Admin Class
 *
 * @class Boostify_Size_Guide_Admin
 */

class Admin {

	private static $instance;

	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Boostify Size Guide Admin Constructor.
	 */
	public function __construct() {
		$this->hooks();
	}

	public function hooks() {
        add_action( 'admin_enqueue_scripts', array( $this, 'load_admin_style' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'load_wp_style' ) );
		add_filter( 'manage_btfsg_builder_posts_columns', array( $this, 'columns_head' ) );
		add_action( 'manage_btfsg_builder_posts_custom_column', array( $this, 'columns_content' ), 10, 2 );
	}

	public function load_admin_style() {

		wp_enqueue_style(
			'boostify-sg-admin',
			BOOSTIFY_SIZE_GUIDE_URL . 'assets/css/admin/admin.css',
			array(),
			BOOSTIFY_SIZE_GUIDE_VER
		);

        wp_enqueue_style(
            'select2-css',
            BOOSTIFY_SIZE_GUIDE_URL . '/assets/css/admin/select2.css',
            array(),
            BOOSTIFY_SIZE_GUIDE_VER
        );

		wp_enqueue_style(
			'ionicons',
			BOOSTIFY_SIZE_GUIDE_URL . '/assets/css/ionicons.css',
			array(),
			BOOSTIFY_SIZE_GUIDE_VER
		);

        wp_enqueue_script(
            'select2',
            BOOSTIFY_SIZE_GUIDE_URL . 'assets/js/select2' . boostify_size_guide_suffix() . '.js',
            array( 'jquery' ),
            BOOSTIFY_SIZE_GUIDE_VER,
            true
        );

        wp_enqueue_script(
            'boostify-custom',
            BOOSTIFY_SIZE_GUIDE_URL . 'assets/js/custom.js',
            array( 'jquery' ),
            BOOSTIFY_SIZE_GUIDE_VER,
            true
        );
	}

    public function load_wp_style() {
        wp_enqueue_script(
            'boostify-sg-size-guide',
            BOOSTIFY_SIZE_GUIDE_URL . 'assets/js/size-guide.js',
            array( 'jquery' ),
            BOOSTIFY_SIZE_GUIDE_VER,
            true
        );
    }

	public function columns_head( $columns ) {
		$date_column = $columns['date'];

		unset( $columns['date'] );
		$columns['shortcode'] = __( 'Shortcode', 'boostify' );
		$columns['date']      = $date_column;

		return $columns;
	}

	// SHOW THE FEATURED IMAGE
	public function columns_content( $column_name, $post_id ) {
		$type = get_post_meta( $post_id, 'bsg_type', true );
		switch ( $column_name ) {
			case 'shortcode':
				ob_start();
				?>
				<span class="bsg-shortcode-col-wrap">
					<input type="text" readonly="readonly" value="[bsg id='<?php echo esc_attr( $post_id ); ?>']" class="bsg-large-text code">
				</span>

				<?php

				ob_get_contents();
				break;
		}
	}
}

Admin::instance();

