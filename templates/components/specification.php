<?php

namespace Linear\Templates;

function specification( $value = null, $label = null, $icon = null ) {
    if( !$value ){
        return "";
    }

    ob_start(); ?>

        <div class="linear-c-icon-info linear-specification">

            <?php if( $icon ){ ?>
                <div class="linear-c-icon-info__icon linear-icon-color-plugin">
                    <?php echo icon( $icon ); ?>
                </div>
            <?php } ?>

            <div>
                <p class="linear-c-icon-info__label linear-specification__label">
                    <?php if( $label ){
                        echo maybe_get_constant( $label );
                    } ?>
                </p>
                <p class="linear-c-icon-info__main linear-specification__value">
                    <?php echo maybe_get_constant( $value ); ?>
                </p>
            </div>
            
        </div>

    <?php return ob_get_clean();
}

?>