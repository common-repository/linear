/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';

/**
 * Internal dependencies
 */
import config from '../config.json';

export default function save({ attributes }) {
	const { 
		listingsType, 
		listingsPerPage,
		showLoadMore,
		classes,
		showFilters,
		showRangeSliders,
		showOrderBy,
		showCommissionType,
		showSearch,
		showProductGroup,
		showRoomCount,
		showListingType,
		showSpecifications,
		showBusinessListingType,
		showPriceRange,
		showRentRange,
		showAreaRange,
		filterCommissionType,
		filterSearch,
		filterProductGroup,
		filterListingType,
		priceRangeLower,
		priceRangeUpper,
		rentRangeLower,
		rentRangeUpper,
		areaRangeLower,
		areaRangeUpper,
	} = attributes;
	const { blockClassName } = config;
	const className = blockClassName;

	return (
		<div
			{...useBlockProps.save({ className })}
			data-listings-type={listingsType}
			data-listings-per-page={listingsPerPage}
			data-show-load-more={showLoadMore}
			data-classes={classes}
			data-show-filters={showFilters}
			data-show-range-sliders={showRangeSliders}
			data-show-order-by={showOrderBy}

			data-show-commission-type={showCommissionType}
			data-show-search={showSearch}
			data-show-product-group={showProductGroup}
			data-show-room-count={showRoomCount}
			data-show-listing-type={showListingType}
			data-show-specifications={showSpecifications}
			data-show-business-listing-type={showBusinessListingType}
			data-show-price-range={showPriceRange}
			data-show-rent-range={showRentRange}
			data-show-area-range={showAreaRange}

			data-filter-commission-type={filterCommissionType}
			data-filter-search={filterSearch}
			data-filter-product-group={filterProductGroup}
			data-filter-listing-type={filterListingType}

			data-price-range-lower={priceRangeLower}
			data-price-range-upper={priceRangeUpper}
			data-rent-range-lower={rentRangeLower}
			data-rent-range-upper={rentRangeUpper}
			data-area-range-lower={areaRangeLower}
			data-area-range-upper={areaRangeUpper}
		/>
	);
}
