<?php
/**
 * Block Name:        Block: Linear listings
 * Description:       A block to show listings.
 * Author:            Oliver Granlund
 * Author URI:        https://olli.works
 */

@require_once 'utils.php';

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */
add_action( 'init', function() {
	register_block_type( __DIR__ . '/build' );
} );

// Globals filter
add_filter( 'linear_listings_block_global_data', function( $string ){
	global $TRP_LANGUAGE;

	$config = linear_listings_get_block_config();
	$lang = linear_listings_get_current_locale();

	return [
		'texts' => [
			'loadMore'       					=> __("Load more", 'linear'),
			'ListingsPerPage'       			=> __("Listings per page", 'linear'),
			'loadMoreButtonVisibility'  		=> __("\"Load more\"-button visibility", 'linear'),
			'showingLoadMoreButton'     		=> __("Showing \"Load more\"-button", 'linear'),
			'hidingLoadMoreButton'      		=> __("\"Load more\"-button hidden", 'linear'),
			'errorFailedLoadingListings'    	=> __("Oops, error has occurred!<br>If the problem persists, please contact %s", 'linear'),
			'noResultsTitle'    				=> __("No results with set filters", 'linear'),
			'noResultsBody'    					=> __("Unfortunately we couldn't find any results with these filters. Please adjust your filters or reset them all together.", 'linear'),
			'frontendFiltersVisibility'    		=> __("Show listing filters", 'linear'),
			'showingFrontendFilters'    		=> __("Showing filters", 'linear'),
			'hidingFrontendFilters'    			=> __("Listings filters are hidden", 'linear'),
			'productGroup'		    			=> __("Product group", 'linear'),
			'apartments'    					=> __("Apartments", 'linear'),
			'plots'    							=> __("Plots", 'linear'),
			'farms' 				   			=> __("Farms", 'linear'),
			'garages'    						=> __("Garages and parking spaces", 'linear'),
			'vacationApartment'    				=> __("Vacation Apartment", 'linear'),
			'roomCount'			    			=> __("Room count", 'linear'),
			'room'    							=> __("room", 'linear'),
			'rooms'    							=> __("rooms", 'linear'),
			'listingType'    					=> __("Listing Type", 'linear'),
			'flat'    							=> __("Block of flats", 'linear'),
			'rowhouse'    						=> __("Rowhouse", 'linear'),
			'pairhouse'    						=> __("Pairhouse", 'linear'),
			'detachedHouse'    					=> __("Detached House", 'linear'),
			'sauna'    							=> __("Sauna", 'linear'),
			'balcony'  		  					=> __("Balcony", 'linear'),
			'elevator'   	 					=> __("Elevator", 'linear'),
			'setFilterMinimumPrice'				=> __("Set filter minimum price", 'linear'),
			'setFilterMaximumPrice'				=> __("Set filter maximum price", 'linear'),
			'setFilterMinimumRent'    			=> __("Set filter minimum rent", 'linear'),
			'setFilterMaximumRent'    			=> __("Set filter maximum rent", 'linear'),
			'setFilterMinimumArea'    			=> __("Set filter minimum area", 'linear'),
			'setFilterMaximumArea'    			=> __("Set filter maximum area", 'linear'),
			'businessListingType'    			=> __("Business Listing Type", 'linear'),
			'officeSpace'    					=> __("Office Space", 'linear'),
			'businessSpace'    					=> __("Business Space", 'linear'),
			'productionSpace'    				=> __("Production Space", 'linear'),
			'storageSpaceHouse'					=> __("Storage Space House", 'linear'),
			'restaurantSpace'					=> __("Restaurant Space", 'linear'),
			'exhibitionSpace'					=> __("Exhibiton Space", 'linear'),
			'hobbySpace'						=> __("Hobby Space", 'linear'),
			'hubSpace'							=> __("Hub Space", 'linear'),
			'otherBusinessSpace'				=> __("Other Business Space", 'linear'),
			'sort'								=> __("Sort", 'linear'),
			'latestFirst'						=> __("Latest first", 'linear'),
			'oldestFirst'						=> __("Oldest first", 'linear'),
			'showFilters'						=> __("Show filters", 'linear'),
			'hideFilters'						=> __("Hide filters", 'linear'),
			'resetFilters'						=> __("Reset filters", 'linear'),
			'frontendRangeSlidersVisibility'	=> __("Show additional range filters eg. price", 'linear'),
			'showingRangeFilters'				=> __("Showing range filters", 'linear'),
			'hidingRangeFilters'				=> __("Range filters hidden", 'linear'),
			'noListingsTitle'					=> __("No results", 'linear'),
			'noListingsBody'					=> __("Unfortunately there are currently no results to show. Come back later to see if any have been added!", 'linear'),
			'frontendOrderByVisibility'			=> __("Show 'Sort by' ordering", 'linear'),
			'showingOrderBy'					=> __("Showing ordering options", 'linear'),
			'hidingOrderBy'						=> __("Ordering options hidden", 'linear'),
			'chooseLocation'					=> __("Choose location", 'linear'),
			'boolFalse'							=> _x("No", 'boolFalse', 'linear'),
			'boolTrue'							=> _x("Yes", 'boolTrue', 'linear'),
			'bidding'							=> __("Bidding", 'linear'),
			'search'							=> __("Search", 'linear'),
			'commissionType'					=> __("Commission type", 'linear'),
			'salesCommissions'					=> __("Sales commissions", 'linear'),
			'rentCommissions'					=> __("Rent commissions", 'linear'),
			'showSearchField'					=> __("Show search field", 'linear'),
			'cssClasses'						=> __("CSS classes", 'linear'),
			'showCommissionType'				=> __("Show commission type", 'linear'),
			'showProductGroup'					=> __("Show product group", 'linear'),
			'showRoomCount'						=> __("Show room count", 'linear'),
			'showListingType'					=> __("Show listing type", 'linear'),
			'showSpecifications'				=> __("Show specifications", 'linear'),
			'showBusinessListingType'			=> __("Show business listing type", 'linear'),
			'showPriceRange'					=> __("Show price range", 'linear'),
			'showRentRange'						=> __("Show rent range", 'linear'),
			'showAreaRange'						=> __("Show area range", 'linear'),

			'filterCommissionType'				=> __("Default set commission type", 'linear'),
			'filterSearch'						=> __("Default search prompt", 'linear'),
			'filterProductGroup'				=> __("Default set product group", 'linear'),
			'filterListingType'					=> __("Default set listing type", 'linear'),

			'priceRangeUpper'					=> __("Price range upper value", 'linear'),
			'priceRangeLower'					=> __("Price range lower value", 'linear'),
			'rentRangeUpper'					=> __("Rent range upper value", 'linear'),
			'rentRangeLower'					=> __("Rent range lower value", 'linear'),
			'areaRangeUpper'					=> __("Area range upper value", 'linear'),
			'areaRangeLower'					=> __("Area range lower value", 'linear'),

			'apartments'						=> __("Apartments", 'linear'),
			'plots'								=> __("Plots", 'linear'),
			'farms'								=> __("Farms", 'linear'),
			'garages'							=> __("Garages", 'linear'),
			'blockOfFlats'						=> __("Block of flats", 'linear'),
			'rowhouse'							=> __("Rowhouse", 'linear'),
			'pairhouse'							=> __("Pairhouse", 'linear'),
			'detachedhouse'						=> __("Detachedhouse", 'linear'),
			'showAll'							=> __("Show all", 'linear'),
			'sell'								=> __("Sell", 'linear'),
			'rent'								=> __("Rent", 'linear'),
			'none'								=> __("None", 'linear'),
			'askPrice'							=> __("Ask price", 'linear'),
			'searchElementPlaceholder'			=> __("Search by location or postal code", "linear"),
			'listingCount'						=> _x("Listings", "count of listings", "linear"),
			'listingCountSingle'				=> _x("Listing", "count of listings", "linear"),
			'listingCountSeveral'				=> _x("Listings", "count of listings", "linear"),
			'filteredListingCount'				=> _x("Listings found", "count of listings", "linear"),
		],
		'locale' => $lang,
		'actions' => $config["actions"],
		'restBase' => linear_listings_get_rest_url(),
		'apiVersion' => apply_filters('linear_get_api_version', ''),
		'blockClassName' => $config["blockClassName"],
		'colors' => apply_filters('linear_get_colors', ''),
		'searchOptions' => apply_filters('linear_get_search_options', $lang)
	];
}, 10, 1 );

/**
 * Enqueue the frontend scripts required by the block.
 * Only load them if the page contains our block.
 */
function linear_listings_block_enqueue_frontend_assets() {
	$metadata = linear_listings_get_block_metadata();

	if( isset($metadata) ){
		if ( !has_block($metadata['name']) ) {
			return;
		}
	}

	$config = linear_listings_get_block_config();

	// Register our frontend script with dependencies.
	wp_enqueue_script(
		$config["frontend"]["handleJS"],
		plugins_url("build-frontend/index.js", __FILE__),
		$config["frontend"]["dependencies"],
		null
	);

	// Pass PHP configuration to our frontend script.
	wp_localize_script(
		$config["frontend"]["handleJS"],
		$config["frontend"]["globalConfig"],
		apply_filters('linear_listings_block_global_data', null)
	);
}

add_action( 'enqueue_block_assets', 'linear_listings_block_enqueue_frontend_assets' );

// Force globals in WP-Admin
add_action( 'admin_head', function() {
	$config = linear_listings_get_block_config();
	$globalConfig = $config["frontend"]["globalConfig"];
	$globalData = apply_filters('linear_listings_block_global_data', null);

	// Start buffer
	ob_start();

		?><script type="text/javascript">
			window.<?= $globalConfig ?> = <?= json_encode($globalData) ?>;
		</script><?php

	// Output globals
	echo ob_get_clean();
} );

// For adding block outside of Gutenberg, could be optimized
add_shortcode( 'linear_block_listings', function( $atts ){

	$options = get_option( 'linear_settings' );
	$perPage = 8;
	if( isset( $options['listing_columns'] ) && intval( $options['listing_columns'] ) === 3 ){
		$perPage = 9;
	}

	// Setup args
	$args = shortcode_atts([
		'type' 									=> 'all', // Types: all, apartments, rent_apartments, business_premises
		'per_page' 								=> $perPage,
		'filters' 								=> 'true',
		'loadmore' 								=> 'true',
		'classes' 								=> '',
		'range_sliders' 						=> 'true',
		'order_by' 								=> 'true',

		'commission_type'						=> 'true',
		'search' 								=> 'true',
		'product_group'							=> 'true',
		'room_count'							=> 'true',
		'listing_type'							=> 'true',
		'specifications'						=> 'true',
		'business_listing_type'					=> 'true',
		'price_range'							=> 'true',
		'rent_range'							=> 'true',
		'area_range'							=> 'false',

		'filter_commission_type' 				=> 'all', // Commission types: all, sell, rent
		'filter_search' 						=> '',
		'filter_product_group'					=> '',
		//'filter_room_count'						=> '',
		'filter_listing_type'					=> '',
		//'filter_business_listing_type'			=> '',

		// specifications
		//'filter_sauna'							=> 'false',
		//'filter_has_balcony'					=> 'false',
		//'filter_housing_cooperative_elevator' 	=> 'false',

		'filter_realtors'						=> '',
		
		'price_range_lower'				=> get_setting_value('price_lower') ? get_setting_value('price_lower') : '20000', // get from settings
		'price_range_upper'				=> get_setting_value('price_upper') ? get_setting_value('price_upper') : '750000',
		'rent_range_lower'				=> get_setting_value('rent_lower') ? get_setting_value('rent_lower') : '0',
		'rent_range_upper'				=> get_setting_value('rent_upper') ? get_setting_value('rent_upper') : '2500',
		'area_range_lower'				=> get_setting_value('area_lower') ? get_setting_value('area_lower') : '0',
		'area_range_upper'				=> get_setting_value('area_upper') ? get_setting_value('area_upper') : '500'
	], $atts );

	// Mappings for more user friendly usage
	$type_mappings = [
		'all' 				=> 'all',
		'apartments' 		=> 'APARTMENTS',
		'rent_apartments' 	=> 'RENT_APARTMENT',
		'business_premises' => 'BUSINESS_PREMISES'
	];
	
	$commission_type_mappings = [
		'all'	=> '',
		'sell'	=> 'sell',
		'rent' 	=> 'rent'
	];

	$config = linear_listings_get_block_config();

	if( !is_admin() ){
		// Register our frontend script with dependencies.
		wp_enqueue_script(
			$config["frontend"]["handleJS"],
			plugins_url("build-frontend/index.js", __FILE__),
			$config["frontend"]["dependencies"],
			null
		);

		wp_enqueue_style(
			$config["frontend"]["handleCSS"],
			plugins_url("build/style-index.css", __FILE__)
		);
	}

	// Pass PHP configuration to our frontend script, disabled for now.
	// wp_localize_script(
	//   	$config["frontend"]["handleJS"],
	//  	$config["frontend"]["globalConfig"],
	// 	    apply_filters('linear_listings_block_global_data', null)
	// );

	ob_start();
 
		// Adding in globals in such a way that caching plugins cannot affect it
		echo '<script id="block-linear-listings-frontend.js-js-extra">
			if( typeof blockLinearListingsConfig === "undefined"){
				var blockLinearListingsConfig = ' . json_encode( apply_filters('linear_listings_block_global_data', null) ) . '
			}
		</script>';

		?>
			<div class="wp-block-group linear-wp-block-group et_pb_row elementor-section elementor-section-boxed">
				<div class="wp-block-linear-listings linear-listings is-style-wide elementor-container elementor-column-gap-default <?php echo $args['classes'] ?>" 
					data-listings-type="<?php echo $type_mappings[ $args['type'] ] ?>"
					data-listings-per-page="<?php echo $args['per_page'] ?>"
					data-show-load-more="<?php echo $args['loadmore'] ?>"
					data-show-filters="<?php echo $args['filters'] ?>"
					data-show-range-sliders="<?php echo $args['range_sliders'] ?>"
					data-show-order-by="<?php echo $args['order_by'] ?>"

					data-show-commission-type="<?php echo $args['commission_type'] ?>"
					data-show-search="<?php echo $args['search'] ?>"
					data-show-product-group="<?php echo $args['product_group'] ?>"
					data-show-room-count="<?php echo $args['room_count'] ?>"
					data-show-listing-type="<?php echo $args['listing_type'] ?>"
					data-show-specifications="<?php echo $args['specifications'] ?>"
					data-show-business-listing-type="<?php echo $args['business_listing_type'] ?>"
					data-show-price-range="<?php echo $args['price_range'] ?>"
					data-show-rent-range="<?php echo $args['rent_range'] ?>"
					data-show-area-range="<?php echo $args['area_range'] ?>"

					data-filter-commission-type="<?php echo $commission_type_mappings[ $args['filter_commission_type'] ] ?>"
					data-filter-search="<?php echo $args['filter_search'] ?>"
					data-filter-product-group="<?php echo $args['filter_product_group'] ?>"
					data-filter-listing-type="<?php echo $args['filter_listing_type'] ?>"

					data-filter-realtors="<?php echo $args['filter_realtors'] ?>"

					data-price-range-lower="<?php echo $args['price_range_lower'] ?>"
					data-price-range-upper="<?php echo $args['price_range_upper'] ?>"
					data-rent-range-lower="<?php  echo $args['rent_range_lower'] ?>"
					data-rent-range-upper="<?php  echo $args['rent_range_upper'] ?>"
					data-area-range-lower="<?php  echo $args['area_range_lower'] ?>"
					data-area-range-upper="<?php  echo $args['area_range_upper'] ?>"
				></div>
			</div>
		<?php

    return ob_get_clean();
} );
