<?php
/**
 * Fetching content from api functionality
 *
 * @package Linear
 */

/**
 * Middleware for handling import of data
 */
class Linear_Middleware {

	protected static $api_url;
	protected static $api_key;
	protected static $company_id;
	protected static $listings_page;
	protected static $rentals_page;
	protected static $workplace_page;
	protected static $buy_commissions_page;
	protected static $api_version;
	protected static $plugin_version;
	protected static $linear;
	protected static $company_id_api_key_combos;

	public function __construct() {
		if ( is_null( self::$linear ) ) {
			self::$linear = Linear::get_instance();
		}
		
		self::$api_url 				= self::$linear->get_option( 'api_url' ) ? self::$linear->get_option( 'api_url' ) : '';
		self::$api_key 				= str_replace('LINEAR-API-KEY ', '', self::$linear->get_option( 'api_key' ) );
		self::$company_id 			= self::$linear->get_option( 'company_id' ) ? self::$linear->get_option( 'company_id' ) : '';
		self::$listings_page 		= self::$linear->get_option( 'listings_page' ) ? self::$linear->get_option( 'listings_page' ) : 0;
		self::$rentals_page			= self::$linear->get_option( 'rentals_page' ) ? self::$linear->get_option( 'rentals_page' ) : 0;
		self::$workplace_page 		= self::$linear->get_option( 'workplace_page' ) ? self::$linear->get_option( 'workplace_page' ) : 0;
		self::$buy_commissions_page	= self::$linear->get_option( 'buy_commissions_page' ) ? self::$linear->get_option( 'buy_commissions_page' ) : 0;
		self::$api_version 			= ( self::$linear->get_option( 'api_key' ) ? 'v1.1' : 'v1' );
		self::$plugin_version 		= defined( 'LINEAR_VERSION' ) ? LINEAR_VERSION : '';

		self::$company_id_api_key_combos = self::$linear->get_option( 'company_id_api_key_combos' ) ? $this->parse_company_id_api_key_combos( self::$linear->get_option( 'company_id_api_key_combos' ) ) : '';

		if (substr(self::$api_url, -4) === "/api") {
			self::$api_url = substr(self::$api_url, 0, -4);
		}
	}

	/**
	 * Initialize the middleware
	 * 
	 * Check current data and if it needs to be updated
	 */
	public function init(){
		if( is_admin() ){

			add_action('admin_head', function(){
				$this->run( 'all', false );
			});

		} else {

			add_action('wp', function(){
				global $post;

				$languages = self::$linear->get_languages();
				if( $languages && is_array($languages) && count($languages) > 1 ){
					foreach( $languages as $lang ){
						if(
							isset( $post->ID) && in_array( $post->ID, [
								intval( self::$linear->get_option( 'listings_page_' . $lang ) ? self::$linear->get_option( 'listings_page_' . $lang ) : 0 ),
								intval( self::$linear->get_option( 'rentals_page_' . $lang ) ? self::$linear->get_option( 'rentals_page_' . $lang ) : 0 ),
								intval( self::$linear->get_option( 'workplace_page_' . $lang ) ? self::$linear->get_option( 'workplace_page_' . $lang ) : 0 )
							] ) ||
							has_block( 'linear/listings', $post )
						){
							$this->pre_run( 'listings', $post->ID, $lang );
						}
		
						if(
							isset( $post->ID) && in_array( $post->ID, [
								intval( self::$linear->get_option( 'buy_commissions_page_' . $lang ) ? self::$linear->get_option( 'buy_commissions_page_' . $lang ) : 0 )
							] ) ||
							has_block( 'linear/buy-commissions', $post )
						){
							$this->pre_run( 'buy-commission', $post->ID, $lang );
						}
					}
				} else {
					if(
						isset( $post->ID) && in_array( $post->ID, [
							intval( self::$linear->get_option( 'listings_page' ) ? self::$linear->get_option( 'listings_page' ) : 0 ),
							intval( self::$linear->get_option( 'rentals_page' ) ? self::$linear->get_option( 'rentals_page' ) : 0 ),
							intval( self::$linear->get_option( 'workplace_page' ) ? self::$linear->get_option( 'workplace_page' ) : 0 )
						] ) ||
						has_block( 'linear/listings', $post )
					){
						$this->pre_run( 'listings', $post->ID );
					}
	
					if(
						isset( $post->ID) && in_array( $post->ID, [
							intval( self::$linear->get_option( 'buy_commissions_page' ) ? self::$linear->get_option( 'buy_commissions_page' ) : 0 )
						] ) ||
						has_block( 'linear/buy-commissions', $post )
					){
						$this->pre_run( 'buy-commission', $post->ID );
					}
				}
			});

		}
	}

	/**
	 * Small wrapper for reusing Gutenberg conditional code.
	 * Will be removed once the old fetching system is removed.
	 * 
	 * @param string $type
	 * @param int $post_id
	 */
	private function pre_run( $data_type = '', $post_id = '', $lang = null ){
		if( !$data_type || !$post_id ){
			return;
		}

		if( !$lang ){
			$lang = self::$linear->get_language();
		}

		if ( function_exists( 'has_blocks' ) && has_blocks( $post_id ) ) {
			if( !get_transient( $this->get_transient_name( $data_type, $lang ) ) ){
				$this->run( $data_type, false, $lang );
			}
		}
	}

	/**
	 * Get the listings
	 * 
	 * @return array|null
	 */
	public function get_listings( $lang ){
		return $this->run( 'listings', true, $lang );
	}

	/**
	 * Get buy commissions
	 * 
	 * @return array|null
	 */
	public function get_buy_commissions( $lang ){
		return $this->run( 'buy-commissions', true, $lang );
	}

	public function get_listings_by_type( $parse_type, $lang ){

		if( !$parse_type ){
			return [];
		}

		$parsed_listings = [];
		$listings = $this->run( 'listings', true, $lang );

		if( !is_array( $listings ) ){
			return $parsed_listings;
		}

		foreach( $listings as $listing ){
			if( $parse_type === 'APARTMENTS' ){

				$additional_types = [
					'APARTMENTS',
					'PLOTS',
					'FARMS',
					'GARAGES',
					'NEWLY_CONSTRUCTED',
					'VACATION_APARTMENT'
				];

				if( 
					isset( $listing['rawDataForFiltering']['productGroup'] ) &&
					in_array( strtoupper( $listing['rawDataForFiltering']['productGroup'] ), $additional_types )
				){
					array_push( $parsed_listings, $listing );
				}
			} else {
				// Easier listing
				if( 
					isset( $listing['rawDataForFiltering']['productGroup'] ) &&
					$listing['rawDataForFiltering']['productGroup'] === $parse_type 
				){
					array_push( $parsed_listings, $listing );
				}
			}
		}

		return $parsed_listings;
	}

	/**
	 * Get the listing
	 * 
	 * @param string $id Listing id
	 * @param string $lang Request language
	 * 
	 * @return array|null
	 */
	public function get_listing( $id = '', $lang = 'fi' ){
		$listings_data = $this->run( 'listings', true, $lang );

		// Validate listings data
		if( !$listings_data || !$id ){
			return null;
		}

		$result = null;

		// Validate either long or short ID
		$valid_fields = ['identifier', 'id'];
		
		// Iterate each until we get a response
		foreach( $listings_data as $single ){
			foreach( $valid_fields as $validator ){
				if( isset( $single[$validator] ) && strval( $single[$validator] ) === $id && !$result ){
					$result = $single;
					break;
				}
			}
		}

		// Fallback
		return $result;
	}

	/**
	 * Get single buy commission
	 * 
	 * @param string $id Listing id
	 * 
	 * @return array|null
	 */
	public function get_buy_commission( $id = '', $lang = 'fi' ){
		$buy_commissions = $this->run( 'buy-commissions', true, $lang );

		// Validate listings data
		if( !$buy_commissions || !$id ){
			return null;
		}

		// Iterate each until we get a response
		foreach( $buy_commissions as $buy_commission ){

			// TODO, this shouldn't be needed
			if( gettype( $buy_commission) === 'array' ){
				if( $buy_commission['id'] === $id ){
					return $buy_commission;
				}
			} else if( gettype( $buy_commission) === 'object' ) {

				if( $buy_commission->id === $id ){
					return $buy_commission;
				}
			}

		}

		// Fallback
		return null;
	}

	/**
	 * Run the middleware.
	 * 
	 * $param bool $return if we want a response
	 */
	public function run( $data_type = '', $return = false, $lang = null ){
		// Transient name not set
		if( !$this->get_transient_name( $data_type, $lang ) ){
			return new WP_Error( 'error', __( "API middleware not getting a transient name to use", "linear" ) );
		}

		if( self::$api_version === 'v1' ){
			return new WP_Error( 'error', __( "Cannot use old API. Please add API Key to continue usage", "linear" ) );
		}

		// We have a response
		if( $response = get_transient( $this->get_transient_name( $data_type, $lang ) ) ){
			if( $return ){
				return $response;
			}

			// End run
			return;
		}

		$response_data = null;

		switch ( $data_type ) {
			case 'listings':
				$fetch_listing_method = 'fetch_listings_api_' . str_replace( '.', '_', self::$api_version );
				$response_data = $this::$fetch_listing_method( $lang );
				break;
			case 'buy-commissions':
				$response_data = $this->fetch_buy_commissions( $lang );
				break;
		}

		// If we have a valid response, set 10 minute transient
		if( $response_data && !is_wp_error( $response_data ) ){
			set_transient( $this->get_transient_name( $data_type, $lang ), $response_data, 600 );
		}

		if( $return ){
			return $response_data;
		}

		return null;
	}

	/**
	 * Helper for fetching the transient name
	 */
	private function get_transient_name( $data_type = '', $lang = null ) {
		global $polylang, $TRP_LANGUAGE;

		// Validate that we have all necessary values
		if( !self::$api_version || !self::$api_url || !self::$company_id || !self::$plugin_version ){
			return '';
		}

		// Current translation plugin
		$translation_plugin = '';

		// WPML
		if( in_array( 
			'sitepress-multilingual-cms/sitepress.php', 
			apply_filters( 'active_plugins', get_option( 'active_plugins' ) )
		) ) {
			$translation_plugin = $translation_plugin . 'wpml';
		}

		// Translatepress
		if( !empty( $TRP_LANGUAGE ) ){
			$translation_plugin = $translation_plugin . 'translatepress';
		}

		// Polylang
		if( !empty( $polylang ) ){
			$translation_plugin = $translation_plugin . 'polylang';
		}

		// Getting the current data-type page url to ensure we always have a working page
		$data_page_url = '';
		$languages = self::$linear->get_languages();

		if( $languages && is_array($languages) && count($languages) > 1 && $lang ){
			switch ( $data_type ) {
				case 'listings':
					$listings_page_url 			= get_the_permalink( self::$linear->get_option( 'listings_page' . '_' . $lang ) );
					$rentals_page_url 			= get_the_permalink( self::$linear->get_option( 'rentals_page' . '_' . $lang ) );
					$business_premises_page_url = get_the_permalink( self::$linear->get_option( 'workplace_page' . '_' . $lang ) );

					if( $listings_page_url ){
						$data_page_url = $listings_page_url . '_';
					}

					if( $rentals_page_url ){
						$data_page_url .= $rentals_page_url . '_';
					}

					if( $business_premises_page_url ){
						$data_page_url .= $business_premises_page_url;
					}

					if( !$data_page_url ){
						$data_page_url = get_permalink( self::$listings_page );
					}

					if( !$data_page_url ){
						// fallback
						$data_page_url = self::$listings_page;
					}
					break;
				case 'buy-commissions':
					$data_page_url = get_permalink( self::$buy_commissions_page . '_' . $lang );
					if( !$data_page_url ){
						// fallback
						$data_page_url = self::$buy_commissions_page;
					}
					break;
			}
		} else {
			switch ( $data_type ) {
				case 'listings':
					$listings_page_url 			= get_the_permalink( self::$listings_page );
					$rentals_page_url 			= get_the_permalink( self::$rentals_page );
					$business_premises_page_url = get_the_permalink( self::$workplace_page );

					if( $listings_page_url ){
						$data_page_url = $listings_page_url . '_';
					}

					if( $rentals_page_url ){
						$data_page_url .= $rentals_page_url . '_';
					}

					if( $business_premises_page_url ){
						$data_page_url .= $business_premises_page_url;
					}

					if( !$data_page_url ){
						$data_page_url = get_permalink( self::$listings_page );
					}

					if( !$data_page_url ){
						// fallback
						$data_page_url = self::$listings_page;
					}
					break;
				case 'buy-commissions':
					$data_page_url = get_permalink( self::$buy_commissions_page );
					if( !$data_page_url ){
						// fallback
						$data_page_url = self::$buy_commissions_page;
					}
					break;
			}
		}

		return 
			"linear_middleware_api_" . 
			str_replace( '.', '_', self::$api_version ) .
			"_" .  
			str_replace( '-', '_', $data_type ) .
			"_" .  
			$lang .
			"_" . 
			hash('crc32', self::$api_url . self::$company_id . (isset( self::$api_key ) ? !self::$api_key : '' ) . (isset( self::$company_id_api_key_combos ) ? !self::$company_id_api_key_combos : '' ) . self::$plugin_version . $translation_plugin . $data_page_url, false);
	}

	private function sort_listings_from_multiple_sources( $listings = [] ){
		$sorted_listings = [];

		if( !$listings ){
			return $sorted_listings;
		}

		// Sort by date
		usort($listings, function($a, $b) {
			return strtotime($b['publishDate']) - strtotime($a['publishDate']);
		});

		return $listings;
	}

	/**
	 * Fetching v1.1 data
	 * 
	 * @param array $parameters URL-parameters for request
	 * 
	 * @return array|WP_Error
	 */
	private function fetch_listings_api_v1_1( $lang ) {

		$languages = self::$linear->get_languages();
		$company_id_api_key_combos = self::$linear->get_option( 'company_id_api_key_combos' ) ? $this->parse_company_id_api_key_combos( self::$linear->get_option( 'company_id_api_key_combos' ) ) : '';

		// Validate
		if( $languages && is_array($languages) && count($languages) > 1 ){
			if( 
				!get_the_permalink( intval( self::$linear->get_option( 'listings_page_' . $lang ) ) ) &&
				!get_the_permalink( intval( self::$linear->get_option( 'rentals_page_' . $lang ) ) ) &&
				!get_the_permalink( intval( self::$linear->get_option( 'workplace_page_' . $lang ) ) )
			){
				return null;
			}
		} else {
			if( 
				!get_the_permalink( intval( self::$linear->get_option( 'listings_page' ) ) ) &&
				!get_the_permalink( intval( self::$linear->get_option( 'rentals_page' ) ) ) &&
				!get_the_permalink( intval( self::$linear->get_option( 'workplace_page' ) ) )
			){
				return null;
			}
		}

		// Check that no previous request is running
		if ( get_transient( 'linear_running_listings_update' ) && get_transient( 'linear_running_listings_update' ) === true ) {

			$transient_name = $this->get_transient_name( 'listings', $lang );
			$max_attempts = 5;
			$attempt = 1;
			
			while ( $attempt <= $max_attempts ) {
				// Wait for 8 seconds before re-checking the transient
				sleep(8);
				
				$response = get_transient( $transient_name );
				if ($response) {
					return $response;
				}
				
				$attempt++;
			}
			
			// End run
			return new WP_Error('error', __('Concurrent request failed', 'company'));
		}

		// Add running status
		set_transient( 'linear_running_listings_update', true, 30 );

		$request_body = [];

		if( $company_id_api_key_combos && count( $company_id_api_key_combos ) >= 1 ){

			foreach( $company_id_api_key_combos as $combination ){

				// Setup params
				$parameters = [];
				$parameters['companyId'] = $combination['company_id'];
				$parameters['migratedListings'] = "1";
				$parameters['translate'] = $this->maybe_transform_language( $lang );
				// $parameters['translate'] = substr( get_locale(), 0, 2 );
				// $parameters['withoutTranslation'] = "true";

				$fetch_url = self::$api_url . '/api/v1/dixu-listings/all?' . http_build_query( $parameters );
				
				$fetch_headers = [
					'headers' => [
						'Accept' => 'application/json',
						'Authorization' => 'LINEAR-API-KEY ' . $combination['api_key'],
					],
					'timeout' => 60,
				];
		
				$response = wp_remote_get( $fetch_url, $fetch_headers );
		
				if ( is_wp_error( $response ) ) {
					return new WP_Error( 'error', __( "API v1.1 middleware failed to fetch data", "linear" ) );
					delete_transient( 'linear_running_listings_update' );
				}

				$single_result_body = [];
				$single_result_body = json_decode( wp_remote_retrieve_body( $response ), true );

				if( is_array( $single_result_body ) ){
					$request_body = array_merge($request_body, $single_result_body);
				}
			}

			$request_body = $this->sort_listings_from_multiple_sources( $request_body );

		} else {
			// single endpoint fetch
			
			// Setup params
			$parameters = [];
			$parameters['companyId'] = self::$company_id;
			$parameters['migratedListings'] = "1";
			$parameters['translate'] = $this->maybe_transform_language( $lang );
			// $parameters['translate'] = substr( get_locale(), 0, 2 );
			// $parameters['withoutTranslation'] = "true";
	
			$fetch_url = self::$api_url . '/api/v1/dixu-listings/all?' . http_build_query( $parameters );

			$fetch_headers = [
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => 'LINEAR-API-KEY ' . self::$api_key,
				],
				'timeout' => 60,
			];
	
			$response = wp_remote_get( $fetch_url, $fetch_headers );
	
			if ( is_wp_error( $response ) ) {
				return new WP_Error( 'error', __( "API v1.1 middleware failed to fetch data", "linear" ) );
				delete_transient( 'linear_running_listings_update' );
			}
	
			$request_body = json_decode( wp_remote_retrieve_body( $response ), true );
		}

		delete_transient( 'linear_running_listings_update' );

		// No results in response
		if ( !isset( $request_body ) ) {
			return new WP_Error( 'error', __( "API v1.1 middleware failed in parsing response data", "linear" ) );
		}

		// If error message
		if( is_string( $request_body ) ){
			return new WP_Error( 'error', $request_body );
		}

		// In case we get a error
		if( isset( $request_body['type'] ) ){
			return new WP_Error( 'error', $request_body['plaintext'] );
		}

		return $this->cleanup_listings_data( $request_body, $lang );
	}

	/**
	 * Fetching buy commissions
	 * 
	 * @param array $parameters URL-parameters for request
	 * 
	 * @return array|WP_Error
	 */
	private function fetch_buy_commissions( $lang ) {

		$languages = self::$linear->get_languages();

		$company_id_api_key_combos = self::$linear->get_option( 'company_id_api_key_combos' ) ? $this->parse_company_id_api_key_combos( self::$linear->get_option( 'company_id_api_key_combos' ) ) : '';

		// Validate
		if( $languages && is_array($languages) && count($languages) > 1 ){
			if( !get_the_permalink( intval( self::$linear->get_option( 'buy_commissions_page_' . $lang ) ) ) ){
				return null;
			}
		} else {
			if( !get_the_permalink( intval( self::$linear->get_option( 'buy_commissions_page' ) ) ) ){
				return null;
			}
		}

		// Check that no previous request is running
		if ( get_transient( 'linear_running_buy_commissions_update' ) && get_transient( 'linear_running_buy_commissions_update' ) === true ) {

			$transient_name = $this->get_transient_name( 'buy-commissions', $lang );
			$max_attempts = 5;
			$attempt = 1;
			
			while ( $attempt <= $max_attempts ) {
				// Wait for 8 seconds before re-checking the transient
				sleep(8);
				
				$response = get_transient( $transient_name );
				if ($response) {
					return $response;
				}
				
				$attempt++;
			}
			
			// End run
			return new WP_Error('error', __('Concurrent request failed', 'company'));
		}

		// Add running status
		set_transient( 'linear_running_buy_commissions_update', true, 30 );

		$request_body = [];

		if( $company_id_api_key_combos && count( $company_id_api_key_combos ) >= 1 ){

			foreach( $company_id_api_key_combos as $combination ){

				$parameters['companyId'] = self::$company_id;

				$fetch_url = self::$api_url . '/api/v1/public-data/published-buy-commissions?' . http_build_query( $parameters );
				$fetch_headers = [
					'headers' => [
						'Accept' => 'application/json',
						'Authorization' => 'LINEAR-API-KEY ' . $combination['api_key'],
					],
					'timeout' => 60,
				];
		
				$response = wp_remote_get( $fetch_url, $fetch_headers );
		
				delete_transient( 'linear_running_buy_commissions_update' );
		
				if ( is_wp_error( $response ) ) {
					return new WP_Error( 'error', __( "API v1.1 middleware failed to fetch data", "linear" ) );
				}

				$single_result_body = json_decode( wp_remote_retrieve_body( $response ), true );

				if( is_array( $single_result_body ) ){
					$request_body = array_merge( $request_body, $single_result_body );
				}
			}

		} else {
			// single endpoint fetch
			
			$parameters['companyId'] = self::$company_id;

			$fetch_url = self::$api_url . '/api/v1/public-data/published-buy-commissions?' . http_build_query( $parameters );
			$fetch_headers = [
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => 'LINEAR-API-KEY ' . self::$api_key,
				],
				'timeout' => 60,
			];
	
			$response = wp_remote_get( $fetch_url, $fetch_headers );
	
			delete_transient( 'linear_running_buy_commissions_update' );
	
			if ( is_wp_error( $response ) ) {
				return new WP_Error( 'error', __( "API v1.1 middleware failed to fetch data", "linear" ) );
			}
	
			$request_body = json_decode( wp_remote_retrieve_body( $response ), true );
		}

		// No results in response
		if ( !isset( $request_body ) ) {
			return new WP_Error( 'error', __( "API v1.1 middleware failed in parsing response data", "linear" ) );
		}

		// If error message
		if( is_string( $request_body ) ){
			return new WP_Error( 'error', $request_body );
		}

		// In case we get a error
		if( isset( $request_body['type'] ) ){
			return new WP_Error( 'error', $request_body['plaintext'] );
		}

		return $this->cleanup_buy_commissions_data( $request_body, $lang );
	}

	/**
	 * Cleanup data for safer/easier handling
	 * 
	 * @params array $listings_data Listings data
	 * 
	 * @return $array Listings data
	 */
	private function cleanup_listings_data( $listings_data = [], $lang = null ){
		
		$listings_data = $this->sanitize_listings_data( $listings_data );
		$listings_data = $this->generalize_listings_data( $listings_data, $lang );
		$listings_data = apply_filters('linear_edit_listings', $listings_data);

		return $listings_data;
	}

	private function cleanup_buy_commissions_data( $buy_commission_data = [], $lang = null ){

		$buy_commission_data = $this->generalize_buy_commissions_data( $buy_commission_data, $lang );
		$buy_commission_data = apply_filters('linear_edit_listings', $buy_commission_data);

		return $buy_commission_data;
	}

	/**
	 * Brought in from wp-qa.com/wordpress-sanitize-array
	 */
	private function recursive_sanitize_textarea_field( $array ) {
		foreach ( $array as $key => &$value ) {
			if ( is_array( $value ) ) {
				$value = $this->recursive_sanitize_textarea_field( $value );
			}
			else {
				$value = sanitize_textarea_field( $value );
			}
		}
	
		return $array;
	}

	/**
	 * Cleanup empty/null values from data
	 * 
	 * @params array $listings_data Listings data
	 * @params array $default default data in case of empty data
	 * 
	 * @return $array Listings data
	 */
	private function sanitize_listings_data( $listings_data = [], $default = [] ){

		$cleaned_data = [];

		if( $listings_data ){
			// Start iterating items
			foreach( $listings_data as $listing ){
				$cleaned_single = [];

				if( $listing ){
					// Iterate single items
					foreach( $listing as $key => $value ){
						if( $value !== [] && $value !== null ){
							$cleaned_single[$key] = $value;

							// Sanitize per data type
							if ( is_array( $value ) ) {
								$cleaned_single[$key] = $this->recursive_sanitize_textarea_field( $value );
							} else if( is_string( $value ) ) {
								$cleaned_single[$key] = sanitize_textarea_field( $value );
							} else if( is_int( $value ) || strval( intval( $value) ) === $value ) {
								$cleaned_single[$key] = intval( $value );
							} else {
								$cleaned_single[$key] = rest_sanitize_boolean( $value );
							}

						}
					}
				}

				// If we have the most important data, populate
				if( $cleaned_single['id'] ){
					array_push( $cleaned_data, $cleaned_single);
				}
			}
		}

		if( !$cleaned_data ){
			return $default;
		}

		return $cleaned_data;
	}

	/**
	 * Different API-endpoints return data in slightly different formats.
	 * Lets use this generalizer to generalize the data to a single format
	 * for easier frontend use.
	 * 
	 * @params array $listings_data Listings data
	 * 
	 * @return $array Listings data
	 */
	private function generalize_listings_data( $listings_data = [], $lang = null ){
		$this->data_handler = self::$linear->get_data_handler();
		
		return $this->data_handler->generalize_listings( $listings_data, $lang );
	}

	private function generalize_buy_commissions_data( $buy_commission_data = [], $lang = null ){
		$this->data_handler = self::$linear->get_data_handler();
		
		return $this->data_handler->generalize_buy_commissions( $buy_commission_data, $lang );
	}

	private function maybe_transform_language( $lang ){

		$fallback_language = 'fi';
		$supported_languages = [
			'fi'
		];

		if( in_array( $lang, $supported_languages ) ){
			return $lang;
		}

		return $fallback_language;
	}

	public function parse_company_id_api_key_combos( $input ){
		$pattern = '/(\d+)\s+-\s+([a-fA-F0-9-]+)/';
    	preg_match_all($pattern, $input, $matches, PREG_SET_ORDER);
	
		$combinations = array();

		if( !$matches ){
			return "";
		}
	
		foreach ($matches as $match) {
			$combination = array(
				'company_id' => intval($match[1]),
				'api_key' => $match[2]
			);
			$combinations[] = $combination;
		}
	
		return $combinations;
	}
}