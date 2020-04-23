<?php

/**
 * Define Script debug.
 *
 * @return     string $suffix
 */
function boostify_size_guide_suffix() {
	$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

	return $suffix;
}


function bosstify_size_guide() {
    global $product;

    $args = array(
        'post_type'      => 'btfsg_builder',
        'posts_per_page' => 1,
        'post_status'    => 'publish',
    );
    $query = new \WP_Query( $args );
    if ( ! $query->have_posts() ) {
        return;
    }

    ?>
    <?php
    $product_id = $product->get_id();
    $product_cat_id = $product->get_category_ids();

    while ( $query->have_posts() ) {
        $query->the_post();
        $display = intval( get_post_meta( get_the_ID(), 'bsg_category', true ) );
        $prd_id  = intval( get_post_meta( get_the_ID(), 'bsg_post', true ) );

        // $categories_id = array_map( 'intval', explode( ',', $display ) );
        if ( in_array( $display, $product_cat_id, true ) && $product_id === $prd_id ) {
            ?>
        <div class="popup">
            <a href="#" class="btn-size-guide">
                <?php echo esc_html( 'Size Guide', 'boostify' ); ?>
            </a>            
            <div class="cd-popup">
                <div class="cd-popup-container">
                    <?php the_content(); ?>
                    <a href="#0" class="cd-popup-close"></a>
                </div>
            </div> <!-- cd-popup -->
        </div>
        <?php
        }
    }

    wp_reset_postdata();
    ?>
<?php
}
