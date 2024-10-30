<?php
/**
 * Internationalization functionality
 *
 * @package Linear
 */

/**
 * Defines the internationalization files for this plugin.
 */
class Linear_i18n {

	/**
	 * Text domain slug.
	 *
	 * @var string
	 */
	static $domain = 'linear';

	/**
	 * Relative path to translations directory.
	 *
	 * @var string
	 */
	public $directory;

	public function __construct() {
		$this->directory = LINEAR_PLUGIN_PATH . '/languages';
	}

	/**
	 * Load the plugin text domain for translation.
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain(
			self::$domain,
			false,
			$this->directory
		);
	}

	/**
	 * Force translations in plugin directory if they exist.
	 */
	public function force_plugin_translations( $mofile, $domain ) {

		if ( $domain === self::$domain ) {
			$locale = apply_filters( 'plugin_locale', determine_locale(), $domain );
			$local_mofile = $this->directory . '/' . basename( 'linear-' . $locale . '.mo' );
			if ( is_readable( $local_mofile ) ) {
				$mofile = $local_mofile;
			}
		}

		return $mofile;
	}
}