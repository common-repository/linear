<?php

namespace Linear\Templates;

function icon( $icon_name ) {

    $icon_path = LINEAR_PLUGIN_PATH . "/dist/icon-" . $icon_name . ".svg";

    if( !file_exists( $icon_path ) ){
        return "";
    }

    $icon = file_get_contents( $icon_path );

    if( !$icon ){
        return "";
    }

    return $icon;
}

?>