<?php
/**
 * Helper check
 *
 * @package Boostify_Size_Guide
 */

/**
 * Define Script debug.
 *
 * @return string $suffix
 */
function boostify_size_guide_suffix() {
	$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

	return $suffix;
}

/**
 * Bosstify size guide
 */
function bosstify_size_guide() {
	global $product;

	$args  = array(
		'post_type'      => 'btfsg_builder',
		'posts_per_page' => 1,
		'post_status'    => 'publish',
	);
	$query = new \WP_Query( $args );

	if ( ! $query->have_posts() ) {
		return;
	}

	$aproid         = $product->get_id();
	$product_cat_id = $product->get_category_ids();

	while ( $query->have_posts() ) {
		$query->the_post();

		$display       = get_post_meta( get_the_ID(), 'bsg_category', true );
		$product_id    = get_post_meta( get_the_ID(), 'bsg_post', true );
		$list_category = array_map( 'intval', explode( ',', $display ) );
		$products_id   = array_map( 'intval', explode( ',', $product_id ) );

		if ( in_array( $product_cat_id[0], $list_category, true ) || in_array( $aproid, $products_id, true ) || 'all' === $product_id ) {
			?>

			<div class="boostify-size-guide-popup">
				<a href="#" class="btn-size-guide">
					<i class="fas fa-ruler"></i>
					<?php echo esc_html( get_the_title( $post = 0 ) ); ?>
				</a>
				<div class="cd-popup">
					<div class="cd-popup-container">
						<?php the_content(); ?>
					</div>
					<a href="#0" class="cd-popup-close"></a>
				</div> <!-- cd-popup -->
			</div>
			<?php
		}
	}

	wp_reset_postdata();
}
