/**
 * Filtering listings according to Gutenberg settings
 */
const BaseFilterer = ({ listings, listingsType }) => {
	// Non-filter option
	if (listingsType === 'all') {
		return listings;
	}

	if( !listings.length || !listingsType ){
		return [];
	}

	// additional types per productType
	let apartmentTypes = [
		'APARTMENTS',
		'PLOTS',
		'FARMS',
		'GARAGES',
		'NEWLY_CONSTRUCTED',
		'VACATION_APARTMENT'
	];

	const baseListings = listings.filter((listing) => {
		let returnable = false;

		if( 
			typeof listing.rawDataForFiltering.productGroup === 'undefined' ||
			typeof listing.rawDataForFiltering.commissionType === 'undefined'
		){
			return returnable;
		}

		if( listingsType === 'APARTMENTS' ){
			if( 
				apartmentTypes.includes(listing.rawDataForFiltering.productGroup) &&
				listing.rawDataForFiltering.commissionType !== 'RENT' ){
				returnable = true;
			}
		}
		
		if( !returnable && listingsType === 'RENT_APARTMENT' ){
			if(
				listing.rawDataForFiltering.productGroup !== 'BUSINESS_PREMISES' &&
			 	listing.rawDataForFiltering.commissionType === 'RENT'
			){
				returnable = true;
			}
		}
		
		if( !returnable && listingsType === 'BUSINESS_PREMISES' ){
			if( listing.rawDataForFiltering.productGroup === 'BUSINESS_PREMISES' ){
				returnable = true;
			}
		}

		return returnable;
	});

	return baseListings;
};

export default BaseFilterer;
