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
		add_action( 'wp_ajax_nopriv_boostify_sg_load_autocomplate', array( $this, 'boostify_sg_input' ) );
		add_action( 'wp_ajax_boostify_sg_post_admin', array( $this, 'boostify_sg_post_admin' ) );
		add_action( 'wp_ajax_nopriv_boostify_sg_post_admin', array( $this, 'boostify_sg_post_admin' ) );
		add_action( 'wp_ajax_bsg_more_rule', array( $this, 'parent_rule' ) );
		add_action( 'wp_ajax_nopriv_bsg_more_rule', array( $this, 'parent_rule' ) );
		add_action( 'wp_ajax_boostify_sg_ex_auto', array( $this, 'boostify_sg_post_exclude' ) );
		add_action( 'wp_ajax_nopriv_boostify_sg_ex_auto', array( $this, 'boostify_sg_post_exclude' ) );
	}

	// Type Builder
	public function type_builder() {
		$type = array(
            'size_guide' => __( 'Size Guide', 'boostify' )
		);

		return $type;
	}

	// Meta Box In btf_builder post type
	public function pagesetting_meta_box() {
		add_meta_box( 'ht_sg_setting', 'Template Settings', array( $this, 'ht_sgsetting_output' ), 'btfsg_builder', 'side', 'high' );
	}


	// Screen meta box in btf_builder post type
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
                // $post o dau vay? cai nay t viet theo cai cua phuong
                // gio lay ra duoc id post cua nhung bai ko muon hien thi
                // t so sanh neu id ma bang voi list kia thi ko hien thi cai nut size guide
                // cai quan trong la ong dang lay $post o dau
                // post no lay bang ajax,
                // cai nay ko phai lay ra tu ajax
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

		if ( 'size_guide' !== $types ) {

			// Do Not Display On
			$no_display = sanitize_text_field( $_POST['bsg_no_display'] );

			update_post_meta(
				$post_id,
				'bsg_no_display',
				$no_display
			);

			// Ex Post
			if ( array_key_exists( 'bsg_ex_post', $_POST ) ) {
				$ex_post = sanitize_text_field( $_POST['bsg_ex_post'] );

				update_post_meta(
					$post_id,
					'bsg_ex_post',
					$ex_post
				);
			}

			// Ex Post Type
			if ( array_key_exists( 'bsg_ex_post_type', $_POST ) ) {
				$ex_post_type = sanitize_text_field( $_POST['bsg_ex_post_type'] );

				update_post_meta(
					$post_id,
					'bsg_ex_post_type',
					$ex_post_type
				);
			}
		}
	}

	public function sg_display( $post ) {
		$options      = $this->pt_support();
		$no_display   = get_post_meta( $post->ID, 'bsg_no_display', true );
		$post_id      = get_post_meta( $post->ID, 'bsg_post', true );
		$post_type    = get_post_meta( $post->ID, 'bsg_post_type', true );
		$ex_post_id   = get_post_meta( $post->ID, 'bsg_ex_post', true );
		$ex_post_type = get_post_meta( $post->ID, 'bsg_ex_post_type', true );
		$list_ex_post = $ex_post_id;

		if ( 'all' !== $ex_post_id ) {
			$list_ex_post = explode( ',', $ex_post_id );
		}

		?>
			<div class="input-wrapper">
				<div class="condition-group not-display">
					<div class="parent-item">
						<label><?php echo esc_html__( 'Do Not Display On', 'boostify' ); ?></label>
						<select name="bsg_no_display" class="no-display-on">
							<?php
							unset( $options['all'] );
							?>
							<option value="0"><?php echo esc_html__( 'Select', 'boostify' ); ?></option>
							<?php
							foreach ( $options as $key => $option ) :
								$selected = ( $key == $no_display ) ? 'selected' : ''; // phpcs:ignore
								?>
								<option value="<?php echo esc_attr( $key ); ?>" <?php echo esc_attr( $selected ); ?>><?php echo esc_html( $option ); ?></option>
							<?php endforeach ?>
						</select>
					</div>

					<div class="child-item">
						<div class="input-item-wrapper">
							<?php
							if ( ! empty( $ex_post_id ) && ! empty( $ex_post_type ) ) :

								?>
							<div class="boostify-section-select-post <?php echo ( is_string( $list_ex_post ) ? 'select-all' : 'render--post has-option' ); ?>">

								<span class="boostify-select-all-post<?php echo ( is_string( $list_ex_post ) ? '' : ' hidden' ); ?>">
									<span class="boostify-select-all"><?php echo esc_html__( 'All', 'boostify' ); ?></span>
									<span class="boostify-arrow ion-chevron-down"></span>
								</span>

								<div class="boostify-section-render--post <?php echo ( is_string( $list_ex_post ) ? 'hidden' : '' ); ?>">
									<div class="boostify-auto-complete-field">
										<?php
										if ( is_array( $list_ex_post ) ) :

											foreach ( $list_ex_post as $id ) :
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
										<input type="text" class="boostify--hf-post-name" aria-autocomplete="list" size="1">
									</div>
								</div>

							</div>
							<input type="hidden" name="bsg_ex_post_type" value="<?php echo esc_attr( $ex_post_type ); ?>" class="bsg-post-type">
							<input type="hidden" name="bsg_ex_post" value="<?php echo esc_html( $ex_post_id ); ?>">
							<div class="boostify-data"></div>
								<?php
							endif;
							?>
						</div>
					</div>
				</div>
			</div>
		<?php
	}

	public function boostify_sg_post_admin() {
		check_ajax_referer( 'ht_hf_nonce' );
		$post_type = sanitize_text_field( $_GET['post_type'] );
		$keyword   = sanitize_text_field( $_GET['key'] );

		$the_query = new \WP_Query(
			array(
				's'              => $keyword,
				'posts_per_page' => -1,
				'post_type'      => $post_type,
			)
		);

		if ( $the_query->have_posts() ) {
			?>
			<div class="boostify-hf-list-post">
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
			<div class="boostify-hf-list-post">
				<h6><?php echo esc_html__( 'Nothing Found', 'boostify' ); ?></h6>
			</div>
			<?php
		}

		die();

	}

	public function get_posts( $post_type ) {
		$args  = array(
			'post_type'      => $post_type,
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
							<input type="text" class="boostify--hf-post-name" aria-autocomplete="list" size="1">
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

	// For Ajax For Select single post not display
	public function boostify_sg_post_exclude() {
		check_ajax_referer( 'ht_hf_nonce' );
		$post_type = sanitize_text_field( $_POST['post_type'] );

		if ( $post_type && 'all' !== $post_type && 'archive' !== $post_type && 'search' !== $post_type && 'blog' !== $post_type && 'not_found' !== $post_type ) :
			?>
			<div class="input-item-wrapper">
				<div class="boostify-section-select-post">
					<span class="boostify-select-all-post">
						<span class="boostify-select-all"><?php echo esc_html__( 'All', 'boostify' ); ?></span>
						<span class="boostify-arrow ion-chevron-down"></span>
					</span>
					<div class="boostify-section-render--post hidden">
						<div class="boostify-auto-complete-field">
							<input type="text" class="boostify--hf-post-name" aria-autocomplete="list" size="1">
						</div>
					</div>
				</div>
				<input type="hidden" name="bsg_ex_post_type" value="<?php echo esc_attr( $post_type ); ?>" class="bsg-post-type">
				<input type="hidden" name="bsg_ex_post" value="all">

				<div class="boostify-data"></div>
			</div>
			<?php
		endif;
		die();
	}

	// Get all post title in Site.
	public function pt_support() {
		$post_types       = get_post_types();
		$post_types_unset = array(
			'attachment'          => 'attachment',
			'revision'            => 'revision',
			'nav_menu_item'       => 'nav_menu_item',
			'custom_css'          => 'custom_css',
			'customize_changeset' => 'customize_changeset',
			'oembed_cache'        => 'oembed_cache',
			'user_request'        => 'user_request',
			'wp_block'            => 'wp_block',
			'elementor_library'   => 'elementor_library',
			'btfsg_builder'       => 'btfsg_builder',
			'elementor-hf'        => 'elementor-hf',
			'elementor_font'      => 'elementor_font',
			'elementor_icons'     => 'elementor_icons',
			'wpforms'             => 'wpforms',
			'wpforms_log'         => 'wpforms_log',
			'acf-field-group'     => 'acf-field-group',
			'acf-field'           => 'acf-field',
			'booked_appointments' => 'booked_appointments',
			'wpcf7_contact_form'  => 'wpcf7_contact_form',
			'scheduled-action'    => 'scheduled-action',
			'shop_order'          => 'shop_order',
			'shop_order_refund'   => 'shop_order_refund',
			'shop_coupon'         => 'shop_coupon',
		);
		$diff             = array_diff( $post_types, $post_types_unset );
		$default          = array(
			'all'       => 'All',
		);
		$options          = array_merge( $default, $diff );

		return $options;
	}

	public function parent_rule() {
		check_ajax_referer( 'ht_hf_nonce' );
		$options = $this->pt_support();
		$length  = $_GET['length'];
		?>
		<div class="condition-group">
			<div class="parent-item">
				<label><?php echo esc_html__( 'Display On', 'boostify' ); ?></label>
				<select name="bsg_condition[<?php echo esc_html( $length ); ?>]" class="display-on">
					<?php
					foreach ( $options as $key => $option ) :
						?>
						<option value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $option ); ?></option>
					<?php endforeach ?>
				</select>
			</div>

			<div class="child-item">
			</div>
		</div>
		<?php

		die();
	}
}

