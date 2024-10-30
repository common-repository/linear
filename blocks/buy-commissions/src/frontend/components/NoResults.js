import React, { useContext } from 'react';
import { AppContext } from './../utils/Context';
import { AnimatePresence, motion } from 'framer-motion';
import { URLParamsPopulator } from './../utils';

const NoResults = () => {
	const { texts, setFrontEndFilters } = useContext(AppContext);

	const handleChange = () => {
		setFrontEndFilters({});
		URLParamsPopulator({});
	}

	return (
		<AnimatePresence>
			<motion.div
				animate={{ opacity: 1, y: 0 }}
				initial={{ opacity: 0, y: 20 }}
				exit={{ opacity: 0, y: 20 }}
				transition={{
					duration: 0.5,
					delay: 0,
					type: 'spring',
				}}
				className="linear-buy-commissions__results__empty"
			>
				<div className="linear-buy-commissions__results__empty__content">
					<h3>{texts.noResultsTitle}</h3>
					<p>{texts.noResultsBody}</p>
				</div>
				<div className="wp-block-button">
					<button
						className="wp-block-button__link"
						onClick={() => handleChange()}
					>
						{texts.resetFilters}
					</button>
				</div>
			</motion.div>
		</AnimatePresence>
	);
};

export default NoResults;
