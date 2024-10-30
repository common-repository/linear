<?php

namespace Linear\Templates;

function listing_pre_title( $listing ) {

    $address_gate       = get_imploded_clean_array( $listing, [ 'address', 'gate' ], ' ', '' );
    $city_district      = get_imploded_clean_array( $listing, [ 'city', 'districtFree' ], ', ', '' );
    $type_of_apartment  = output_string( $listing, 'typeOfApartment' );

    if( !$address_gate && !$city_district){
        return "";
    }

    ob_start(); ?>
    
        <div class="linear-o-row">
            <div class="linear-o-col-12">
                <ul class="linear-c-line-info">

                    <?php if( $address_gate ){ ?>
                        <li class="linear-c-line-info__item">
                            <?php echo $address_gate; ?>
                        </li>
                    <?php }

                    if( $city_district ){ ?>
                        <li class="linear-c-line-info__item">
                            <?php echo $city_district; ?>
                        </li>
                    <?php }

                    if( $type_of_apartment ){ ?>
                        <li class="linear-c-line-info__item">
                            <?php echo $type_of_apartment; ?>
                        </li>
                    <?php } ?>

                </ul>
            </div>
        </div>

    <?php return ob_get_clean();
}
