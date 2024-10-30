<?php

namespace Linear\Templates;

function buy_commission_realtor( $buy_commission = null ) {

    if( !$buy_commission ){
        return '';
    }

    if( isset($buy_commission['realtor']) ){
        $realtor = $buy_commission['realtor'];
    }

    if ( !$realtor ) {
        return '';
    }

    $contact_link = '';
    if( esc_html( get_linear_option_value('contact_url') ) ){
        $contact_link = esc_html( get_linear_option_value('contact_url') );

        $contact_link_query = parse_url( esc_html( get_linear_option_value('contact_url') ), PHP_URL_QUERY );

        $additive_buy_commission_id = 'listing_id=' . $buy_commission['id'];
        $additive_buy_commission_realtor_name = 'realtor_name=' . ( isset( $realtor['name'] ) ? urlencode( $realtor['name'] ) : '' );
        $additive_buy_commission_realtor_email = 'realtor_email=' . ( isset( $realtor['email'] ) ? urlencode( $realtor['email'] ) : '' );
        $additive_buy_commission_realtor_company = 'realtor_company=' . ( isset( $realtor['companyName'] ) ? urlencode( $realtor['companyName'] ) : '' );
        $additive_buy_commission_realtor_company_email = 'realtor_company_email=' . ( isset( $realtor['companyEmail'] ) ? urlencode( $realtor['companyEmail'] ) : '');

        if( $contact_link_query ){
            $contact_link .= '&';
        } else {
            $contact_link .= '?';
        }

        $contact_link .= $additive_buy_commission_id . 
        //'&' . strtolower( $additive_buy_commission_short_id ) .
        //'&' . strtolower( $additive_buy_commission_address ) .
        '&' . strtolower( $additive_buy_commission_realtor_name ) .
        '&' . strtolower( $additive_buy_commission_realtor_email ) .
        '&' . strtolower( $additive_buy_commission_realtor_company ) .
        '&' . strtolower( $additive_buy_commission_realtor_company_email );

    }

    ob_start(); ?>

        <div class="linear-o-row">
            <div class="linear-o-col-12">
                <div class="linear-c-card">
                    <div class="linear-o-row linear-u-items-center linear-u-text-center linear-u-text-left@md" style="display: flex; justify-content: space-between">

                        <?php if( isset( $realtor['avatar'] ) && $realtor['avatar'] ){ ?>
                            <div class="linear-o-col-5 linear-o-col-2@md linear-o-col-1@xl linear-u-mx-auto">
                                <img src="<?php echo $realtor['avatar']; ?>" class="linear-c-avatar--agent linear-u-mb-2 linear-u-mb-0@md linear-u-ml-0@md linear-u-mx-auto" />
                            </div>
                        <?php } ?>

                        <div class="linear-o-col-7@md linear-o-col-9@xl">
                        <p class="linear-u-mb-0.5"><?php echo $realtor['name']; ?> - <?php echo $realtor['companyName']; ?></p>
                            <a class="linear-c-card__heading__phone" href="tel:<?php echo $realtor['tel']?>">
                                <span class="linear-c-card__heading__icon"><?php echo icon( 'phone' ); ?></span>
                                <span class="linear-c-card__heading__number"><?php echo $realtor['tel']?></span>
                            </a>
                            <a class="linear-c-card__heading__email" href="mailto:<?php echo $realtor['email']?>">
                                <span class="linear-c-card__heading__icon"><?php echo icon( 'envelope' ); ?></span>
                                <span class="linear-c-card__heading__text"><?php echo $realtor['email']?></span>
                            </a>
                        </div>


                        <div class="linear-o-col-3@md linear-o-col-2@xl linear-u-text-right@md mobile-margin-top">

                            <?php if ( get_linear_option_value('contact_url') !== null && $contact_link ) { ?>
                                <a 
                                    href="<?php echo $contact_link; ?>" 
                                    target="_blank" 
                                    class="linear-c-button linear-c-button--outline linear-c-button--primary linear-c-button--full"
                                ><?php
                                    esc_attr_e( 'Contact', 'linear' );
                                ?></a>
                            <?php } ?>

                            <div class="linear-u-mt-0.5 linear-c-logo--company">
                                <img src="<?php echo get_company_logo( $realtor ); ?>" class="linear-c-logo linear-c-avatar--company__content linear-u-mx-auto linear-u-ml-0@md">
                            </div>
                        </div>

                    </div>

                    <?php do_action('linear_buy_commission_realtor', $buy_commission); ?>
                    <?php do_action('linear_realtor', $buy_commission); ?>
                </div>
            </div>
        </div>
        
    <?php return ob_get_clean();
}
