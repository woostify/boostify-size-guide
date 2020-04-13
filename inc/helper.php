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
    $args = array(
        'post_type'      => 'btf_builder',
        'posts_per_page' => 1,
        'post_status'    => 'publish',
    );
    $query = new \WP_Query( $args );
    ?>
    <div class="popup">
        <a href="#" class="btn-size-guide">
            <?php echo esc_html( 'Size Guide', 'miini' ); ?>
        </a>

        <div class="cd-popup">
            <div class="cd-popup-container">
                <?php
                while ( $query->have_posts() ) {
                    $query->the_post();
                    the_content();
                }
                wp_reset_postdata();
                ?>
                <a href="#0" class="cd-popup-close"></a>
            </div> <!-- cd-popup-container -->
        </div> <!-- cd-popup -->
    </div>
    <?php
}

/**
 * Get content single builder .
 *
 * @return     get content
 */

function boostify_size_guide_content() {
	$id   = get_the_ID();
	$type = get_post_meta( $id, 'bsg_type' );

	if ( empty( $type ) ) {
		$type[0] = 'size_guide';
	}
	if ( 'size_guide' === $type[0] ) {
        $path = BOOSTIFY_SIZE_GUIDE_PATH . 'templates/content/content-size-guide.php';
    }
	load_template( $path );
}
