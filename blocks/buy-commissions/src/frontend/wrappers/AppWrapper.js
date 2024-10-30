/**
 * External dependencies
 */
import React from 'react';

/**
 * Internal dependencies
 */

const AppWrapper = ({ children, windowConfig }) => {
	return <div className={`${windowConfig.blockClassName}`}>{children}</div>;
};

export default AppWrapper;
