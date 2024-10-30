import React, { useContext } from 'react';
import { AppContext } from '../utils/Context';
import { AnimatePresence, motion } from 'framer-motion';

const NoCommissions = () => {
	const { texts } = useContext(AppContext);

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
				<h3>{texts.noCommissionsTitle}</h3>
				<p>{texts.noCommissionsBody}</p>
			</motion.div>
		</AnimatePresence>
	);
};

export default NoCommissions;
