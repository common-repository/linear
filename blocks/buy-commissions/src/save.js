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
	const { showOrderBy, showFilters, showRangeSliders, commissionsPerPage, showLoadMore, showSearch, filterSearch } = attributes;
	const { blockClassName } = config;
	const className = blockClassName;

	return (
		<div
			{...useBlockProps.save({ className })}
			data-show-order-by={showOrderBy}
			data-show-filters={showFilters}
			data-show-range-sliders={showRangeSliders}
			data-commissions-per-page={commissionsPerPage}
			data-show-load-more={showLoadMore}
			data-show-search={showSearch}
			data-filter-search={filterSearch}
		/>
	);
}
