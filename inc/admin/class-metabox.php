<?php

namespace Boostify_Size_Guide;

defined( 'ABSPATH' ) || exit;

/**
 * Main Boostify Size Guide Metabox Class
 *
 * @class Boostify_Size_Guide_Metabox
 */

class Metabox {

    /**
     * Meta Option
     *
     * @var $meta_option
     */
    private static $meta_option;

	/**
	 * Boostify Size Guide Metabox Constructor.
	 */
	public function __construct() {
		$this->hooks();
	}

	public function hooks() {
		add_action( 'add_meta_boxes', array( $this, 'setup_size_guide_metabox' ) );
		add_action( 'save_post', array( $this, 'save_size_guide_metabox' ) );
        self::$meta_option = array(
            'size-guide-for-category' => array(
                'default'  => '',
                'sanitize' => 'FILTER_DEFAULT',
            ),
            'size-guide-for-product'  => array(
                'default'  => '',
                'sanitize' => 'FILTER_DEFAULT',
            ),
        );

		add_action( 'wp_ajax_boostify_sg_load_autocomplate', array( $this, 'boostify_sg_input' ) );
		add_action( 'wp_ajax_boostify_sg_post_admin', array( $this, 'boostify_sg_post_admin' ) );
        add_action( 'wp_ajax_boostify_sg_cat_admin', array( $this, 'boostify_sg_cat_admin' ) );
	}

	// Type Builder
	public function type_builder() {
		$type = array(
            'size_guide' => __( 'Size Guide', 'boostify' )
		);

		return $type;
	}

	/**
     * Setup Metabox
     */
    public function setup_size_guide_metabox() {
        add_meta_box(
            'boostify_metabox_settings_size_guide',
            __( 'Size Guide Settings', 'boostify' ),
            array( $this, 'size_guide_markup' ),
            'btfsg_builder',
            'side',
            'high'
        );
    }

	/**
     * Metabox Markup
     *
     * @param  object $post Post object.
     * @return void
     */
	public function size_guide_markup( $post ) {
		wp_nonce_field( 'boostify_sg_action', 'boostify_sg' );

		$type       = get_post_meta( $post->ID, 'bsg_type', true );
		$display    = get_post_meta( $post->ID, 'bsg_display', true );
		$posts      = get_post_meta( $post->ID, 'bsg_post', true );
		$post_type  = get_post_meta( $post->ID, 'bsg_post_type', true );

		?>

		<div class="form-meta-footer">
            <?php
            if ( 'size_guide' !== $type ) {
                $this->sg_display( $post );
            }
            ?>
		</div>
		<?php
	}

	/**
     * Metabox Save
     *
     * @param  number $post_id Post ID.
     * @return void
     */
	public function save_size_guide_metabox( $post_id ) {

        // Checks save status.
        $is_user_can_edit = current_user_can( 'edit_posts' );
        $is_autosave      = wp_is_post_autosave( $post_id );
        $is_revision      = wp_is_post_revision( $post_id );
        $is_valid_nonce   = ( isset( $_POST['boostify_metabox_settings_size_guide'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['boostify_metabox_settings_size_guide'] ) ), basename( __FILE__ ) ) ) ? true : false;

		$nonce_name   = ( array_key_exists( 'boostify_metabox_settings_size_guide', $_POST ) ) ? sanitize_text_field( $_POST['boostify_metabox_settings_size_guide'] ) : '';
		$nonce_action = 'boostify_sg_action';

		if ( ! isset( $nonce_name ) ) {
			return;
		}

        // Exits script depending on save status.
        if ( ! $is_user_can_edit || $is_autosave || $is_revision || ! $is_valid_nonce || ! wp_verify_nonce( $nonce_name, $nonce_action ) ) {
            return;
        }

        /**
         * Get meta options
         */
        $post_meta = self::get_size_guide_metabox_option();

        foreach ( $post_meta as $key => $data ) {

            // Sanitize values.
            $sanitize_filter = isset( $data['sanitize'] ) ? $data['sanitize'] : 'FILTER_DEFAULT';

            switch ( $sanitize_filter ) {

                case 'FILTER_SANITIZE_STRING':
                        $meta_value = filter_input( INPUT_POST, $key, FILTER_SANITIZE_STRING );
                    break;

                case 'FILTER_SANITIZE_URL':
                        $meta_value = filter_input( INPUT_POST, $key, FILTER_SANITIZE_URL );
                    break;

                case 'FILTER_SANITIZE_NUMBER_INT':
                        $meta_value = filter_input( INPUT_POST, $key, FILTER_SANITIZE_NUMBER_INT );
                    break;

                default:
                        $meta_value = filter_input( INPUT_POST, $key, FILTER_DEFAULT );
                    break;
            }

            // Update values.
            if ( $meta_value ) {
                update_post_meta( $post_id, $key, $meta_value );
            } else {
                delete_post_meta( $post_id, $key );
            }
        }

		// Type of Template Builder
		$type = sanitize_text_field( $_POST['bsg_type'] );

		update_post_meta(
			$post_id,
			'bsg_type',
			$type
		);

        // Display On
        $display = sanitize_text_field( $_POST['bsg_display'] );

        update_post_meta(
            $post_id,
            'bsg_display',
            $display
        );

        if ( 'size_guide' !== $type ) {

            // Post
            if ( array_key_exists( 'bsg_post', $_POST ) ) {
                $post = sanitize_text_field( $_POST['bsg_post'] );

                update_post_meta(
                    $post_id,
                    'bsg_post',
                    $post
                );
            }

            // Post Type
            if ( array_key_exists( 'bsg_post_type', $_POST ) ) {
                $post_type = sanitize_text_field( $_POST['bsg_post_type'] );

                update_post_meta(
                    $post_id,
                    'bsg_post_type',
                    $post_type
                );
            }
        }
	}

	public function sg_display( $post ) {
        $display      = get_post_meta( $post->ID, 'bsg_display', true );
        $post_id      = get_post_meta( $post->ID, 'bsg_post', true );
        $post_type    = get_post_meta( $post->ID, 'bsg_post_type', true );
        $list_post    = $post_id;
        $list_display = explode( ',', $display );
        if ( 'all' !== $post_id ) {
            $list_post = explode( ',', $post_id );
        }

        var_dump( $list_display );
        var_dump( $list_post );
		?>
			<div class="input-wrapper">
                <div class="condition-group display--on">
                    <div class="parent-item">
                        <label><?php echo esc_html__( 'Display On', 'boostify' ); ?></label>
                        <select name="bsg_display" class="display-on" multiple='multiple'>
                            <?php
                                $args = array(
                                    'hide_empty' => true,
                                );

                                $cats = get_terms( 'product_cat', $args );

                                if ( ! empty( $cats ) && ! is_wp_error( $cats ) ) {
                                    foreach ( $cats as $key => $k ) {
                                        ?>
                                        <option value="<?php echo esc_attr( $k->term_id ); ?>">
                                            <?php echo esc_html( $k->name ); ?>
                                        </option>
                                        <?php
                                    }
                                }
                            ?>
                        </select>
                    </div>

                    <div class="child-item">
                        <div class="input-item-wrapper">
                            <?php if ( ! empty( $post_id ) && ! empty( $post_type ) ) : ?>
                                <div class="boostify-section-select-post <?php echo ( is_string( $list_post ) ? 'select-all' : 'render--post has-option' ); ?>">
                                    <span class="boostify-select-all-post<?php echo ( is_string( $list_post ) ? '' : ' hidden' ); ?>">
                                        <span class="boostify-select-all">
                                            <?php echo esc_html__( 'All', 'boostify' ); ?>
                                        </span>
                                        <span class="boostify-arrow ion-chevron-down"></span>
                                    </span>

                                    <div class="boostify-section-render--post <?php echo ( is_string( $list_post ) ? 'hidden' : '' ); ?>">
                                        <div class="boostify-auto-complete-field">
                                            <?php
                                            if ( is_array( $list_post ) ) :

                                                foreach ( $list_post as $id ) :
                                                    $id = (int) $id;
                                                    ?>

                                                    <span class="boostify-auto-complete-key">
                                                        <span class="boostify-title"><?php echo esc_html( get_the_title( $id ) ); ?></span>
                                                        <span class="btn-boostify-auto-complete-delete ion-close" data-item="<?php echo esc_attr( $id ); ?>"></span>
                                                    </span>
                                                    <?php
                                                endforeach;
                                            endif;
                                            ?>
                                            <input type="text" class="boostify--sg-post-name" aria-autocomplete="list" size="1">
                                        </div>
                                    </div>
                                </div>

                                <input type="hidden" name="bsg_post" value="<?php echo esc_html( $post_id ); ?>">
                                <div class="boostify-data"></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
		<?php
	}

	public function boostify_sg_post_admin() {
		check_ajax_referer( 'ht_hf_nonce' );
		$keyword    = sanitize_text_field( $_GET['key'] );
        $product_data        = get_post_meta( $post->ID, 'bsg_post', true );
        $selected_product_id = explode( '|', $product_data );

		$the_query = new \WP_Query(
			array(
                's'              => $keyword,
				'posts_per_page' => -1,
				'post_type'      => 'product',
			)
		);

		if ( $the_query->have_posts() ) {
			?>
			<div class="boostify-sg-list-post">
				<ul class="hf-list-post">
				<?php
				while ( $the_query->have_posts() ) {
					$the_query->the_post();
					$results[ get_the_ID() ] = get_the_title();
					?>
						<li class="post-item" data-item="<?php echo esc_attr( get_the_ID() ); ?>">
							<?php the_title(); ?>
						</li>

					<?php
				}
				?>
				</ul>
			</div>
			<?php

			/* Restore original Post Data */

			wp_reset_postdata();

		} else {
			?>
			<div class="boostify-sg-list-post">
				<h6><?php echo esc_html__( 'Nothing Found', 'boostify' ); ?></h6>
			</div>
			<?php
		}

		die();

	}

	// For Ajax For Select single post display
	public function boostify_sg_input() {
		check_ajax_referer( 'ht_hf_nonce' );
		$post_type = sanitize_text_field( $_POST['post_type'] );

		if ( 'all' !== $post_type && 'archive' !== $post_type && 'search' !== $post_type && 'blog' !== $post_type && 'not_found' !== $post_type ) :
			?>
			<div class="input-item-wrapper">
				<div class="boostify-section-select-post">
					<span class="boostify-select-all-post">
						<span class="boostify-select-all"><?php echo esc_html__( 'All', 'boostify' ); ?></span>
						<span class="boostify-arrow ion-chevron-down"></span>
					</span>
					<div class="boostify-section-render--post hidden">
						<div class="boostify-auto-complete-field">
							<input type="text" class="boostify--sg-post-name" aria-autocomplete="list" size="1">
						</div>
					</div>
				</div>
				<input type="hidden" name="bsg_post_type" value="<?php echo esc_attr( $post_type ); ?>" class="bsg-post-type">
				<input type="hidden" name="bsg_post" value="all">
				<div class="boostify-data"></div>
			</div>
			<?php
		endif;
		die();
	}

}

