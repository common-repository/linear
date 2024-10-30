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
	const { showOrderBy, showFilters, showRangeSliders, commissionsPerPage, showLoadMore, showSearch, filterSearch } = attributes;
	let globals;
	let texts;

	const className = blockClassName;

	// Enforce globals
	if (window !== undefined) {
		globals = window[frontend.globalConfig];
		texts = globals.texts;
	}

	return (
		<Fragment>
			<InspectorControls>
				<PanelBody title={__('Block Settings', textdomain)}>
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
					<ToggleControl
						label={texts.showSearchField}
						help={texts.showSearchField}
						checked={showSearch}
						onChange={(value) =>
							setAttributes({ showSearch: value })
						}
					/>
					<TextControl
						label={texts.defaultSearchPrompt}
						value={attributes.filterSearch}
						onChange={(value) =>
							setAttributes({ filterSearch: value })
						}
					/>
					<RangeControl
						label={texts.CommissionsPerPage}
						value={attributes.commissionsPerPage}
						onChange={(value) =>
							setAttributes({ commissionsPerPage: value })
						}
						min={1}
						max={32}
					/>
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
				</PanelBody>
			</InspectorControls>

			<div {...useBlockProps({ className })}>
				<App
					showOrderBy={showOrderBy}
					showFilters={showFilters}
					showRangeSliders={showRangeSliders}
					commissionsPerPage={commissionsPerPage}
					showLoadMore={showLoadMore}
					showSearch={showSearch}
					filterSearch={filterSearch}
					admin={true}
				/>
			</div>
		</Fragment>
	);
}
