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
	Commission,
	LoadMore,
	ErrorMessage,
	NoCommissions,
	NoResults,
	Filters,
	OrderBy,
} from './components';
import { CommissionsWrapper } from './wrappers';
import { CommissionsFilterer, URLParamsHandler, LocaleHandler } from './utils';
import { AppContext } from './utils/Context';

/**
 * Configuration
 */
import config from './config';
let { texts, actions, assets, locale, restBase, apiVersion, colors } = config;

const App = ({ showOrderBy = true, showFilters = true, showRangeSliders = true, commissionsPerPage = 8, showLoadMore = true, showSearch = true, filterSearch = '', admin }) => {
	const [commissions, setCommissions] = useState([]);
	const [isLoading, setIsLoading] = useState(true);
	const [errorStatus, setErrorStatus] = useState(false);
	const [paging, setPaging] = useState(1);
	const [frontEndFilters, setFrontEndFilters] = useState({});

	// Remap values
	const windowConfig = window.blockLinearBuyCommissionsConfig;

	texts = windowConfig.texts;
	actions = windowConfig.actions;
	assets = windowConfig.assets;
	locale = LocaleHandler(windowConfig.locale);
	restBase = windowConfig.restBase;
	apiVersion = windowConfig.apiVersion;
	colors = windowConfig.colors;

	// upon certain filters change
	useEffect(() => {

		// Get pre-determed values
		if( filterSearch ){
			setFrontEndFilters({ ...frontEndFilters, 'search': filterSearch });
		}

		// Populate URL params
		const onLoadParams = URLParamsHandler();
		if( Object.keys(onLoadParams).length ){
			setFrontEndFilters({ ...frontEndFilters, ...onLoadParams });
		}

		// Fetch data
		const action = actions.commissions.replace('%s', apiVersion.replace('.', '_'));

		let requestUrl = '';
		if( !restBase.includes('?') ){
			// Pretty permalinks
			requestUrl = restBase + action + '?lang=' + locale;
		} else {
			// Ugly permalinks
			requestUrl = restBase + action + '&lang=' + locale;
		}

		axios
			.get(requestUrl, {
				params: {},
			})
			.then(function (response) {

				if( typeof response.data.errors !== 'undefined' ){
					setErrorStatus(true);
					setIsLoading(false);
					setCommissions([]);
				} else {
					setCommissions(response.data);
					setIsLoading(false);
				}

			})
			.catch(function (thrown) {

				setErrorStatus(true);
				setIsLoading(false);
				setCommissions([]);
				
			});
	}, []);

	// Pagination and filters reset
	useEffect(() => {
		setPaging(1);
	}, [showFilters]);

	// Pagination loadmore
	const loadMore = () => {
		setPaging(paging + 1);
	};

	if( commissions === {} ){
		setErrorStatus(true);
	}

	// Filter by frontend filters
	let filteredCommissions = CommissionsFilterer({
		commissions: commissions,
		filters: frontEndFilters,
	});

	// order elements
	if( typeof frontEndFilters['orderBy'] !== 'undefined' && frontEndFilters['orderBy'] === 'reverse'){
		filteredCommissions = filteredCommissions.reverse();
	}

	const context = useMemo(() => {
		return {
			texts,
			colors,
			assets,
			locale,
			frontEndFilters,
			setFrontEndFilters,
			showFilters,
			showRangeSliders,
			showSearch
		};
	}, [frontEndFilters, showFilters, showRangeSliders, showSearch]);

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
						<div className="linear-buy-commissions__handlers">
							<Filters/>
							{ showOrderBy && (
								<OrderBy />
							)}
						</div>
						<CommissionsWrapper windowConfig={windowConfig} commissionsCount={filteredCommissions.length}>
							{filteredCommissions.length > 0 ? (
								<>
									{filteredCommissions
										.slice(0, paging * commissionsPerPage)
										.map((commission, index) => {
											return (
												<Commission
													commission={commission}
													key={commission.id + index}
													commissionsPerPage={
														commissionsPerPage
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
										<NoCommissions />
									:
										<NoResults />
									)}
								</>
							)}
						</CommissionsWrapper>
						{showLoadMore &&
						filteredCommissions.length > paging * commissionsPerPage ? (
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
