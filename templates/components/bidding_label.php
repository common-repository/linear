<?php

namespace Linear\Templates;

function bidding_label( $bidding = null ) {
    if( !$bidding || !isset( $bidding['id'] ) || !$bidding['id'] ){
        return;
    }
    
    ob_start(); ?>

    <div class="linear-c-tag">
        <?php _e( 'Bidding', 'linear' ); ?>
    </div>

    <?php return ob_get_clean();
}
