<?php

/**
 * Gets the block metadata.
 *
 * @return array Metadata.
 */
function linear_buy_commissions_get_block_metadata() {
    return json_decode( file_get_contents( plugin_dir_path( __FILE__ ) . 'build/block.json' ), true );
}

/**
 * Gets the custom block configuration.
 *
 * @return array Configuration.
 */
function linear_buy_commissions_get_block_config() {
    return json_decode( file_get_contents( plugin_dir_path( __FILE__ ) . 'config.json' ), true );
}

/**
 * Get locale
 *
 * @return string 2-letter locale.
 */
function linear_buy_commissions_get_current_locale() {
    global $TRP_LANGUAGE;

    // Translatepress workaround
    if( in_array( 
        'translatepress-multilingual/index.php', 
        apply_filters( 'active_plugins', get_option( 'active_plugins' ) )
    ) ){
        if ( !empty( $TRP_LANGUAGE ) ) {
        	return substr( $TRP_LANGUAGE, 0, 2 );
        }
    }

    // Basic Polylang/WPML/Stock WordPress
    return substr( get_locale(), 0, 2 );
}

/**
 * Handle REST URL
 * 
 * Some plugins make this not work properly
 */
function linear_buy_commissions_get_rest_url(){

    $current_rest_url = get_rest_url();
    $languages = apply_filters('linear_languages', '');

    if( $languages && is_array($languages) ){
        if( count($languages) > 1 ){
            foreach( $languages as $lang ){
                if ( strpos( $current_rest_url, $lang . '/wp-json/' ) !== false ) {
                    $current_rest_url = str_replace( '/' . $lang . '/wp-json/' , '/wp-json/', $current_rest_url );
                }
            }
        }
    }

    return $current_rest_url;
}