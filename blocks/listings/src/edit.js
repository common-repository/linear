/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { Fragment } from '@wordpress/element';
import {
	PanelBody,
	RangeControl,
	TextControl,
	ToggleControl,
	SelectControl,
} from '@wordpress/components';

import App from './frontend/App.js';
import './editor.scss';

/**
 * Internal dependencies
 */
import metadata from './block.json';
import config from '../config.json';

export default function Edit({ attributes, setAttributes }) {

	const { textdomain } = metadata;
	const { blockClassName, frontend } = config;
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
	let globals;
	let texts;

	const className = blockClassName;

	// Enforce globals
	if (window !== undefined) {
		globals = window[frontend.globalConfig];
		texts = globals.texts;
	}

	return <h1>TEST</h1>;

	return (
		<Fragment>
			<InspectorControls>
				<PanelBody title={__('Block Settings', textdomain)}>

					{/* type */}
					<SelectControl
						label="What do you want to display?"
						value={attributes.listingsType}
						options={[
							{ label: 'Show all', value: 'all' },
							{ label: 'Listings', value: 'APARTMENTS' },
							{ label: 'Rentals', value: 'RENT_APARTMENT' },
							{ label: 'Workplaces', value: 'BUSINESS_PREMISES' },
						]}
						onChange={(value) =>
							setAttributes({ listingsType: value })
						}
					/>

					{/* per_page */}
					<RangeControl
						label={texts.ListingsPerPage}
						value={attributes.listingsPerPage}
						onChange={(value) =>
							setAttributes({ listingsPerPage: value })
						}
						min={1}
						max={32}
					/>

					{/* loadmore */}
					<ToggleControl
						label={texts.loadMoreButtonVisibility}
						help={
							showLoadMore
								? texts.showingLoadMoreButton
								: texts.hidingLoadMoreButton
						}
						checked={showLoadMore}
						onChange={(value) =>
							setAttributes({ showLoadMore: value })
						}
					/>

					{/* classes */}
					<TextControl
						label={texts.cssClasses}
						value={attributes.classes}
						onChange={(value) =>
							setAttributes({ classes: value })
						}
					/>

					{/* filters */}
					<ToggleControl
						label={texts.frontendFiltersVisibility}
						help={
							showFilters
								? texts.showingFrontendFilters
								: texts.hidingFrontendFilters
						}
						checked={showFilters}
						onChange={(value) =>
							setAttributes({ showFilters: value })
						}
					/>

					{/* ranger_sliders */}
					{ showFilters && (
						<ToggleControl
							label={texts.frontendRangeSlidersVisibility}
							help={
								showRangeSliders
									? texts.showingRangeFilters
									: texts.hidingRangeFilters
							}
							checked={showRangeSliders}
							onChange={(value) =>
								setAttributes({ showRangeSliders: value })
							}
						/>
					)}

					{/* order_by */}
					<ToggleControl
						label={texts.frontendOrderByVisibility}
						help={
							showOrderBy
								? texts.showingOrderBy
								: texts.hidingOrderBy
						}
						checked={showOrderBy}
						onChange={(value) =>
							setAttributes({ showOrderBy: value })
						}
					/>

					{/* commission_type */}
					<ToggleControl
						label={texts.showCommissionType}
						help={texts.showCommissionType}
						checked={showCommissionType}
						onChange={(value) =>
							setAttributes({ showCommissionType: value })
						}
					/>

					{/* search */}
					<ToggleControl
						label={texts.showSearchField}
						help={texts.showSearchField}
						checked={showSearch}
						onChange={(value) =>
							setAttributes({ showSearch: value })
						}
					/>

					{/* product_group */}
					<ToggleControl
						label={texts.showProductGroup}
						help={texts.showProductGroup}
						checked={showProductGroup}
						onChange={(value) =>
							setAttributes({ showProductGroup: value })
						}
					/>

					{/* room_count */}
					<ToggleControl
						label={texts.showRoomCount}
						help={texts.showRoomCount}
						checked={showRoomCount}
						onChange={(value) =>
							setAttributes({ showRoomCount: value })
						}
					/>

					{/* listing_type */}
					<ToggleControl
						label={texts.showListingType}
						help={texts.showListingType}
						checked={showListingType}
						onChange={(value) =>
							setAttributes({ showListingType: value })
						}
					/>

					{/* specifications */}
					<ToggleControl
						label={texts.showSpecifications}
						help={texts.showSpecifications}
						checked={showSpecifications}
						onChange={(value) =>
							setAttributes({ showSpecifications: value })
						}
					/>

					{/* business_listing_type */}
					<ToggleControl
						label={texts.showBusinessListingType}
						help={texts.showBusinessListingType}
						checked={showBusinessListingType}
						onChange={(value) =>
							setAttributes({ showBusinessListingType: value })
						}
					/>

					{/* price_range */}
					<ToggleControl
						label={texts.showPriceRange}
						help={texts.showPriceRange}
						checked={showPriceRange}
						onChange={(value) =>
							setAttributes({ showPriceRange: value })
						}
					/>

					{/* rent_range */}
					<ToggleControl
						label={texts.showRentRange}
						help={texts.showRentRange}
						checked={showRentRange}
						onChange={(value) =>
							setAttributes({ showRentRange: value })
						}
					/>

					{/* area_range */}
					<ToggleControl
						label={texts.showAreaRange}
						help={texts.showAreaRange}
						checked={showAreaRange}
						onChange={(value) =>
							setAttributes({ showAreaRange: value })
						}
					/>

					{/* filter_commission_type */}
					<SelectControl
						label={texts.filterCommissionType}
						value={attributes.filterCommissionType}
						options={[
							{ label: texts.showAll, value: 'all' },
							{ label: texts.sell, value: 'sell' },
							{ label: texts.rent, value: 'rent' },
						]}
						onChange={(value) =>
							setAttributes({ filterCommissionType: value })
						}
					/>

					{/* filter_search */}
					<TextControl
						label={texts.filterSearch}
						value={attributes.filterSearch}
						onChange={(value) =>
							setAttributes({ filterSearch: value })
						}
					/>

					{/* filter_product_group */}
					<SelectControl
						label={texts.filterProductGroup}
						value={attributes.filterProductGroup}
						options={[
							{ label: texts.none, value: '' },
							{ label: texts.apartments, value: 'apartments' },
							{ label: texts.plots, value: 'plots' },
							{ label: texts.farms, value: 'farms' },
							{ label: texts.garages, value: 'garages' },
						]}
						onChange={(value) =>
							setAttributes({ filterProductGroup: value })
						}
					/>

					{/* filter_listing_type */}
					<SelectControl
						label={texts.filterListingType}
						value={attributes.filterListingType}
						options={[
							{ label: texts.none, value: '' },
							{ label: texts.blockOfFlats, value: 'flat' },
							{ label: texts.rowhouse, value: 'rowhouse' },
							{ label: texts.pairhouse, value: 'pairhouse' },
							{ label: texts.detachedhouse, value: 'detachedhouse' },
						]}
						onChange={(value) =>
							setAttributes({ filterListingType: value })
						}
					/>

					{/* price_range_lower */}
					<RangeControl
						label={texts.priceRangeLower}
						onChange={(value) => 
							setAttributes({ priceRangeLower: value })
						}
						value={ attributes.priceRangeLower }
						min={0}
						max={10000000}
					/>

					{/* price_range_upper */}
					<RangeControl
						label={texts.priceRangeUpper}
						onChange={(value) => 
							setAttributes({ priceRangeUpper: value })
						}
						value={ attributes.priceRangeUpper }
						min={0}
						max={10000000}
					/>

					{/* rent_range_lower */}
					<RangeControl
						label={texts.rentRangeLower}
						onChange={(value) => 
							setAttributes({ rentRangeLower: value })
						}
						value={ attributes.rentRangeLower }
						min={0}
						max={50000}
					/>

					{/* rent_range_upper */}
					<RangeControl
						label={texts.rentRangeUpper}
						onChange={(value) => 
							setAttributes({ rentRangeUpper: value })
						}
						value={ attributes.rentRangeUpper }
						min={0}
						max={50000}
					/>

					{/* area_range_lower */}
					<RangeControl
						label={texts.areaRangeLower}
						onChange={(value) => 
							setAttributes({ areaRangeLower: value })
						}
						value={ attributes.areaRangeLower }
						min={0}
						max={5000}
					/>

					{/* area_range_upper */}
					<RangeControl
						label={texts.areaRangeUpper}
						onChange={(value) => 
							setAttributes({ areaRangeUpper: value })
						}
						value={ attributes.areaRangeUpper }
						min={0}
						max={5000}
					/>
				</PanelBody>
			</InspectorControls>

			<div {...useBlockProps({ className })}>
				<App
					listingsType={listingsType}
					listingsPerPage={listingsPerPage}
					showLoadMore={showLoadMore}
					classes={classes}
					showFilters={showFilters}
					showRangeSliders={showRangeSliders}
					showOrderBy={showOrderBy}

					showCommissionType={showCommissionType}
					showSearch={showSearch}
					showProductGroup={showProductGroup}
					showRoomCount={showRoomCount}
					showListingType={showListingType}
					showSpecifications={showSpecifications}
					showBusinessListingType={showBusinessListingType}
					showPriceRange={showPriceRange}
					showRentRange={showRentRange}
					showAreaRange={showAreaRange}

					filterCommissionType={filterCommissionType}
					filterSearch={filterSearch}
					filterProductGroup={filterProductGroup}
					filterListingType={filterListingType}

					priceRangeLower={priceRangeLower}
					priceRangeUpper={priceRangeUpper}
					rentRangeLower={rentRangeLower}
					rentRangeUpper={rentRangeUpper}
					areaRangeLower={areaRangeLower}
					areaRangeUpper={areaRangeUpper}
					admin={true}
				/>
			</div>
		</Fragment>
	);
}
