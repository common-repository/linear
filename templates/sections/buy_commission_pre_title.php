<?php

namespace Linear\Templates;

function buy_commission_pre_title( $buy_commission = null ) {
    if( !$buy_commission ){
        return '';
    }

    $buy_commissions_page_id = get_linear_option_value( 'buy_commissions_page' );

    if( !$buy_commissions_page_id ){
        return "";
    }

    $buy_commissions_page_url = get_permalink( $buy_commissions_page_id );

    $pre_title = '';

    if( $location = get_locale_value( $buy_commission, 'location' ) ){
        $pre_title .= $location;
    }

    if( $wanted_listing_type = get_locale_value( $buy_commission, 'wantedListingType' ) ){
        $pre_title .= ( $pre_title !== '' ? ', ' : '') . $wanted_listing_type;
    }

    // price range
    $debt_free_price_lower  = get_locale_value( $buy_commission, 'debtFreePriceLowerBound' );
    $debt_free_price_upper  = get_locale_value( $buy_commission, 'debtFreePriceUpperBound' );

    switch ( true ) {
        case ( !!$debt_free_price_lower && !!$debt_free_price_upper ):
            $pre_title .= ( $pre_title !== '' ? ', ' : '');
            
            $pre_title .= $debt_free_price_lower . ' - ' . $debt_free_price_upper;
            
            $wantedListingType;
            break;
        case !!$debt_free_price_lower:
            $pre_title .= ( $pre_title !== '' ? ', ' : '');
            
            $pre_title .= $debt_free_price_lower;
            break;
        case !!$debt_free_price_upper:
            $pre_title .= ( $pre_title !== '' ? ', ' : '');
            
            $pre_title .= $debt_free_price_upper;
            break;
    }

    // area range
    $living_area_lower  = get_locale_value( $buy_commission, 'livingAreaLowerBound' );
    $living_area_upper  = get_locale_value( $buy_commission, 'livingAreaUpperBound' );

    switch ( true ) {
        case ( !!$living_area_lower && !!$living_area_upper ):
            $pre_title .= ( $pre_title !== '' ? ', ' : '');
            
            $pre_title .= $living_area_lower . '&nbsp;m2';
            $pre_title .= ' - ';
            $pre_title .= $living_area_upper . '&nbsp;m2';
            
            $wantedListingType;
            break;
        case !!$living_area_lower:
            $pre_title .= ( $pre_title !== '' ? ', ' : '');
            
            $pre_title .= $living_area_lower . '&nbsp;m2';
            break;
        case !!$living_area_upper:
            $pre_title .= ( $pre_title !== '' ? ', ' : '');
            
            $pre_title .= $living_area_upper . '&nbsp;m2';
            break;
    }

    if( $pre_title === '' ){
        return '';
    }

    $pre_title = str_replace(", ", ",", $pre_title);
    $pre_title = str_replace(",", ", ", $pre_title);

    ob_start(); ?>
    
        <div class="linear-o-row">
            <div class="linear-o-col-12">
                <ul class="linear-c-line-info">

                    <li class="linear-c-line-info__item">
                        <span style="color: #8E8D8A;">
                            <a style="color: #8E8D8A;" href="<?php echo $buy_commissions_page_url ?>"><?php echo __('Buy commission', 'linear'); ?></a> / 
                        </span>
                        <?php echo $pre_title; ?>
                    </li>

                </ul>
            </div>
        </div>

    <?php return ob_get_clean();
}
