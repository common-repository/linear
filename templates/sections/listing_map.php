<?php

namespace Linear\Templates;

function listing_map( $listing ) {

    $coordinates = get_object_value( $listing, 'mapCoordinates' ) ? get_object_value( $listing, 'mapCoordinates' ) : [];

    ob_start(); ?>
    
    <div class="linear-c-card linear-c-card--collapse linear-js-collapse">
        <button class="linear-c-card__collapse-toggle open"><?php esc_html_e( 'Location', 'linear' ); ?></button>

        <div class="linear-c-card__collapse-content">
            <div class="linear-c-card__collapse-content-inner">
                <?php

                    $get_name_parts  = get_clean_array( $listing, [ 'address', 'postalCode', 'districtFree', 'city' ] );

                    $location = array(
                        'name'       => $get_name_parts ? esc_html( implode( ', ', $get_name_parts ) ) : '',
                        'cordinates' => $coordinates,
                        'icon'       => icon( 'dot' )
                    );
                ?>
                <div id="linear-js-location-map" class="linear-c-card__map" data-location="<?php echo esc_attr( json_encode( $location ) ); ?>" style="background-image: url('<?php echo LINEAR_PLUGIN_URL . 'dist/map_bg.png' ?>');">
                    <div class="linear-c-card__map-button">
                        <div class="linear-listings__loadmore wp-block-button elementor-cta__button-wrapper">
                            <button id="linear-js-load-map-button" class="linear-listings__loadmore__button wp-block-button__link elementor-cta__button"><?php echo __('Load map', 'linear') ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        
    <?php return ob_get_clean();
}
