<?php

namespace Linear\Templates;

function get_youtube_video_embed_url( $video_url, $query = array( 'autoplay' => 1 ) ) {

    $url = '';

    if ( preg_match('/(?:youtube(?:-nocookie)?\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/', $video_url, $match ) ) {
        $url = 'https://www.youtube.com/embed/' . $match[1];
        if( is_array( $query ) ){
            $query = http_build_query( $query );
            if ( ! empty( $query ) ) {
                $url .= "?$query";
            }
        }
    }

    if( $url === '' ){
        return $url;
    }
    
    return esc_url( $url );
}
