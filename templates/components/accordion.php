<?php

namespace Linear\Templates;

function accordion( $title = '', $sub_title = '', $content = [], $type = 'simple', $extra = false ) {

    if( !$content ){
        return "";
    }

    ob_start(); ?>
    
        <div class="linear-c-card linear-c-card--collapse linear-js-collapse">
            <button class="linear-c-card__collapse-toggle open"><?php echo $title; ?></button>

            <div class="linear-c-card__collapse-content">
                <div class="linear-c-card__collapse-content-inner">

                    <?php if( $type === 'simple' ){ ?>

                        <?php if ( $sub_title ) { ?>
                            <p>
                                <strong><?php echo $sub_title; ?></strong>
                            </p>
                        <?php } ?>

                        <p>
                            <?php 
                                if( is_array( $content ) ){
                                    foreach( $content as $single ){
                                        echo nl2br( $single );
                                    }
                                } else {
                                    echo nl2br( $content );
                                }
                            ?>
                        </p>

                    <?php } else if( $type === 'list' ){ ?>

                        <div class="linear-c-content-table">

                            <?php foreach( $content as $single ){
                                if( $single['value'] ){   
                                    $class_key = html_entity_decode($single['label'], ENT_COMPAT | ENT_HTML401, 'UTF-8');

                                    $class_key = preg_replace('/[^\p{L}\p{N}]+/u', '_', $class_key);

                                    $class_key = preg_replace('/\p{Mn}/u', '', $class_key);

                                    $class_key = mb_strtolower($class_key, 'UTF-8');

                                    $class_key = 'single_listing_accordion_' . $class_key;
                                ?>

                                    <div class="linear-c-content-table__row <?php echo $class_key; ?>">
                                        <div class="linear-c-content-table__item linear-c-content-table__item--label">
                                            <p><?php echo $single['label']; ?><span>:</span></p>
                                        </div>

                                        <div class="linear-c-content-table__item">
                                            <p>
                                                <?php 
                                                    if( is_array( $single['value'] ) ){
                                                        foreach( $single['value'] as $key => $sub_single ){

                                                            if ( $key === array_key_first( $single['value'] ) ) {
                                                                echo "";
                                                            } else if ( $key === array_key_last( $single['value'] ) ) {
                                                                echo ' ' . _x( 'and', 'accordion item separator', 'linear' ) . ' ';
                                                            } else {
                                                                echo ", ";
                                                            }

                                                            if ( $key === array_key_first( $single['value'] ) ) {
                                                                echo nl2br( ucfirst( strtolower( maybe_get_constant( $sub_single ) ) ) );
                                                            } else {
                                                                echo nl2br( strtolower( maybe_get_constant( $sub_single ) ) );
                                                            }
                                                            
                                                        }
                                                    } else {
                                                        echo nl2br( maybe_get_constant( $single['value'] ) );
                                                    }
                                                ?>
                                            </p>
                                        </div>
                                    </div>
                                
                                <?php }
                            } ?>
                        </div>

                    <?php } ?>

                    <?php if( $extra ){
                        echo $extra;
                    } ?>

                </div>
            </div>

        </div>
        
    <?php return ob_get_clean();
}
