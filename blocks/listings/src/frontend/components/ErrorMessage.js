import React, { useContext } from 'react';
import { AppContext } from './../utils/Context';
import { AnimatePresence, motion } from 'framer-motion';

const ErrorMessage = () => {
	return "";
	const { texts } = useContext(AppContext);

	let errorText = texts.errorFailedLoadingListings;
	if( errorText.includes('%s') ){
		errorText = errorText.replace( '%s', '<a href="mailto:it@linear.fi">it@linear.fi</a>' )
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
			>
				<div className="linear-listings__results__error">
					<h3 dangerouslySetInnerHTML={{ __html: errorText }}></h3>
				</div>
			</motion.div>
		</AnimatePresence>
	);
};

export default ErrorMessage;
