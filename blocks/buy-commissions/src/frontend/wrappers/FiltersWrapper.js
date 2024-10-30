/**
 * External dependencies
 */
import React from 'react';

/**
 * Internal dependencies
 */
import config from '../config';
const { blockClassName } = config || {};

const FiltersWrapper = ({ children }) => {
	return <div className={`${blockClassName}__filters`}>{children}</div>;
};

export default FiltersWrapper;
