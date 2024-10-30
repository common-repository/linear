<?php
/**
 * Hooks for ease of use
 *
 * @package Linear
 */

/**
 * Defines all code necessary to run during the plugin's activation.
 */
class Linear_Hooks {

	protected static $linear;
	protected static $linear_middleware;

	public function __construct() {
		if ( is_null( self::$linear ) ) {
			self::$linear = Linear::get_instance();
		}

        self::$linear_middleware = self::$linear->get_middleware();
	}

	public static function get_listings( $lang ) {
		return self::$linear_middleware->get_listings( $lang );
	}

	public static function get_listings_by_type( $data_type, $lang ) {

		// Remapping values for simplicity
		switch ( str_replace( ' ', '_', strtoupper( $data_type ) ) ) {
			case 'APARTMENTS':
				$data_type = 'APARTMENTS';
				break;
			case 'RENTALS':
				$data_type = 'RENT_APARTMENT';
				break;
			case 'BUSINESS_PREMISES':
				$data_type = 'BUSINESS_PREMISES';
				break;
		}

		return self::$linear_middleware->get_listings_by_type( $data_type, $lang );
	}

	public static function get_listing( $id, $lang ) {
		return self::$linear_middleware->get_listing( $id, $lang );
	}

	public static function get_buy_commissions( $lang ) {
		return self::$linear_middleware->get_buy_commissions( $lang );
	}

	public static function get_buy_commission( $id, $lang ) {
		return self::$linear_middleware->get_buy_commission( $id, $lang );
	}

	public static function get_languages() {
		return self::$linear->get_languages();
	}

	public static function get_language() {
		return self::$linear->get_language();
	}

	public static function get_default_language() {
		global $TRP_LANGUAGE;
		
		// Polylang
		if( function_exists('pll_default_language') ) {
			return pll_default_language( 'slug' );
		}

		// current active plugins
		$plugins = apply_filters( 'active_plugins', get_option( 'active_plugins' ) );

		// WPML
		if( in_array( 
			'sitepress-multilingual-cms/sitepress.php', 
			$plugins
		) ) {
			if( has_filter('wpml_default_language') ){
				return apply_filters( 'wpml_default_language', NULL );
			}
		}

		// Translatepress
		if( in_array( 
			'translatepress-multilingual/index.php', 
			$plugins
		) ){
			if ( !empty( $TRP_LANGUAGE ) && is_string( $TRP_LANGUAGE ) ) {
				return $TRP_LANGUAGE;
			}
		}

		return get_locale();
	}

	// default return
	public static function edit_listings_data( $listings ) {
		return $listings;
	}
}
