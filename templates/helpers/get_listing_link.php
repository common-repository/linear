<?php

namespace Linear\Templates;

function get_listing_link( $listing, $linkType = '' ){
    global $post;
    
    if( !$listing || !$linkType ){
        return '';
    }

    $formats = [
        'dixu_bidding_url'  => 'https://dixu.fi/fi/tarjouskauppa/%2$s/%1$s',
		'dixu_listing_url'  => 'https://dixu.fi/fi/myytavat-asunnot/asunto/%2$s/%1$s',
		'listing_image_url_relative' => 'https://images.linear.fi/%1$s',
		'listing_image_url' => '%1$s'
    ];

    if( array_key_exists( $linkType, $formats ) ){
        return sprintf( $formats[$linkType], get_object_value( $listing, 'id' ), sanitize_title( get_object_value( $listing, 'address' ) ) );
    }

    // fallback
    $listings_page = null;
    $lang = substr( get_locale(), 0, 2 );

    // check non-lang version
    if( $post->ID === intval( get_option( 'listings_page' ) ) ){
        $listings_page = get_the_permalink( get_option('listings_page') );
    } else if( $post->ID === intval( get_option( 'rentals_page' ) ) ){
        $listings_page = get_the_permalink( get_option('rentals_page') );
    } else if( $post->ID === intval( get_option( 'workplace_page' ) ) ){
        $listings_page = get_the_permalink( get_option('workplace_page') );
    }

    // start checking lang versions
    if( !$listings_page ){
        if( $post->ID === intval( get_option( 'listings_page_' . $lang ) ) ){
            $listings_page = get_the_permalink( get_option( 'listings_page_' . $lang ) );
        } else if( $post->ID === intval( get_option( 'rentals_page' . $lang ) ) ){
            $listings_page = get_the_permalink( get_option( 'rentals_page' . $lang) );
        } else if( $post->ID === intval( get_option( 'workplace_page' . $lang ) ) ){
            $listings_page = get_the_permalink( get_option('workplace_page' . $lang ) );
        }
    }

    if( !$listings_page ){
        return "";
    }

    return sprintf( $listings_page . '%2$s/%1$s', get_object_value( $listing, 'id' ), sanitize_title( get_object_value( $listing, 'address' ) ) );
}
