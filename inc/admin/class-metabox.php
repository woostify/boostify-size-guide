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
	 * Boostify Size Guide Metabox Constructor.
	 */
	public function __construct() {
		$this->hooks();
	}

	public function hooks() {
		add_action( 'add_meta_boxes', array( $this, 'setup_size_guide_metabox' ) );
		add_action( 'save_post', array( $this, 'save_size_guide_metabox' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
	}

	// Type Builder.
	public function type_builder() {
		$type = array(
            'size_guide' => __( 'Size Guide', 'boostify' )
		);

		return $type;
	}

    // Admin Script Screen.
    public function admin_scripts() {
        $screen               = get_current_screen();
        $is_size_guide_screen = false !== strpos( $screen->id, 'btfsg_builder' );

        if ( $is_size_guide_screen ) {
            wp_register_style( 'boostify-sg-screen-modal', false );
            wp_enqueue_style( 'boostify-sg-screen-modal' );
            wp_add_inline_style( 'boostify-sg-screen-modal', '#tawcvs-modal-container{display:none;}' );
        }
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
        $types      = $this->type_builder();
		$display    = get_post_meta( $post->ID, 'bsg_category', true );
		$posts      = get_post_meta( $post->ID, 'bsg_post', true );
		$post_type  = get_post_meta( $post->ID, 'bsg_post_type', true );
		wp_nonce_field( 'boostify_sg_action', 'boostify_sg' );

		?>

		<div class="form-meta-footer">
            <?php $this->sg_display( $post ); ?>
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
        $nonce_name   = ( array_key_exists( 'boostify_sg', $_POST ) ) ? sanitize_text_field( $_POST['boostify_sg'] ) : '';
        $nonce_action = 'boostify_sg_action';

        if ( ! isset( $nonce_name ) ) {
            return;
        }
        // Check if a nonce is valid.
        if ( ! wp_verify_nonce( $nonce_name, $nonce_action ) ) {
            return;
        }

        // Check if the user has permissions to save data.
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }

        // Check if it's not an autosave.
        if ( wp_is_post_autosave( $post_id ) ) {
            return;
        }

        // Check if it's not a revision.
        if ( wp_is_post_revision( $post_id ) ) {
            return;
        }

        // Display On Category.
        $display = sanitize_text_field( $_POST['bsg_category'] );

        update_post_meta(
            $post_id,
            'bsg_category',
            $display
        );

        // Post
        $product_id = sanitize_text_field( $_POST['bsg_post'] );

        update_post_meta(
            $post_id,
            'bsg_post',
            $product_id
        );

    }

	public function sg_display( $post ) {
        $display    = get_post_meta( $post->ID, 'bsg_category', true );
        $product_id = get_post_meta( $post->ID, 'bsg_post', true );
		?>
			<div class="input-wrapper">
                <div class="condition-group display--on">
                    <div class="parent-item">
                        <label><?php echo esc_html__( 'Product Category', 'boostify' ); ?></label>

                        <select class="display-on" multiple="multiple">
                            <option value="0">
                                <?php echo esc_html( 'All category', 'boostify' ); ?>
                            </option>
                            <?php
                                $args = array(
                                    'hide_empty' => true,
                                );
                                $cats = get_terms( 'product_cat', $args );

                                if ( ! empty( $cats ) && ! is_wp_error( $cats ) ) {
                                    foreach ( $cats as $k ) {
                                        ?>
                                        <option value="<?php echo esc_attr( $k->term_id ); ?>">
                                            <?php echo esc_html( $k->name ); ?>
                                        </option>
                                        <?php
                                    }
                                    wp_reset_postdata();

                                    ?>
                                    <input type="hidden" name="bsg_category" value="<?php echo esc_attr( $display); ?>" class="product-category-data">
                                    <?php
                                }
                            ?>
                        </select>
                    </div>

                    <div class="child-item">
                        <label><?php echo esc_html__( 'Apply For Products', 'boostify' ); ?></label>

                        <select class="display-product-on" multiple="multiple">
                            <option value="0">
                                <?php echo esc_html( 'All category', 'boostify' ); ?>
                            </option>
                            <?php
                                $selected_args = array(
                                    'post_type'      => 'product',
                                    'post_status'    => 'publish',
                                    'posts_per_page' => 100,
                                );

                                $selected_products = new \WP_Query( $selected_args );

                                if ( ! $selected_products->have_posts() ) {
                                    return;
                                }

                                while ( $selected_products->have_posts() ) {
                                    $selected_products->the_post();
                                    ?>
                                    <option value="<?php the_ID(); ?>">
                                        <?php the_title(); ?>
                                    </option>
                                    <?php
                                }
                                wp_reset_postdata();
                                ?>
                                <input type="hidden" name="bsg_post" value="<?php echo esc_attr( $product_id); ?>" class="product-data">
                                <?php
                            ?>
                        </select>
                    </div>
                </div>
            </div>
		<?php
	}

}

