<?php

namespace Linear\Templates;

/**
 * Gets the proper data according to the locale and supported locales
 */

function get_locale_value( $data = null, $key = null ){
    if( !$data || !$key ){
        return '';
    }

    $locale = get_linear_locale();

    if( isset( $data[$key][$locale] ) ){
        return $data[$key][$locale]['value'];
    }

    // fallback
    return $data[$key]['en']['value'];
}
