<?php
/**
 * For handling API data in various ways
 *
 * @package Linear
 */

class Linear_Data_Handler {
	protected static $linear;
	protected static $colliding_string_array_values;

	public function __construct() {
		if ( is_null( self::$linear ) ) {
			self::$linear = Linear::get_instance();
		}

		self::$colliding_string_array_values = [
			'constructionMaterial',
			'heatingSystem',
			'kitchenEquipment',
			'kitchenFloorMaterial',
			'kitchenWallMaterial',
			'bathroomFloorMaterial',
			'bathroomWallMaterial',
			'bathroomEquipment',
			'livingRoomFloorMaterial',
			'livingRoomWallMaterial',
			'bedroomFloorMaterial',
			'constructionMaterial',
			'bedroomWallMaterial',
			'areaBasis',
			'housingCooperativeHas',
		];
	}

	/**
	 * Wrapper to generalize all items in array
	 */
	public function generalize_listings( $listings_data = [], $lang = null ){
		foreach( $listings_data as &$listing ){
			$listing = $this->generalize_listing( $listing, $lang );
		}

		return $listings_data;
	}

	/**
	 * Generalize a single listing data
	 */
	private function generalize_listing( $listing, $lang ){

		if( !$listing ){
			return null;
		}

		// booleans to keep
		$booleans = [
			'integrationDixuEnabled'
		];

		// Handle booleans
		foreach( $listing as $key => &$value ){
			if( in_array( $key, $booleans ) ){
				continue;
			}

			if( is_bool( $value ) ){
				if( $value === true ){
					$value = _x( 'Yes', 'Label for constant YES', 'linear' );
				} else {
					$value = _x( 'No', 'Label for constant NO', 'linear' );
				}
			}
		}

		// generalize "images" field
		$listing['images'] = $this->generalize_field_images( isset( $listing['images'] ) ? $listing['images'] : null );

		// setup "thumbnails
		$listing['thumbnails'] = $this->generalize_field_thumbnails(
			(isset( $listing['thumbnails'] ) ? $listing['thumbnails'] : null),
			(isset( $listing['images'] ) ? $listing['images'] : null)
		);

		// populate helpers if they are not set
		if( !isset( $listing['rawDataForFiltering'] ) ){
			$listing['rawDataForFiltering'] = $this->get_raw_data_for_filtering(
				$listing['productGroup'], 
				$listing['type'], 
				$listing['listingType']
			);
		}

		// setup $listing_type
		$listing_type = null;
		if( isset( $listing['rawDataForFiltering']['listingType'] ) ){
			$listing_type = $listing['rawDataForFiltering']['listingType'];
		}

		// setup $product_group
		$product_group = null;
		if( isset( $listing['rawDataForFiltering']['productGroup'] ) ){
			$product_group = $listing['rawDataForFiltering']['productGroup'];
		}

		// setup card-elements header specification
		if( $listing_type ){
			$listing['card_spec'] = $this->setup_array_of_strings( [
				$this->maybe_convert_constant( $listing_type ),
				(isset( $listing_type ) && isset( $listing['roomCount'] ) ? ' - ' : html_entity_decode('&#8203;')),
				(isset( $listing['roomCount'] ) ? 
					$listing['roomCount'] . ' ' . _nx( 'room', 'rooms', $listing['roomCount'], 'Rooms info for listing', 'linear' )
					: 
					''),
			], '' );
		}

		//  setup permalink
		$listing_id = isset( $listing['identifier'] ) ? $listing['identifier'] : $listing['id'];
		$listing['permalink'] = $this->get_listing_permalink( $product_group, $listing['address'], $listing_id, $lang );

		// setup card-elements content "title"
		$listing['card_title'] = $this->setup_array_of_strings( [
			(isset( $listing['address'] ) ? $listing['address'] : null),
			(isset( $listing['gate'] ) ? $listing['gate'] : null)
		], ' ' );

		// setup card-elements content "subtitle"
		$listing['card_subtitle'] = $this->setup_array_of_strings( [
			(isset( $listing['city'] ) ? $listing['city'] : null),
			(isset( $listing['districtFree'] ) ? $listing['districtFree'] : null)
		], ', ' );

		// calculate selling price (debtFree - debt)
		$listing['selling_price'] = $this->calculate_selling_price(
			( isset( $listing['debtFreePrice'] ) ? $listing['debtFreePrice'] : '' ),
			( isset( $listing['debt'] ) ? $listing['debt'] : '' )
		);

		// easier prices for filterings
		$listing['rawDebtFreePrice'] = $this->get_raw_price( isset($listing['debtFreePrice']) ? $listing['debtFreePrice'] : null );
		$listing['rawRent'] = $this->get_raw_price( isset($listing['rent']) ? $listing['rent'] : null );

		// Area formatting
		$listing['area'] = $this->transform_area_for_display( isset( $listing['area'] ) ? $listing['area'] : null );
		$listing['lotArea'] = $this->transform_area_for_display( ( isset( $listing['lotArea'] ) ? $listing['lotArea'] : null ) );
		$listing['overallArea'] = $this->transform_area_for_display( ( isset( $listing['overallArea'] ) ? $listing['overallArea'] : null ) );
		$listing['businessPremiseArea'] = $this->transform_area_for_display( ( isset( $listing['businessPremiseArea'] ) ? $listing['businessPremiseArea'] : null ) );

		if( isset( $listing['housingCooperativeRetailSpaceArea'] ) ){
			$listing['housingCooperativeRetailSpaceArea'] = $this->transform_area_for_display( $listing['housingCooperativeRetailSpaceArea'] );
		}

		// Debtfreeprice formatting
		$listing['formatted_debtFreePrice'] = $this->transform_debt_free_price( isset( $listing['debtFreePrice'] ) ? $listing['debtFreePrice'] : null );
		$listing['formatted_debt'] = $this->transform_debt_free_price( isset( $listing['debt'] ) ? $listing['debt'] : null );
		$listing['formatted_rent'] = $this->transform_rent( isset( $listing['rent'] ) ? $listing['rent'] : null );


		$listing['lotOwnership'] = $this->maybe_convert_constant( isset( $listing['lotOwnership'] ) ? $listing['lotOwnership'] : null );

		$listing['waterCharge'] = $this->maybe_transform_water_charge(
			( isset( $listing['waterCharge'] ) ? $listing['waterCharge'] : null ),
			( isset( $listing['waterChargeType'] ) ? $listing['waterChargeType'] : null )
		);

		$listing['rent'] = $this->maybe_format_price( ( isset( $listing['rent'] ) ? $listing['rent'] : null ), 0 );
		$listing['maintenanceCharge'] = $this->maybe_format_price( ( isset( $listing['maintenanceCharge'] ) ? $listing['maintenanceCharge'] : null ), 0 );
		$listing['mandatoryCharges'] = $this->maybe_format_price( ( isset( $listing['mandatoryCharges'] ) ? $listing['mandatoryCharges'] : null ), 0 );
		$listing['fundingCharge'] = $this->maybe_format_price( ( isset( $listing['fundingCharge'] ) ? $listing['fundingCharge'] : null ), 0 );
		$listing['housingCooperativeRevenue'] = $this->maybe_format_price( ( isset( $listing['housingCooperativeRevenue'] ) ? $listing['housingCooperativeRevenue'] : null ), 0 );

		$listing['formatted_maintenanceCharge'] = $this->add_per_month_to_price( isset( $listing['maintenanceCharge'] ) ? $listing['maintenanceCharge'] : null );
		$listing['formatted_mandatoryCharges'] = $this->add_per_month_to_price( isset( $listing['mandatoryCharges'] ) ? $listing['mandatoryCharges'] : null );
		$listing['formatted_fundingCharge'] = $this->add_per_month_to_price( isset( $listing['fundingCharge'] ) ? $listing['fundingCharge'] : null );
		$listing['formatted_renovationCharge'] = $this->add_per_month_to_price( isset( $listing['renovationCharge'] ) ? $listing['renovationCharge'] : null );
		$listing['formatted_otherCharge'] = $this->add_per_month_to_price( isset( $listing['otherCharge'] ) ? $listing['otherCharge'] : null );
		$listing['formatted_saunaCharge'] = $this->maybe_transform_sauna_charge(
			( isset( $listing['saunaCharge'] ) ? $listing['saunaCharge'] : null),
			( isset( $listing['saunaChargeType'] ) ? $listing['saunaChargeType'] : null)
		);
		$listing['formatted_parkingCharge'] = $this->maybe_transform_charge(
			( isset( $listing['parkingCharge'] ) ? $listing['parkingCharge'] : null),
			( isset( $listing['parkingChargeType'] ) ? $listing['parkingChargeType'] : null)
		);

		if( isset( $listing['propertyTax'] ) ){
			$listing['propertyTax'] = $this->transform_yearly_price( $listing['propertyTax'] );
		}
		if( isset( $listing['landYearRent'] ) ){
			$listing['landYearRent'] = $this->transform_yearly_price( $listing['landYearRent'] );
		}

		$listing['formatted_plotRentCharge'] = $this->add_per_month_to_price( isset( $listing['plotRentCharge'] ) ? $listing['plotRentCharge'] : null );

		$listing['formatted_carPortCount'] = isset( $listing['carPortCount'] ) ? $listing['carPortCount'] . ' ' . __('units', 'linear') : null;
		$listing['formatted_garageCount'] = isset( $listing['garageCount'] ) ? $listing['garageCount'] . ' ' . __('units', 'linear') : null;
		$listing['formatted_electricPlugParkingSpaceCount'] = isset( $listing['electricPlugParkingSpaceCount'] ) ? $listing['electricPlugParkingSpaceCount'] . ' ' . __('units', 'linear') : null;
		$listing['formatted_yardParkingSpaceCount'] = isset( $listing['yardParkingSpaceCount'] ) ? $listing['yardParkingSpaceCount'] . ' ' . __('units', 'linear') : null;
		$listing['formatted_parkingGarageCount'] = isset( $listing['parkingGarageCount'] ) ? $listing['parkingGarageCount'] . ' ' . __('units', 'linear') : null;

		// Handling values that are sometimes string and sometimes arrays
		foreach( self::$colliding_string_array_values as $single ){
			$single_value = $this->maybe_convert_array_to_string( isset( $listing[$single] ) ? $listing[$single] : null );
			if( $single_value !== null ){
				$listing[$single] = $single_value;
			}
		}

		$listing['balcony'] = $this->maybe_transform_balcony(
			( isset( $listing['balcony'] ) ? $listing['balcony'] : null ),
			( isset( $listing['hasBalcony'] ) ? $listing['hasBalcony'] : null )
		);
		$listing['hasBalcony'] = ( isset( $listing['hasBalcony'] ) ? !!$listing['hasBalcony'] : false);

		// Handle date transforms
		if( isset( $listing['propertyManagerCertificateDate'] ) ){
			$listing['propertyManagerCertificateDate'] = $this->transform_date( $listing['propertyManagerCertificateDate'] );
		}

		if( isset( $listing['conditionInvestigationDate'] ) ){
			$listing['conditionInvestigationDate'] = $this->transform_date( $listing['conditionInvestigationDate'] );
		}

		// Handle phone formats
		if( isset( $listing['propertyManagerPhone'] ) ){
			$listing['propertyManagerPhone'] = $this->transform_phone_number( $listing['propertyManagerPhone'] );
		}

		if( isset( $listing['electricHeatingPowerUsage'] ) ){
			$listing['electricHeatingPowerUsage'] = $this->transform_electric_heating_power( $listing['electricHeatingPowerUsage'] );
		}

		// Release Date
		if( isset( $listing['releaseDate'] ) && $listing['releaseDate'] ){
			$listing['releaseDate'] = $this->transform_date( $listing['releaseDate'] );
		}

		// Property building right
		if( isset( $listing['propertyBuildingRights'] ) ){
			$listing['propertyBuildingRights'] = $this->transform_area_for_display( $listing['propertyBuildingRights'] );
		}

		// Other area
		if( isset( $listing['otherArea'] ) ){
			$listing['otherArea'] = $this->transform_area_for_display( $listing['otherArea'] );
		}

		// Business premise area
		if( isset( $listing['businessPremiseArea'] ) ){
			$listing['businessPremiseArea'] = $this->transform_area_for_display( $listing['businessPremiseArea'] );
		}

		// Charges
		$charges = [
			'electricHeatingCharge',
			'heatingCharge',
			'nonElectricHeatingCharge',
			'averageTotalHeatingCharge',
			'roadMaintenanceCharge',
			'broadbandCharge',
			'waterAndSewageCharge',
			'sanitationCharge',
			'satelliteCableTVCharge'
		];

		foreach( $charges as $charge ){
			if( isset( $listing[$charge] ) ){
				$listing[$charge] = $this->maybe_transform_charge(  $listing[$charge], 'PER_MONTH' );
			}
		}

		// beach line
		if( isset( $listing['ownBeachLine'] ) ){
			$listing['ownBeachLine'] = strval( floatval( $listing['ownBeachLine'] ) ) === strval( $listing['ownBeachLine'] ) ? $listing['ownBeachLine'] . ' m' : $listing['ownBeachLine'];
		}
		
		return $listing;
	}

	/**
	 * Generalize "images" field
	 * 
	 * End with array of objects
	 */
	private function generalize_field_images( $images ){
		if( !$images ){
			return [];
		}

		// Handling v1 API
		if( is_string( $images[0] ) ){
			foreach( $images as &$image ){
				$imageObj = new stdClass();
				$imageObj->compressed = $image;
				$imageObj->description = null;
				$image = $imageObj;
			}
		}

		return $images;
	}

	/**
	 * Generalize "thumbnails" field
	 * 
	 * End with array of strings
	 */
	private function generalize_field_thumbnails( $thumbnails, $images ){
		if( $thumbnails ){
			return $thumbnails;
		}

		// Handling v1 API
		if( isset( $images[0] ) ){
			return [ $images[0]->compressed ];
		}

		return [];
	}

	/**
	 * Input array values, output nice string
	 */
	private function setup_array_of_strings( $values, $delimiter, $default = "" ){

		// verify inputs
		if( !$values || $delimiter === null ){
			return $default;
		}

		// verify that all values are strings
		if( !array_sum( array_map( 'is_string', $values ) ) == count( $values ) ){
			return $default;
		}

		// Filter potential empty values
		$filtered_values = array_filter( $values, function( $value, $key ){
			if( is_string( $value ) && $value !== "" ){
				return true;
			}

			return false;
		}, ARRAY_FILTER_USE_BOTH );

		return implode( $delimiter, $filtered_values );
	}

	/**
	 * Convert a constant if it exists
	 */
	private function maybe_convert_constant( $key ){
		if( !$key || !is_string( $key ) ){
			return $key;
		}

		return self::$linear->get_slug_label($key );
	}

	/**
	 * The the dynamic "permalink" for the single listings
	 */
	private function get_listing_permalink( $listing_type, $address, $id, $lang ){

		// Get host page url
		$page_permalink = '';
		$languages = self::$linear->get_languages();
		$plugins = apply_filters( 'active_plugins', get_option( 'active_plugins' ) );

		if( $languages && is_array($languages) && count( $languages ) > 1 ){

			// Translatepress workaround
			if( in_array( 
				'translatepress-multilingual/index.php', 
				$plugins
			) && !$page_permalink ){
					$trp = TRP_Translate_Press::get_trp_instance();
					if( $trp ){
						$url_converter 	= $trp->get_component( 'url_converter' );

						$translatepress_settings = get_option( 'trp_settings' );
						$default_language = substr( $translatepress_settings['default-language'], 0, 2 );

						if( $listing_type === 'RENT_APARTMENT' && self::$linear->get_option( 'rentals_page_' . $default_language ) ){
							$page_permalink = get_permalink( self::$linear->get_option( 'rentals_page_' . $default_language ) );
						} else if( $listing_type === 'BUSINESS_PREMISES' && self::$linear->get_option( 'workplace_page_' . $default_language ) ){
							$page_permalink = get_permalink( self::$linear->get_option( 'workplace_page_' . $default_language ) );
						} else {
							$page_permalink = get_permalink( self::$linear->get_option( 'listings_page_' . $default_language ) );
						}

						// Translate permalink
						$page_permalink = $url_converter->get_url_for_language( $lang, $page_permalink, '' );
					}
			}

			// Basic multilang functionality
			if( !$page_permalink ){
				if( $listing_type === 'RENT_APARTMENT' && self::$linear->get_option( 'rentals_page_' . $lang ) ){
					$page_permalink = get_permalink( self::$linear->get_option( 'rentals_page_' . $lang ) );
				} else if( $listing_type === 'BUSINESS_PREMISES' && self::$linear->get_option( 'workplace_page_' . $lang ) ){
					$page_permalink = get_permalink( self::$linear->get_option( 'workplace_page_' . $lang ) );
				} else {
					$page_permalink = get_permalink( self::$linear->get_option( 'listings_page_' . $lang ) );
				}
			}

		} else {

			// Single language
			if( !$page_permalink ){
				if( $listing_type === 'RENT_APARTMENT' && self::$linear->get_option( 'rentals_page' ) ){
					$page_permalink = get_permalink( self::$linear->get_option( 'rentals_page' ) );
				} else if( $listing_type === 'BUSINESS_PREMISES' && self::$linear->get_option( 'workplace_page' ) ){
					$page_permalink = get_permalink( self::$linear->get_option( 'workplace_page' ) );
				} else {
					$page_permalink = get_permalink( self::$linear->get_option( 'listings_page' ) );
				}
			}

		}

		if( !$page_permalink ){
			return null;
		}

		// Get host page url
		return rtrim( $page_permalink, '/' ) . '/' . sanitize_title( $address ) . '/' . $id;
	}

	/**
	 * Calculate the selling price
	 * 
	 * HOX! Will be removed once the API provides this value straight out of the box
	 */
	private function calculate_selling_price( $price, $debt ){
		if( !$price || !$debt ){
			return null;
		}

		$price_as_number = 0;
		$debt_as_number = 0;

		// how to handle depending on versions
		if( strpos( $price, " €" ) === false ){
			// old api
			$price_as_number = intval( $price );
			$debt_as_number = intval( $debt );

		} else {
			// new api
			$price_as_number = intval( str_replace(' ', '', $price) );
			$debt_as_number = intval( str_replace(' ', '', $debt) );
		}

		return number_format( $price_as_number - $debt_as_number, 0, ",", " ") . " €";
	}

	// Get raw price for easier data-handling
	private function get_raw_price( $price ){
		if( !$price ){
			return null;
		}

		// how to handle depending on versions
		if( strpos( $price, " €" ) === false ){
			// old api
			return intval( $price );

		} else {
			// new api
			return intval( str_replace(' ', '', $price) );
		}
	}

	// Helper for API V1 missing values
	private function get_raw_data_for_filtering( $product_group, $type, $listing_type ){
		return [
			'productGroup' => ($product_group ? $product_group : ''),
			'type' => ($type ? $type : ''),
			'listingType' => ($listing_type ? $listing_type : ''),
		];
	}

	// handle area
	private function transform_area_for_display( $area ){
		if( !$area ){
			return null;
		}

		$transformed_area = $area;

		if( $area && strpos( $area, "m²" ) === false ){
			$transformed_area = $area . "&nbsp;m²";
		}

		// convert dots to commas
		$transformed_area = str_replace( '.', ',', $transformed_area );

		return $transformed_area;
	}

	private function transform_debt_free_price( $debt_free_price ){
		if( !$debt_free_price ){
			return $debt_free_price;
		}

		if( strpos( $debt_free_price, " €" ) !== false ){
			return $debt_free_price;
		}

		return number_format( $debt_free_price, 0, ",", " ") . " €";
	}

	private function transform_rent( $rent ){
		if( !$rent ){
			return $rent;
		}

		if( strpos( $rent, " €" ) !== false ){
			return $rent;
		}

		if( strval( $rent ) !== strval( floatval( $rent ) ) ){
			return $rent;
		}

		return number_format( $rent, 0, ",", " ") . _x(" € / m", 'per month pricing', 'linear');
	}

	private function transform_yearly_price( $price ){
		if( !$price ){
			return $price;
		}

		if( strval( $price ) !== strval( floatval( $price ) ) ){
			return $price;
		}

		return number_format( $price, 2, ",", " ") . _x(" € / year", 'per year fees', 'linear');
	}

	private function maybe_format_price( $price, $decimals = 0 ){
		if( strpos( $price, " €" ) !== false ){
			return $price;
		}

		$price_as_number = intval( str_replace(' ', '', $price) );

		return number_format( $price_as_number, $decimals, ",", " ") . " €";
	}

	private function maybe_convert_array_to_string( $input ){
		if( !$input ){
			return null;
		}

		if( !is_array( $input ) ){
			return $input;
		}

		if( count( $input ) === 1 ){
			return $input[0];
		}

		return $input;
	}

	private function maybe_transform_water_charge( $water_charge, $water_charge_type ){
		if( !$water_charge ){
			return null;
		}

		// Check if we only only have numbers
		if( strval( $water_charge ) !== strval( floatval( $water_charge ) ) ){
			return $water_charge;
		}

		if( $water_charge_type === 'PER_PERSON_MONTH' ){
			return $this->maybe_format_price( $water_charge, 0 ) . ' ' . $this->maybe_convert_constant('PER_PERSON_MONTH');
		}

		return $water_charge;
	}

	private function maybe_transform_balcony( $balcony, $has_balcony ){
		if( in_array( $has_balcony, [ null, false, '', [] ] ) ){
			return null;
		}

		if( !$balcony && $has_balcony ){
			return __('Has balcony', 'linear');
		}

		return $balcony;
	}

	private function add_per_month_to_price( $price ){
		if( strpos( $price, "€" ) === false ){
			return $price;
		}

		if( strpos( $price, _x(" / m", 'per month fees', 'linear') ) !== false ){
			return $price;
		}

		return $price . _x(" / m", 'per month fees', 'linear');
	}

	private function maybe_transform_sauna_charge( $sauna_charge, $sauna_charge_type = 'MONTHLY'){
		if( !$sauna_charge ){
			return $sauna_charge;
		}

		if( strval( floatval( $sauna_charge ) ) !== strval( $sauna_charge ) ){
			return $sauna_charge;
		}

		if( $sauna_charge_type === 'MONTHLY'){
			return $sauna_charge . _x(" € / m", 'per month fees', 'linear');
		}

		return $sauna_charge;
	}

	private function maybe_transform_charge( $charge, $charge_type = 'PER_MONTH'){
		if( !$charge ){
			return $charge;
		}

		if( strval( floatval( $charge ) ) !== strval( $charge ) ){
			return $charge;
		}

		if( $charge_type === 'PER_MONTH'){
			return $charge . _x(" € / m", 'per month fee', 'linear');
		}

		if( $charge_type === 'PER_PERSON_MONTH'){
			return $charge . _x(" € / m / person", 'per month per person fee', 'linear');
		}

		if( $charge_type === 'PER_YEAR'){
			return $charge . _x(" € / year", 'per year fee', 'linear');
		}

		if( $charge_type === 'PER_TIME'){
			return $charge . _x(" € / time", 'per time fee', 'linear');
		}

		if( $charge_type === 'WEEKLY'){
			return $charge . _x(" € / week", 'per week fee', 'linear');
		}

		if( $charge_type === 'MONTHLY'){
			return $charge . _x(" € / month", 'per month fee', 'linear');
		}

		if( $charge_type === 'YEARLY'){
			return $charge . _x(" € / year", 'per year fee', 'linear');
		}

		if( $charge_type === 'ONCE'){
			return $charge . _x(" € / time", 'per time fee', 'linear');
		}

		return $charge . " €";
	}

	private function transform_date( $date ){
		if( !$date ){
			return null;
		}
	
		if( !is_string( $date ) ){
			return $date;
		}
	
		return date_i18n( get_option( 'date_format' ), strtotime( $date ) );
	}

	private function transform_phone_number( $phone ){
		if( !$phone ){
			return null;
		}
	
		if( !is_string( $phone ) ){
			return $phone;
		}

		if( strpos( $phone, '+' ) ) {
			return substr($phone, 0, 4) . ' ' . substr($phone, 4, 3) . ' ' . substr($phone, 7, 3)  . ' ' . substr($phone, 10, 20);
		} else {
			return substr($phone, 0, 3) . ' ' . substr($phone, 3, 3) . ' ' . substr($phone, 6, 20);
		}
	
		return $phone;
	}

	private function transform_electric_heating_power( $value ){
		if( !$value ){
			return null;
		}
	
		if( !is_string( $value ) ){
			return $value;
		}

		if( strval( floatval( $value ) ) === $value ){
			return $value . ' ' . esc_html__(  'kWh / year', 'linear' );
		}

		return $value;
	}

	private function maybe_get_constant( $value ){
		if( !is_string( $value ) ){
			return $value;
		}
	
		// get constants
		$slugs = require plugin_dir_path( __DIR__ ) . "includes/constants_and_labels.php";
	
		if( !$slugs ){
			return null;
		}
	
		// Handle straight matches
		if ( array_key_exists( $value, $slugs ) ) {
			return $slugs[$value];
		}
	
		if ( strpos( $value, '@' ) !== false) {
	
			// Handle split value with not a straight set value
			$slug = explode( '@', $value, 2 );
	
			if( $slug[0] === strtoupper( $slug[0] ) ){
				if ( array_key_exists( $slug[0], $slugs ) ) {
	
					return $slugs[$slug[0]];
	
				} else {
	
					$label = ucwords( strtolower( $slug[0] ) );
					return str_replace( '_', ' ', $label );
	
				}
			}
	
		}
	
		return $value;
	}

	private function format_price_data( $price, $suffix = '', $factor = 1 ) {
		if ( is_numeric( $price ) ) {
			$value = $price;
		} elseif ( is_numeric( str_replace( array(',', ' '), array('.', ''), $price ) ) ) {
			$value = str_replace( array(',', ' '), array('.', ''), $price );
		} else {
			$value = $price ? $price : 0;
			if( is_string( $value ) ) {
				$value = str_replace( array(',', ' '), array('.', ''), $value );
			}
		}

		if ( ! is_numeric( $value ) ) {
			return strval( $value );
		}

		$value = round( $value * $factor, 2);

		if( ! empty( $suffix ) && $suffix[0] !== ' ' ) {
			$suffix = ' ' . $suffix;
		}

		$value = number_format( $value, 2, ',', '&nbsp;' );

		if( strpos( $value, ',' ) !== false ) {
			$value = rtrim( rtrim( $value,'0' ), ',' );
		}
		
		return $value  . '&nbsp;'. '€' . $suffix;
	}
	
	/**
	 * Similar as for listings but for buy commissions
	 */
	public function generalize_buy_commissions( $buy_commissions, $lang = null ){
		foreach( $buy_commissions as &$buy_commission ){
			$buy_commission = $this->generalize_buy_commission( $buy_commission, $lang );
		}

		return $buy_commissions;
	}

	private function generalize_buy_commission( $buy_commission, $lang ){
		if( !$buy_commission ){
			return null;
		}

		/*
		 * Permalink
		 */

		$languages = self::$linear->get_languages();
		$fallback_lang = 'fi';

		// Get host page url
		if( $languages && is_array($languages) && count( $languages ) > 1 ){
			$page_permalink = get_permalink( self::$linear->get_option( 'buy_commissions_page_' . $lang ) );
		} else {
			$page_permalink = get_permalink( self::$linear->get_option( 'buy_commissions_page' ) );
		}

		if( !$page_permalink ){
			return null;
		}

		$location = '';
		
		if( 
			isset( $buy_commission['data']['location'][$lang]['value'] ) ||
			isset( $buy_commission['data']['location'][$fallback_lang]['value'] )
		){
			if( isset( $buy_commission['data']['location'][$lang]['value'] ) ){
				$locale_value = $buy_commission['data']['location'][$lang]['value'];

				if( $locale_value !== 'null' ){
					$location = $locale_value;
				}
			} else {
				$locale_value = $buy_commission['data']['location'][$fallback_lang]['value'];

				if( $locale_value !== 'null' ){
					$location = $locale_value;
				}
			}
		}

		if( !$location ){
			$location = __('buy_commission', 'linear');
		}


		// Get host page url
		$buy_commission['permalink'] = rtrim( $page_permalink, '/' ) . '/' . sanitize_title( $location ) . '/' . $buy_commission['id'];

		return $buy_commission;
	}
}
