<?php

namespace Linear\Templates;

/**
 * Display escaped youtube video thumbnail url.
 * Size that must exist:       480x360 hqdefault.jpg
 * Sizes that might not exist: 640x480 sddefault.jpg, 1280x720 hq720.jpg, 1920x1080 maxresdefault.jpg
 * 
 * @param  string $video_url Youtube video url.
 * @param  string $size      Optional size for youtube video thumbnail.
 * @return string
 */
function get_youtube_video_image_url( $video_url, $size = 'hqdefault.jpg' ) {

    $url = 'https://img.youtube.com/vi/';
    if ( preg_match('/(?:youtube(?:-nocookie)?\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/', $video_url, $match ) ) {
        $url .= $match[1];
        if ( $size !== 'hqdefault.jpg' && 200 != wp_remote_retrieve_response_code( wp_remote_get( "$url/$size" ) ) ) {
            $size = 'hqdefault.jpg';
        }
    }
    return esc_url( "$url/$size" );

}
