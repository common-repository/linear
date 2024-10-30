<?php

namespace Linear\Templates;

function text_area( $area = null, $extra = null, $icon = null ) {
    if( !$area ){
        return "";
    }

    $extra_component = '';

    if( $extra ){
        $extra_component = "<br /><span>" . output_string( $extra ) . "</span>";
    }

    ob_start(); ?>

        <div class="linear-c-icon-info linear-u-mb-0 component-text-area">
                <div class="linear-c-icon-info__icon linear-icon-color-plugin">
                    <?php if( $icon ){
                        echo icon( $icon );
                    } ?>
                </div>
            <div>
                <p class="linear-c-icon-info__main linear-c-icon-info__main--md">
                    <?php echo $area; ?> 
                    <?php echo $extra_component; ?>
                </p>
            </div>
        </div>

    <?php return ob_get_clean();
}

?>