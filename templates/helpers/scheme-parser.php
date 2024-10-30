<?php

namespace Linear\Templates;

/**
 * Load parts of site via scheme
 */
function scheme_parser( $scheme, $listing ) {

    if( !$scheme ){
        $scheme = json_decode( file_get_contents( LINEAR_PLUGIN_PATH . "/templates/scheme.json", false ) );
    }

    ob_start();

        foreach( $scheme->sections as $section ){
            $type = $section->type;
            $content = $section->content;

            $section_component = str_replace('-', '_', $type);

            if( function_exists( __NAMESPACE__ . '\\' . $section_component ) ){
                echo call_user_func(  __NAMESPACE__ . '\\' . $section_component, $listing, $content );
            }
        }

    return ob_get_clean();

}
