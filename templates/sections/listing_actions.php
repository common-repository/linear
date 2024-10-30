<?php

namespace Linear\Templates;

function listing_actions( $listing ) {

    ob_start(); ?>

    <?php if(
        get_object_value( $listing, 'status') !== 'LIGHT_MIGRATED' &&
        !comparator_is_sub_value( $listing, ['rawDataForFiltering', 'commissionType'], 'RENT')
    ){ ?>

        <div class="linear-o-row linear-u-justify-center">

            <div class="linear-single-column-section <?php echo esc_attr( !get_linear_option_value('loan_request') ? 'linear-o-col-6@md linear-o-col-4@lg' : 'linear-o-col-12@sm linear-o-col-6@md'); ?> linear-u-mb-3">
                <?php

                $viewing_link = '';
                if( get_object_value( $listing, 'integrationDixuEnabled' ) ){
                    $viewing_link = get_listing_link( $listing, 'dixu_listing_url', false );
                } else if ( get_constant( $listing, 'status') === 'LIGHT_MIGRATED' || get_linear_option_value('contact_url') !== null && esc_html( get_linear_option_value('contact_url') ) ) {

                    $viewing_link = esc_html( get_linear_option_value('contact_url') );

                    $viewing_link_query = parse_url( esc_html( get_linear_option_value('contact_url') ), PHP_URL_QUERY );
            
                    $additive_listing_id = 'listing_id=' . $listing['id'];
                    $additive_listing_short_id = 'short_id=' . $listing['identifier'];
                    $additive_listing_address = 'listing_address=' . urlencode( $listing['address'] );
                    $additive_listing_realtor_name = 'realtor_name=' . urlencode( $listing['realtor']['name'] );
                    $additive_listing_realtor_email = 'realtor_email=' . urlencode( $listing['realtor']['email'] );
                    $additive_listing_realtor_company = 'realtor_company=' . urlencode( $listing['realtor']['companyName'] );
                    $additive_listing_realtor_company_email = 'realtor_company_email=' . urlencode( $listing['realtor']['companyEmail'] );
            
                    if( $viewing_link_query ){
                        $viewing_link .= '&';
                    } else {
                        $viewing_link .= '?';
                    }
            
                    $viewing_link .= $additive_listing_id . 
                        '&' . strtolower( $additive_listing_short_id ) .
                        '&' . strtolower( $additive_listing_address ) .
                        '&' . strtolower( $additive_listing_realtor_name ) .
                        '&' . strtolower( $additive_listing_realtor_email ) .
                        '&' . strtolower( $additive_listing_realtor_company ) .
                        '&' . strtolower( $additive_listing_realtor_company_email );
                }

                if( $viewing_link ){ ?>
                    <a href="<?php echo $viewing_link; ?>" class="linear-c-card linear-c-card--full-height" target="_blank" rel="noopener noreferrer">
                <?php } else { ?>
                    <div class="linear-c-card linear-c-card--full-height" target="_blank" rel="noopener noreferrer">
                <?php } ?>

                        <div class="linear-o-row linear-u-items-center">
                            <div class="linear-o-col-2 linear-o-col-3@md linear-u-text-center@sm linear-u-mb-1 linear-u-mb-0@sm linear-c-actions__icon">
                                <?php echo icon( 'calendar' ); ?>
                            </div>

                            <div class="linear-o-col-10 linear-o-col-9@md">
                                <p class="linear-c-card__heading linear-c-card__heading--xs"><?php esc_html_e( 'Book a private tour', 'linear' ); ?></p>
                                <p class="linear-u-text-sm">
                                    <?php esc_html_e( 'Next public presentation', 'linear' ); ?>: <br>
                                    <?php echo get_presentation( $listing, ['PUBLIC', 'FIRST_SHOWING'], '-' ); ?>
                                </p>
                            </div>
                        </div>

                <?php if( $viewing_link ){ ?>
                    </a>
                <?php } else { ?>
                    </div>
                <?php } ?>

            </div>

            <div class="linear-single-column-section <?php echo esc_attr( !get_linear_option_value('loan_request')  ? 'linear-o-col-6@md linear-o-col-4@lg' : 'linear-o-col-12@sm linear-o-col-6@md'); ?> linear-u-mb-3">
                <div class="linear-c-card linear-c-card--full-height" target="_blank" rel="noopener noreferrer">
                    <div class="linear-o-row linear-u-items-center">

                        <div class="linear-o-col-5 linear-o-col-4@md linear-u-text-center@sm linear-u-mb-0@sm">
                            <p class="linear-c-card__heading linear-c-card__heading--xs"><?php echo _x( 'Share to a friend', 'Share to a friend', 'linear' ); ?></p>
                        </div>

                        <div class="linear-o-col-7 linear-o-col-8@md">

                            <?php
                                $get_title_parts  = get_clean_array( $listing, [ 'address', 'gate' ] );
                                $title            = $get_title_parts ? esc_html( implode( ' ', $get_title_parts ) ) : '';
                            
                                echo share_links(
                                    $title, 
                                    ( has_value( $listing, 'permalink' ) ? get_object_value( $listing, 'permalink' ) : get_permalink() ), 
                                    get_home_url()
                                );
                            ?>

                        </div>

                    </div>
                </div>
            </div>

            <?php if ( !get_linear_option_value('loan_request') && comparator_is_sub_value( $listing, ['rawDataForFiltering', 'commissionType'], 'RENT') ) { ?>
                <div class="linear-o-col-12@sm linear-o-col-6@md linear-o-col-4@lg linear-u-mb-3 linear-single-column-section">
                    <a href="<?php echo get_listing_link( $listing, 'dixu_listing_url' ); ?>" class="linear-c-card linear-c-card--full-height" target="_blank" rel="noopener noreferrer">
                        <div class="linear-o-row linear-u-items-center">
                            <div class="linear-o-col-2 linear-o-col-3@md linear-u-text-center@sm linear-u-mb-1 linear-u-mb-0@sm linear-c-actions__icon">
                                <?php echo icon( 'payment' ); ?>
                            </div>

                            <div class="linear-o-col-10 linear-o-col-9@md">
                                <p class="linear-c-card__heading linear-c-card__heading--xs">
                                    <?php esc_html_e( 'Apply for a loan', 'linear' ); ?>
                                </p>
                                <p class="linear-u-text-sm">
                                    <?php esc_html_e( 'In cooperation', 'linear' ); ?> <svg style="margin-left:10px;width:80px; height:auto;" class="linear-o-icon linear-u-align-bottom" viewBox="0 0 86 21"><path d="M78 5.5a7 7 0 00.5 14c1.5 0 3.4-.8 4.3-2.3v2h2.7v-6.6A6.8 6.8 0 0078 5.5zm4.6 7.1a4 4 0 01-4.5 3.8 4.1 4.1 0 01-3.5-3.5 4 4 0 014-4.5 4 4 0 014 3.8v.4zM63.2 5.5a6.9 6.9 0 00-6.8 7c0 3.8 3 7 7 7a7 7 0 006.5-4.3l-2.6-.8a4.3 4.3 0 01-3 2.3c-2 .4-3.7-.8-4.4-2l10.5-3a7 7 0 00-7.2-6.2zM59.4 12c0-1.4.8-3.1 2.7-3.8 2.1-.8 4 .3 4.8 1.8l-7.5 2zM40.3 8.3V5.4c-2.7 0-3.5 1.3-4 2V6h-3v13.3h3v-6.6c0-3 1.8-4.2 4-4.3zM12.6 14L3.5 3H.4v16.1h3.2V8.4L13 19.3h2.8V3h-3V14z"/><path d="M54.6 1.7h-2.9v5.2c-.7-.8-2.8-1.6-4.6-1.4a7 7 0 00.5 14c1.5 0 3.6-.7 4.2-2v1.7h2.7v-6.8h0l.1-10.7zm-3 11a4 4 0 01-4.5 3.7 4.1 4.1 0 01-3.5-3.5 4 4 0 014-4.5 4 4 0 014 3.8v.4zm-27-7.3a7 7 0 100 14 7 7 0 000-14zm0 11c-2.3 0-4-1.8-4-4s1.7-4 4-4 4 1.9 4 4a4 4 0 01-4 4z"/></svg>
                                </p>
                            </div>
                        </div>
                    </a>
                </div>
            <?php } ?>

            <?php do_action('linear_listing_actions', $listing); ?>

        </div>

    <?php } ?>
        
    <?php return ob_get_clean();
}
