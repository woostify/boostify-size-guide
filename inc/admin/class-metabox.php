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
		add_action( 'add_meta_boxes', array( $this, 'pagesetting_meta_box' ) );
		add_action( 'save_post', array( $this, 'pagesetting_save' ) );
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

	// Meta Box In btfsg_builder post type
	public function pagesetting_meta_box() {
		add_meta_box( 'ht_sg_setting', 'Size Guide Settings', array( $this, 'ht_sgsetting_output' ), 'btfsg_builder', 'side', 'high' );
	}


	// Screen meta box in btfsg_builder post type
	public function ht_sgsetting_output( $post ) {
		$types         = $this->type_builder();
		$type          = get_post_meta( $post->ID, 'bsg_type', true );
		$display       = get_post_meta( $post->ID, 'bsg_display', true );
		$posts         = get_post_meta( $post->ID, 'bsg_post', true );
		$post_type     = get_post_meta( $post->ID, 'bsg_post_type', true );

		wp_nonce_field( 'boostify_sg_action', 'boostify_sg' );
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

	// Save meta box setting in btf_buider postType
	public function pagesetting_save( $post_id ) {
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

		// Type of Template Builder
		$type = sanitize_text_field( $_POST['bsg_type'] );

		update_post_meta(
			$post_id,
			'bsg_type',
			$type
		);

        if ( 'size_guide' !== $type ) {

            // Display On
            $display = sanitize_text_field( $_POST['bsg_display'] );

            update_post_meta(
                $post_id,
                'bsg_display',
                $display
            );

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
        if ( 'all' !== $post_id ) {
            $list_post = explode( ',', $post_id );
        }

        var_dump( $list_post );
		?>
			<div class="input-wrapper">
                <div class="condition-group display--on">
                    <div class="parent-item">
                        <label><?php echo esc_html__( 'Display On', 'boostify' ); ?></label>
                        <div class="input-item-wrapper">
                            <div class="boostify-section-select-category <?php echo ( is_string( $list_post ) ? 'select-all' : 'render--post has-option' ); ?>">
                                <span class="boostify-select-all-category<?php echo ( is_string( $list_post ) ? '' : ' hidden' ); ?>">
                                    <span class="boostify-select-all"><?php echo esc_html__( 'All catergory', 'boostify' ); ?></span>
                                    <span class="boostify-arrow ion-chevron-down"></span>
                                </span>

                                <div class="boostify-section-render--category <?php echo ( is_string( $list_post ) ? 'hidden' : '' ); ?>">
                                    <div class="boostify-auto-complete-field">
                                        <?php
                                        $args = array(
                                            'hide_empty' => true,
                                        );

                                        $cats = get_terms( 'product_cat', $args );

                                        if ( ! empty( $cats ) && ! is_wp_error( $cats ) ) {
                                            foreach ( $cats as $k ) {
                                                ?>
                                                <span class="boostify-auto-complete-key">
                                                    <span class="boostify-title">
                                                        <?php echo esc_html( $k->name ); ?>
                                                    </span>
                                                    <span class="btn-boostify-auto-complete-delete ion-close" data-item="<?php echo esc_attr( $k->term_id ); ?>"></span>
                                                </span>

                                                <?php
                                            }
                                        }
                                        ?>
                                        <input type="text" class="boostify--sg-post-name" aria-autocomplete="list" size="1">
                                    </div>
                                </div>

                            </div>
                            <input type="hidden" name="bsg_category" value="<?php echo esc_html( $post_id ); ?>">
                            <div class="boostify-data-category"></div>
                        </div>
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
		$keyword   = sanitize_text_field( $_GET['key'] );

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

	public function get_posts( $post_type ) {
		$args  = array(
			'post_type'      => 'product',
			'orderby'        => 'name',
			'order'          => 'ASC',
			'posts_per_page' => -1,
		);
		$posts = new \WP_Query( $args );

		return $posts;
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

