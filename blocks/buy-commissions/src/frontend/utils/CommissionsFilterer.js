/**
 * Filtering commissions according to the frontend filters
 */
const CommissionsFilterer = ({ commissions, filters }) => {

	const windowConfig = window.blockLinearBuyCommissionsConfig;
	const texts = windowConfig.texts;
	const locale = windowConfig.locale;

	// Base filterer
	commissions = commissions.filter((commission) => {
		let temporaryLocale = locale;

		if( typeof commission.data.location[temporaryLocale] === 'undefined'){
			temporaryLocale = 'fi';
		}

		// Base location
		if( typeof commission.data['location'][temporaryLocale] === 'undefined' ){
			return false;
		}
		
		// Base price
		if( typeof commission.data['debtFreePriceLowerBound'][temporaryLocale] === 'undefined' || typeof commission.data['debtFreePriceUpperBound'][temporaryLocale] === 'undefined' ){
			return false;
		}

		if( commission.data['debtFreePriceLowerBound'][temporaryLocale]['value'] === "" && commission.data['debtFreePriceUpperBound'][temporaryLocale]['value'] === "" ){
			return false;
		}

		return true;
	});

	// Product group
	if (typeof filters['productGroup'] !== 'undefined') {

		if (filters['productGroup'] !== '') {
			let newlyConstructedBypass = false;

			// hotfix
			if( filters['productGroup'] === 'apartments' ) {
				newlyConstructedBypass === true;
			}

			commissions = commissions.filter((commission) => {

				if( typeof commission['rawDataForFiltering'] ){
					return false;
				}

				if( typeof commission['rawDataForFiltering']['wantedListingType'] === 'undefined' ){
					return false;
				}

				const filterProductGroup = filters['productGroup'].replace(/_/g, '').toLowerCase();

				return commission['rawDataForFiltering']['wantedListingType'].some(( wantedListingType ) => {

					if ( typeof wantedListingType !== 'undefined' ) {
						const formattedWantedListingType = wantedListingType.replace(/_/g, '').toLowerCase();

						if ( formattedWantedListingType === filterProductGroup ) {
							return true;
						}
	
						// hotfix
						if( newlyConstructedBypass ){
							if ( formattedWantedListingType === 'newlyconstructed' ) {
								return true;
							}
						}
					}

					return false;

				});

			});
		}
	}

	// RoomCount
	if (typeof filters.roomCount !== 'undefined') {
		if( !filters.roomCount.every(count => !count) ){

			commissions = commissions.filter((commission) => {

				if( typeof commission['rawDataForFiltering'] ){
					return false;
				}

				let matchingRoomCount = false;
				const rawRoomCounts = commission['rawDataForFiltering']['roomCount'];

				if (rawRoomCounts !== null && typeof rawRoomCounts !== 'undefined') {

					filters.roomCount.forEach((count, index) => {

						if( count === true ){

							const currentCount = parseInt(index) + 1;

							rawRoomCounts.forEach(( rawRoomCount ) => {
								// If filtering is set to max aka. 6
								if (currentCount !== 6) {
									if (currentCount === parseInt( rawRoomCount )) {
										matchingRoomCount = true;
									}
								} else {
									// Check more equal as 6
									if (currentCount <= parseInt( rawRoomCount )) {
										matchingRoomCount = true;
									}
								}
							});

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

	// Specifications
	['sauna', 'balcony', 'elevator'].forEach(
		(specification) => {
			if ( typeof filters[specification] !== 'undefined' ) {
				if ( Array.isArray( filters[specification] ) ) {
					if( filters[specification].length === 1 && filters[specification][0] === true ){
						commissions = commissions.filter((commission) => {

							if( typeof commission['rawDataForFiltering'] ){
								return false;
							}

							let returnValue = false;

							if ( 
								typeof commission["rawDataForFiltering"]["housingCoopIncludes"] !== 'undefined' &&
								commission["rawDataForFiltering"]["housingCoopIncludes"] !== null &&
								commission["rawDataForFiltering"]["housingCoopIncludes"]
							) {
								commission["rawDataForFiltering"]["housingCoopIncludes"].forEach(( spec ) => {
									if( spec.toLowerCase() === specification ){
										returnValue = true;
									}
								});
							}

							if( returnValue ){
								return true;
							}

							return false;
						});
					}
				}
			}
		}
	);

	// Price ranges
	if ( typeof filters['priceMin'] !== 'undefined' && typeof filters['priceMax'] !== 'undefined' ) {
		commissions = commissions.filter((commission) => {

			if( typeof commission['rawDataForFiltering'] === 'undefined'){
				return false;
			}

			if(
				typeof commission['rawDataForFiltering']['minPrice'] === 'undefined' ||
				typeof commission['rawDataForFiltering']['maxPrice'] === 'undefined'
			){
				return false;
			}

			// Handling min & max price
			if ( 
				filters['priceMin'] === 0 &&
				filters['priceMax'] === 750000
			) {
				return true;
			}

			if ( 
				commission['rawDataForFiltering']['minPrice'] !== null &&
				commission['rawDataForFiltering']['maxPrice'] !== null
			) {

				const priceLowerBound = parseInt( commission['rawDataForFiltering']['minPrice'] );
				const priceUpperBound = parseInt( commission['rawDataForFiltering']['maxPrice'] );

				// Either end over the scale
				if (
					(priceUpperBound <= filters['priceMin'] && filters['priceMin'] !== 0 ) ||
					(priceLowerBound >= filters['priceMax'] && filters['priceMax'] !== 750000 )
				) {
					return false;
				}

				return true;
			}

			return false;
		});
	}

	// Search
	if (typeof filters['search'] !== 'undefined') {
		if (filters['search'] !== '') {

			const comparableKeys = [
				'wantedListingType',
				'wantedType',
				'location',
				'condition',
				'housingCoopIncludes'
			];

			commissions = commissions.filter((commission) => {

				return comparableKeys.some((comparableKey) => {

					// split search term to array
					let searchTerms = filters['search'].toLowerCase().split(' ');
					let returnValue = false;

					searchTerms.forEach(( searchTerm ) => {

						if( typeof commission.data[comparableKey] !== 'undefined' && commission.data[comparableKey] ){

							// In case we don't have locale language data
							let temporaryLocale = locale;

							if( typeof commission.data.location[temporaryLocale] === 'undefined'){
								temporaryLocale = 'fi';
							}

							if( typeof commission.data[comparableKey][temporaryLocale] !== 'undefined' ){
								if( typeof commission.data[comparableKey][temporaryLocale]['value'] !== 'undefined' && commission.data[comparableKey][temporaryLocale]['value'] !== null ){
									if( (commission.data[comparableKey][temporaryLocale]['value']).toLowerCase().includes( searchTerm ) ){
										returnValue = true;
									}
								}
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

	return commissions;
};

export default CommissionsFilterer;
