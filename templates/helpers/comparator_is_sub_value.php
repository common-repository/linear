<?php

namespace Linear\Templates;

function comparator_is_sub_value( $listing, $comparators, $value = null ){
    if( !$listing || !$comparators ){
        return false;
    }

    $builded_comparator_value = $listing;

    foreach( $comparators as $comparator ){
        if( isset( $builded_comparator_value[$comparator] ) ){
            $builded_comparator_value = $builded_comparator_value[ $comparator ];
        }
    }

    if( $builded_comparator_value === $listing ){
        return false;
    }

    if( $builded_comparator_value !== $value ){
        return false;
    }

    return true;
}
