<?php

namespace Linear\Utils;

function get_asset( $name, $manifest_name = 'frontend' ) {
    $manifest = get_asset_manifest( $manifest_name );

    if ( !isset( $manifest->$name ) ) {
        throw new Exception("Asset " . $name . " not found in manifest " . $manifest_name . ".");
    }

    return "dist/" . $manifest->$name;
}

function get_asset_manifest( $manifest_name = 'frontend' ) {
    $manifest_file = file_get_contents( plugin_dir_path( __DIR__ ) . "dist/" . $manifest_name . "-manifest.json" );

    if( !$manifest_file ){
        return;
    }

    $manifest = json_decode( $manifest_file );

    if ( !isset( $manifest ) ) {
        throw new Exception("Couldn't find manifest " . $manifest_name . " from the server.");
    }

    return $manifest;
}