<?php

namespace Linear\Templates;

function buy_commission_introduction( $buy_commission = null ) {
    if( !$buy_commission ){
        return '';
    }

    ob_start(); ?>

        <div class="linear-o-row">
            <div class="linear-o-col-12">
                <div class="linear-c-card">
                    <div class="linear-o-row">
                        
                        <?php
                            //
                            // The left side content
                            //
                        ?>
                        <div class="linear-o-col-6@md linear-u-mb-2">
                            <div class="linear-u-mb-2"><?php

                                echo text_heading( __( 'Buy commission', 'linear' ) );

                            ?></div>
                            
                            <?php
                                
                                // price range
                                $price                  = '';
                                $debt_free_price_lower  = get_locale_value( $buy_commission, 'debtFreePriceLowerBound' );
                                $debt_free_price_upper  = get_locale_value( $buy_commission, 'debtFreePriceUpperBound' );

                                switch ( true ) {
                                    case ( $debt_free_price_lower && $debt_free_price_upper ):
                                        $price = $debt_free_price_lower . ' - ' . $debt_free_price_upper;
                                        break;
                                    case !!$debt_free_price_lower:
                                        $price = $debt_free_price_lower;
                                        break;
                                    case !!$debt_free_price_upper:
                                        $price = $debt_free_price_upper;
                                        break;
                                }

                                echo text_price( $price );


                                // area range
                                $area               = '';
                                $living_area_lower  = get_locale_value( $buy_commission, 'livingAreaLowerBound' );
                                $living_area_upper  = get_locale_value( $buy_commission, 'livingAreaUpperBound' );

                                switch ( true ) {
                                    case ( $living_area_lower && $living_area_upper ):
                                        $area = $living_area_lower . ' m2';
                                        $area .= ' - ';
                                        $area .= $living_area_upper . ' m2';
                                        break;
                                    case !!$living_area_lower:
                                        $area = $living_area_lower . ' m2';
                                        break;
                                    case !!$living_area_upper:
                                        $area = $living_area_upper . ' m2';
                                        break;
                                }

                                echo text_area( $area, null, 'ground-plan' );
                            
                            ?>
                        </div>

                        <?php
                            //
                            // The right side content
                            //
                        ?>
                        <div class="linear-o-col-6@md linear-u-mt-1@sm">
                            <div class="linear-o-row linear-u-mb-1">
                                <div class="linear-o-col-6@sm">

                                    <?php

                                        //
                                        // Left specifications
                                        //

                                        // Location
                                        if( $location_value = get_locale_value( $buy_commission, 'location' ) ){
                                            $location_key = get_locale_key( $buy_commission, 'location' );

                                            $location_value = str_replace(", ", ",", $location_value);
                                            $location_value = str_replace(",", ", ", $location_value);

                                            echo specification( $location_value, $location_key, null );
                                        }

                                        // Listing type
                                        if( $wanted_type_value = get_locale_value( $buy_commission, 'wantedListingType' ) ){
                                            $wanted_type_key   = get_locale_key( $buy_commission, 'wantedListingType' );

                                            $wanted_type_value = str_replace(", ", ",", $wanted_type_value);
                                            $wanted_type_value = str_replace(",", ", ", $wanted_type_value);

                                            echo specification( $wanted_type_value, $wanted_type_key, null );
                                        }

                                        // Roomcount
                                        if( $room_count_value = get_locale_value( $buy_commission, 'roomCount' ) ){
                                            $room_count_key   = get_locale_key( $buy_commission, 'roomCount' );

                                            echo specification( $room_count_value, $room_count_key, null );
                                        }

                                    ?>
                                </div>
                                <div class="linear-o-col-6@sm">

                                    <?php

                                        //
                                        // Right specifications
                                        //
                                    
                                        // Type
                                        if( $type_value = get_locale_value( $buy_commission, 'wantedType' ) ){
                                            $type_key   = get_locale_key( $buy_commission, 'wantedType' );

                                            echo specification( $type_value, $type_key, null );
                                        }
                                    
                                        // Ownership type
                                        if( $ownership_type_value = get_locale_value( $buy_commission, 'ownershipType' )){
                                            $ownership_type_key   = get_locale_key( $buy_commission, 'ownershipType' );

                                            echo specification( $ownership_type_value, $ownership_type_key, null );
                                        }
                                    
                                        // ID
                                        $id = isset( $buy_commission->id ) ? $buy_commission->id : '';

                                        echo specification( $id, __('Buy commission ID', 'linear' ), null );
                                    
                                    ?>

                                </div>
                            </div>
                        </div>
                    </div>

                    <?php do_action('linear_buy_commissions_introduction', $buy_commission); ?>

                </div>
            </div>
        </div>
        
    <?php return ob_get_clean();
}