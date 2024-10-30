<?php

namespace Linear\Templates;

get_header();

/* Start the Loop */
while ( have_posts() ) {
	the_post(); ?>

    <article class="template-plain-listing" id="post-<?php the_ID(); ?>" <?php post_class(); ?> style="max-width: 1080px; margin: 0 auto; padding: 0 4.5455%; padding-top: 20px;">

        <div class="entry-content">
            <?php
            the_content();

            wp_link_pages(
                array(
                    'before'   => '<nav class="page-links" aria-label="' . esc_attr__( 'Page', 'linear' ) . '">',
                    'after'    => '</nav>',
                    'pagelink' => esc_html__( 'Page %', 'linear' ),
                )
            );
            ?>
        </div><!-- .entry-content -->

        <?php if ( get_edit_post_link() ) : ?>
            <footer class="entry-footer default-max-width">
                <?php
                edit_post_link(
                    sprintf(
                        /* translators: %s: Post title. Only visible to screen readers. */
                        esc_html__( 'Edit %s', 'linear' ),
                        '<span class="screen-reader-text">' . get_the_title() . '</span>'
                    ),
                    '<span class="edit-link">',
                    '</span>'
                );
                ?>
            </footer><!-- .entry-footer -->
        <?php endif; ?>
    </article><!-- #post-<?php the_ID(); ?> -->

    <?php
	// If comments are open or there is at least one comment, load up the comment template.
	if ( comments_open() || get_comments_number() ) {
		comments_template();
	}
}; // End of the loop.

get_footer();

?>