<?php

namespace Linear\Templates;

function get_linear_locale(){

    $linear_supported_locales = [
        'fi'
    ];

    $default_locale = 'fi';

    $current_locale = substr( get_locale(), 0, 2 );

    if( in_array( $current_locale, $linear_supported_locales ) ){
        return $current_locale;
    }

    // fallback
    return $default_locale;
}
