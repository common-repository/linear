<?php

namespace Linear\Templates;

function buy_commission_content( $buy_commission = null, $id = null ) {

    if( !$buy_commission ){
        return '';
    }

    ob_start(); ?>

        <div class="linear-o-row">
            <div class="linear-o-col-12">
                <div class="linear-c-card">

                    <div class="linear-o-row">
                            <div class="linear-o-col-12">
                                <div class="linear-o-row linear-u-mb-1">
                                    <div class="linear-o-col-6@sm">

                                        <?php

                                            //
                                            // Left specifications
                                            //

                                            // Construction year
                                            if( $construction_year_value    = get_locale_value( $buy_commission, 'constructionYearSpecify' ) ){
                                                $construction_year_key      = get_locale_key( $buy_commission, 'constructionYearSpecify' );

                                                echo specification( $construction_year_value, $construction_year_key, null );
                                            }

                                            // Plot ownership type
                                            if( $plot_ownership_type_value = get_locale_value( $buy_commission, 'plotOwnershipType' ) ){
                                                $plot_ownership_type_key   = get_locale_key( $buy_commission, 'plotOwnershipType' );

                                                echo specification( $plot_ownership_type_value, $plot_ownership_type_key, null );
                                            }

                                            // Plot area specify
                                            if( $plot_area_specify_value = get_locale_value( $buy_commission, 'plotAreaSpecify' ) ){
                                                $plot_area_specify_key   = get_locale_key( $buy_commission, 'plotAreaSpecify' );

                                                echo specification( $plot_area_specify_value, $plot_area_specify_key, null );
                                            }

                                        ?>
                                    </div>
                                    <div class="linear-o-col-6@sm">

                                        <?php

                                            //
                                            // Right specifications
                                            //
                                        
                                            // Condition
                                            if( $condition_value = get_locale_value( $buy_commission, 'condition' ) ){
                                                $condition_key   = get_locale_key( $buy_commission, 'condition' );

                                                echo specification( $condition_value, $condition_key, null );
                                            }
                                        
                                            // Release
                                            if( $release_value = get_locale_value( $buy_commission, 'release' ) ){
                                                $release_key   = get_locale_key( $buy_commission, 'release' );

                                                echo specification( $release_value, $release_key, null );
                                            }
                                        
                                            // Housing coop includes
                                            if( $housing_coop_includes_value    = get_locale_value( $buy_commission, 'housingCoopIncludes' ) ){
                                                $housing_coop_includes_key      = get_locale_key( $buy_commission, 'housingCoopIncludes' );

                                                echo specification( $housing_coop_includes_value, $housing_coop_includes_key, null );
                                            }
                                        
                                        ?>

                                    </div>
                                </div>
                            </div>
                        </div>

                    <div class="linear-o-row">
                        <div class="linear-o-col-12">
                            <p>
                                <?php
                                    //
                                    // Text contents
                                    //

                                    // Room description
                                    if( $room_description_value    = get_locale_value( $buy_commission, 'roomDescription' ) ){
                                        $room_description_key      = get_locale_key( $buy_commission, 'roomDescription' );

                                        ?>
                                            <div class="linear-c-icon-info linear-specification">
                                                <div>
                                                    <p class="linear-c-icon-info__label linear-specification__label"><?php echo $room_description_key ?></p>
                                                    <p class="linear-c-icon-info__main linear-specification__value"><?php echo $room_description_value ?></p>
                                                </div>
                                            </div>
                                        <?php
                                    }
                                    
                                    // Release special condition
                                    if( $release_special_condition_value    = get_locale_value( $buy_commission, 'releaseSpecialCondition' ) ){
                                        $release_special_condition_key      = get_locale_key( $buy_commission, 'releaseSpecialCondition' );

                                        ?>
                                            <div class="linear-c-icon-info linear-specification">
                                                <div>
                                                    <p class="linear-c-icon-info__label linear-specification__label"><?php echo $release_special_condition_key ?></p>
                                                    <p class="linear-c-icon-info__main linear-specification__value"><?php echo $release_special_condition_value ?></p>
                                                </div>
                                            </div>
                                        <?php
                                    }
                                    
                                    // Special wishes
                                    if( $special_wishes_value    = get_locale_value( $buy_commission, 'specialWishes' ) ){
                                        $special_wishes_key      = get_locale_key( $buy_commission, 'specialWishes' );

                                        ?>
                                            <div class="linear-c-icon-info linear-specification">
                                                <div>
                                                    <p class="linear-c-icon-info__label linear-specification__label"><?php echo $special_wishes_key ?></p>
                                                    <p class="linear-c-icon-info__main linear-specification__value"><?php echo $special_wishes_value ?></p>
                                                </div>
                                            </div>
                                        <?php
                                    }

                                    // ID
                                    if( $id ){ ?>
                                            <div class="linear-c-icon-info linear-specification">
                                                <div>
                                                    <p class="linear-c-icon-info__label linear-specification__label"><?php echo __('Buy commission ID','linear') ?></p>
                                                    <p class="linear-c-icon-info__main linear-specification__value"><?php echo $id ?></p>
                                                </div>
                                            </div>
                                        <?php
                                    }
                                    
                                ?>
                            </p>

                            <?php do_action('linear_buy_commissions_content', $buy_commission); ?>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    <?php
    return ob_get_clean();
}