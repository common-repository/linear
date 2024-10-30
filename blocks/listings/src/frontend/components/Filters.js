import React, { useState, useContext, useEffect, useRef } from 'react';
import { AppContext } from './../utils/Context';
import { URLParamsPopulator } from './../utils';
import Slider from 'rc-slider';
import { AnimatePresence, motion } from 'framer-motion';
import { debounce } from 'lodash';

const RadioGroup = ({ optionName, optionSlug, options, filtersReset, globals }) => {
	const { frontEndFilters, setFrontEndFilters } = useContext(AppContext);

	if (!options) {
		return '';
	}

	const handleChange = (e, globals) => {
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
			}, globals);
		} else {
			setFrontEndFilters({
				...frontEndFilters,
				[optionSlug]: newRadioValue
			});

			URLParamsPopulator({
				...frontEndFilters,
				[optionSlug]: newRadioValue
			}, globals);
		}
	};

	const groupClassName = "linear-listings__filters__group linear-listings__filters__group__" + optionSlug;

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
					const filterClass = "linear-listings__filters__checkbox " + "linear-listings__filters__" + optionSlug + "__" + option.value.toLowerCase();

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
								onChange={(e) => handleChange(e, globals)}
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

const SelectGroup = ({ optionName, optionSlug, options, globals }) => {
	const { frontEndFilters, setFrontEndFilters } = useContext(AppContext);

	if (!options) {
		return '';
	}

	const handleChange = (e, index, options, globals) => {
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
		}, globals);
	};

	const groupClassName = "linear-listings__filters__group linear-listings__filters__group__" + optionSlug;

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
					const filterClass = "linear-listings__filters__checkbox " + "linear-listings__filters__" + optionSlug + "__" + option.value;

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
									handleChange(e, index, options, globals)
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

const ProductGroup = ({globals}) => {
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
				globals={globals}
			/>
		</>
	);
};

const RoomCount = ({globals}) => {
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
				globals={globals}
			/>
		</>
	);
};

const ListingType = ({ globals }) => {
	const { frontEndFilters, setFrontEndFilters, texts } = useContext(AppContext);

	return (
		<>
			<RadioGroup
				optionName={texts.listingType}
				optionSlug="listingType"
				options={[
					{ value: 'flat', label: texts.flat },
					{ value: 'rowhouse', label: texts.rowhouse },
					{ value: 'pairhouse', label: texts.pairhouse },
					{ value: 'detachedhouse', label: texts.detachedHouse },
				]}
				frontEndFilters={frontEndFilters}
				setFrontEndFilters={setFrontEndFilters}
				globals={globals}
			/>
		</>
	);
};

const CommissionType = ({ globals }) => {
	const { texts } = useContext(AppContext);

	return (
		<>
			<RadioGroup
				optionName={texts.commissionType}
				optionSlug="commissionType"
				options={[
					{ value: 'sell', label: texts.salesCommissions, reset: true },
					{ value: 'rent', label: texts.rentCommissions, reset: true }
				]}
				filtersReset={true}
				globals={globals}
			/>
		</>
	);
};

const Specifications = ({ globals }) => {
	const { texts } = useContext(AppContext);

	return (
		<>
			<SelectGroup
				optionName={texts.sauna}
				optionSlug="sauna"
				options={[{ value: 'sauna', label: texts.sauna }]}
				globals={globals}
			/>
			<SelectGroup
				optionName={texts.balcony}
				optionSlug="hasBalcony"
				options={[{ value: 'balcony', label: texts.balcony }]}
				globals={globals}
			/>
			<SelectGroup
				optionName={texts.elevator}
				optionSlug="housingCooperativeElevator"
				options={[{ value: 'elevator', label: texts.elevator }]}
				globals={globals}
			/>
		</>
	);
};

const PriceRange = ({globals}) => {
	const { frontEndFilters, setFrontEndFilters, colors, texts } = useContext(AppContext);
	const priceRangeLower = parseInt( globals.priceRangeLower );
	const priceRangeUpper = parseInt( globals.priceRangeUpper );

	const priceMin = priceRangeLower;
	const priceMinStyled = priceMin.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
	const priceMax = priceRangeUpper;
	const priceMaxStyled = priceMax.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
	const defaultValueLow = typeof frontEndFilters['priceMin'] !== 'undefined' && !isNaN(frontEndFilters['priceMin']) ? frontEndFilters['priceMin'] : priceMinStyled;
	const defaultValueHigh = typeof frontEndFilters['priceMax'] !== 'undefined' && !isNaN(frontEndFilters['priceMax']) ? frontEndFilters['priceMax'] : priceMaxStyled;
	const [priceRange, setPriceRange] = useState([defaultValueLow, defaultValueHigh]);
	const [onLoad, setOnLoad] = useState(true);

	const handleInputChange = (e, index) => {
		const { value } = e.target;

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
		}, globals);

		setOnLoad( false );
	};


	const handleRangeChange = (value) => {
		setPriceRange( value );
	}

	const debounced = debounce(() => {
		if (
			onLoad &&
			parseInt(priceRange[0].toString().replace(/ /g, '')) <= priceMin &&
			parseInt(priceRange[1].toString().replace(/ /g, '')) === priceMax
		) {
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
		}, globals);
	}, 200);

	useEffect(() => {
		debounced();
		setOnLoad( false );

		return () => {
			debounced.cancel();
		};

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
		priceRange[0] = priceRangeLower.toString().replace(/ /g, '').replace(/\B(?=(\d{3})+(?!\d))/g, " ");
	}

	const priceRange0AsNumber = parseInt( priceRange[0].replace(/ /g, ''));
	const priceRange1AsNumber = parseInt( priceRange[1].replace(/ /g, ''));

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
				className="linear-listings__filters__group linear-listings__filters__group__priceRange linear-listings__filters__group--wide"
			>
				<div className="linear-listings__filters__range">
					<span className="linear-listings__filters__range__input">
						<input
							id="linearPriceMin"
							type="text"
							value={priceRange0AsNumber === priceRangeLower ? '' : priceRange[0]}
							placeholder="Min"
							onChange={(e) => handleInputChange(e, 0)}
						/>
						<label htmlFor="linearPriceMin">
							{texts.setFilterMinimumPrice}
						</label>
						<span className="linear-listings__filters__range__input__euro" dangerouslySetInnerHTML={{ __html: "€" }}></span>
					</span>
					<Slider
						range
						min={priceMin}
						max={priceMax}
						step={500}
						onChange={(value) => handleRangeChange(value)}
						value={[
							parseInt( priceRange[0].toString().replace(" ","") ),
							parseInt( priceRange[1].toString().replace(" ","") ),
						]}
						className="linear-listings__filters__range__slider"
						trackStyle={{ backgroundColor: colors.primary_color }}
						railStyle={{ backgroundColor: '#d6d8da' }}
						pushable={true}
						handleStyle={{
							borderColor: colors.primary_color,
							backgroundColor: 'white',
						}}
					/>
					<span className="linear-listings__filters__range__input">
						<input
							id="linearPriceMax"
							type="text"
							value={priceRange1AsNumber === priceRangeUpper ? '' : priceRange[1]}
							placeholder="Max"
							onChange={(e) => handleInputChange(e, 1)}
						/>
						<label htmlFor="linearPriceMax">
							{texts.setFilterMaximumPrice}
						</label>
						<span className="linear-listings__filters__range__input__euro" dangerouslySetInnerHTML={{ __html: "€" }}></span>
					</span>
				</div>
			</motion.div>
		</>
	);
};

const RentRange = ({globals}) => {
	const { frontEndFilters, setFrontEndFilters, colors, texts } = useContext(AppContext);

	const rentRangeLower = parseInt( globals.rentRangeLower );
	const rentRangeUpper = parseInt( globals.rentRangeUpper );
	const rentMin = rentRangeLower;
	const rentMinStyled = rentMin.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
	const rentMax = rentRangeUpper;
	const rentMaxStyled = rentMax.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
	const defaultValueLow = typeof frontEndFilters['rentMin'] !== 'undefined' ? frontEndFilters['rentMin'] : rentMinStyled;
	const defaultValueHigh = typeof frontEndFilters['rentMax'] !== 'undefined' ? frontEndFilters['rentMax'] : rentMaxStyled;
	const [rentRange, setRentRange] = useState([defaultValueLow, defaultValueHigh]);
	const [onLoad, setOnLoad] = useState(true);

	const handleInputChange = (e, index) => {
		const { value } = e.target;

		const newValue = parseInt(value.replace(/ /g, ''));

		let newState = [...rentRange];
		newState[index] = newValue;

		setRentRange(newState);
		setFrontEndFilters({
			...frontEndFilters,
			['rentMin']: parseInt(newState[0].toString().replace(" ","")),
			['rentMax']: parseInt(newState[1].toString().replace(" ","")),
		});

		URLParamsPopulator({
			...frontEndFilters,
			['rentMin']: parseInt(newState[0].toString().replace(" ","")),
			['rentMax']: parseInt(newState[1].toString().replace(" ","")),
		}, globals);

		setOnLoad( false );
	};

	const handleRangeChange = (value) => {
		setRentRange( value );
	}

	const debounced = debounce(() => {
		if(
			onLoad && 
			parseInt(rentRange[0].toString().replace(/ /g, '')) <= rentMin && 
			parseInt(rentRange[1].toString().replace(/ /g, '')) === rentMax
		){
			// init, dont' set values to filters
		} else {
			setFrontEndFilters({
				...frontEndFilters,
				['rentMin']: parseInt(rentRange[0].toString().replace(" ","")),
				['rentMax']: parseInt(rentRange[1].toString().replace(" ","")),
			});
		}

		URLParamsPopulator({
			...frontEndFilters,
			['rentMin']: parseInt(rentRange[0].toString().replace(" ","")),
			['rentMax']: parseInt(rentRange[1].toString().replace(" ","")),
		}, globals);
	}, 200);

	useEffect(() => {
		debounced();
		setOnLoad( false );

		return () => {
			debounced.cancel();
		};
	}, [rentRange]);

	// ensure no nulls
	if( !rentRange[0] ){
		rentRange[0] = 0;
	}
	if( !rentRange[1] ){
		rentRange[1] = 0;
	}

	// Style numbers
	rentRange[0] = rentRange[0].toString().replace(/ /g, '').replace(/\B(?=(\d{3})+(?!\d))/g, " ");
	rentRange[1] = rentRange[1].toString().replace(/ /g, '').replace(/\B(?=(\d{3})+(?!\d))/g, " ");

	// Min-value indicator
	if (parseInt(rentRange[0].toString().replace(/ /g, '')) === parseInt( rentMin )) {
		rentRange[0] = rentRangeLower.toString().replace(/ /g, '').replace(/\B(?=(\d{3})+(?!\d))/g, " ");;
	}

	const rentRange0AsNumber = parseInt( rentRange[0].replace(/ /g, ''));
	const rentRange1AsNumber = parseInt( rentRange[1].replace(/ /g, ''));

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
				className="linear-listings__filters__group linear-listings__filters__group__rentRange linear-listings__filters__group--wide"
			>
				<div className="linear-listings__filters__range">
					<span className="linear-listings__filters__range__input">
						<input
							id="linearRentMin"
							type="text"
							value={rentRange0AsNumber === rentRangeLower ? '' : rentRange[0]}
							placeholder="Min"
							onChange={(e) => handleInputChange(e, 0)}
						/>
						<label htmlFor="linearRentMin">
							{texts.setFilterMinimumRent}
						</label>
						<span className="linear-listings__filters__range__input__euro" dangerouslySetInnerHTML={{ __html: "€" }}></span>
					</span>
					<Slider
						range
						min={rentMin}
						max={rentMax}
						step={25}
						onChange={(value) => handleRangeChange(value)}
						value={[
							parseInt( rentRange[0].toString().replace(" ","") ),
							parseInt( rentRange[1].toString().replace(" ","") ),
						]}
						className="linear-listings__filters__range__slider"
						trackStyle={{ backgroundColor: colors.primary_color }}
						railStyle={{ backgroundColor: '#d6d8da' }}
						pushable={true}
						handleStyle={{
							borderColor: colors.primary_color,
							backgroundColor: 'white',
						}}
					/>
					<span className="linear-listings__filters__range__input">
						<input
							id="linearRentMax"
							type="text"
							value={rentRange1AsNumber === rentRangeUpper ? '' : rentRange[1]}
							placeholder="Max"
							onChange={(e) => handleInputChange(e, 0)}
						/>
						<label htmlFor="linearRentMax">
							{texts.setFilterMaximumRent}
						</label>
						<span className="linear-listings__filters__range__input__euro" dangerouslySetInnerHTML={{ __html: "€" }}></span>
					</span>
				</div>
			</motion.div>
		</>
	);
};

const AreaRange = ({globals}) => {
	const { frontEndFilters, setFrontEndFilters, colors, texts } = useContext(AppContext);

	const areaRangeLower = parseInt( globals.areaRangeLower );
	const areaRangeUpper = parseInt( globals.areaRangeUpper );
	const areaMin = areaRangeLower;
	const areaMinStyled = areaMin.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
	const areaMax = areaRangeUpper;
	const areaMaxStyled = areaMax.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
	const defaultValueLow = typeof frontEndFilters['areaMin'] !== 'undefined' ? frontEndFilters['areaMin'] : areaMinStyled;
	const defaultValueHigh = typeof frontEndFilters['areaMax'] !== 'undefined' ? frontEndFilters['areaMax'] : areaMaxStyled;
	const [areaRange, setAreaRange] = useState([defaultValueLow, defaultValueHigh]);
	const [onLoad, setOnLoad] = useState(true);

	const handleInputChange = (e, index) => {
		const { value } = e.target;

		const newValue = parseInt(value.replace(/ /g, ''));

		let newState = [...areaRange];
		newState[index] = newValue;

		setAreaRange(newState);
		setFrontEndFilters({
			...frontEndFilters,
			['areaMin']: parseInt(newState[0].toString().replace(" ","")),
			['areaMax']: parseInt(newState[1].toString().replace(" ","")),
		});

		URLParamsPopulator({
			...frontEndFilters,
			['areaMin']: parseInt(newState[0].toString().replace(" ","")),
			['areaMax']: parseInt(newState[1].toString().replace(" ","")),
		}, globals);

		setOnLoad( false );
	};

	const handleRangeChange = (value) => {
		setAreaRange( value );
	}

	const debounced = debounce(() => {
		if(
			onLoad && 
			parseInt(areaRange[0].toString().replace(/ /g, '')) <= areaMin && 
			parseInt(areaRange[1].toString().replace(/ /g, '')) === areaMax
		){
			// init, dont' set values to filters
		} else {
			setFrontEndFilters({
				...frontEndFilters,
				['areaMin']: parseInt(areaRange[0].toString().replace(" ","")),
				['areaMax']: parseInt(areaRange[1].toString().replace(" ","")),
			});
		}

		URLParamsPopulator({
			...frontEndFilters,
			['areaMin']: parseInt(areaRange[0].toString().replace(" ","")),
			['areaMax']: parseInt(areaRange[1].toString().replace(" ","")),
		}, globals);
	}, 200);

	useEffect(() => {
		debounced();
		setOnLoad( false );

		return () => {
			debounced.cancel();
		};
	}, [areaRange]);

	// ensure no nulls
	if( !areaRange[0] ){
		areaRange[0] = 0;
	}
	if( !areaRange[1] ){
		areaRange[1] = 0;
	}

	// Style numbers
	areaRange[0] = areaRange[0].toString().replace(/ /g, '').replace(/\B(?=(\d{3})+(?!\d))/g, " ");
	areaRange[1] = areaRange[1].toString().replace(/ /g, '').replace(/\B(?=(\d{3})+(?!\d))/g, " ");

	// Min-value indicator
	if (parseInt(areaRange[0].toString().replace(/ /g, '')) === parseInt( areaMin )) {
		areaRange[0] = areaRangeLower.toString().replace(/ /g, '').replace(/\B(?=(\d{3})+(?!\d))/g, " ");;
	}

	const areaRange0AsNumber = parseInt( areaRange[0].replace(/ /g, ''));
	const areaRange1AsNumber = parseInt( areaRange[1].replace(/ /g, ''));

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
				className="linear-listings__filters__group linear-listings__filters__group__areaRange linear-listings__filters__group--wide"
			>
				<div className="linear-listings__filters__range">
					<span className="linear-listings__filters__range__input">
						<input
							id="linearAreaMin"
							type="text"
							value={areaRange0AsNumber === areaRangeLower ? '' : areaRange[0]}
							placeholder="Min"
							onChange={(e) => handleInputChange(e, 0)}
						/>
						<label htmlFor="linearAreaMin">
							{texts.setFilterMinimumArea}
						</label>
						<span className="linear-listings__filters__range__input__area">m<sup>2</sup></span>
					</span>
					<Slider
						range
						min={areaMin}
						max={areaMax}
						step={5}
						onChange={(value) => handleRangeChange(value)}
						value={[
							parseFloat( areaRange[0].toString().replace(" ","") ),
							parseFloat( areaRange[1].toString().replace(" ","") ),
						]}
						className="linear-listings__filters__range__slider"
						trackStyle={{ backgroundColor: colors.primary_color }}
						railStyle={{ backgroundColor: '#d6d8da' }}
						pushable={true}
						handleStyle={{
							borderColor: colors.primary_color,
							backgroundColor: 'white',
						}}
					/>
					<span className="linear-listings__filters__range__input">
						<input
							id="linearAreaMax"
							type="text"
							value={areaRange1AsNumber === areaRangeUpper ? '' : areaRange[1]}
							placeholder="Max"
							onChange={(e) => handleInputChange(e, 1)}
						/>
						<label htmlFor="linearAreaMax">
							{texts.setFilterMaximumArea}
						</label>
						<span className="linear-listings__filters__range__input__area">m<sup>2</sup></span>
					</span>
				</div>
			</motion.div>
		</>
	);
};

const SearchOptions = ({globals}) => {
	const { frontEndFilters, setFrontEndFilters, texts, searchOptions } = useContext(AppContext);

	const handleChange = (e) => {
		const { value } = e.target;

		let newValue = frontEndFilters['searchOption'] === value ? '' : value;

		setFrontEndFilters({
			...frontEndFilters,
			['searchOption']: newValue
		});

		URLParamsPopulator({
			...frontEndFilters,
			['searchOption']: newValue
		}, globals);
	};

	if( 
		searchOptions.length === 0 || 
		searchOptions === '' || 
		JSON.stringify(searchOptions) === JSON.stringify([''])
	){
		// Return classic search instead
		return <Search />;
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
				className="linear-listings__filters__group linear-listings__filters__group__searchOptions"
			>
				<div className="linear-listings__filters__select">
					<select value={frontEndFilters.searchOption} onChange={(e) => handleChange(e)}>
						<option key={'empty'} value={""}>{texts.chooseLocation}</option>
						{searchOptions.map((option, i) => {         
							return ( <option key={i} value={option}>{option}</option> );
						})}
					</select>
				</div>
			</motion.div>
		</>
	);
}

const Search = ({globals}) => {
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
		}, globals);
	};

	const searchTerm = typeof frontEndFilters['search'] !== undefined && frontEndFilters['search'] ? frontEndFilters['search'] : '';

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
				className="linear-listings__filters__group linear-listings__filters__group__search"
			>
				<div className="linear-listings__filters__input">
					<input 
						type="text"
						name={texts.search}
						onChange={handleChange}
						value={searchTerm}
						placeholder={texts.searchElementPlaceholder}
					/>
				</div>
			</motion.div>
		</>
	);
}

const BusinessListingType = ({globals}) => {
	const { texts } = useContext(AppContext);

	return (
		<>
			<SelectGroup
				optionName={texts.businessListingType}
				optionSlug="businessListingType"
				options={[
					{ value: 'office_space', label: texts.officeSpace },
					{ value: 'business_space', label: texts.businessSpace },
					{ value: 'production_space', label: texts.productionSpace },
					{ value: 'storage_space', label: texts.storageSpaceHouse },
					{ value: 'restaurant_space', label: texts.restaurantSpace },
					{ value: 'exhibition_space', label: texts.exhibitionSpace },
					{ value: 'hobby_space', label: texts.hobbySpace },
					{ value: 'hub_space', label: texts.hubSpace },
					{ value: 'other_business_space', label: texts.otherBusinessSpace },
				]}
			/>
		</>
	);
};

const FiltersList = ({globals}) => {
	const { listingsType, frontEndFilters, showRangeSliders } = useContext(AppContext);
	let showExtendedFilters = false;
	let commissionType = false;
	let productGroup = false

	// Check if we want to show extended filters
	if( typeof frontEndFilters.productGroup !== 'undefined' ){
		if( 
			['apartments', 'vacation_apartment'].includes( frontEndFilters.productGroup ) ||
			frontEndFilters.productGroup === ""
		){
			showExtendedFilters = true;
		}
	} else {
		showExtendedFilters = true;
	}

	if( typeof frontEndFilters.commissionType !== 'undefined' && frontEndFilters.commissionType !== '' ){
		commissionType = frontEndFilters.commissionType.toLowerCase();
	}

	if( typeof frontEndFilters.productGroup !== 'undefined' && frontEndFilters.productGroup !== '' ){
		productGroup = frontEndFilters.productGroup.toLowerCase();
	}

	// return with logic
	return (
		<div className="linear-listings__filters">			

			{ globals.showSearch && (
				<div className="linear-listings__filters__row">
					<SearchOptions globals={globals} />
				</div>
			)}

			{ ( !!listingsType && ['all', 'BUSINESS_PREMISES'].includes( listingsType ) ) && globals.showCommissionType ? (
				<div className="linear-listings__filters__row">
					<CommissionType globals={globals} />
				</div>
			) : ''}


			{(listingsType !== 'BUSINESS_PREMISES' || listingsType === 'all') && globals.showProductGroup ? (
				<>
					<div className="linear-listings__filters__row">
						<ProductGroup globals={globals} />
					</div>
				</>
			) : (
				''
			)}


			{ 
				( !!productGroup && ['apartments', 'vacation_apartment'].includes(productGroup) && globals.showRoomCount ) || 
				( ( !productGroup || productGroup === false || productGroup === '' ) && globals.showRoomCount ) 
			? (
				<>
					<div className="linear-listings__filters__row">
						<RoomCount globals={globals} />
					</div>
				</>
			) : (
				''
			)}

			{listingsType !== 'BUSINESS_PREMISES' && showExtendedFilters ? (
				<>
					{ globals.showListingType ? (
						<>
							<div className="linear-listings__filters__row" data-group>
								<ListingType globals={globals} />
							</div>
						</>
					) : (
						''
					)}
				</>
			) : ('')}

			{
				( !!productGroup && ['apartments', 'vacation_apartment'].includes(productGroup) && globals.showSpecifications ) || 
				( ( !productGroup || productGroup === false || productGroup === '' ) && globals.showSpecifications ) 
			? (
				<>
					<div className="linear-listings__filters__row" data-group>
						<Specifications globals={globals} />
					</div>
				</>
			) : (
				''
			)}

			{(listingsType === 'BUSINESS_PREMISES' || listingsType === 'all') && globals.showBusinessListingType ? (
				<>
					<div className="linear-listings__filters__row">
						<BusinessListingType globals={globals} />
					</div>
				</>
			) : (
				''
			)}

			{ showRangeSliders && (
				<>
					{ ['all', 'APARTMENTS', 'RENT_APARTMENT'].includes(listingsType) ? (
						<>
							{ 
								(commissionType === 'sell' && globals.showPriceRange) || 
								(globals.showPriceRange && listingsType === 'APARTMENTS')
							? (
								<>
									<div className="linear-listings__filters__row">
										<PriceRange globals={globals} />
									</div>
								</>
							) : (
								''
							)}

							{ 
								(commissionType === 'rent' && globals.showRentRange) || 
								(globals.showRentRange && listingsType === 'RENT_APARTMENT')
							? (
								<>
									<div className="linear-listings__filters__row">
										<RentRange globals={globals} />
									</div>
								</>
							) : (
								''
							)}
							</>
					) : ('')}

					{ globals.showAreaRange ? (
						<>
							<div className="linear-listings__filters__row">
								<AreaRange globals={globals} />
							</div>
						</>
					) : (
						''
					)}
						
				</>
			)}
		</div>
	);
};

// Wrapper around the filters to add a accordion-element
const Filters = ({globals}) => {
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
		<div className="linear-listings__accordion">

			{ windowWidth < breakpoint && 
				<div className="wp-block-button linear-listings__accordion__toggle elementor-cta__button-wrapper">
					<button
						className="wp-block-button__link elementor-cta__button"
						onClick={() => setAccordionOpen(!accordionOpen)}
						aria-expanded={accordionOpen}
					>
						{accordionOpen ? texts.hideFilters : texts.showFilters}
					</button>
				</div>
			}

			<div className="linear-listings__accordion__content" aria-hidden={!accordionOpen} data-open={accordionOpen}>
				<AnimatePresence>
					<FiltersList globals={globals} />
				</AnimatePresence>
			</div>

		</div>
	);
};

export default Filters;
