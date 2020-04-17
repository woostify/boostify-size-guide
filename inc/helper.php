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
        'post_type'      => 'btfsg_builder',
        'posts_per_page' => 1,
        'post_status'    => 'publish',
    );
    $query = new \WP_Query( $args );
    
    ?>
    <div class="popup">
        <?php
        global $product;
        $id = $product->get_id();

        while ( $query->have_posts() ) {
            $query->the_post();
            $display_id = get_post_meta( get_the_ID(), 'bsg_post', true );
            $a = explode( ',' , $display_id );

            foreach ($a as $value) {
                switch ($value) {
                    case $id:
                        ?>
                        <a href="#" class="btn-size-guide">
                            <?php echo esc_html( 'Size Guide', 'miini' ); ?>
                        </a>
                        <?php
                        break;

                    case 'all':
                        ?>
                        <a href="#" class="btn-size-guide">
                            <?php echo esc_html( 'Size Guide', 'miini' ); ?>
                        </a>
                        <?php
                        break;
                    
                    default:
                        echo '';
                        break;
                }
            }
        }
        ?>

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
