import React, { useContext } from 'react';
import { AppContext } from '../utils/Context';
import { motion } from 'framer-motion';

const Commission = ({ commission, commissionsPerPage, index, admin }) => {
	// Context API
	const { colors, texts, assets, locale } = useContext( AppContext );

	// Header
	let commissionLink = commission.permalink;

	// Validate
	if( 
		typeof commission.data.debtFreePriceLowerBound === 'undefined' ||
		typeof commission.data.location === 'undefined' ||
		typeof commission.data.livingAreaSpecify === 'undefined' ||
		typeof commission.data.wantedListingType === 'undefined'
	){
		return "";
	}

	// Thumbnail
	let thumbnailUrl = assets.bgDixu;
	if( typeof commission.thumbnails !== 'undefined' ){
		thumbnailUrl = commission.thumbnails;
	}

	// Price range
	let priceRange = '';
	if( typeof commission.rawDataForFiltering.minPrice !== 'undefined' ){
		// TODO handle other cash formats in the future
		const priceLowEnd = new Intl.NumberFormat('fi-FI', { style: 'currency', currency: 'EUR', minimumFractionDigits: 0 }).format( commission.rawDataForFiltering.minPrice );
		priceRange = priceLowEnd;
	}
	if( typeof commission.rawDataForFiltering.maxPrice !== 'undefined' ){
		const priceHighEnd = new Intl.NumberFormat('fi-FI', { style: 'currency', currency: 'EUR', minimumFractionDigits: 0 }).format( commission.rawDataForFiltering.maxPrice );
		priceRange = priceRange + ' - ' + priceHighEnd;
	}

	const localeDataReader = ( data, target = 'value' ) => {
		const { locale } = useContext( AppContext );
		let usableLocale = '';
		const fallBackLocale = 'fi';

		if( typeof data[locale] !== 'undefined' ){
			usableLocale = locale;
		}

		if( !usableLocale ){
			usableLocale = fallBackLocale;
		}

		if( typeof data[usableLocale] !== 'undefined' ){
			if( typeof data[usableLocale][target] !== 'undefined' ){
				return data[usableLocale][target];
			}
		}

		return '';
	}

	const potentiallyTruncate = (( string, length ) => {
		if( !string ){
			return string;
		}
		return (string.length > length) ? string.slice(0, length-1) + '…' : string;
	});

	// Content
	return (
		<div key={commission.id}>
			<motion.div
				animate={{ opacity: 1, y: 0 }}
				initial={{ opacity: 0, y: 20 }}
				exit={{ opacity: 0, y: 20 }}
				transition={{
					duration: 0.5,
					delay: (index % commissionsPerPage) * 0.15,
					type: 'spring',
				}}
				className="linear-buy-commissions__single"
			>
				<a
					href={commissionLink}
					className="linear-buy-commissions__single__link"
				>

					{/*
					<div className="linear-buy-commissions__single__image">
						<div
							className="linear-buy-commissions__single__image__src"
							style={{
								backgroundImage: `url("${thumbnailUrl}")`,
							}}
						></div>
					</div>
					*/}

					<div className="linear-buy-commissions__single__content">
						<div className="linear-buy-commissions__single__column" data-columns="3">

							{ localeDataReader( commission.data.location, 'value' ) && (
								<div className="linear-buy-commissions__single__spec">
									<p className="linear-buy-commissions__single__content__subtitle">{localeDataReader( commission.data.location, 'key' )}</p>
									<p className="linear-buy-commissions__single__content__data">{potentiallyTruncate( localeDataReader( commission.data.location, 'value' ), 20 )}</p>
								</div>
							)}

							{ localeDataReader( commission.data.livingAreaSpecify, 'value' ) && (
								<div className="linear-buy-commissions__single__spec">
									<p className="linear-buy-commissions__single__content__subtitle">{localeDataReader( commission.data.livingAreaSpecify, 'key' )}</p>
									<p className="linear-buy-commissions__single__content__data">{localeDataReader( commission.data.livingAreaSpecify, 'value' )}</p>
								</div>
							)}

						</div>

						<div className="linear-buy-commissions__single__column" data-columns="3">

							{ localeDataReader( commission.data.wantedListingType, 'value' ) && (
								<div className="linear-buy-commissions__single__spec">
									<p className="linear-buy-commissions__single__content__subtitle">{localeDataReader( commission.data.wantedListingType, 'key' )}</p>
									<p className="linear-buy-commissions__single__content__data">{potentiallyTruncate( localeDataReader( commission.data.wantedListingType, 'value' ), 20 )}</p>
								</div>
							)}

							{ localeDataReader( commission.data.roomCount, 'value' ) && (
								<div className="linear-buy-commissions__single__spec">
									<p className="linear-buy-commissions__single__content__subtitle">{localeDataReader( commission.data.roomCount, 'key' )}</p>
									<p className="linear-buy-commissions__single__content__data">{localeDataReader( commission.data.roomCount, 'value' )}</p>
								</div>
							)}

						</div>

						<div className="linear-buy-commissions__single__column" data-columns="3">

							{ priceRange && (
								<div className="linear-buy-commissions__single__spec">
									<p className="linear-buy-commissions__single__content__subtitle">{texts.priceRange}</p>
									<p className="linear-buy-commissions__single__content__data">{priceRange}</p>
								</div>
							)}

							{ commission.id && (
								<div className="linear-buy-commissions__single__spec">
									<p className="linear-buy-commissions__single__content__subtitle">{texts.id}</p>
									<p className="linear-buy-commissions__single__content__data">{potentiallyTruncate( commission.id, 8 )}</p>
								</div>
							)}

						</div>
					</div>
				</a>
			</motion.div>
		</div>
	);
};

export default Commission;
