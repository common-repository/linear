<?php

namespace Linear\Templates;

function output_component( $listing, $component, $data = [] ){
    if( !$listing || !$component || $data ){
        return "";
    }

    if( function_exists( __NAMESPACE__ . '\\' . $component ) ){
        return call_user_func( __NAMESPACE__ . '\\' . $component, $listing, $data );
    }
}
