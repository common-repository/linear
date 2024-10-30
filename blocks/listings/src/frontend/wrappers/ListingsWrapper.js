/**
 * External dependencies
 */
import React from 'react';
import { AnimatePresence } from 'framer-motion';

/**
 * Internal dependencies
 */

const ListingsWrapper = ({ children, windowConfig, listingsCount }) => {
	return (
		<div className={`${windowConfig.blockClassName}__container ` + ( listingsCount === 0 ? `${windowConfig.blockClassName}__container--empty ` : '')}>
			<AnimatePresence>{children}</AnimatePresence>
		</div>
	);
};

export default ListingsWrapper;
