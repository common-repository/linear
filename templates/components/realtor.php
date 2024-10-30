<?php

namespace Linear\Templates;

function realtor( $listing = null ) {

    if( !$listing || !$listing['realtor'] ){
        return '';
    }

    $realtor = $listing['realtor'];
    $use_realtor_data = false;
    if( isset( $listing['contactInfoSource'] ) && $listing['contactInfoSource'] === 'REALTOR' ){
        $use_realtor_data = true;
    }

    $options = get_option( 'linear_settings' );
    $contact_method = '';
    if( isset( $options['contact_method'] ) ){
        $contact_method = $options['contact_method'];
    }

    $contact_api_url = '';
    if( isset( $options['contact_api_url'] ) ){
        $contact_api_url = $options['contact_api_url'];
    }

    // Custom URL contact link
    $contact_link = '';
    if( $contact_method === 'custom_url' ){
        if( esc_html( get_linear_option_value('contact_url') ) ){
            $contact_link = esc_html( get_linear_option_value('contact_url') );
    
            $contact_link_query = parse_url( esc_html( get_linear_option_value('contact_url') ), PHP_URL_QUERY );
    
            $additive_listing_id = 'listing_id=' . $listing['id'];
            $additive_listing_short_id = 'short_id=' . $listing['identifier'];
            $additive_listing_address = 'listing_address=' . urlencode( $listing['address'] );
            $additive_listing_realtor_name = 'realtor_name=' . urlencode( $realtor['name'] );
            $additive_listing_realtor_email = 'realtor_email=' . urlencode( $realtor['email'] );
            $additive_listing_realtor_company = 'realtor_company=' . urlencode( $realtor['companyName'] );
            $additive_listing_realtor_company_email = 'realtor_company_email=' . urlencode( $realtor['companyEmail'] );
    
            if( $contact_link_query ){
                $contact_link .= '&';
            } else {
                $contact_link .= '?';
            }
    
            $contact_link .= $additive_listing_id . 
            '&' . strtolower( $additive_listing_short_id ) .
            '&' . strtolower( $additive_listing_address ) .
            '&' . strtolower( $additive_listing_realtor_name ) .
            '&' . strtolower( $additive_listing_realtor_email ) .
            '&' . strtolower( $additive_listing_realtor_company ) .
            '&' . strtolower( $additive_listing_realtor_company_email );
    
        } else if( get_object_value( $listing, 'integrationDixuEnabled' ) ) {
            $contact_link = esc_html( get_listing_link( $listing, 'dixu_listing_url' ) );
        }
    }

    $contact_form_embed = '';
    if( $contact_method === 'form' ){
        $contact_form_embed = get_linear_option_value('contact_form_embed');
        $contact_form_embed = preg_replace('/data-listing-id="[^"]*"/', 'data-listing-id="' . $listing['id'] . '"', $contact_form_embed);

        if (strpos($contact_form_embed, 'data-listing-id=') === false) {
            $contact_form_embed = str_replace("<div ", "<div data-listing-id='" . $listing["id"] . "' ", $contact_form_embed);
        }
    }

    ob_start();
        
    /**
     * Realtor data with form
     */
        
        ?><div class="linear-o-row">
            <div class="linear-o-col-12">
                <div class="linear-c-card">
                    <div class="linear-o-row linear-u-items-center linear-u-text-center linear-u-text-left@md" style="display: flex; justify-content: space-between">

                        <?php if( $use_realtor_data && isset( $realtor['avatar'] ) && $realtor['avatar'] ){ ?>
                            <div class="linear-o-col-5 linear-o-col-2@md linear-o-col-1@xl linear-u-mx-auto">
                                <img src="<?php echo $realtor['avatar']; ?>" class="linear-c-avatar--agent linear-u-mb-2 linear-u-mb-0@md linear-u-ml-0@md linear-u-mx-auto" />
                            </div>
                        <?php } ?>

                        <div class="linear-o-col-7@md linear-o-col-9@xl">
                            <p class="linear-c-card__heading linear-c-card__heading--sm linear-u-mb-0.5">

                                <?php
                                    if( isset( $listing['rawDataForFiltering']['productGroup'] ) ){

                                        $type = _x('apartment', 'type name in realtor-block', 'linear');

                                        $data_types = [
                                            [
                                                'value' => 'APARTMENTS',
                                                'string' => _x('apartment', 'type name in realtor-block', 'linear')
                                            ],
                                            [
                                                'value' => 'FARMS',
                                                'string' => _x('farm', 'type name in realtor-block', 'linear')
                                            ],
                                            [
                                                'value' => 'PLOTS',
                                                'string' => _x('plot', 'type name in realtor-block', 'linear')
                                            ],
                                            [
                                                'value' => 'GARAGES',
                                                'string' => _x('garage', 'type name in realtor-block', 'linear')
                                            ],
                                            [
                                                'value' => 'NEWLY_CONSTRUCTED',
                                                'string' => _x('new building', 'type name in realtor-block', 'linear')
                                            ],
                                            [
                                                'value' => 'VACATION_APARTMENT',
                                                'string' => _x('vacation apartment', 'type name in realtor-block', 'linear')
                                            ],
                                            [
                                                'value' => 'BUSINESS_PREMISES',
                                                'string' => _x('business premise', 'type name in realtor-block', 'linear')
                                            ]
                                        ];

                                        foreach( $data_types as $data_type ){
                                            if( $data_type['value'] === $listing['rawDataForFiltering']['productGroup'] ){
                                                $type = $data_type['string'];
                                            }
                                        }

                                        if( comparator_is_sub_value( $listing, ['rawDataForFiltering', 'commissionType'], 'SELL') ){
                                            echo esc_html( __('This listing is sold by', 'linear') );
                                        } else {
                                            echo esc_html( __('This listing is rented by', 'linear') );
                                        }

                                        if( $use_realtor_data ){
                                            echo esc_html(
                                                sprintf(
                                                    ' %s%s',
                                                    $realtor['name'],
                                                    isset( $realtor['jobTitle'] ) && $realtor['jobTitle'] ? ', ' . $realtor['jobTitle'] : ''
                                                )
                                            );
                                        } else {
                                            echo esc_html(
                                                sprintf(
                                                    ' %s',
                                                    $realtor['companyName']
                                                )
                                            );
                                        }
                                    }
                                ?>
                            </p>

                            <?php if( $use_realtor_data ){
                                if( !comparator_is_sub_value( $listing, ['realtor', 'companyName'], null) ){ ?>
                                    <p class="linear-u-mb-0.5"><?php echo $realtor['companyName']; ?></p>
                                    <a class="linear-c-card__heading__phone" href="tel:<?php echo $listing['realtor']['tel']?>">
                                        <span class="linear-c-card__heading__icon"><?php echo icon( 'phone' ); ?></span>
                                        <span class="linear-c-card__heading__number"><?php echo $listing['realtor']['tel']?></span>
                                    </a>
                                    <a class="linear-c-card__heading__email" href="mailto:<?php echo $listing['realtor']['email']?>">
                                        <span class="linear-c-card__heading__icon"><?php echo icon( 'envelope' ); ?></span>
                                        <span class="linear-c-card__heading__text"><?php echo $listing['realtor']['email']?></span>
                                    </a>
                                <?php }
                            } else {
                                if( !comparator_is_sub_value( $listing, ['realtor', 'companyEmail'], null) ){ ?>
                                    <a class="linear-c-card__heading__phone" href="tel:<?php echo $listing['realtor']['companyTel']?>">
                                        <span class="linear-c-card__heading__icon"><?php echo icon( 'phone' ); ?></span>
                                        <span class="linear-c-card__heading__number"><?php echo $listing['realtor']['companyTel']?></span>
                                    </a>
                                    <a class="linear-c-card__heading__email" href="mailto:<?php echo $listing['realtor']['companyEmail']?>">
                                        <span class="linear-c-card__heading__icon"><?php echo icon( 'envelope' ); ?></span>
                                        <span class="linear-c-card__heading__text"><?php echo $listing['realtor']['companyEmail']?></span>
                                    </a>
                                <?php }
                            } ?>

                        </div>


                        <div class="linear-o-col-3@md linear-o-col-2@xl linear-u-text-right@md mobile-margin-top">
                            <?php if( $contact_method === 'custom_url' && $contact_link ) { ?>
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

                    <?php if( $contact_method === 'form' && $contact_form_embed !== '' ){ ?>
                        <div class="linear-o-row linear-u-items-center linear-u-text-center linear-u-text-left@md" style="display: flex; justify-content: space-between; margin-top: 20px;">
                            <div class="linear-o-col-12">
                                <?php echo $contact_form_embed ?>
                            </div>
                        </div>
                    <?php } ?>

                </div>
            </div>
        </div><?php 

        ob_start();

        do_action('linear_listing_realtor', $listing);
        do_action('linear_realtor', $listing);

        $hooks_content = ob_get_clean();

        if( $hooks_content && $hooks_content !== '' ){ ?>
            <div class="linear-o-row">
                <div class="linear-o-col-12">
                    <div class="linear-c-card">
                        <?php echo $hooks_content ?>
                    </div>
                </div>
            </div>
        <?php } ?>
        
    <?php return ob_get_clean();
}