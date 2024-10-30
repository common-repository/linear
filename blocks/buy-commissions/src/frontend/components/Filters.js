import React, { useState, useContext, useEffect, useRef } from 'react';
import { AppContext } from './../utils/Context';
import { URLParamsPopulator } from './../utils';
import Slider from 'rc-slider';
import { AnimatePresence, motion } from 'framer-motion';

const RadioGroup = ({ optionName, optionSlug, options, filtersReset }) => {
	const { frontEndFilters, setFrontEndFilters, colors } = useContext(AppContext);

	if (!options) {
		return '';
	}

	const handleChange = (e) => {
		const { value } = e.target;

		let frontendFilterValue = frontEndFilters[optionSlug];
		if( typeof frontendFilterValue === 'undefined' ){
			frontendFilterValue = '';
		} else {
			frontendFilterValue = frontEndFilters[optionSlug].toLowerCase();
		}

		let newRadioValue = ''
		if(
			// typeof frontEndFilters[optionSlug] === 'undefined' ||
			frontendFilterValue === value.toLowerCase() ||
			!value
		){
			newRadioValue = '';
		} else {
			newRadioValue = value.toLowerCase();
		}

		if( filtersReset && newRadioValue !== '' ){
			setFrontEndFilters({
				[optionSlug]: newRadioValue
			});

			URLParamsPopulator({
				[optionSlug]: newRadioValue
			});
		} else {
			setFrontEndFilters({
				...frontEndFilters,
				[optionSlug]: newRadioValue
			});

			URLParamsPopulator({
				...frontEndFilters,
				[optionSlug]: newRadioValue
			});
		}
	};

	const groupClassName = "linear-buy-commissions__filters__group linear-buy-commissions__filters__group__" + optionSlug;

	return (
		<>
			<motion.div
				animate={{ opacity: 1, y: 0 }}
				initial={{ opacity: 0, y: 20 }}
				exit={{ opacity: 0, y: 20 }}
				transition={{
					duration: 0.5,
					type: 'spring',
				}}
				className={groupClassName}
			>
				{options.map((option, index) => {
					const checked = typeof frontEndFilters[optionSlug] !== 'undefined' ? ( frontEndFilters[optionSlug].toLowerCase() === option.value.toLowerCase() ? true : false ) : false;
					const filterClass = "linear-buy-commissions__filters__checkbox " + "linear-buy-commissions__filters__" + optionSlug + "__" + option.value.toLowerCase();

					return (
						<div
							key={optionName + '_' + index}
							className={filterClass}
						>
							<input
								type="checkbox"
								id={optionSlug + '_' + index}
								name={optionName}
								value={option.value}
								checked={checked}
								onChange={(e) => handleChange(e, index)}
							/>
							<label htmlFor={optionSlug + '_' + index}>
								{option.label}
							</label>
						</div>
					);
				})}
			</motion.div>
		</>
	);
};

const SelectGroup = ({ optionName, optionSlug, options }) => {
	const { frontEndFilters, setFrontEndFilters, colors } = useContext(AppContext);

	if (!options) {
		return '';
	}

	const handleChange = (e, index, options) => {
		const { checked } = e.target;

		// initial setup if necessary
		let currentStateCheckboxes =
			typeof frontEndFilters[optionSlug] !== 'undefined'
				? frontEndFilters[optionSlug]
				: new Array(options.length).fill(false);

		// flip value
		currentStateCheckboxes[index] = checked;

		setFrontEndFilters({
			...frontEndFilters,
			[optionSlug]: currentStateCheckboxes,
		});

		URLParamsPopulator({
			...frontEndFilters,
			[optionSlug]: currentStateCheckboxes,
		});
	};

	const groupClassName = "linear-buy-commissions__filters__group linear-buy-commissions__filters__group__" + optionSlug;

	return (
		<>
			<motion.div
				animate={{ opacity: 1, y: 0 }}
				initial={{ opacity: 0, y: 20 }}
				exit={{ opacity: 0, y: 20 }}
				transition={{
					duration: 0.5,
					type: 'spring',
				}}
				className={groupClassName}
				data-filters-count={options.length}
			>
				{options.map((option, index) => {
					const checked = typeof frontEndFilters[optionSlug] !== 'undefined' ? frontEndFilters[optionSlug][index] : false;
					const filterClass = "linear-buy-commissions__filters__checkbox " + "linear-buy-commissions__filters__" + optionSlug + "__" + option.value;

					return (
						<div
							key={optionName + '_' + index}
							className={filterClass}
						>
							<input
								type="checkbox"
								id={optionSlug + '_' + index}
								name={optionName}
								value={option.value}
								onChange={(e) =>
									handleChange(e, index, options)
								}
								checked={checked}
							/>
							<label htmlFor={optionSlug + '_' + index}>
								{option.label}
							</label>
						</div>
					);
				})}
			</motion.div>
		</>
	);
};

/*
const ProductGroup = () => {
	const { texts } = useContext(AppContext);

	return (
		<>
			<RadioGroup
				optionName={texts.apartments}
				optionSlug="productGroup"
				options={[
					{ value: 'apartments', label: texts.apartments },
					{ value: 'plots', label: texts.plots, reset: true },
					{ value: 'farms', label: texts.farms, reset: true },
					{ value: 'garages', label: texts.garages, reset: true },
					{
						value: 'vacation_apartment',
						label: texts.vacationApartment,
					},
				]}
				filtersReset={true}
			/>
		</>
	);
};
*/

const RoomCount = () => {
	const { texts } = useContext(AppContext);

	return (
		<>
			<SelectGroup
				optionName={texts.roomCount}
				optionSlug="roomCount"
				options={[
					{ value: '1', label: '1 ' + texts.room },
					{ value: '2', label: '2 ' + texts.rooms },
					{ value: '3', label: '3 ' + texts.rooms },
					{ value: '4', label: '4 ' + texts.rooms },
					{ value: '5', label: '5 ' + texts.rooms },
					{ value: '6', label: '5+ ' + texts.rooms },
				]}
			/>
		</>
	);
};

const CommissionType = ({ frontEndFilters, setFrontEndFilters }) => {
	const { texts } = useContext(AppContext);

	return (
		<>
			<RadioGroup
				optionName={texts.commissionType}
				optionSlug="commissionType"
				options={[
					{ value: 'flat', label: texts.flat },
					{ value: 'rowhouse', label: texts.rowhouse },
					{ value: 'pairhouse', label: texts.pairhouse },
					{ value: 'detachedhouse', label: texts.detachedHouse },
				]}
				frontEndFilters={frontEndFilters}
				setFrontEndFilters={setFrontEndFilters}
			/>
		</>
	);
};

const Specifications = () => {
	const { frontEndFilters, setFrontEndFilters, texts } = useContext(AppContext);

	return (
		<>
			<SelectGroup
				optionName={texts.sauna}
				optionSlug="sauna"
				options={[{ value: 'sauna', label: texts.sauna }]}
				frontEndFilters={frontEndFilters}
				setFrontEndFilters={setFrontEndFilters}
			/>
			<SelectGroup
				optionName={texts.balcony}
				optionSlug="balcony"
				options={[{ value: 'balcony', label: texts.balcony }]}
				frontEndFilters={frontEndFilters}
				setFrontEndFilters={setFrontEndFilters}
			/>
			<SelectGroup
				optionName={texts.elevator}
				optionSlug="elevator"
				options={[{ value: 'elevator', label: texts.elevator }]}
				frontEndFilters={frontEndFilters}
				setFrontEndFilters={setFrontEndFilters}
			/>
		</>
	);
};

const HouseType = () => {
	const { texts } = useContext( AppContext );

	return (
		<>
			<RadioGroup
				optionName={texts.apartments}
				optionSlug="productGroup"
				options={[
					{ value: 'highRise', label: texts.highRise, reset: true },
					{ value: 'terracedHouse', label: texts.terracedHouse, reset: true },
					{ value: 'semiDetachedHouse', label: texts.semiDetachedHouse, reset: true },
					{ value: 'detachedHouse', label: texts.detachedHouse, reset: true }
				]}
				filtersReset={true}
			/>
		</>
	);
};

const PriceRange = () => {
	const priceMin = 15000;
	const priceMinStyled = priceMin.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
	const priceMax = 750000;
	const priceMaxStyled = priceMax.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
	const { frontEndFilters, setFrontEndFilters, colors, texts } = useContext(AppContext);
	const defaultValueLow = typeof frontEndFilters['priceMin'] !== 'undefined' ? frontEndFilters['priceMin'] : priceMinStyled;
	const defaultValueHigh = typeof frontEndFilters['priceMax'] !== 'undefined' ? frontEndFilters['priceMax'] : priceMaxStyled;
	const [priceRange, setPriceRange] = useState([defaultValueLow, defaultValueHigh]);
	const [onLoad, setOnLoad] = useState(true);

	const handleInputChange = (e, index) => {
		let { value } = e.target;

		const newValue = parseInt(value.replace(/ /g, ''));

		let newState = [...priceRange];
		newState[index] = newValue;
		
		setPriceRange(newState);
		setFrontEndFilters({
			...frontEndFilters,
			['priceMin']: parseInt(newState[0].toString().replace(" ","")),
			['priceMax']: parseInt(newState[1].toString().replace(" ","")),
		});

		URLParamsPopulator({
			...frontEndFilters,
			['priceMin']: parseInt(newState[0].toString().replace(" ","")),
			['priceMax']: parseInt(newState[1].toString().replace(" ","")),
		});

		setOnLoad( false );
	};

	const handleRangeChange = (value) => {
		setPriceRange( value )
	}

	useEffect(() => {

		if(
			onLoad && 
			parseInt(priceRange[0].toString().replace(/ /g, '')) <= priceMin && 
			parseInt(priceRange[1].toString().replace(/ /g, '')) === priceMax
		){
			// init, dont' set values to filters
		} else {
			setFrontEndFilters({
				...frontEndFilters,
				['priceMin']: parseInt(priceRange[0].toString().replace(/ /g, '')),
				['priceMax']: parseInt(priceRange[1].toString().replace(/ /g, '')),
			});
		}

		URLParamsPopulator({
			...frontEndFilters,
			['priceMin']: parseInt(priceRange[0].toString().replace(/ /g, '')),
			['priceMax']: parseInt(priceRange[1].toString().replace(/ /g, '')),
		});

		setOnLoad( false );

	}, [priceRange]);

	// ensure no nulls
	if( !priceRange[0] ){
		priceRange[0] = 0;
	}
	if( !priceRange[1] ){
		priceRange[1] = 0;
	}

	// Style numbers
	priceRange[0] = priceRange[0].toString().replace(/ /g, '').replace(/\B(?=(\d{3})+(?!\d))/g, " ");
	priceRange[1] = priceRange[1].toString().replace(/ /g, '').replace(/\B(?=(\d{3})+(?!\d))/g, " ");

	// Min-value indicator
	if (parseInt(priceRange[0].toString().replace(/ /g, '')) === parseInt( priceMin )) {
		priceRange[0] = 0;
	}

	return (
		<>
			<motion.div
				animate={{ opacity: 1, y: 0 }}
				initial={{ opacity: 0, y: 20 }}
				exit={{ opacity: 0, y: 20 }}
				transition={{
					duration: 0.5,
					type: 'spring',
				}}
				className="linear-buy-commissions__filters__group linear-buy-commissions__filters__group__priceRange linear-commissions__filters__group--wide"
			>
				<div className="linear-buy-commissions__filters__range">
					<span className="linear-buy-commissions__filters__range__input">
						<input
							id="linearPriceMin"
							type="text"
							value={priceRange[0]}
							onChange={(e) => handleInputChange(e, 0)}
						/>
						<label htmlFor="linearPriceMin">
							{texts.setFilterMinimumPrice}
						</label>
						<span className="linear-buy-commissions__filters__range__input__euro" dangerouslySetInnerHTML={{ __html: "€" }}></span>
					</span>
					<Slider
						range
						min={priceMin}
						max={priceMax}
						step={5000}
						onChange={(value) => handleRangeChange(value)}
						value={[
							parseInt( priceRange[0].toString().replace(" ","") ),
							parseInt( priceRange[1].toString().replace(" ","") ),
						]}
						className="linear-buy-commissions__filters__range__slider"
						trackStyle={{ backgroundColor: colors.primary_color }}
						railStyle={{ backgroundColor: '#d6d8da' }}
						pushable={true}
						handleStyle={{
							borderColor: colors.primary_color,
							backgroundColor: 'white',
						}}
					/>
					<span className="linear-buy-commissions__filters__range__input">
						<input
							id="linearPriceMax"
							type="text"
							value={priceRange[1]}
							onChange={(e) => handleInputChange(e, 1)}
						/>
						<label htmlFor="linearPriceMax">
							{texts.setFilterMaximumPrice}
						</label>
						<span className="linear-buy-commissions__filters__range__input__euro" dangerouslySetInnerHTML={{ __html: "€" }}></span>
					</span>
				</div>
			</motion.div>
		</>
	);
};

const Search = () => {
	const { frontEndFilters, setFrontEndFilters, texts } = useContext(AppContext);

	const handleChange = (e) => {
		const { value } = e.target;

		let newValue = frontEndFilters['search'] === value ? '' : value;

		setFrontEndFilters({
			...frontEndFilters,
			['search']: newValue
		});

		URLParamsPopulator({
			...frontEndFilters,
			['search']: newValue
		});
	};

	return (
		<>
			<motion.div
				animate={{ opacity: 1, y: 0 }}
				initial={{ opacity: 0, y: 20 }}
				exit={{ opacity: 0, y: 20 }}
				transition={{
					duration: 0.5,
					type: 'spring',
				}}
				className="linear-buy-commissions__filters__group linear-buy-commissions__filters__group__search"
			>
				<div className="linear-buy-commissions__filters__input">
					<input 
						type="text"
						name={texts.search}
						onChange={handleChange}
						value={frontEndFilters.search}
						placeholder={texts.searchElementPlaceholder}
					/>
				</div>
			</motion.div>
		</>
	);
}

const FiltersList = ({}) => {
	const { showRangeSliders, showSearch } = useContext(AppContext);

	// return with logic
	return (
		<div className="linear-buy-commissions__filters">
			{ showSearch ?
				<div className="linear-buy-commissions__filters__row">
					<Search />
				</div>
			: ''}
			<div className="linear-buy-commissions__filters__row">
				<RoomCount />
			</div>
			<div className="linear-buy-commissions__filters__row" data-group>
				<HouseType />
				<Specifications />
			</div>
			{showRangeSliders && 
				<div className="linear-buy-commissions__filters__row">
					<PriceRange />
				</div>
			}
		</div>
	);
};

// Wrapper around the filters to add a accordion-element
const Filters = () => {
	const { texts, showFilters } = useContext(AppContext);
	const [accordionOpen, setAccordionOpen] = useState(false);
	const [windowWidth, setWindowWidth] = useState(window.innerWidth);
	const breakpoint = 768;

	useEffect(() => {
		const onResize = () => {
			setWindowWidth(window.innerWidth);
		};

		window.addEventListener('resize', onResize);

		return () => {
			window.removeEventListener('resize', onResize);
		};
	}, []);

	useEffect(() => {
		if( windowWidth < breakpoint ){
			setAccordionOpen(false);
		} else {
			setAccordionOpen(true);
		}
	}, [windowWidth]);

	if( !showFilters ){
		return '';
	}

	return (
		<div className="linear-buy-commissions__accordion">

			{ windowWidth < breakpoint && 
				<div className="wp-block-button linear-commissions__accordion__toggle">
					<button
						className="wp-block-button__link"
						onClick={() => setAccordionOpen(!accordionOpen)}
						aria-expanded={accordionOpen}
					>
						{accordionOpen ? texts.hideFilters : texts.showFilters}
					</button>
				</div>
			}

			<div className="linear-buy-commissions__accordion__content" aria-hidden={!accordionOpen} data-open={accordionOpen}>
				<AnimatePresence>
					<FiltersList/>
				</AnimatePresence>
			</div>

		</div>
	);
};

export default Filters;
