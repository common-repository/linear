/**
 * External dependencies
 */
import React, { useState, useEffect, useMemo } from 'react';
import axios from 'axios';

/**
 * Internal dependencies
 */
import { AppWrapper } from './wrappers';
import { ResultsLoader } from './loaders';
import {
	Listing,
	LoadMore,
	ErrorMessage,
	NoListings,
	NoResults,
	Filters,
	OrderBy,
	ListingsCount,
} from './components';
import { ListingsWrapper } from './wrappers';
import { BaseFilterer, ListingsFilterer, URLParamsHandler, LocaleHandler } from './utils';
import { AppContext } from './utils/Context';

/**
 * Configuration
 */
import config from './config';
let { texts, actions, locale, restBase, apiVersion, colors, searchOptions } = config;

const App = ({ 
	listingsType = 'all', 
	showOrderBy = true, 
	showFilters = true, 
	showRangeSliders = true, 
	listingsPerPage = 8, 
	showLoadMore = true, 
	admin,

	showCommissionType 			= true,
	showSearch 					= true,
	showProductGroup 			= true,
	showRoomCount 				= true,
	showListingType 			= true,
	showSpecifications 			= true,
	showBusinessListingType 	= true,
	showPriceRange 				= true,
	showRentRange 				= true,
	showAreaRange 				= true,

	filterCommissionType 		= '',
	filterSearch 				= '',
	filterProductGroup 			= '',
	filterRoomCount 			= '',
	filterListingType 			= '',
	// filterSpecifications 		= '', // are set one by one
	filterBusinessListingType 	= '',

	filterSauna					= '',
	filterhasBalcony			= '',
	filterHousingCooperativeElevator = '',

	filterRealtors				= '',

	priceRangeLower 			= '',
	priceRangeUpper 			= '',
	rentRangeLower 				= '',
	rentRangeUpper 				= '',
	areaRangeLower 				= '',
	areaRangeUpper 				= '',
}) => {
	const [listings, setListings] = useState([]);
	const [isLoading, setIsLoading] = useState(true);
	const [errorStatus, setErrorStatus] = useState(false);
	const [paging, setPaging] = useState(1);
	const [frontEndFilters, setFrontEndFilters] = useState({});

	// Remap values
	const windowConfig = window.blockLinearListingsConfig;
	texts = windowConfig.texts;
	actions = windowConfig.actions;
	locale = LocaleHandler(windowConfig.locale);
	restBase = windowConfig.restBase;
	apiVersion = windowConfig.apiVersion;
	colors = windowConfig.colors;
	searchOptions = windowConfig.searchOptions;

	// upon certain filters change
	useEffect(() => {

		let initFilters = {};

		if( filterSearch ){
			initFilters.search = filterSearch;
		}
		if( filterCommissionType ){
			initFilters.commissionType = filterCommissionType;
		}
		if( filterProductGroup ){
			initFilters.productGroup = filterProductGroup;
		}
		if( filterListingType ){
			initFilters.listingType = filterListingType;
		}

		const onLoadParams = URLParamsHandler();

		setFrontEndFilters({ 
			...frontEndFilters, 
			...initFilters,
			...onLoadParams
		});

		// Fetch data
		const action = actions.listings.replace('%s', apiVersion.replace('.', '_'));

		let requestUrl = '';
		if( !restBase.includes('?') ){
			// Pretty permalinks
			requestUrl = restBase + action + '?lang=' + locale;
		} else {
			// Ugly permalinks
			requestUrl = restBase + action + '&lang=' + locale;
		}

		// Load routes data
		axios
			.get(requestUrl, {
				params: {},
			})
			.then(function (response) {

				if( typeof response.data.errors !== 'undefined' ){
					setErrorStatus(true);
					setIsLoading(false);
					setListings([]);
				} else {
					setListings(response.data);
					setIsLoading(false);
				}

			})
			.catch(function (thrown) {

				setErrorStatus(true);
				setIsLoading(false);
				setListings([]);
				
			});
	}, []);

	// Pagination and filters reset
	useEffect(() => {
		setPaging(1);
	}, [listingsType, showFilters]);

	// Pagination loadmore
	const loadMore = () => {
		setPaging(paging + 1);
	};

	// Filter data by admin settings
	const baseListings = BaseFilterer({ listings, listingsType });

	// Filter by frontend filters
	let filteredListings = ListingsFilterer({
		listings: baseListings,
		filters: frontEndFilters,
		listingsType: listingsType,
		filterRealtors: 	filterRealtors,
		priceRangeLower: 	priceRangeLower,
		priceRangeUpper: 	priceRangeUpper,
		rentRangeLower: 	rentRangeLower,
		rentRangeUpper: 	rentRangeUpper,
		areaRangeLower: 	areaRangeLower,
		areaRangeUpper: 	areaRangeUpper
	});

	// order elements
	if( typeof frontEndFilters['orderBy'] !== 'undefined' && frontEndFilters['orderBy'] === 'reverse'){
		filteredListings = filteredListings.reverse();
	}

	const context = useMemo(() => {
		return {
			texts,
			colors,
			listingsType,
			showFilters,
			showRangeSliders,

			frontEndFilters,
			searchOptions,
			setFrontEndFilters
		};
	}, [listingsType, frontEndFilters]);

	const filterGlobals = {
		'showSearch'				: showSearch,
		'showCommissionType'		: showCommissionType,
		'showProductGroup' 			: showProductGroup,
		'showRoomCount' 			: showRoomCount,
		'showListingType' 			: showListingType,
		'showSpecifications' 		: showSpecifications,
		'showBusinessListingType' 	: showBusinessListingType,
		'showPriceRange' 			: showPriceRange,
		'showRentRange'				: showRentRange,
		'showAreaRange' 			: showAreaRange,

		'filterCommissionType'		: filterCommissionType,
		'filterSearch'				: filterSearch,
		//'filterProductGroup': filterProductGroup,
		//'filterRoomCount': filterRoomCount,
		//'filterListingType': filterListingType,
		//'filterSpecifications': filterSpecifications,
		//'filterBusinessListingType': filterBusinessListingType,

		'priceRangeLower'			: priceRangeLower,
		'priceRangeUpper'			: priceRangeUpper,
		'rentRangeLower'			: rentRangeLower,
		'rentRangeUpper'			: rentRangeUpper,
		'areaRangeLower'			: areaRangeLower,
		'areaRangeUpper'			: areaRangeUpper
	}

	return (
		<AppContext.Provider value={context}>
			<AppWrapper windowConfig={windowConfig}>
				{isLoading || errorStatus ? (
					<>
						{isLoading ? <ResultsLoader /> : ''}
						{errorStatus ? (
							<ErrorMessage />
						) : (
							''
						)}
					</>
				) : (
					<>
						<div className="linear-listings__handlers">
							<Filters globals={filterGlobals} />
							{ (showOrderBy && filteredListings.length > 0) && (
								<div className="linear-listings__count-order">
									<ListingsCount count={ filteredListings.length } maxCount={ baseListings.length } />
									<OrderBy />
								</div>
							)}
						</div>
						<ListingsWrapper windowConfig={windowConfig} listingsCount={filteredListings.length}>
							{filteredListings.length > 0 ? (
								<>
									{filteredListings
										.slice(0, paging * listingsPerPage)
										.map((listing, index) => {
											return (
												<Listing
													listing={listing}
													key={listing.id + index}
													listingsPerPage={
														listingsPerPage
													}
													index={index}
													admin={admin}
												/>
											);
										})}
								</>
							) : (
								<>
									{( ( Object.keys(frontEndFilters).length === 0 ) ? 
										<NoListings />
									:
										<NoResults />
									)}
								</>
							)}
						</ListingsWrapper>
						{showLoadMore &&
						filteredListings.length > paging * listingsPerPage ? (
							<LoadMore trigger={loadMore} />
						) : (
							''
						)}
					</>
				)}
			</AppWrapper>
		</AppContext.Provider>
	);
};

export default App;
