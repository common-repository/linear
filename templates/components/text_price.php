<?php

namespace Linear\Templates;

function text_price( $debtFreePrice = null, $squarePrice = null, $bidding = null, $icon = 'price-tag' ) {
    if( !$debtFreePrice && !$bidding ){
        return "";
    }

    ob_start(); ?>

        <div class="linear-c-icon-info linear-u-mb-0 compontent-text-price">
            <div class="linear-c-icon-info__icon linear-icon-color-plugin">
                <?php if( $icon ){
                    echo icon( $icon );
                } ?>
            </div>
            <div>
                <p class="linear-c-icon-info__main linear-c-icon-info__main--lg">

                    <?php
                    
                    $output = $debtFreePrice;
                    
                    if( has_value( $bidding, 'latestOfferPrice' ) ){ // Todo clarify what the values truly are

                        $output = number_format( intval( str_replace(' ', '', $bidding['latestOfferPrice']) ), 0, ",", " ") . '&nbsp;€';
                        
                    } else if( has_value( $bidding, 'latestPriceOffer' ) ){

                        $output = number_format( intval( str_replace(' ', '', $bidding['latestPriceOffer']) ), 0, ",", " ") . '&nbsp;€';
                        
                    } else if( has_value( $bidding, 'debtlessStartPrice' ) ){
                        
                        $output = number_format( intval( str_replace(' ', '', $bidding['debtlessStartPrice']) ), 0, ",", " ") . '&nbsp;€';

                    }

                    echo $output;
                    
                    if( $output === $debtFreePrice ){
                        if( $squarePrice ){ ?>
                            <span>
                                (<?php echo $squarePrice; ?>)
                            </span>
                        <?php }
                    } ?>

                </p>
            </div>
        </div>

    <?php return ob_get_clean();
}

?>