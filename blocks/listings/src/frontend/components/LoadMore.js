import React, { useContext } from 'react';
import { AppContext } from './../utils/Context';

const LoadMore = ({ title, trigger }) => {
	const { texts } = useContext(AppContext);

	return (
		<>
			<div className="linear-listings__loadmore wp-block-button elementor-cta__button-wrapper">
				<button
					className="linear-listings__loadmore__button wp-block-button__link elementor-cta__button"
					onClick={trigger}
				>
					{texts.loadMore}
				</button>
			</div>
		</>
	);
};

export default LoadMore;
