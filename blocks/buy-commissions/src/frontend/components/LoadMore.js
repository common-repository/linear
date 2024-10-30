import React, { useContext } from 'react';
import { AppContext } from './../utils/Context';

const LoadMore = ({ title, trigger }) => {
	const { texts } = useContext(AppContext);

	return (
		<>
			<div className="linear-buy-commissions__loadmore wp-block-button">
				<button
					className="linear-buy-commissions__loadmore__button wp-block-button__link"
					onClick={trigger}
				>
					{texts.loadMore}
				</button>
			</div>
		</>
	);
};

export default LoadMore;
