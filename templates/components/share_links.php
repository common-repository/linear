<?php

namespace Linear\Templates;

function share_links( $title = '', $url = '', $site_name = '' ) {
    if( !$title || !$url ){
        return;
    }
    
    ob_start(); ?>

    <p class="linear-share-links">
        <a class="linear-share-links__link linear-icon-color-theme linear-js-share-link" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode( $url ) ?>" target="_blank">
            <span class="screen-reader-text"><?php _e('Share this on Facebook', 'linear') ?></span>
            <?php echo icon( 'facebook' ); ?>
        </a>
        <a class="linear-share-links__link linear-icon-color-theme linear-js-share-link" href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo $url ?>&title=<?php echo $site_name ?>&summary=<?php echo $title ?>&source=<?php echo $site_name ?>" target="_blank">
            <span class="screen-reader-text"><?php _e('Share this on LinkedIn', 'linear') ?></span>
            <?php echo icon( 'linkedin' ); ?>
        </a>
        <a class="linear-share-links__link linear-icon-color-theme linear-js-share-link" href="https://api.whatsapp.com/send?text=<?php echo $url ?>" target="_blank" data-action="share/whatsapp/share">
            <span class="screen-reader-text"><?php _e('Share this on WhatsApp', 'linear') ?></span>
            <?php echo icon( 'whatsapp' ); ?>
        </a>
    </p>

    <?php return ob_get_clean();
}
