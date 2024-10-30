/**
 * External dependencies
 */
import React from 'react';
import { render } from 'react-dom';

/**
 * Internal dependencies
 */
import config from '../../config.json';
import App from './App';

(function () {
	let isInitialized = false;

	window.addEventListener('DOMContentLoaded', () => {
		if (!isInitialized) {
			isInitialized = true;
			init();
		}
	});

	const init = () => {
		const { blockClassName } = config;
		const els = document.querySelectorAll(`.${blockClassName}`);

		els.forEach((el) => render(<App { ...parseDataset( el.dataset ) } />, el));
	};

	const parseDataset = ( dataset ) => {
		return Object.keys( dataset ).reduce( function( object, key ) {

			if( dataset[ key ] === 'true' ){
				object[ key ] = true; 
			} else if( dataset[ key ] === 'false' ){
				object[ key ] = false; 
			} else {
				object[ key ] = dataset[ key ]; 
			}

			return object;
		}, {});
	}
})();
