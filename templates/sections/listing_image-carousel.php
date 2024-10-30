<?php

namespace Linear\Templates;

function listing_image_carousel( $listing ) {

    ob_start(); ?>
    
        <div class="linear-o-row">
            <div class="linear-o-col-12">
                <div class="linear-c-card linear-c-card--no-spacing">
                    <div class="linear-c-gallery-slider linear-js-gallery-slider linear-js-fullscreen-element">
                        <div class="linear-u-relative">
                            <button class="linear-c-gallery-slider__fullscreen-toggle linear-js-fullscreen-toggle">
                                <div class="linear-c-gallery-slider__fullscreen-toggle-maximize">
                                    <?php echo icon( 'maximize' ); ?>
                                </div>

                                <div class="linear-c-gallery-slider__fullscreen-toggle-minimize">
                                    <?php echo icon( 'minimize' ); ?>
                                </div>
                            </button>

                            <button class="linear-c-gallery-slider__arrow linear-c-gallery-slider__arrow--previous">
                                <?php echo icon( 'arrow-previous' ); ?>
                            </button>

                            <button class="linear-c-gallery-slider__arrow linear-c-gallery-slider__arrow--next">
                                <?php echo icon( 'arrow-next' ); ?>
                            </button>

                            <div class="linear-c-gallery-slider__main">

                                <?php if( has_value( $listing, 'virtualShowing' ) ){ ?>
                                    <div class="linear-c-gallery-slider__slide linear-c-gallery-slider__iframe linear-u-text-primary">
                                        <iframe width="100%" height="100%" src="<?php echo get_value( $listing, 'virtualShowing' )?>" title="Matterport iframe"></iframe>
                                    </div>
                                <?php } ?>

                                <?php if ( has_value( $listing, 'videoUrl' ) ){ ?>
                                    <div class="linear-c-gallery-slider__slide linear-c-gallery-slider__slide--video linear-js-video-slide linear-u-text-primary" data-embed="<?php echo get_youtube_video_embed_url( $listing['videoUrl'] ); ?>">
                                        <img src="<?php echo get_youtube_video_image_url( $listing['videoUrl'], 'hq720.jpg' ); ?>" class="linear-c-gallery-slider__image">

                                        <a href="<?php echo esc_url( $listing['videoUrl'] ); ?>" target="_blank" rel="noopener noreferrer" class="linear-c-gallery-slider__content linear-c-gallery-slider__slider-play-icon">
                                            <?php echo icon( 'play' ); ?>
                                        </a>
                                    </div>
                                <?php } ?>

                                <?php if( has_value( $listing, 'images' ) ){
                                    foreach( $listing['images'] as $image ){
                                        if( $image['compressed'] ){ ?>
                                            <figure class="linear-c-gallery-slider__slide">
                                                <div class="linear-c-gallery-slider__figurecontents">
                                                    <img src="<?php echo $image['compressed']; ?>" alt="<?php echo $image['description']; ?>" class="linear-c-gallery-slider__image">
                                                    <?php if( $image['description'] ){ ?>
                                                        <figcaption class="linear-c-gallery-slider__caption"><?php echo $image['description'] ?></figcaption>
                                                    <?php } ?>
                                                </div>
                                            </figure>
                                        <?php }
                                    }
                                } ?>
                            </div>
                        </div>
                        <div class="linear-u-relative">
                            <div class="linear-c-gallery-slider__thumbnails">
                                <?php if ( has_value( $listing, 'virtualShowing' ) ){ ?>
                                    <div class="linear-c-gallery-slider__slide linear-c-gallery-slider__slide--matterport">
                                        <div>
                                            <?php echo icon( '360-view' ); ?>
                                            <p class="linear-u-text-sm"><?php esc_html_e( 'Virtual tour', 'linear'); ?></p>
                                        </div>
                                    </div>
                                <?php } ?>

                                <?php if ( has_value( $listing, 'videoUrl' ) ){ ?>
                                    <div class="linear-c-gallery-slider__slide linear-c-gallery-slider__slide--video">
                                        <img src="<?php echo get_youtube_video_image_url( $listing['videoUrl'] ); ?>" class="linear-c-gallery-slider__image">

                                        <a href="<?php echo esc_url( $listing['videoUrl'] ); ?>" target="_blank" rel="noopener noreferrer" class="linear-c-gallery-slider__content linear-c-gallery-slider__thumbnail-play-icon">
                                            <?php echo icon( 'play' ); ?>
                                        </a>
                                    </div>
                                <?php } ?>

                                <?php if( has_value( $listing, 'thumbnails' ) ){
                                    foreach( $listing['thumbnails'] as $thumbnail ){
                                        if( $thumbnail ){ ?>
                                            <div class="linear-c-gallery-slider__slide">
                                                <img src="<?php echo $thumbnail ?>" class="linear-c-gallery-slider__image">
                                            </div>
                                        <?php }
                                    }
                                } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    <?php return ob_get_clean();
}
