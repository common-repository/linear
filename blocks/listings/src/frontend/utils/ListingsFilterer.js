/**
 * Filtering listings according to the frontend filters
 */
const ListingsFilterer = ({
	listings, 
	filters,
	filterRealtors,
	priceRangeLower,
	priceRangeUpper,
	rentRangeLower,
	rentRangeUpper,
	areaRangeLower,
	areaRangeUpper
}) => {

	const windowConfig = window.blockLinearListingsConfig;
	const texts = windowConfig.texts;

	// commission type
	if (typeof filterRealtors !== 'undefined' && filterRealtors !== '') {

		const realtors = filterRealtors.split(' ');
		
		listings = listings.filter((listing) => {
			let validListing = false;

			realtors.forEach((realtor) => {
				if ( typeof listing['realtor']['email'] !== 'undefined' ) {
					if ( listing['realtor']['email'].toLowerCase() === realtor.toLowerCase() ) {
						validListing = true;
					}
				}
			});

			if ( validListing ) {
				return true;
			}

			return false;
		});
	}

	// commission type
	if (typeof filters['commissionType'] !== 'undefined' && filters['commissionType']) {
		
		if ( filters['commissionType'] && filters['commissionType'] !== '' && filters['commissionType'] !== 'all') {
			listings = listings.filter((listing) => {
				if ( typeof listing['rawDataForFiltering']['commissionType'] !== 'undefined' ) {
					if ( (listing['rawDataForFiltering']['commissionType']).toLowerCase() === filters['commissionType'].toLowerCase() ) {
						return true;
					}
				}

				return false;
			});
		}
	}

	// Product group
	if (typeof filters['productGroup'] !== 'undefined' && filters['productGroup']) {

		if (filters['productGroup'] !== '') {
			let newlyConstructedBypass = false;

			// hotfix
			if( filters['productGroup'] === 'apartments' ) {
				newlyConstructedBypass === true;
			}

			listings = listings.filter((listing) => {
				if ( typeof listing['rawDataForFiltering']['productGroup'] !== 'undefined' ) {
					if ( (listing['rawDataForFiltering']['productGroup']).toLowerCase() === filters['productGroup'] ) {
						return true;
					}

					// hotfix
					if( newlyConstructedBypass ){
						if ( (listing['rawDataForFiltering']['productGroup']).toLowerCase() === 'newly_constructed' ) {
							return true;
						}
					}
				}

				return false;
			});
		}
	}

	// RoomCount
	if (typeof filters.roomCount !== 'undefined' && filters.roomCount) {
		if( !filters.roomCount.every(count => !count) ){

			listings = listings.filter((listing) => {

				let matchingRoomCount = false;

				if (listing.roomCount !== null && typeof listing.roomCount !== 'undefined') {
					filters.roomCount.forEach((count, index) => {
						
						if (count) {
							let currentCount = parseInt(index) + 1;

							// If filtering is set to max aka. 6
							if (currentCount !== 6) {
								if (currentCount === parseInt(listing.roomCount)) {
									matchingRoomCount = true;
								}
							} else {
								// Check more equal as 6
								if (currentCount <= parseInt(listing.roomCount)) {
									matchingRoomCount = true;
								}
							}
						}
					});

					if( matchingRoomCount ){
						return true;
					}

					return false;
				}

				return false;
			});

		}

	}

	// Listing-type
	if (typeof filters['listingType'] !== 'undefined' && filters['listingType']) {
		if (filters['listingType'] !== '') {
			listings = listings.filter((listing) => {
				if (
					typeof listing['rawDataForFiltering']['listingType'] !==
					'undefined'
				) {
					if (
						listing['rawDataForFiltering'][
							'listingType'
						].toLowerCase() === filters['listingType']
					) {
						return true;
					}
				}
				return false;
			});
		}
	}

	// Specifications
	['sauna', 'hasBalcony', 'housingCooperativeElevator'].forEach(
		(specification) => {
			if (typeof filters[specification] !== 'undefined') {
				if ( Array.isArray( filters[specification] ) ) {
					if( filters[specification][0] === true ){
						listings = listings.filter((listing) => {
							if ( typeof listing[specification] !== 'undefined') {
								if ( listing[specification] === true || listing[specification].toString().toLowerCase() === texts.boolTrue.toLowerCase() ) {
									return true;
								}
							}
							return false;
						});
					}
				}
			}
		}
	);

	// Price ranges
	if ( typeof filters['priceMin'] !== 'undefined' && filters['priceMin'] ) {
		listings = listings.filter((listing) => {
			// Handling min price
			if ( filters['priceMin'] === parseInt( priceRangeLower ) ) {
				return true;
			}

			if ( listing['rawDebtFreePrice'] !== null ) {
				// Normal filtering
				if (listing['rawDebtFreePrice'] >= filters['priceMin']) {
					return true;
				}

				return false;
			}

			// return listings with no set price
			return true;
		});
	}

	if ( typeof filters['priceMax'] !== 'undefined' && filters['priceMax'] ) {
		listings = listings.filter((listing) => {
			// Handling max price
			if ( filters['priceMax'] === parseInt( priceRangeUpper ) ) {
				return true;
			}

			if ( listing['rawDebtFreePrice'] !== null ) {
				// Normal filtering
				if (listing['rawDebtFreePrice'] <= filters['priceMax']) {
					return true;
				}

				return false;
			}

			// return listings with no set price
			return true;
		});
	}

	// Rent ranges
	if ( typeof filters['rentMin'] !== 'undefined' && filters['rentMin'] ) {
		listings = listings.filter((listing) => {
			// Handling min rent
			if ( filters['rentMin'] === parseInt( rentRangeLower ) ) {
				return true;
			}

			if ( listing['rawRent'] !== null ) {
				// Normal filtering
				if (listing['rawRent'] >= filters['rentMin']) {
					return true;
				}

				return false;
			}

			return true;
		});
	}

	if ( typeof filters['rentMax'] !== 'undefined' && filters['rentMax'] ) {
		listings = listings.filter((listing) => {
			// Handling max rent
			if ( filters['rentMax'] === parseInt( rentRangeUpper ) && listing['rawRent'] >= parseInt( rentRangeUpper ) ) {
				return true;
			}

			if ( listing['rawRent'] !== null ) {
				// Normal filtering
				if (listing['rawRent'] <= filters['rentMax']) {
					return true;
				}

				return false;
			}

			return true;
		});
	}

	// Area ranges
	if ( typeof filters['areaMin'] !== 'undefined' && filters['areaMin'] ) {
		listings = listings.filter((listing) => {

			let usedArea = null;
			if( typeof listing['area'] !== 'undefined' && listing['area'] ){
				usedArea  = listing['area'];
			}

			if( !usedArea && typeof listing['overallArea'] !== 'undefined' && listing['overallArea'] ){
				usedArea  = listing['overallArea'];
			}

			if( !usedArea ){
				return true;
			}

			const useableAreaNum = usedArea.replace(",", ".");
			let useableArea = null;
			if( useableAreaNum ){
				useableArea = parseFloat(useableAreaNum);
			}

			// Handling min area
			if ( filters['areaMin'] === parseFloat(areaRangeLower) ) {
				return true;
			}

			if ( useableArea !== null ) {
				// Normal filtering
				if (useableArea >= parseFloat( filters['areaMin'] )) {
					return true;
				}
			}

			return false;
		});
	}

	if ( typeof filters['areaMax'] !== 'undefined' && filters['areaMax'] ) {
		listings = listings.filter((listing) => {

			let usedArea = null;
			if( typeof listing['area'] !== 'undefined' && listing['area'] ){
				usedArea  = listing['area'];
			}

			if( !usedArea && typeof listing['overallArea'] !== 'undefined' && listing['overallArea'] ){
				usedArea  = listing['overallArea'];
			}

			if( !usedArea ){
				return true;
			}

			const useableAreaNum = usedArea.replace(",", ".");
			let useableArea = null;
			if( useableAreaNum ){
				useableArea = parseFloat(useableAreaNum);
			}

			// Handling max area
			if ( filters['areaMax'] === 500 && useableArea >= parseFloat(areaRangeUpper) ) {
				return true;
			}

			if ( useableArea !== null ) {
				// Normal filtering
				if (useableArea <= parseFloat( filters['areaMax'] )) {
					return true;
				}
			}

			return false;
		});
	}

	if ( ( typeof filters['areaMin'] !== 'undefined' && filters['areaMin']) || (typeof filters['areaMax'] !== 'undefined' && filters['areaMax'] ) ) {
		listings = listings.filter((listing) => {
			let usedArea = null;
			if( typeof listing['area'] !== 'undefined' && listing['area'] ){
				usedArea  = listing['area'];
			}
	
			if( !usedArea && typeof listing['overallArea'] !== 'undefined' && listing['overallArea'] ){
				usedArea  = listing['overallArea'];
			}

			if( !usedArea ){
				return false;
			}

			return true;
		});
	}

	// Business listing type
	if (typeof filters['businessListingType'] !== 'undefined' && filters['businessListingType']) {
		if (filters['businessListingType'] !== '' && typeof filters['businessListingType'] === 'object') {
			if( !filters['businessListingType'].every(value => value === false) ){
				const businessPremisesOptions = [
					'office_space',
					'business_space',
					'production_space',
					'storage_space',
					'restaurant_space',
					'exhibition_space',
					'hobby_space',
					'hub_space',
					'other_business_space',
				];

				listings = listings.filter((listing) => {
					if (typeof listing['rawDataForFiltering']['listingType'] !== 'undefined') {
						return filters['businessListingType'].some((bool, index) => {
							if( bool ){
								if( businessPremisesOptions[index] === listing['rawDataForFiltering']['listingType'].toLowerCase() ){
									return true;
								}
							}

							return false;
						});
					}

					return false;
				});
			}
		}
	}

	// Search options
	if (typeof filters['searchOption'] !== 'undefined' && filters['searchOption']) {
		if ( filters['searchOption'] !== '') {

			const comparables = [
				'address',
				'city',
				'municipality',
				'districtFree',
			];

			listings = listings.filter((listing) => {
				return comparables.some((comparable) => {
					if( typeof listing[comparable] !== 'undefined' && listing[comparable] ){
						if( (listing[comparable]).toLowerCase().includes( filters['searchOption'].toLowerCase() ) ){
							return true;
						}
					}
				});
			});
		}
	}

	// Search
	if (typeof filters['search'] !== 'undefined' && filters['search']) {
		if (filters['search'] !== '') {

			const comparableKeys = [
				'id',
				'identifier',
				'address',
				'city',
				'municipality',
				'districtFree',
				'postalCode'
			];

			listings = listings.filter((listing) => {

				return comparableKeys.some((comparableKey) => {

					// split search term to array
					let searchTerms = filters['search'].toLowerCase().split(' ');
					let returnValue = false;

					searchTerms.forEach(( searchTerm ) => {

						if( typeof listing[comparableKey] !== 'undefined' && listing[comparableKey] && !returnValue ){
							if( (listing[comparableKey].toString()).toLowerCase().includes( searchTerm ) ){
								returnValue = true;
							}
						}

					});

					if( returnValue ){
						return true;
					}

				});
			});
		}
	}

	return listings;
};

export default ListingsFilterer;
