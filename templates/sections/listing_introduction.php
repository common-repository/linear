<?php

namespace Linear\Templates;

function listing_introduction( $listing ) {

    $is_bidding = has_value( get_value( $listing, 'bidding' ), 'id' );
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

                                if( $is_bidding ){
                                    echo bidding_label( $listing['bidding'] );
                                }

                                if( has_array_values( $listing, ['address', 'gate'] ) ){
                                    $get_title_parts  = get_clean_array( $listing, [ 'address', 'gate' ] );
                                    $title            = $get_title_parts ? esc_html( implode( ' ', $get_title_parts ) ) : '';

                                    echo text_heading( $title );
                                }
                                
                                if( has_array_values( $listing, ['city', 'districtFree'] ) ){
                                    $get_location_parts = get_clean_array( $listing, [ 'city', 'districtFree' ] );
                                    $location           = $get_location_parts ? esc_html( implode( ', ', $get_location_parts ) ) : '';

                                    echo text_subheading( $location );
                                }

                                if( has_value( $listing, 'typeOfApartment' ) ){
                                    echo '<p>' . $listing['typeOfApartment'] . '</p>';
                                }

                            ?></div>
                            
                            <?php
                                
                                // special cases for rent & price
                                if( 
                                    comparator_is_sub_value( $listing, ['rawDataForFiltering', 'commissionType'], 'RENT')
                                ){
                                    // special case rent

                                    $formatted_rent     = get_object_value( $listing, 'formatted_rent', '');
                                    $rent_update_date   = get_object_value( $listing, 'rentUpdateDate', '');
                                    $updated_rent       = get_object_value( $listing, 'updatedRent', '');

                                    if( !$listing['rawRent'] || $listing['rawRent'] === 0 ){
                                        $formatted_rent = __( 'Ask price', 'linear' );
                                    }

                                    echo text_rent_updated( $formatted_rent, $rent_update_date, $updated_rent, 'price-tag' );

                                } else {
                                    // special case for price

                                    $debt_free_price    = get_object_value( $listing, 'formatted_debtFreePrice', null);
                                    $squarePrice = get_object_value( $listing, 'squarePrice', null);
                                    $bidding            = get_object_value( $listing, 'bidding', null);

                                    if( !$listing['rawDebtFreePrice'] || $listing['rawDebtFreePrice'] === 0 ){
                                        $debt_free_price = __( 'Ask price', 'linear' );
                                    }

                                    echo text_price( $debt_free_price, $squarePrice, $bidding );
                                }

                                // special case for area and lotArea
                                if( comparator_is_sub_value( $listing, ['rawDataForFiltering', 'listingType'], 'PLOT') ){
                                    // special case for lotArea

                                    $lotArea = get_object_value( $listing, 'lotArea', null );

                                    echo text_area( $lotArea, null, 'ground-plan' );
                                } else {
                                    // special case for area

                                    $area = get_object_value( $listing, 'area', null );

                                    echo text_area( $area, null, 'ground-plan' );
                                }
                            
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

                                        if( has_value( $listing, 'listingType' ) ){

                                            $listing_type   = get_object_value( $listing, 'listingType', null );
                                            $label          = esc_html__( 'Type', 'linear' );
                                            $icon           = 'building';

                                            echo specification( $listing_type, $label, $icon );
                                        }

                                        if( !comparator_is_sub_value( $listing, ['rawDataForFiltering', 'listingType'], 'PLOT') ){
                                            if( has_value( $listing, 'listingType' ) ){

                                                $complete_year      = get_object_value( $listing, 'completeYear', '-');
                                                $deployment_year    = get_object_value( $listing, 'deploymentYear', null);
                                                $label              = esc_html__( 'Year of construction', 'linear' );
                                                $icon               = 'crane';
    
                                                echo specification( ($complete_year ? $complete_year : $deployment_year), $label, $icon );
                                            }
                                        }

                                        $presentation   = get_presentation( $listing, ['PUBLIC', 'FIRST_SHOWING'], '-' );
                                        $label          = esc_html__( 'Next presentation', 'linear' );
                                        $icon           = 'open-house';

                                        echo specification( $presentation, $label, $icon );

                                    ?>
                                </div>
                                <div class="linear-o-col-6@sm">

                                    <?php

                                        //
                                        // Right specifications
                                        //
                                    
                                        if( !comparator_is_sub_value( $listing, ['rawDataForFiltering', 'listingType'], 'PLOT') ){

                                            if( 
                                                comparator_is_sub_value( $listing, ['rawDataForFiltering', 'commissionType'], 'RENT')
                                            ){
                                                
                                                if( has_value( $listing, 'securityDeposit' ) ){

                                                    $security_deposit    = get_object_value( $listing, 'securityDeposit', '-' );
                                                    $label              = esc_html__( 'Security deposit', 'linear' );
                                                    $icon               = 'deposit';

                                                    echo specification( $security_deposit, $label, $icon );
                                                }
                                                
                                                //if( has_value( $listing, 'waterCharge' ) ){

                                                    $water_charge    = get_object_value( $listing, 'waterCharge', '-' );
                                                    $label          = esc_html__( 'Water charge', 'linear' );
                                                    $icon           = 'faucet';

                                                    echo specification( $water_charge, $label, $icon );
                                                //}

                                            } else {

                                                if(
                                                    ( has_value( $listing, 'formatted_mandatoryCharges' ) && get_object_value( $listing, 'formatted_mandatoryCharges' ) !== "0 €" )
                                                ){
                                                    
                                                    $merged_charges             = get_object_value( $listing, 'formatted_mandatoryCharges' );
                                                    $label                      = esc_html__( 'Mandatory charges', 'linear' );
                                                    $icon                       = 'wrench';

                                                    if( $merged_charges && ( !in_array( $listing['mandatoryCharges'], ["0", "0 €", "0 €"] ) ) ){
                                                        echo specification( get_value( $listing, 'formatted_mandatoryCharges' ), $label, $icon );
                                                    }
                                                }

                                                if( has_value( $listing, 'lotOwnership' ) ){

                                                    $lot_ownership  = get_object_value( $listing, 'lotOwnership', '-' );
                                                    $label          = esc_html__( 'Lot', 'linear' );
                                                    $icon           = 'plot';

                                                    echo specification( $lot_ownership, $label, $icon );
                                                }

                                                if( has_value( $listing, 'pipeRenovationYear' ) ){

                                                    $pipe_renovation_year   = get_object_value( $listing, 'pipeRenovationYear', '' );
                                                    $label                  = esc_html__( 'Pipe renovation', 'linear' );
                                                    $icon                   = 'pipes';

                                                    echo specification( $pipe_renovation_year, $label, $icon );
                                                }

                                            }

                                        }
                                    
                                    ?>

                                </div>
                            </div>
                        </div>
                    </div>

                    <?php
                    
                    //
                    // Download documents & Make an offer
                    //
                    
                    if( 
                            get_object_value( $listing, 'status' ) !== 'LIGHT_MIGRATED' &&
                            comparator_is_sub_value( $listing, ['rawDataForFiltering', 'productGroup'], 'APARTMENTS') &&
                            !comparator_is_sub_value( $listing, ['rawDataForFiltering', 'type'], 'PROPERTY') &&
                            get_object_value( $listing, 'integrationDixuEnabled' )
                    ){ ?>

                        <div class="linear-o-row linear-dixu-integration-buttons">
                            <div class="linear-o-col-6@sm">
                                <a 
                                    href="<?php echo get_listing_link( $listing, 'dixu_listing_url' ); ?>"
                                    target="_blank" 
                                    class="linear-c-button linear-c-button--outline linear-c-button--primary linear-c-button--full linear-u-mb-1 linear-u-mb-0@sm"
                                >
                                    <div class="linear-c-introduction__icon">
                                        <?php echo icon( 'download' ); ?>
                                    </div>
                                    <?php esc_html_e( 'Download documents', 'linear' ); ?>
                                </a>
                            </div>

                            <div class="linear-o-col-6@sm">
                                <a 
                                    href="<?php echo $is_bidding ? get_listing_link( $listing, 'dixu_bidding_url' ) : get_listing_link( $listing, 'dixu_listing_url' ) ?>" 
                                    target="_blank" 
                                    class="linear-c-button linear-c-button--solid linear-c-button--primary linear-c-button--full"
                                >
                                    <div class="linear-c-introduction__icon">
                                        <?php echo icon( 'euro' ); ?>
                                    </div>
                                    <?php 
                                        if ( $is_bidding ) {
                                            esc_html_e( 'Follow bidding', 'linear' );
                                        } else {
                                            esc_html_e( 'Make an offer', 'linear' );
                                        }
                                    ?>
                                </a>
                            </div>
                        </div>

                    <?php } ?>

                    <?php do_action('linear_listing_introduction', $listing); ?>

                </div>
            </div>
        </div>
        
    <?php return ob_get_clean();
}