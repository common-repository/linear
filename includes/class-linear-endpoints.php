<?php
/**
 * Fetching content from api functionality
 *
 * @package Linear
 */

/**
 * Endpoints for Gutenberg blocks
 */
class Linear_Endpoints {

	protected static $linear;
    protected $linear_middleware;
    protected $api_key;

	public function __construct() {
		if ( is_null( self::$linear ) ) {
			self::$linear = Linear::get_instance();
		}

        $this->linear_middleware 	= self::$linear->get_middleware();
		$this->api_version 			= ( self::$linear->get_option( 'api_key' ) ? 'v1.1' : 'v1' );
		$this->api_key 				= str_replace('LINEAR-API-KEY ', '', self::$linear->get_option( 'api_key' ) );

        add_action( 'rest_api_init', function(){
			
			// Listings
			$listings_endpoints = [
				'all' => 'get_listings',
				'apartments' => 'get_listings_apartments',
				'rentals' => 'get_listings_rentals',
				'business_premises' => 'get_listings_business_premises',
			];

			foreach( $listings_endpoints as $endpoint => $callback ){
				register_rest_route( 'linear/' . str_replace( '.', '_', $this->api_version ), '/listings/' . $endpoint,
					[
						'methods' 				=> 'GET',
						'callback' 				=> [ $this, $callback ],
						'permission_callback' 	=> function( $request ){
							return true;
						},
					]
				);
			}

			register_rest_route( 'linear/' . str_replace( '.', '_', $this->api_version ), '/listing/(?P<id>.+)',
				[
					'methods' 				=> 'GET',
					'callback' 				=> [ $this, 'get_listing' ],
					'permission_callback' 	=> function( $request ){
						return true;
					},
				]
			);

			// Buy commissions
			$buy_commissions_endpoints = [
				'all' => 'get_buy_commissions'
			];

			foreach( $buy_commissions_endpoints as $endpoint => $callback ){
				register_rest_route( 'linear/' . str_replace( '.', '_', $this->api_version ), '/buy-commissions/' . $endpoint,
					[
						'methods' 				=> 'GET',
						'callback' 				=> [ $this, $callback ],
						'permission_callback' 	=> function( $request ){
							return true;
						},
					]
				);
			}

			register_rest_route( 'linear/' . str_replace( '.', '_', $this->api_version ), '/buy-commission/(?P<id>.+)',
				[
					'methods' 				=> 'GET',
					'callback' 				=> [ $this, 'get_buy_commission' ],
					'permission_callback' 	=> function( $request ){
						return true;
					},
				]
			);

			// Contact feature
			register_rest_route( 'linear/' . str_replace( '.', '_', $this->api_version ), '/contact',
				[
					'methods' 				=> 'POST',
					'callback' 				=> [ $this, 'handle_contact_form' ],
					'permission_callback' 	=> function( $request ){
						return true;
					},
				]
			);
		} );
	}

    public function get_listings( \WP_REST_Request $request ) {

		$lang = $this->get_request_language( $request );

		if( 
			get_the_permalink( self::$linear->get_option( 'listings_page' ) ) ||
			get_the_permalink( self::$linear->get_option( 'listings_page_' . $lang ) )
		){
			// continue
		} else {
			return $this->return_error_no_page();
		}

		$api_response = $this->linear_middleware->get_listings( $lang );

		// API/Transient response
		if( $api_response ){
			return new \WP_REST_Response( $api_response, 200 );
		}

		if( $api_response === [] ){
			return $this->return_empty_response();
		}

		return $this->return_error_invalid();
	}

    public function get_listings_apartments( \WP_REST_Request $request ) {

		$lang = $this->get_request_language( $request );

		if( 
			get_the_permalink( self::$linear->get_option( 'listings_page' ) ) ||
			get_the_permalink( self::$linear->get_option( 'listings_page_' . $lang ) )
		){
			// continue
		} else {
			return $this->return_error_no_page();
		}

		$api_response = $this->linear_middleware->get_listings_by_type( 'APARTMENTS', $lang );

		// API/Transient response
		if( $api_response ){
			return new \WP_REST_Response( $api_response, 200 );
		}

		return $this->return_error_invalid();
	}

    public function get_listings_rentals( \WP_REST_Request $request ) {

		$lang = $this->get_request_language( $request );

		if( 
			get_the_permalink( self::$linear->get_option( 'rentals_page' ) ) ||
			get_the_permalink( self::$linear->get_option( 'rentals_page_' . $lang ) ) ||
			get_the_permalink( self::$linear->get_option( 'listings_page' ) ) ||
			get_the_permalink( self::$linear->get_option( 'listings_page_' . $lang ) )
		){
			// continue
		} else {
			return $this->return_error_no_page();
		}

		$api_response = $this->linear_middleware->get_listings_by_type( 'RENT_APARTMENT', $lang );

		// API/Transient response
		if( $api_response ){
			return new \WP_REST_Response( $api_response, 200 );
		}

		if( $api_response === [] ){
			return $this->return_empty_response();
		}

		return $this->return_error_invalid();
	}

    public function get_listings_business_premises( \WP_REST_Request $request ) {

		$lang = $this->get_request_language( $request );

		if( 
			get_the_permalink( self::$linear->get_option( 'workplace_page' ) ) ||
			get_the_permalink( self::$linear->get_option( 'workplace_page_' . $lang ) ) ||
			get_the_permalink( self::$linear->get_option( 'listings_page' ) ) ||
			get_the_permalink( self::$linear->get_option( 'listings_page_' . $lang ) )
		){
			// continue
		} else {
			return $this->return_error_no_page();
		}

		$api_response = $this->linear_middleware->get_listings_by_type( 'BUSINESS_PREMISES', $lang );

		// Empty
		if( empty( $api_response ) ){
			return new \WP_REST_Response( $api_response, 200 );
		}

		// API/Transient response
		if( $api_response ){
			return new \WP_REST_Response( $api_response, 200 );
		}

		if( $api_response === [] ){
			return $this->return_empty_response();
		}

		return $this->return_error_invalid();
	}

	public function get_listing( \WP_REST_Request $request ) {

		$lang = $this->get_request_language( $request );

		if( 
			get_the_permalink( self::$linear->get_option( 'listings_page' ) ) ||
			get_the_permalink( self::$linear->get_option( 'listings_page_' . $lang ) )
		){
			// continue
		} else {
			return $this->return_error_no_page();
		}

		$params = $request->get_params();

		if( !isset( $params['id'] ) ){
			return $this->return_error_invalid_id();
		}

		$id = sanitize_title( $params['id'] );
		$fail = false;

		// validate long id
		if( strlen( $id ) !== 36 || substr_count( $id, "-" ) !== 4 ){
			$fail = true;
		}

		if( $fail ){
			if( strlen( $id ) === strlen( intval( $id ) ) ){
				$fail = false;
			}
		}

		if( $fail ){
			return $this->return_error_invalid_id();
		}

		$api_response = $this->linear_middleware->get_listing( $id, $lang );

		// API/Transient response
		if( $api_response ){
			return new \WP_REST_Response( $api_response, 200 );
		}

		return $this->return_error_invalid();
	}

	public function get_buy_commissions( \WP_REST_Request $request ) {

		$lang = $this->get_request_language( $request );

		if( 
			get_the_permalink( self::$linear->get_option( 'buy_commissions_page' ) ) ||
			get_the_permalink( self::$linear->get_option( 'buy_commissions_page_' . $lang ) )
		){
			// continue
		} else {
			return $this->return_error_no_page();
		}

		$api_response = $this->linear_middleware->get_buy_commissions( $lang );

		// API/Transient response
		if( $api_response ){
			return new \WP_REST_Response( $api_response, 200 );
		}

		if( $api_response === [] ){
			return $this->return_empty_response();
		}

		return $this->return_error_invalid();
	}

	public function get_buy_commission( \WP_REST_Request $request ) {

		$lang = $this->get_request_language( $request );

		if( 
			get_the_permalink( self::$linear->get_option( 'buy_commissions_page' ) ) ||
			get_the_permalink( self::$linear->get_option( 'buy_commissions_page_' . $lang ) )
		){
			// continue
		} else {
			return $this->return_error_no_page();
		}

		$params = $request->get_params();

		if( !isset( $params['id'] ) ){
			return $this->return_error_invalid_id();
		}

		$id = sanitize_title( $params['id'] );

		// validate title contents
		if( strlen( $id ) !== 36 || substr_count( $id, "-" ) !== 4 ){
			return $this->return_error_invalid_id();
		}

		$api_response = $this->linear_middleware->get_buy_commission( $id, $lang );

		// API/Transient response
		if( $api_response ){
			return new \WP_REST_Response( $api_response, 200 );
		}

		if( $api_response === [] ){
			return $this->return_empty_response();
		}

		return $this->return_error_invalid();
	}

	public function handle_contact_form( $request ) {
		return $this->submit_contact_form( $request );
	}

	private function submit_contact_form( $request ){

		$data = json_decode( file_get_contents('php://input'), true );

		$more_info =  isset( $data['more_info'] ) ? !!sanitize_text_field( $data['more_info'] ) : false;
		$want_to_see = isset( $data['want_to_see'] ) ? !!sanitize_text_field( $data['want_to_see'] ) : false;
		$want_to_buy = isset( $data['want_to_buy'] ) ? !!sanitize_text_field( $data['want_to_buy'] ) : false;
		$name = sanitize_text_field( $data['name'] );
		$email = sanitize_email( $data['email'] );
		$phone = sanitize_text_field( $data['phone'] );
		$message = sanitize_textarea_field( $data['message'] );
		$contact_source = urldecode($data['sourceUrl']);
		$listing_id = $data['listingId'];

		if( strlen( $name ) < 1 ){
			return new \WP_REST_Response( array( 'error' => 'Invalid name.' ), 400 );
		}

		if ( !filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
			return new \WP_REST_Response( array( 'error' => 'Invalid email address.' ), 400 );
		}

		if( !$contact_source ){
			return new \WP_REST_Response( array( 'error' => 'Invalid source url.' ), 400 );
		}

		if( !$listing_id ){
			return new \WP_REST_Response( array( 'error' => 'Invalid listing.' ), 400 );
		}

		return $this->post_message_to_linear([
			'wantsMoreDetails' => $more_info,
			'wantsAPresentation' => $want_to_see,
			'wantsToMakeAnOffer' => $want_to_buy,
			'name' => $name,
			'email' => $email,
			'tel' => $phone,
			'contactMessage' => $message,
			'sourceUrl' => $contact_source,
			'sourceType' => 'HOMEPAGE',
			'sentAt' => date('c')
		], $listing_id);

	}

	private function post_message_to_linear( $values = [], $listing_id = 0 ){

		if( !$values || !$listing_id ){
			return new \WP_REST_Response( array( 'error' => 'Contact submission failed.' ), 400 );
		}

		$contact_api_url = self::$linear->get_option( 'contact_api_url' );
		$url = $contact_api_url . '/leads/listing/' . $listing_id;

		$response = wp_remote_request($url, array(
			'method'			=> 'POST',
			'headers'     		=> array(
				'Content-Type' 	=> 'application/json',
				'Authorization' => 'LINEAR-API-KEY ' . $this->api_key,
			),
			'body' => json_encode($values),
			'timeout' => 30
		));

		if( is_wp_error( $response ) ) {
			$error_message = $response->get_error_message();
			return new \WP_REST_Response( array( 'error' => "Something went wrong" ), 400 );
		}

		$status_code = wp_remote_retrieve_response_code($response);
		$response_body = wp_remote_retrieve_body($response);
	
		if ($status_code === 201) {
			return new \WP_REST_Response( $response_body, 201 );
		} else if ( in_array($status_code, [400, 401, 403, 404, 500]) ) {
			return new \WP_REST_Response( array( 'error' => "Fail: " . $response_body ), $status_code );
		} else {
			return new \WP_REST_Response( array( 'error' => "Unkonwn error: " . $response_body ), $status_code );
		}

		// never hitting fallback
		return new \WP_REST_Response( array( 'error' => "Success" ), 200 );
	}

	public function get_request_language( $request ){

		$default = 'fi';
		$language = null;

		// Add supported languages, fallback to 'fi'
		//$supported_languages = ['fi'];

		if( $translate = $request->get_param('lang') ){
			// $language = $translate;
			return $translate;
		}

		/*
		if( !in_array( $language, $supported_languages ) ){
			$language = $default;
		}
		*/

		if( !$language ){
			$language = $default;
		}

		return $default;
	}

    private function return_error_invalid() {
		return new \WP_REST_Response(array('error' => 'Invalid input.'), 400);
	}

    private function return_error_invalid_id() {
		return new \WP_REST_Response(array('error' => 'Invalid id.'), 400);
	}

	private function return_error_duplicate() {
		return new \WP_REST_Response(array('error' => 'Cannot use the same "from" and "to" values.'), 400);
	}

	private function return_error_no_page() {
		return new \WP_REST_Response(array('error' => 'No page set for the data in Linear settings.'), 400);
	}

	private function return_error_empty() {
		return new \WP_REST_Response(array('error' => 'No results for chosen route.'), 200);
	}

	private function return_error_api() {
		return new \WP_Error('error', 'API error');
	}

	private function return_empty_response() {
		return new \WP_REST_Response([], 200);
	}
}