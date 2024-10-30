<?php
/**
 * Fired during plugin activation
 *
 * @package Linear
 */

/**
 * Defines all code necessary to run during the plugin's activation.
 */
class Linear_Activator {
	public static function activate() {
		$options = get_option( 'linear_settings' );
		if ( $options === false ) {
			$options = [];
		}

		// Create default listing page
		$linear_page = isset( $options['listings_page'] ) ? $options['listings_page'] : -1;
		if( $linear_page === -1 ){
			if( isset( $options['listings_page_' . substr( get_locale(), 0, 2 )] ) ){
				$linear_page = $options['listings_page_' . substr( get_locale(), 0, 2 )];
			}
		}

		if ( is_null( get_post( $linear_page ) ) ) {
			$linear_page = array(
				'post_title'   	=> __( 'Apartments', 'linear' ),
				'post_content' 	=> '',
				'post_status'  	=> 'publish',
				'post_type'    	=> 'page',
				'post_author'  	=> get_current_user_id(),
				'post_date'    	=> date_i18n('Y-m-d H:i:s'),
				// 'page_template'	=> 'plain-listings.php',
			);
			$options['listings_page'] = wp_insert_post( $linear_page );
			update_option( 'linear_settings', $options );
		}

		// Handle default color setting
		if( !isset( $options['primary_color'] ) ){
			if (class_exists('WP_Theme_JSON_Resolver')) {
				$settings = WP_Theme_JSON_Resolver::get_core_data()->get_settings();
				if (isset($settings['color']['palette']['default'])) {
					$colorPalette = $settings['color']['palette']['default'];

					foreach( $colorPalette as $palette ){

						$hex 	   = $palette['color'];
						$red = hexdec(substr($hex, 1, 2));
						$green = hexdec(substr($hex, 3, 2));
						$blue = hexdec(substr($hex, 5, 2));
						$lightness = (($red * 299) + ($green * 587) + ($blue * 114)) / 1000;

						if( 
							isset( $palette ) && 
							isset( $palette['color'] ) && 
							$palette['color'] !== '#000000' && 
							$palette['color'] !== '#ffffff' && 
							$lightness < 170 // 256 max lightness
						){
							$options['primary_color'] 	= $palette['color'];
							$options['solid_color'] 	= $palette['color'];
							$options['outline_color'] 	= $palette['color'];
	
							update_option( 'linear_settings', $options );
							break;
						}
					}
				}
			}
		}

		// Handle rewrite rules
		Linear::listings_page_rewrite_rules( true );
	}
}
