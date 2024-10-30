const defaultConfig = require('@wordpress/scripts/config/webpack.config');

module.exports = {
	...defaultConfig,
	/**
	 * Disable the dependency extraction plugin by WordPress
	 */
	plugins: [
		...defaultConfig.plugins.filter(
			(plugin) =>
				plugin.constructor.name !== 'DependencyExtractionWebpackPlugin'
		),
	],
	/**
	 * Let's provide our own set of dependencies.
	 */
	externals: {
		react: 'React',
		'react-dom': 'ReactDOM',
	},
};
