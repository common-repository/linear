<?php

namespace Linear\Templates;

function compare_to_constant( $listing, $key, $constant ){
    if( !$listing || !$key || !$constant){
        return false;
    }

    $set_constant = get_constant( $listing, $key );

    if( $set_constant && $set_constant === $constant ){
        return true;
    }

    return false;
}
