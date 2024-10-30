/**
 * Internal dependencies
 */
import config from '../../config.json';

export default window[config?.frontend?.globalConfig] || {
	actions: {},
	texts: {},
	assets: {},
	restBase: '',
	apiVersion: '',
};
