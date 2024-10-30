import React, { useContext } from 'react';
import { AppContext } from './../utils/Context';
import { motion } from 'framer-motion';

const Listing = ({ listing, listingsPerPage, index, admin }) => {
	// Context API
	const { colors, texts } = useContext( AppContext );

	// Header
	// let listingLink = listing.permalink && !admin ? listing.permalink : '#';
	let listingLink = listing.permalink;
	let price = listing.formatted_debtFreePrice;

	if( listing.rawDataForFiltering.commissionType === 'RENT' ){
		price = listing.formatted_rent;

		if( listing.rawRent === 0 ){
			price = null;
		}
	}

	let area = '';

	if( listing.rawDataForFiltering.listingType === 'PLOT' && listing.lotArea ){
		area = listing.lotArea
	}

	if( !area && listing.rawDataForFiltering.listingType === 'BUSINESS_SPACE' && listing.businessPremiseArea ){
		area = listing.businessPremiseArea
	}

	if( !area || area === "0" || parseInt( area ) === 0 ){
		area = listing.overallArea;
	}

	if( !area && listing.area){
		area = listing.area;
	}

	let bidding = typeof listing.bidding !== 'undefined' && listing.bidding.latestOfferPrice !== '' ? listing.bidding : false;

	// Thumbnail
	let thumbnailUrl =
		typeof listing.thumbnails !== 'undefined' ? listing.thumbnails[0] : '';

	// Content

	return (
		<div key={listing.id} className={'linear-listings-' + listing.identifier}>
			<motion.div
				animate={{ opacity: 1, y: 0 }}
				initial={{ opacity: 0, y: 20 }}
				exit={{ opacity: 0, y: 20 }}
				transition={{
					duration: 0.5,
					delay: (index % listingsPerPage) * 0.15,
					type: 'spring',
				}}
				className="linear-listings__single"
			>
				<a
					href={listingLink}
					className="linear-listings__single__link"
				>
					<div className="linear-listings__single__header">
						<p
							className="linear-listings__single__header__title"
							style={{ color: colors['primary_color'] }}
						>
							{price ? price : <><span>{texts.askPrice}</span></>}
						</p>

						<div className="linear-listings__single__column">
							<p className="linear-listings__single__header__spec" dangerouslySetInnerHTML={{__html: (area ? area : '&nbsp;')}}>
							</p>
							<p className="linear-listings__single__header__subtitle">
								{listing.card_spec ? listing.card_spec : <>&nbsp;</>}
							</p>
						</div>
					</div>

					<div
						className="linear-listings__single__image"
						style={{
							backgroundImage: `url("${thumbnailUrl}")`,
						}}
					>
						{ bidding ? <span className="linear-listings__single__bidding" style={{ backgroundColor: colors['primary_color'] }}>{texts.bidding}</span> : '' }
					</div>

					<div className="linear-listings__single__content">
						<div className="linear-listings__single__row">
							<h5 className="linear-listings__single__content__title">
								{listing.card_title ? listing.card_title : <>&nbsp;</>}
							</h5>
						</div>

						<div className="linear-listings__single__row">
							<p className="linear-listings__single__content__subtitle">
								{listing.card_subtitle ? listing.card_subtitle : <>&nbsp;</>}
							</p>
						</div>
					</div>
				</a>
			</motion.div>
		</div>
	);
};

export default Listing;
