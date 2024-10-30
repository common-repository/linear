<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 * 
 * @package Linear
 */

/**
 * The core plugin class.
 */
class Linear {

	/**
	 * The loader responsible for maintaining and registering all hooks that power the plugin.
	 *
	 * @var Linear_Loader
	 */
	protected $loader;

	/**
	 * Middleware, simpler implementation of the Linear_Middleware.
	 *
	 * @var Linear_Middleware
	 */
	protected $middleware;

	/**
	 * Endpoints, creating rest-endpoints.
	 *
	 * @var Linear_Endpoints
	 */
	protected $endpoints;

	/**
	 * Data handler
	 *
	 * @var Linear_Data_Handler
	 */
	protected $data_handler;

	/**
	 * Upgrader
	 *
	 * @var Linear_Upgrader
	 */
	protected $upgrader;

	/**
	 * Hooks
	 *
	 * @var Linear_Hooks
	 */
	protected $hooks;

	/**
	 * Shortcodes
	 *
	 * @var Linear_Shortcodes
	 */
	protected $shortcodes;

	/**
	 * Unique identifier for plugin.
	 *
	 * @var string
	 */
	protected $plugin_uid;

	/**
	 * Plugin version.
	 *
	 * @var string
	 */
	protected $version;

	/**
	 * Settings options.
	 *
	 * @var array
	 */
	protected $options;

	/**
	 * Slugs with label for all api constants.
	 *
	 * @var array
	 */
	protected static $slugs;

	/**
	 * Linear API request parameters:
	 * - balcony (boolean)
	 * - company (company id from the settings, integer)
	 * - elevator (boolean)
	 * - listingType (string constant:
	 *	FLAT, ROWHOUSE, PAIRHOUSE, SEPARATEHOUSE, DETACHEDHOUSE, VACATIONHOME, LEISUREHOME, COTTAGE_OR_VILLA, WOODEN_HOUSE_SHARE
	 *	PLOT, LOFT, VACATION_SHARE, DETACHEDHOUSE_PLOT, ROWHOUSE_PLOT, LEISUREHOME_PLOT, APARTMENT_HOUSE_PLOT
	 *	BUSINESS_PLOT, FARM, FOREST_FARM, FIELD_AREA, PROPERTY_SCRIPT, WILDERNESS_AREA, OTHER, OFFICE_SPACE
	 *	BUSINESS_SPACE, PRODUCTION_SPACE, STORAGE_SPACE or VACATION_APARTMENT )
	 * - minimumPrice (in thousands of €, integer)
	 * - maximumPrice (in thousands of €, integer)
	 * - orderBy (NEWEST_FIRST or OLDEST_FIRST, default NEWEST_FIRST)
	 * - productGroup (string constant: VACATION_APARTMENT, RENT_APARTMENT, NEWLY_CONSTRUCTED, BUSINESS_PREMISES, APARTMENTS, PLOTS, FARMS, GARAGES)
	 * - page (pagination, default 1, integer)
	 * - roomFilter ( array of intigers: 1, 2, 3, 4, 5 and 6 )
	 * - searchValue ( any string eg: address, city, region)
	 * @var string[]
	 */
	protected static $allowed_filters = array(
		'balcony',
		'company',
		'elevator',
		'listingType',
		'migratedListings',
		'minimumPrice',
		'maximumPrice',
		'orderBy',
		'roomFilter',
		'productGroup',
		'page',
		'searchValue',
		'sauna'
	);

	/**
	 * Sprite function.
	 *
	 * Example of usage:
	 * ```php
	 * <?php sprite('checkmark', 'u-fill-current'); ?>
	 * ```
	 *
	 * @param string  $name    SVG icon name.
	 * @param string  $classes Additional classes.
	 * @param boolean $echo    Echo or return.
	 * @return void|string
	 */
	public static function sprite( $name, $classes = '', $echo = true ) {
		$path   = self::sprite_path( $name, false );
		$output = "<svg class=\"linear-o-icon {$classes}\"><use xlink:href=\"{$path}\"></use></svg>";

		if ( ! $echo ) {
			return $output;
		}

		echo $output; // WPCS: xss ok.
	}

	/**
	 * Returns svg path for icon name.
	 *
	 * @param string  $name SVG icon name.
	 * @param boolean $echo Echo or return.
	 * @return void|string
	 */
	public static function sprite_path( $name, $echo = true ) {
		$result = plugins_url( "assets_old/sprite/icons.svg#icon-$name", __DIR__ );
		if ( ! $echo ) {
			return $result;
		}
		echo esc_url( $result ); // WPCS: xss ok.
	}

	/**
	 * Returns file path for template.
	 * Files in theme directory 'linear' will have priority.
	 *
	 * @param string $template File name or relative path.
	 * @return void|string
	 */
	public static function get_template_path( $template ) {
		$template_path = get_template_directory() . "/linear/$template";
		if( ! file_exists( $template ) ) {
			$template_path = plugin_dir_path( __DIR__ ) . "templates/$template";
		}
		return $template_path;
	}

	/**
	 * Renders linear template component.
	 *
	 * @param string $component File name.
	 * @return void
	 */
	public static function template_component( $component, $args = array() ) {
		$component = self::get_template_path( "components/$component" );

		// Functionality was copied from WP 5.5 ( 'load_template' in 'wp-includes/template.php' ) to enable $args support for older WP verisons
		// In the future it should be replaced with comented line below 'load_template( $component, false, $args )'

		global $posts, $post, $wp_did_header, $wp_query, $wp_rewrite, $wpdb, $wp_version, $wp, $id, $comment, $user_ID;

		if ( is_array( $wp_query->query_vars ) ) {
			extract( $wp_query->query_vars, EXTR_SKIP );
		}

		if ( isset( $s ) ) {
			$s = esc_attr( $s );
		}
	
		require $component;
	}

	/**
	 * Returns sanitized and validated allowed filter values from GET.
	 *
	 * @param array $filters Array of filter keys and values.
	 * @return array
	 */
	public function sanitize_allowed_filters( $filters ) {
		// Remove any filter that is not allowed
		$filters = array_intersect_key( $filters, array_flip( self::$allowed_filters ) );
		// Sanitize and validate values for every allowed filter
		foreach ( $filters as $key => $value ) {
			if ( in_array( $key, array( 'balcony', 'elevator', 'sauna', 'migratedListings' ), true ) ) {
				// Checkbox bool values
				$filters[ $key ] = empty( $value ) ? 0 : 1;
			} elseif ( $key === 'roomFilter' ) {
				// Array of int values from 1 to 6
				$filters[ $key ] = array_filter(
					$filters[ $key ],
					function( $value ) {
						return in_array( $value, array( 1, 2, 3, 4, 5, 6) );
					}
				);
			} elseif ( $key === 'page' || $key === 'company' ) {
				// Positive integers
				if ( is_numeric( $value ) ) {
					$filters[ $key ] = absint( $value );
				} else {
					unset( $filters[ $key ] );
				}
			} elseif ( $key === 'minimumPrice' || $key === 'maximumPrice' ) {
				// Can be in special string format or positive number, convert it to number
				if ( is_string( $value ) ) {
					$value = str_replace( ' ', '', $value );
					if ( preg_match( '/^([0-9]+(?:(?:\.|,)[0-9 ]+)?).*$/', $value, $value ) ) {
						$value = str_replace( ',', '.', $value[1] );
					};
				}
				if ( is_numeric( $value ) ){
					$filters[ $key ] = abs( $value );
				} else {
					unset( $filters[ $key ] );
				}
			} elseif ( $key === 'listingType' || $key === 'orderBy' || $key === 'productGroup' ) {
				$value = sanitize_text_field( $value );
				if ( preg_match('/^[A-Z]+(?:_[A-Z]+)*$/', $value ) ) {
					$filters[ $key ] = $value;
				} else {
					unset( $filters[ $key ] );
				}
			} else {
				$value = sanitize_text_field( $value );
				if ( empty( $value ) ) {
					unset( $filters[ $key ] );
				}
			}
		}

		return $filters;
	}

	/**
	 * Returns allowed filters in provided array of filters.
	 *
	 * @param array $filters Array of filters that will be filtered.
	 * @return array
	 */
	public function get_allowed_filters( $filters = array() ) {
		if ( empty( $filters ) && isset( $_GET['linear'] ) && is_array( $_GET['linear'] ) ) {
			$filters = $this->sanitize_allowed_filters( $_GET['linear'] );
		} else {
			$filters = $this->sanitize_allowed_filters( $filters );
		}

		if ( ! isset( $filters['page'] ) || ! is_numeric( $filters['page'] ) ) {
			$filters['page'] = 1;
		}

		foreach ( $filters as $key => $value ) {
			if ( $value === '' || is_null( $value ) || $value === array() ) {
				unset( $filters[$key] );
			}
		}

		return $filters;
	}

	public static function validate_listing_id( $id ) {
		return (bool) preg_match( '/^[a-z0-9]{8}(?:-[a-z0-9]{4}){3}-[a-z0-9]{12}$/', $id );
	}

	public static function validate_listing_short_id( $id ) {
		if( strlen( $id ) === strlen( intval( $id ) ) ){
			return true;
		}

		return false;
	}

	/**
	 * Adds rewrite rules for listing/s pages.
	 * 
	 * @param bool $flush Optional, set true to flush rewrite rules.
	 */
	public static function listings_page_rewrite_rules( $flush = false ) {
		$linear_settings = get_option( 'linear_settings' );

		$languages = self::get_languages();
		$priority = 'top';
		
		if ( get_option( 'permalink_structure' ) ) {
			if ( ! empty( $linear_settings ) ) {

				// listings
				foreach ( ['listings_page', 'rentals_page', 'workplace_page'] as $linear_page ) {
					if( $languages && is_array($languages) && count( $languages ) > 1 ){
						foreach( $languages as $lang ){
							self::listings_page_rewrite_rule( $linear_page, $lang, $priority );
						}
					} else {
						self::listings_page_rewrite_rule( $linear_page, null, $priority );
					}
				}

			}
		}
		if ( $flush ) {
			flush_rewrite_rules();
		}
	}

	public static function listings_page_rewrite_rule( $linear_page, $lang = null, $priority = 'top' ) {
		$linear_settings = get_option( 'linear_settings' );

		$option_key = $linear_page;
		if( $lang ){
			$option_key = $linear_page . '_' . $lang;
		}

		if ( isset( $linear_settings[$option_key] ) ) {

			$target_page_id = $linear_settings[$option_key];
			$target_page_permalink = get_permalink( $target_page_id );
			$site_url = home_url();
			$target_page_path = str_replace( '/','\/', ltrim( str_replace( $site_url, '', $target_page_permalink ), '/') );

			$target_page_path = self::language_plugins_workaround( $target_page_path, $lang );

			$priority = ( !self::if_path_main_lang( $target_page_path, $lang ) ? 'top' : $priority );

			$regex = sprintf(
				'^%s[^\/]+\/([a-zA-Z0-9\-]+)\/?$',
				$target_page_path
			);

			add_rewrite_rule(
				$regex,
				'index.php?page_id=' . $linear_settings[$option_key] . '&linear_data_id=$matches[1]',
				$priority
			);
		}
	}

	/**
	 * Adds rewrite rules for buy-commissions.
	 * 
	 * @param bool $flush Optional, set true to flush rewrite rules.
	 */
	public static function buy_commissions_page_rewrite_rules( $flush = false, $lang = null ) {
		$linear_settings = get_option( 'linear_settings' );

		$languages = self::get_languages();
		$priority = 'top';

		if ( get_option( 'permalink_structure' ) ) {
			if ( ! empty( $linear_settings ) ) {

				// buy commissions
				foreach ( ['buy_commissions_page'] as $linear_page ) {
					if( $languages && is_array($languages) && count( $languages ) > 1 ){
						foreach( $languages as $lang ){
							self::buy_commissions_page_rewrite_rule( $linear_page, $lang, $priority );
						}
					} else {
						self::buy_commissions_page_rewrite_rule( $linear_page, null, $priority );
					}
				}
			}
		}
		if ( $flush ) {
			flush_rewrite_rules();
		}
	}

	public static function buy_commissions_page_rewrite_rule( $linear_page, $lang, $priority = 'top' ){
		$linear_settings = get_option( 'linear_settings' );

		$option_key = $linear_page;
		if( $lang ){
			$option_key = $linear_page . '_' . $lang;
		}

		if ( isset( $linear_settings[$option_key] ) ) {

			$target_page_id = $linear_settings[$option_key];
			$target_page_permalink = get_permalink( $target_page_id );
			$site_url = home_url();
			$target_page_path = str_replace( '/','\/', ltrim( str_replace( $site_url, '', $target_page_permalink ), '/') );

			$target_page_path = self::language_plugins_workaround( $target_page_path, $lang );

			$priority = ( !self::if_path_main_lang( $target_page_path, $lang ) ? 'top' : $priority );

			$regex = sprintf(
				'^%s[^\/]+\/([a-zA-Z0-9\-]+)\/?$',
				$target_page_path
			);

			add_rewrite_rule(
				$regex,
				'index.php?page_id=' . $linear_settings[$option_key] . '&linear_data_id=$matches[1]',
				$priority
			);
		}
	}

	/**
	 * A potential workaround for WPML issues with add_rewrite_rule
	 */
	public static function language_plugins_workaround( $path, $path_language ){
		$plugins = apply_filters( 'active_plugins', get_option( 'active_plugins' ) );
		$languages = self::get_languages();

		// WPML
		if( in_array( 
			'sitepress-multilingual-cms/sitepress.php', 
			$plugins
		) ) {
			if( $languages && is_array($languages) && count($languages) > 1 ){
				foreach( $languages as $lang ){
					if( substr( $path, 0, 4 ) === $lang . '\/' ){
						$path = rtrim( substr( $path, 4, -1 ), '\/') . '\/';
					}
				}
			}
		}

		// Translatepress
		if( in_array( 
			'translatepress-multilingual/index.php', 
			$plugins
		) ){
			if( count($languages) > 1 ){
				$trp = TRP_Translate_Press::get_trp_instance();

				$home_url = get_home_url();
				$target_permalink = $home_url . '\/' . $path;

				if( $trp ){
					$url_converter 	= $trp->get_component( 'url_converter' );
					$maybe_translated_permalink = $url_converter->get_url_for_language( $path_language, $target_permalink, '' );
					$path = str_replace( $home_url . '\/', '', $maybe_translated_permalink );
				}
			}
		}
		
		return $path;
	}

	/**
	 * Add query vars for listing data.
	 *
	 * @return Linear
	 */
	public static function listings_page_query_vars( $vars ) {
		$vars[] = 'linear_data_id';
		return $vars;
	}

	public static function buy_commissions_page_query_vars( $vars ) {
		$vars[] = 'linear_data_id';
		return $vars;
	}	

	/**
	 * Returns instance of the Linear plugin class.
	 *
	 * @return Linear
	 */
	public static function get_instance() {
		static $instance = null;

		if ( null === $instance ) {
			$instance = new Linear();
		}

		return $instance;
	}

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 */
	protected function __construct() {
		if ( defined( 'LINEAR_VERSION' ) ) {
			$this->version = LINEAR_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_uid = 'linear';

		$this->options = get_option( 'linear_settings' );
		if ( isset( $this->options[ 'search_options' ] ) ) {
			if ( ! is_array( $this->options[ 'search_options' ] ) ) {
				$this->options[ 'search_options' ] = array();
			} else {
				$this->options[ 'search_options' ] = array_filter( $this->options[ 'search_options' ] );
			};
		}
	}

	/**
	 * Returns instance of the Linear middleware class.
	 *
	 * @return Linear_Middleware
	 */
	public function get_middleware() {
		return $this->middleware;
	}

	/**
	 * Returns instance of the Linear data handler class.
	 *
	 * @return Linear_Data_Handler
	 */
	public function get_data_handler() {
		return $this->data_handler;
	}

	/**
	 * Returns instance of the Linear hooks class
	 *
	 * @return Linear_Hooks
	 */
	public function get_hooks() {
		return $this->hooks;
	}

	/**
	 * Returns formated number stirng.
	 *
	 * @param int|float
	 * @return string
	 */
	public function number_format( $number ){
		$number = number_format( $number, 2, ',', '&nbsp;' );
		if( strpos( $number, ',' ) !== false ) {
			$number = rtrim( rtrim( $number,'0' ), ',' );
		}
		return $number;
	}

	/**
	 * Returns lable for given API constants.
	 *
	 * If lable for constant canot be found it will assume human readable format and return it.
	 *
	 * @param string $slug API constant string.
	 * @return string
	 */
	public function get_slug_label( $slug ){
		if ( is_null( self::$slugs ) ) {
			self::$slugs = require plugin_dir_path( __FILE__ ) . 'constants_and_labels.php';
		}

		if ( array_key_exists( $slug, self::$slugs ) ) {
			$label = self::$slugs[$slug];
		} else {
			$slug = explode( '@', $slug, 2 );
			if ( array_key_exists( $slug[0], self::$slugs ) ) {
				$label = self::$slugs[$slug[0]];
			} else {
				$label = ucwords( strtolower( $slug[0] ) );
				$label = str_replace( '_', ' ', $label );
			}
		}

		return $label;
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include files that make up the plugin:
	 * - Linear_Loader    Orchestrates the hooks of the plugin.
	 * - Linear_i18n      Defines internationalization functionality.
	 * - Linear_Templater Sets up correct template for listing/s content.
	 * - Linear_Settings  Creates plugin settings page in admin dashboard.
	 * - Linear_Listing   Used to contain listing object.
	 */
	private function load_dependencies() {
		require_once plugin_dir_path( __FILE__ ) . 'class-linear-loader.php';
		require_once plugin_dir_path( __FILE__ ) . 'class-linear-i18n.php';
		require_once plugin_dir_path( __FILE__ ) . 'class-linear-templater.php';
		require_once plugin_dir_path( __FILE__ ) . 'class-linear-settings.php';
		require_once plugin_dir_path( __FILE__ ) . 'class-linear-middleware.php';
		require_once plugin_dir_path( __FILE__ ) . 'class-linear-endpoints.php';
		require_once plugin_dir_path( __FILE__ ) . 'class-linear-data-handler.php';
		require_once plugin_dir_path( __FILE__ ) . 'class-linear-admin.php';
		require_once plugin_dir_path( __FILE__ ) . 'class-linear-upgrader.php';
		require_once plugin_dir_path( __FILE__ ) . 'class-linear-hooks.php';
		require_once plugin_dir_path( __FILE__ ) . 'class-linear-shortcodes.php';
		require_once plugin_dir_path( __FILE__ ) . 'class-linear-sitemaps.php';

		$this->loader = new Linear_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 */
	private function set_locale() {
		$plugin_i18n = new Linear_i18n();
		$this->loader->add_action( 'init', $plugin_i18n, 'load_plugin_textdomain' );
		$this->loader->add_filter( 'load_textdomain_mofile', $plugin_i18n, 'force_plugin_translations', 10, 2 );
	}

	/**
	 * Include templates for plugin content.
	 */
	private function plugin_templates() {

		$render_method = $this->get_option( 'render_method' );

		$templater = new Linear_Templater();
		$this->loader->add_action( 'template_include', $templater, 'include_templates', 100 );

		if( !$render_method || $render_method === 'content' ){
			$this->loader->add_filter( 'the_content', $templater, 'include_listings_content' );
			$this->loader->add_filter( 'the_content', $templater, 'include_buy_commissions_content' );
			$this->loader->add_filter( 'the_content', $templater, 'include_single_content' );
		}

		$this->loader->add_action( 'wp_enqueue_scripts', $templater, 'enqueue_style' );
		$this->loader->add_action( 'wp_enqueue_scripts', $templater, 'enqueue_script' );
		$this->loader->add_filter( 'page_template', $templater, 'template_creation' );
		$this->loader->add_filter( 'theme_page_templates', $templater, 'template_population', 10, 4 );
		// $this->loader->add_filter( 'the_title', $templater, 'hide_title', 10, 4 ); // disabled due to Elementor compability issues

		// New assets
		$this->loader->add_action( 'enqueue_block_editor_assets', $templater, 'enqueue_block_editor_assets' );
		$this->loader->add_action( 'after_setup_theme', $templater, 'enqueue_editor_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $templater, 'enqueue_frontend_scripts' );
		$this->loader->add_action( 'admin_enqueue_scripts', $templater, 'enqueue_frontend_scripts' );
		$this->loader->add_action( 'admin_enqueue_scripts', $templater, 'enqueue_admin_scripts' );
		$this->loader->add_action( 'login_enqueue_scripts', $templater, 'enqueue_admin_scripts' );

		// Templates
		include plugin_dir_path( __DIR__ ) . "templates/linear-listing.php";
		include plugin_dir_path( __DIR__ ) . "templates/linear-buy-commission.php";

		// Template parts
		$dynamically_included_folders = [
			'helpers',
			'sections',
			'components',
		];

		foreach( $dynamically_included_folders as $folder ){
			foreach (glob( plugin_dir_path( __DIR__ ) . "templates/" . $folder . "/*.php" ) as $filename) {
				include $filename;
			}
		}
	}

	/**
	 * Handle logic with fonts
	 */
	private function plugin_assets(){
		$templater = new Linear_Templater();
		$this->loader->add_action( 'body_class', $templater, 'specify_linear_page' );
		$this->loader->add_action( 'body_class', $templater, 'maybe_use_fonts_frontend' );
		$this->loader->add_filter( 'admin_body_class', $templater, 'maybe_use_fonts_admin' );
		$this->loader->add_action( 'wp_enqueue_scripts', $templater, 'enqueue_global_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $templater, 'enqueue_global_styles' );
	}

	/**
	 * Include settings admin page for plugin.
	 */
	private function plugin_settings() {
		$settings = new Linear_Settings();
		$this->loader->add_action( 'admin_menu', $settings, 'add_plugin_page' );
		$this->loader->add_action( 'admin_init', $settings, 'page_init' );
		$this->loader->add_filter( 'admin_body_class', $settings, 'admin_body_class' );
		$this->loader->add_action( 'admin_notices', $settings, 'linear_admin_debug_page_notices' );
		$this->loader->add_action( 'admin_init', $settings, 'linear_admin_debug_page_reset' );

		$this->loader->add_action( 'update_option_linear_settings', $settings, 'linear_settings_updated_callback', 10, 3);
		$this->loader->add_filter( 'pre_update_option_linear_settings', $settings, 'linear_settings_pre_update_callback', 10, 2);
		$this->loader->add_action( 'setup_filters', $settings, 'add_filters' );
	}

	/**
	 * Hooks for custom solutions
	 */
	private function plugin_hooks() {
		$hooks = new Linear_Hooks();

		// listings
		$this->loader->add_filter( 'linear_listings', $hooks, 'get_listings', 10, 2 );
		$this->loader->add_filter( 'linear_listings_by_type', $hooks, 'get_listings_by_type', 10, 2 );
		$this->loader->add_filter( 'linear_listing', $hooks, 'get_listing', 10, 2 );

		// buy commissions
		$this->loader->add_filter( 'linear_buy_commissions', $hooks, 'get_buy_commissions', 10, 2 );
		$this->loader->add_filter( 'linear_buy_commission', $hooks, 'get_buy_commission', 10, 2 );

		// utils
		$this->loader->add_filter( 'linear_languages', $hooks, 'get_languages', 10, 1 );
		$this->loader->add_filter( 'linear_default_language', $hooks, 'get_default_language', 10, 1 );
		$this->loader->add_filter( 'linear_current_language', $hooks, 'get_language', 10, 1 );

		// make it easy to reformat data
		$this->loader->add_filter( 'linear_edit_listings', $hooks, 'edit_listings_data', 10, 1 );
	}

	/**
	 * Shortcodes
	 */
	private function plugin_shortcodes(){
		$shortcodes = new Linear_Shortcodes();
		$render_method = $this->get_option( 'render_method' );

		if( $render_method === 'shortcode' ){
			$this->loader->add_filter( 'wp', $shortcodes, 'setup_shortcodes' );
		}
	}

	/**
	 * Include admin-page logic
	 */
	private function admin_logic() {
		$admin = new Linear_Admin();
		$this->loader->add_action( 'admin_notices', $admin, 'user_notify_contact_url' );
	}

	/**
	 * Sitemaps
	 */
	private function sitemaps() {
		$sitemaps = new Linear_Sitemaps();
		$this->loader->add_filter( 'init', $sitemaps, 'register_sitemap_provider' );
	}

	/**
	 * Middleware init
	 */
	private function plugin_middleware() {
		$this->middleware = new Linear_Middleware();
		$this->middleware->init();
	}

	/**
	 * Endpoints init
	 */
	private function plugin_endpoints() {
		$this->endpoints = new Linear_Endpoints();
	}

	/**
	 * Data handler init
	 */
	private function plugin_data_handler() {
		$this->data_handler = new Linear_Data_Handler();
	}

	/**
	 * Plugin upgrader
	 */
	private function plugin_upgrader() {
		$this->upgrader = new Linear_Upgrader();
		// $this->loader->add_action( 'upgrader_process_complete', $this->upgrader, 'upgrade_tasks' );
	}

	/**
	 * Set rewrite rules for listings page and run the loader to execute all of the hooks with WordPress.
	 */
	public function run() {

		$this->load_dependencies();
		$this->set_locale();
		$this->plugin_data_handler();
		$this->plugin_assets();
		$this->plugin_shortcodes();
		
		if ( is_admin() ) {
			if ( ! isset( $_GET['page'] ) || $_GET['page'] != 'linear-settings' ) {
				add_action( 'init', array( __CLASS__, 'listings_page_rewrite_rules' ) );
				add_action( 'init', array( __CLASS__, 'buy_commissions_page_rewrite_rules' ) );
			}
			$this->plugin_settings();
			$this->plugin_middleware();
			
		} else {
			if ( ! ( empty( $this->get_option( 'company_id' ) ) || empty( $this->get_option( 'api_url' ) ) || empty( $this->get_option( 'terms_of_service' ) ) ) ) {
				add_action( 'init', array( __CLASS__, 'listings_page_rewrite_rules' ) );
				add_action( 'init', array( __CLASS__, 'buy_commissions_page_rewrite_rules' ) );
				$this->plugin_middleware();
				add_action( 'query_vars', array( __CLASS__, 'listings_page_query_vars' ) );
				add_action( 'query_vars', array( __CLASS__, 'buy_commissions_page_query_vars' ) );
			}
		}

		$this->plugin_templates();
		$this->set_filters();
		$this->set_actions();
		$this->plugin_endpoints();
		$this->admin_logic();
		$this->plugin_upgrader();
		$this->plugin_hooks();
		$this->sitemaps();
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 * 
	 * @return string
	 */
	public function get_plugin_uid() {
		return $this->plugin_uid;
	}

	/**
	 * Retrieve the reference to the class that orchestrates the hooks.
	 *
	 * @return Linear_Loader
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @return string
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Returns instance options value if it exist, if not returns default value.
	 *
	 * @return mixed
	 */
	public function get_option( $key, $default = null ) {
		if ( isset( $this->options[$key] ) ) {
			return $this->options[$key];
		}

		// Handle potential lang versions
		$languages = self::get_languages();

		if( $languages && is_array($languages) && count($languages) > 1 ){
			foreach( $languages as $lang ){
				if ( isset( $this->options[$key . '_' . $lang] ) ) {
					return $this->options[$key . '_' . $lang];
				}
			}
		}

		return $default;
	}

	/**
	 * Filters for easier sharing of Linear values
	 */
	public function set_filters() {
		add_filter( 'linear_get_api_version', function(){

			$api_key = $this->get_option( 'api_key' );

			if( $api_key === null ){
				return null;
			}

			return $this->get_option( 'api_key' ) ? 'v1.1' : 'v1';
		});

		add_filter( 'linear_get_colors', function(){
			return array(
				'primary_color' => $this->get_option( 'primary_color', '#1890ff' ),
				'solid_color'   => $this->get_option( 'solid_color', '#096dd9' ),
				'outline_color' => $this->get_option( 'outline_color', '#0050b3' )
			);
		});

		add_filter( 'linear_get_search_options', function( $lang ){
			// Handle potential lang versions
			$languages = self::get_languages();

			if( $languages && is_array($languages) && count($languages) > 1 && $lang ){
				return $this->get_option( 'search_options_' . $lang, '' );
			}

			return $this->get_option( 'search_options', '' );
		});
	}

	/**
	 * Certain actions
	 */
	public function set_actions() {
		add_action( 'wp_before_admin_bar_render', function(){
			global $wp_admin_bar, $wp_query, $pagenow;

			if( $pagenow === 'admin.php' ){
				return;
			}

			$linear_data_id = $wp_query->get( 'linear_data_id', false );

			if( $linear_data_id ){
				$wp_admin_bar->remove_menu( 'edit' );
			}
		});
	}

	/**
	 * Get recursive parent pages for rewrite slugs
	 */
	public static function get_parent_pages_ids( $id = 0, $ids = [] ){
		$parent_page_id = wp_get_post_parent_id( $id );

		// Check if there is a parent page
		if ( $parent_page_id ) {
			array_push( $ids, $parent_page_id );
			return self::get_parent_pages_ids( $parent_page_id, $ids );
		}

		return $ids;
	}

	/**
	 * Get locale
	 */
	public static function get_language(){
		global $TRP_LANGUAGE;

		// Polylang
		if( function_exists('pll_current_language') ) {
			return substr( pll_current_language( 'slug' ), 0, 2 );
		}

		// current active plugins
		$plugins = apply_filters( 'active_plugins', get_option( 'active_plugins' ) );

		// WPML
		if( in_array( 
			'sitepress-multilingual-cms/sitepress.php', 
			$plugins
		) ) {
			if( has_filter('wpml_current_language') ){
				return substr( apply_filters( 'wpml_current_language', NULL ), 0, 2 );
			}
		}

		// Translatepress
		if( in_array( 
			'translatepress-multilingual/index.php', 
			$plugins
		) ){
			if ( !empty( $TRP_LANGUAGE ) && is_string( $TRP_LANGUAGE ) ) {
				return substr( $TRP_LANGUAGE, 0, 2 );
			}
		}

		return substr( get_locale(), 0, 2 );
	}

	/**
	 * Get locales
	 */
	public static function get_languages(){
		global $polylang;

		// Polylang
		if ( isset($polylang) ) {
			$pl_languages = $polylang->model->get_languages_list();
			foreach ($pl_languages as $pl_language) {
				$languages[] = $pl_language->slug;
			}

			return $languages;
		}

		// current active plugins
		$plugins = apply_filters( 'active_plugins', get_option( 'active_plugins' ) );

		// WPML
		if( in_array( 
			'sitepress-multilingual-cms/sitepress.php', 
			$plugins
		) ) {
			$wpml_languages = apply_filters( 'wpml_active_languages', NULL, 'skip_missing=0' );

			if( $wpml_languages ){
				$languages = [];

				foreach ($wpml_languages as $wpml_language) {
					$languages[] = !empty($wpml_language['language_code']) ? $wpml_language['language_code'] : $wpml_language['code'];
				}
	
				return $languages;
			}
		}

		// Translatepress
		if( in_array( 
			'translatepress-multilingual/index.php', 
			$plugins
		) ){
			$translatepress_settings = get_option( 'trp_settings' );

			if( isset( $translatepress_settings['publish-languages'] ) ){
				$languages = self::simplify_languages_array( $translatepress_settings['publish-languages'] );

				return $languages;
			}
		}
		
		// Fallback
		$languages[] = substr(get_locale(),0,2);
		return $languages;
	}

	/**
	 * Get current admin language
	 */
	public static function get_admin_language(){
		global $polylang;

		// Handle polylang
		if ( isset( $polylang ) ) {
			if( isset( $polylang->curlang ) && $polylang->curlang === false ){
				$current_lang = pll_default_language();
			} else {
				$current_lang = $polylang->curlang->slug;

				if( !$current_lang ){
					$current_lang = pll_default_language();
				}
			}

			return $current_lang;
		}

		// Handle WPML
		if( in_array( 
			'sitepress-multilingual-cms/sitepress.php', 
			apply_filters( 'active_plugins', get_option( 'active_plugins' ) )
		) ) {
			$current_lang = apply_filters('wpml_current_language', NULL );

			if( $current_lang ){
				return $current_lang;
			}
		}

		// Get plugins
		$plugins = apply_filters( 'active_plugins', get_option( 'active_plugins' ) );

		// Handle TranslatePress
		if( in_array( 
			'translatepress-multilingual/index.php', 
			$plugins
		) ){
			return false;
		}

		// Fallback
		return get_locale();
	}

	public static function simplify_languages_array( $languages ){
		if( !$languages ){
			return [ substr(get_locale(),0,2) ];
		}

		$cleaned_languages = [];

		foreach( $languages as $lang ){
			array_push( $cleaned_languages, substr( $lang, 0, 2 ) );
		}

		return $cleaned_languages;
	}

	// Handle directory structure lang check
	public static function if_path_main_lang( $path, $lang ){
		if( substr( $path, 0, 4 ) === $lang . '\/' ){
			return true;
		}

		return false;
	}
}
