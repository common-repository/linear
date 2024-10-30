<?php
/**
 * Block Name:        Block: Linear buy commissions
 * Description:       A block to show buy commissions.
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
add_filter( 'linear_buy_commissions_block_global_data', function( $string ){
	$config = linear_buy_commissions_get_block_config();
	$lang = linear_buy_commissions_get_current_locale();

	return [
		'texts' => [
			'loadMore'       					=> __("Load more", 'linear'),
			'CommissionsPerPage'       			=> __("Commissions per page", 'linear'),
			'loadMoreButtonVisibility'  		=> __("\"Load more\"-button visibility", 'linear'),
			'showingLoadMoreButton'     		=> __("Showing \"Load more\"-button", 'linear'),
			'hidingLoadMoreButton'      		=> __("\"Load more\"-button hidden", 'linear'),
			'errorFailedLoadingCommissions'    	=> __("Oops, error has occurred!<br>If the problem persists, please contact %s", 'linear'),
			'noResultsTitle'    				=> __("No results with set filters", 'linear'),
			'noResultsBody'    					=> __("Unfortunately we couldn't find any results with these filters. Please adjust your filters or reset them all together.", 'linear'),
			'frontendFiltersVisibility'    		=> __("Show commission filters", 'linear'),
			'showingFrontendFilters'    		=> __("Showing filters", 'linear'),
			'hidingFrontendFilters'    			=> __("Commissions filters are hidden", 'linear'),
			'productGroup'		    			=> __("Product group", 'linear'),
			'apartments'    					=> __("Apartments", 'linear'),
			'plots'    							=> __("Plots", 'linear'),
			'farms' 				   			=> __("Farms", 'linear'),
			'garages'    						=> __("Garages and parking spaces", 'linear'),
			'vacationApartment'    				=> __("Vacation Apartment", 'linear'),
			'roomCount'			    			=> __("Room count", 'linear'),
			'room'    							=> __("room", 'linear'),
			'rooms'    							=> __("rooms", 'linear'),
			'commissionType'    				=> __("Commission Type", 'linear'),
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
			'businessCommissionType'    		=> __("Business Commission Type", 'linear'),
			'officeSpace'    					=> __("Office Space", 'linear'),
			'businessSpace'    					=> __("Business Space", 'linear'),
			'productionSpace'    				=> __("Production Space", 'linear'),
			'storageSpaceHouse'					=> __("Storage Space House", 'linear'),
			'sort'								=> __("Sort", 'linear'),
			'latestFirst'						=> __("Latest first", 'linear'),
			'oldestFirst'						=> __("Oldest first", 'linear'),
			'showFilters'						=> __("Show filters", 'linear'),
			'hideFilters'						=> __("Hide filters", 'linear'),
			'resetFilters'						=> __("Reset filters", 'linear'),
			'frontendRangeSlidersVisibility'	=> __("Show additional range filters eg. price", 'linear'),
			'showingRangeFilters'				=> __("Showing range filters", 'linear'),
			'hidingRangeFilters'				=> __("Range filters hidden", 'linear'),
			'noCommissionsTitle'				=> __("No results", 'linear'),
			'noCommissionsBody'					=> __("Unfortunately there are currently no results to show. Come back later to see if any have been added!", 'linear'),
			'frontendOrderByVisibility'			=> __("Show 'Sort by' ordering", 'linear'),
			'showingOrderBy'					=> __("Showing ordering options", 'linear'),
			'hidingOrderBy'						=> __("Ordering options hidden", 'linear'),
			'chooseLocation'					=> __("Choose location", 'linear'),
			'boolFalse'							=> _x("No", 'boolFalse', 'linear'),
			'boolTrue'							=> _x("Yes", 'boolTrue', 'linear'),
			'blockOfFlats'						=> __("Block of flats", 'linear'),
			'highRise'							=> __("High rise", 'linear'),
			'terracedHouse'						=> __("Terraced House", 'linear'),
			'semiDetachedHouse'					=> __("Semi-detached house", 'linear'),
			'detachedHouse'						=> __("Detached house", 'linear'),
			'shortRoom'							=> _x("r", 'r as short for room', 'linear'),
			'location'							=> __("Location", 'linear'),
			'type'								=> __("Type", 'linear'),
			'size'								=> __("Size", 'linear'),
			'priceRange'						=> __("Price range", 'linear'),
			'id'								=> __("ID", 'linear'),
			'search'							=> __("Search", 'linear'),
			'showSearchField'					=> __("Show search field", 'linear'),
			'defaultSearchPrompt'				=> __("Default search prompt", 'linear'),
			'askPrice'							=> __("Ask price", 'linear'),
			'searchElementPlaceholder'			=> __("Search by location or postal code", "linear"),
		],
		'actions' => $config["actions"],
		'assets' => [
			'bgDixu'	=> LINEAR_PLUGIN_URL . 'dist/bg-dixu.png'
		],
		'locale' => substr( get_locale(), 0, 2 ),
		'restBase' => linear_buy_commissions_get_rest_url(),
		'apiVersion' => apply_filters('linear_get_api_version', ''),
		'blockClassName' => $config["blockClassName"],
		'colors' => apply_filters('linear_get_colors', '')
	];
}, 10, 1 );

/**
 * Enqueue the frontend scripts required by the block.
 * Only load them if the page contains our block.
 */
function linear_buy_commissions_block_enqueue_frontend_assets() {
	$metadata = linear_buy_commissions_get_block_metadata();

	if( isset($metadata) ){
		if ( !has_block($metadata['name']) ) {
			return;
		}
	}

	$config = linear_buy_commissions_get_block_config();

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
		apply_filters('linear_buy_commissions_block_global_data', null)
	);
}

add_action( 'enqueue_block_assets', 'linear_buy_commissions_block_enqueue_frontend_assets' );

// Force globals in WP-Admin
add_action( 'admin_head', function() {
	$config = linear_buy_commissions_get_block_config();
	$globalConfig = $config["frontend"]["globalConfig"];
	$globalData = apply_filters('linear_buy_commissions_block_global_data', null);

	// Start buffer
	ob_start();

		?><script type="text/javascript">
			window.<?= $globalConfig ?> = <?= json_encode($globalData) ?>;
		</script><?php

	// Output globals
	echo ob_get_clean();
} );

// For adding block outside of Gutenberg, could be optimized
add_shortcode( 'linear_block_buy_commissions', function( $atts ){

	// Setup args
	$args = shortcode_atts([
		'type' 			=> 'all', // Types: all, APARTMENTS, RENT_APARTMENT, BUSINESS_PREMISES
		'per_page' 		=> '8',
		'filters' 		=> 'true',
		'loadmore' 		=> 'true',
		'classes' 		=> '',
		'range_sliders' => 'true',
		'order_by' 		=> 'true',
		'search_field' 	=> 'true',
		'filter_search' => '',
	], $atts );

	// Mappings for more user friendly usage
	$value_mappings = [
		'all' 				=> 'all',
		'apartments' 		=> 'APARTMENTS',
		'rent_apartments' 	=> 'RENT_APARTMENT',
		'business_premises' => 'BUSINESS_PREMISES'
	];

	$config = linear_buy_commissions_get_block_config();

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

	ob_start();
 
		// Adding in globals in such a way that caching plugins cannot affect it
		echo '<script id="block-linear-buy-commissions-frontend.js-js-extra">
			if( typeof blockLinearBuyCommissionsConfig === "undefined"){
				var blockLinearBuyCommissionsConfig = ' . json_encode( apply_filters('linear_buy_commissions_block_global_data', null) ) . '
			}
		</script>';
		?>
			<div class="wp-block-group linear-wp-block-group et_pb_row elementor-section elementor-section-boxed">
				<div class="wp-block-linear-buy-commissions linear-buy-commissions is-style-wide <?php echo $args['classes'] ?>" 
					data-commissions-type="<?php echo $value_mappings[ $args['type'] ] ?>"
					data-commissions-per-page="<?php echo $args['per_page'] ?>"
					data-show-load-more="<?php echo $args['loadmore'] ?>"
					data-show-filters="<?php echo $args['filters'] ?>"
					data-show-range-sliders="<?php echo $args['range_sliders'] ?>"
					data-show-order-by="<?php echo $args['order_by'] ?>"
					data-show-search="<?php echo $args['search_field'] ?>"
					data-filter-search="<?php echo $args['filter_search'] ?>"
				></div>
			</div>
		<?php

    return ob_get_clean();
} );
