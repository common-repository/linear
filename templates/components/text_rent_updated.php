<?php

namespace Linear\Templates;

function text_rent_updated( $formatted_rent = null, $rentUpdateDate = null, $updatedRent = null, $icon = 'price-tag' ) {
    if( !$formatted_rent && ( !$rentUpdateDate && !$updatedRent ) ){
        return "";
    }

    ob_start(); ?>

        <div class="linear-c-icon-info linear-u-mb-0 component-rent-updated">
            <div class="linear-c-icon-info__icon linear-icon-color-plugin">
                <?php if( $icon ){
                    echo icon( $icon );
                } ?>
            </div>
            <div>
                <p class="linear-c-icon-info__main linear-c-icon-info__main--lg">
                    <?php
                    
                        if( $formatted_rent ){
                            echo $formatted_rent;
                        }
                        
                        if( $rentUpdateDate && $updatedRent ){ ?>
                             <br />
                            <span>
                                <?php
                                    echo esc_html(
                                        sprintf(
                                            __( 'Rent will be updated to %s on %s.', 'linear' ),
                                            $rentUpdateDate,
                                            $updatedRent
                                        )
                                    );
                                ?>
                            </span>
                        <?php }
                    
                    ?>
                </p>
            </div>
        </div>

    <?php return ob_get_clean();
}

?>