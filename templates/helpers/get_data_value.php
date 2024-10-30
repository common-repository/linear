<?php

namespace Linear\Templates;

/**
 * Gets the proper data according to the locale and supported locales
 */

function get_data_value( $data = null, $value = null ){
    if( !$data || !$value ){
        return '';
    }

    $locale = get_linear_locale();

    if( isset( $data->$locale ) ){
        return $data->$locale->value;
    }
}
