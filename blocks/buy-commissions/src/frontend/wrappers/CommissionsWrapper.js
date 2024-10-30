/**
 * External dependencies
 */
import React from 'react';
import { AnimatePresence } from 'framer-motion';

/**
 * Internal dependencies
 */

const CommissionsWrapper = ({ children, windowConfig, commissionsCount }) => {
	return (
		<div className={`${windowConfig.blockClassName}__container ` + ( commissionsCount === 0 ? `${windowConfig.blockClassName}__container--empty ` : '')}>
			<AnimatePresence>{children}</AnimatePresence>
		</div>
	);
};

export default CommissionsWrapper;
