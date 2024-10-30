import React, { useContext } from 'react';
import { AppContext } from './../utils/Context';

const ListingsCount = ({count}) => {
    const { texts } = useContext(AppContext);

    let text = '';

    if( count && count > 1 ){
        text = count + ' ' + texts.listingCountSeveral;
    }

    if( count && count === 1 ){
        text = count + ' ' + texts.listingCountSingle;
    }

    if( !count || count === 0 ){
        text = '';
    }

	return (
		<>
            <p className="linear-listings__listing-count">
                {text}
            </p>
		</>
	);
};

export default ListingsCount;
