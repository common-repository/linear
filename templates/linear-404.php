<?php

get_header();

/* Start the Loop */
while ( have_posts() ) {
	the_post(); ?>

    <article class="template-plain-listing" id="post-<?php the_ID(); ?>" <?php post_class(); ?> style="max-width: 1080px; margin: 0 auto; padding: 0 4.5455%; padding-top: 20px;">

        <header class="entry-header">
            <h1 class="entry-title">
                <?php echo __( '', 'linear' ) ?>
            </h1>
        </header><!-- .entry-header -->

        <div class="entry-content">
            <div class="wp-container-6 wp-block-group">
                <h2 class="has-text-align-center" style="font-size:clamp(4rem, 40vw, 20rem);font-weight:200;line-height:1;margin-bottom:0px;">404</h2>
                <p class="has-text-align-center"><?php echo _x( 'Unfortunately couldn\'t find this listing, it is perhaps removed.', '404-page error message', 'linear' ) ?></p>

                <?php
                    $options = get_option( 'linear_settings' );
                    if( isset( $options['listings_page'] ) ){
                        $permalink = get_permalink( $options['listings_page'] );

                        ?>
                            <a class="has-text-align-center" style="text-align: center; margin: 0 auto; display: table;" href="<?php echo $permalink ?>"><?php echo __( 'Back to listings', 'linear' ) ?></a>
                        <?php
                    }
                ?>
            </div>
        </div><!-- .entry-content -->

    </article><!-- #post-<?php the_ID(); ?> -->

    <?php
}; // End of the loop.

get_footer();

?>