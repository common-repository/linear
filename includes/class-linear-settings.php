<?php
/**
 * Displays plugin settings page and takes care of saving.
 * 
 * @package Linear
 */

class Linear_Settings {

	/**
	 * Core plugin class instance.
	 *
	 * @var Linear
	 */
	protected static $linear;
	protected $api_key;
	protected $api_version;
	protected $contact_method;

	/**
	 * Defines class properties.
	 */
	public function __construct() {
		if ( is_null( self::$linear ) ) {
			self::$linear = Linear::get_instance();
		}

		$contact_method = self::$linear->get_option( 'contact_method' );

		$this->api_version = ( self::$linear->get_option( 'api_key' ) ? '1.1' : 'v1' );
		$this->contact_method = $contact_method ? $contact_method : null;
	}
 
	/**
	 * Enqueue necessary scripts for plugin settings page.
	 */
	function admin_page_scripts() {
		if ( ! did_action( 'wp_enqueue_media' ) ) {
			wp_enqueue_media();
		}
	}

	/**
	 * Adds settings page in admin dashboard.
	 */
	public function add_plugin_page() {
		$title = 'Linear';
		$tooltip = false;
		$contact_method = esc_attr( self::$linear->get_option( 'contact_method' ) );
		$contact_form_embed = esc_attr( self::$linear->get_option( 'contact_form_embed' ) );

		// Is using old API
		if( $this->api_version === 'v1'){
			$tooltip = true;
			$title = $title . " <span class='update-plugins count-1'><span class='plugin-count' aria-hidden='true'>!</span><span class='screen-reader-text'>" . __('Update your API credentials', 'linear') . "</span></span>";
		}

		// Contact form TODO
		if( $tooltip === false && $contact_method === 'form' && !$contact_form_embed ){
			$title = $title . " <span class='update-plugins count-1'><span class='plugin-count' aria-hidden='true'>!</span><span class='screen-reader-text'>" . __('Please insert your embedable Linear contact form', 'linear') . "</span></span>";
		}

		// Init admin page
		add_menu_page(
			'Linear settings',
			$title,
			'manage_options',
			'linear-settings',
			'',
			'dashicons-admin-multisite',
			81
		);

		// Create link to main option page
		add_submenu_page(
			'linear-settings',
			__( 'Settings', 'linear' ),
			__( 'Settings', 'linear' ),
			'manage_options',
			'linear-settings',
			array( $this, 'create_admin_page' )
		);

		// Instructions page
		add_submenu_page(
			'linear-settings',
			__( 'Instructions', 'linear' ),
			__( 'Instructions', 'linear' ),
			'manage_options',
			'linear-instructions',
			array( $this, 'create_admin_instructions' )
		);

		// Advanced usage page
		add_submenu_page(
			'linear-settings',
			__( 'Advanced usage', 'linear' ),
			__( 'Advanced usage', 'linear' ),
			'manage_options',
			'linear-advanced',
			array( $this, 'create_admin_guide' )
		);

		// Debug
		add_submenu_page(
			'linear-settings',
			__( 'Debug', 'linear' ),
			__( 'Debug', 'linear' ),
			'manage_options',
			'linear-debug',
			array( $this, 'create_debug_page' )
		);

		// Feedback
		add_submenu_page(
			'linear-settings',
			__( 'Feedback', 'linear' ),
			__( 'Feedback', 'linear' ),
			'manage_options',
			'linear-feedback',
			array( $this, 'create_admin_feedback' )
		);
	}

	/**
	 * Create settings page.
	 */
	public function create_admin_page() {
		?>
		<div class="wrap">
			<h2><?php esc_html_e( 'Linear settings', 'linear' ); ?></h2>
			<p></p>
			<?php settings_errors(); ?>

			<form method="post" action="options.php">
				<?php
					settings_fields( 'option_group' );
					do_settings_sections( 'linear-settings-admin' );
					submit_button();
				?>
			</form>
		</div>
		<?php 
	}

	/**
	 * Create instructions page
	 */
	public function create_admin_instructions() { 
		?>
		<div class="wrap">
			<h1><?php echo __( 'Instructions', 'linear' ); ?></h1>

			<h2><?php echo __('What Linear plugin is for', 'linear') ?></h2>

			<p>
				<?php echo __('The Linear plugin is used to list apartments, rentals, business premises and buy-commissions on your site with ease. 
				Set your credentials and make sure you have a set listings-page and you are set to go.', 'linear'); ?>
			<p>

			<br>
			<h2><?php echo __('Prerequisites', 'linear') ?></h2>

			<p>
				<strong>
				<?php echo __('Are you already registered to Linear?', 'linear'); ?>
				</strong><br>
				<?php echo __('If not, you need to register in Linear to get the credentials for this plugin. The sales team can provide you with test-credentials for test purposes.', 'linear'); ?>
				<br><a class="button" href="https://linear.fi/"><?php echo __('Sign up to Linear', 'linear'); ?></a>

				<br><br><strong>
				<?php echo __('Permalink settings', 'linear'); ?>
				</strong><br>
				<?php echo __('Make sure your permalink settings are set to be "article name".', 'linear'); ?>

				<br><br><strong>
					<?php echo __('Listings page', 'linear'); ?>
				</strong><br>
				<?php echo __('Upon activating this plugin, we have created a listings page for you. If you want a sub-page to be the page for listings, you can change this in Linear settings.', 'linear'); ?>
			<p>

			<br>
			<h2><?php echo __('Plugin adjustments', 'linear') ?></h2>

			<p>
				<strong>
				<?php echo __('Rentals, Business premises and Buy commissions', 'linear'); ?>
				</strong><br>
				<?php echo __('These are not set automatically but can be set manually. E.g. to have a separate rentals page you need first to create a new Page (lets call it "Rentals"). 
				Once that is done, you can choose in the Linear plugin settings the page that should be used for rentals, and you choose the page you just created. ', 'linear'); ?>

				<br><br><strong>
				<?php echo __('Page width', 'linear'); ?>
				</strong><br>
				<?php echo __('If it looks like the Listing pages are too thin compared to he rest of the page, you can try changing the used template on the page with the issue. 
				Linear plugin provides a plain and wide-enough template for your use called "linear-plain". You can also use any other page-template that outputs WordPress native content.', 'linear'); ?>
			<p>

			<br>
			<h2><?php echo __('Troubleshooting', 'linear') ?></h2>
			
			<strong>
				<?php echo '<p>' . __('The listings are not visible', 'linear') . '</p>'; ?>
			</strong>
			<?php echo '<p>' . __('There are several reasons why this could happen, please check these steps in order and you should get the listings visible:', 'linear') . '</p>'; ?>
			<?php echo '
				<div class="linear__advanced__section" style="margin-top: 0; margin-bottom: 0;">
					<strong style="display: block;">' . __( 'Are the credentials right?','linear' ) . '</strong>
					<span>' . __( 'Please make sure that the API-URL and API-Key are set as instructed and that you\'ve agreed to the terms and conditions.','linear' ) . '</span>
					<br><br>
					<strong style="display: block;">' . __( 'Do you have a set listings page?','linear' ) . '</strong>
					<span>' . __( 'You need to have a set listings page to be able to get the listings visible. If your site has multiple languages, you need to set the listings page on each language that you wish the listings to work on. The listings can be included via e.g. shortcodes or gutenberg blocks, but it\'s still necessary to have a dedicated listings page for single listings.','linear' ) . '</span>
					<br><br>
					<strong style="display: block;">' . __( 'The listings-page is set but I can\'t see any listings?','linear' ) . '</strong>
					<span>' . __( 'Depending on the WordPress theme that you use, your theme might have a non-standard built page-template. The Linear WordPress plugin provides a simple page-template (linear-plain) for your use in cases like this where the theme might not support native WordPress content hooks. Please try changing the page-template of the listings page to "linear-plain".','linear' ) . '</span>
					<br>
				</div>
			'; ?>

			<br>
			<strong>
				<?php echo '<p>' . __('A single listing is not showing', 'linear') . '</p>'; ?>
			</strong>
			<?php echo '
				<div class="linear__advanced__section" style="margin-top: 0; margin-bottom: 0;">
					<strong style="display: block;">' . __( 'Re-save permalink settings','linear' ) . '</strong>
					<span>' . __( 'Each single listing has their own dynamic page, but the logic for this to work requires that the permalinks are saved. Please save the permalinks without doing any changes to the permalink settings.','linear' ) . '</span>
					<br><br>
					<strong style="display: block;">' . __( 'What permalink settings are you using?','linear' ) . '</strong>
					<span>' . __( 'Please make sure that you only use pretty-permalinks as other permalink formats might cause issues with the Linear plugin.','linear' ) . '</span>
					<br>
				</div>
			'; ?>
		
			<br>
			<strong>
				<?php echo '<p>' . __('Other issues', 'linear') . '</p>'; ?>
			</strong>
			<?php echo __('In case of other issues, please be in touch with your person at Linear and we\'ll do our best to resolve your issue.', 'linear'); ?>
			
		</div>
		<?php
	}

	/**
	 * Create advanced usage page
	 */
	public function create_admin_guide() { 
		?>
		<div class="wrap">
			<h1><?php _e( 'Advanced usage', 'linear' ); ?></h1>
			
			<?php _e( 'The Linear plugin contains quite a bit of logic under the hood and provides ease of access to its features.', 'linear' ); ?>

			<ul>
				<li>
					<a href="#shortcode"><?php _e( 'Shortcodes', 'linear' ); ?></a>
					<ul style="list-style: initial; margin-left: 20px;">
						<li style="margin-bottom: 0px;"><a href="#listings"><?php _e('Listings', 'linear'); ?></a></li>
						<li style="margin-bottom: 0px;"><a href="#buy-commissions"><?php _e('Buy commissions', 'linear'); ?></a></li>
					</ul>
				</li>
				<li><a href="#restapi"><?php _e( 'REST-API', 'linear' ); ?></a></li>
				<li><a href="#custom-css"><?php _e('Custom CSS', 'linear'); ?></a></li>
				<li><a href="#content-hooks"><?php _e('Content Hooks', 'linear'); ?></a></li>
			</ul>

			<?php
				// Main guides
				echo $this->shortcode_guide( "shortcode" );
				echo $this->rest_api_guide( "restapi" );
				echo $this->custom_css_guide( "custom-css" );
				echo $this->content_hooks_guide( "content-hooks" );
			?>

		</div>
		<?php
	}

	public function create_debug_page(){
		global $pagenow, $typenow;

		?>
		<div class="wrap">
			<h2><?php esc_html_e( 'Debug (experimental)', 'linear' ); ?></h2>
			<p><?php echo __('This page is experimental and is meant to help debug certain issues with the plugin.', 'linear') ?></p>
			<?php settings_errors(); ?>

			<?php /*
			<form method="post" action="options.php">
				<?php
					settings_fields( 'option_group' );
					do_settings_sections( 'linear-settings-debug' );
					submit_button();
				?>
			</form>
			*/ ?>

			<?php
				echo "<br>";
				echo '<h2>' . __( 'Reset Linear transients','linear' ) . '</h2>';
				echo '<p>' . __( 'Sometimes the listings data isnt updated. This might be due to some caching plugins. As a temporary solution, reseting the plugin transient cache might resolve the issue for the moment.','linear' ) . '</p>';
				echo '<a href="' . admin_url( $pagenow . '?page=linear-debug' ) . '&linear_transient_cache_reset=1' . '" class="button" name="my_plugin_reset_cache">' . __( 'Reset Transient Cache', 'linear' ) . '</a>';
			?>
		</div>
		<?php
	}

	/**
	 * Create advin feedback
	 */
	public function create_admin_feedback() { 
		?>
		<div class="wrap">
			<h1><?php _e( 'Feedback and suggestions', 'linear' ); ?></h1>
			
			<?php echo('
				<p>' . __('If you feel like there is some feature/filter missing or something should be different, please let us know as we actively try to develop this plugin and are open for suggestions and improvements.', 'linear') . '</p>
				<p>' . __('You can share your thoughts via email to <a href="mailto:it@linear.fi">it@linear.fi</a>', 'linear') . '</p>
			'); ?>

		</div>
		<?php
	}

	/**
	 * Add and register settings sections and fields.
	 */
	public function page_init() {
		global $TRP_LANGUAGE;
		$section_key = 'setting_section';
		$languages = self::$linear->get_languages();
		$admin_language = self::$linear->get_admin_language();
		$linear_settings = get_option( 'linear_settings' );

		$contact_method = '';
		if ( ! empty( $linear_settings ) ) {
			if( isset( $linear_settings['contact_method'] ) ){
				$contact_method = $linear_settings['contact_method'];
			}
		}
		
		register_setting(
			'option_group',
			'linear_settings',
			array( $this, 'sanitize' )
		);

		/*
		add_settings_section(
			$section_key,
			__( 'Reset Linear cache', 'linear' ),
			array( $this, 'reset_linear_cache' ),
			'linear-settings-admin'
		);
		*/

		add_settings_section(
			$section_key,
			__( 'Settings', 'linear' ),
			array( $this, 'section_info' ),
			'linear-settings-admin'
		);

		add_settings_field(
			'company_id',
			__( 'Company ID', 'linear' )  . ' <span style="color:red">*</span>',
			array( $this, 'company_id_callback' ),
			'linear-settings-admin',
			$section_key
		);

		add_settings_field(
			'api_url',
			__( 'Listings API URL', 'linear' ) . ' <span style="color:red">*</span>',
			array( $this, 'listings_api_url_callback' ),
			'linear-settings-admin',
			$section_key
		);

		/*
		add_settings_field(
			'contact_api_url',
			__( 'Contact API URL', 'linear' ),
			array( $this, 'contact_api_url_callback' ),
			'linear-settings-admin',
			$section_key,
			array()
		);
		*/

		add_settings_field(
			'api_key',
			__( 'API key', 'linear' ) . ' <span style="color: red;">*</span>',
			array( $this, 'api_key_callback' ),
			'linear-settings-admin',
			$section_key
		);

		add_settings_field(
			'terms_of_service',
			__( 'Terms of service', 'linear' ) . ' <span style="color:red">*</span>',
			array( $this, 'terms_of_service_callback' ),
			'linear-settings-admin',
			$section_key
		);

		add_settings_field(
			'select_contact_method',
			__( 'Select contact method', 'linear' ),
			array( $this, 'select_contact_method_callback' ),
			'linear-settings-admin',
			$section_key,
			array( 'key' => 'contact_method', 'default' => 'custom_url', 'class' => 'js-linear-contact-method' )
		);

		add_settings_field(
			'contact_url',
			__( 'Contact request URL', 'linear' ),
			array( $this, 'contact_url_callback' ),
			'linear-settings-admin',
			$section_key,
			array( 'class' => 'js-linear-setting-contact-form js-linear-custom ' . ( $contact_method === 'custom_url' ? '' : 'js-linear-hidden') )
		);

		add_settings_field(
			'contact_form_embed',
			__( 'Linear listing contact form embed', 'linear' ),
			array( $this, 'set_contant_form_embed' ),
			'linear-settings-admin',
			$section_key,
			array( 'class' => 'js-linear-setting-contact-form js-linear-form ' . ( $contact_method === 'form' ? '' : 'js-linear-hidden') )
		);

		/*
		add_settings_field(
			'contact_url',
			__( 'Contact request URL', 'linear' ),
			array( $this, 'contact_url_callback' ),
			'linear-settings-admin',
			$section_key
		);
		*/

		add_settings_field(
			'company_logo',
			__( 'Company Logo', 'linear' ),
			array( $this, 'company_logo_callback' ),
			'linear-settings-admin',
			$section_key
		);

		add_settings_field(
			'theme_fonts',
			__( 'Use theme fonts', 'linear' ),
			array( $this, 'theme_fonts_callback' ),
			'linear-settings-admin',
			$section_key
		);
		
		add_settings_field(
			'loan_request',
			__( 'Hide loan request button', 'linear' ),
			array( $this, 'loan_request_callback' ),
			'linear-settings-admin',
			$section_key
		);

		/*
		add_settings_field(
			'leadoo_bot',
			__( 'Use Leadoo InPageBot', 'linear' ),
			array( $this, 'leadoo_bot_callback' ),
			'linear-settings-admin',
			$section_key
		);
		*/

		if( $languages && is_array($languages) && count( $languages ) > 1 ){
			foreach( $languages as $lang ){
				add_settings_field(
					'search_options_' . $lang,
					__( 'Default search options', 'linear' ) . ' - ' . $lang,
					array( $this, 'search_options_callback' ),
					'linear-settings-admin',
					$section_key,
					array( 'lang' => $lang, 'class' => 'search_options_' . $lang )
				);
			}
		} else {
			add_settings_field(
				'search_options',
				__( 'Default search options', 'linear' ),
				array( $this, 'search_options_callback' ),
				'linear-settings-admin',
				$section_key
			);
		}

		foreach ( array(
			array( 
				'id' => 'primary_color',
				'title' => __( 'Primary color', 'linear' ),
				'default_color' => '#1890ff'
			),
			array(
				'id' => 'solid_color',
				'title' => __( 'Solid color', 'linear' ),
				'default_color' => '#096dd9'
			),
			array(
				'id' => 'outline_color',
				'title' => __( 'Outline color', 'linear' ),
				'default_color' => '#0050b3'
			)
		) as $props ) {
			add_settings_field(
				$props['id'],
				$props['title'],
				array( $this, 'select_color_callback' ),
				'linear-settings-admin',
				$section_key,
				$props
			);
		}

		add_settings_field(
			'select_listing_columns',
			__( 'Listing column count', 'linear' ),
			array( $this, 'select_listing_columns_callback' ),
			'linear-settings-admin',
			$section_key,
			array( 'key' => 'listing_columns', 'default' => 2 )
		);

		add_settings_field(
			'select_render_method',
			__( 'Select render method', 'linear' ),
			array( $this, 'select_render_method_callback' ),
			'linear-settings-admin',
			$section_key,
			array( 'key' => 'render_method', 'default' => 'content' )
		);

		add_settings_field(
			'range_slider_price_lower',
			__( 'Price range-slider lower limit', 'linear' ),
			array( $this, 'range_option_callback' ),
			'linear-settings-admin',
			$section_key,
			array( 'key' => 'price_lower', 'default' => '20000', 'post_str' => '€' )
		);

		add_settings_field(
			'range_slider_price_upper',
			__( 'Price range-slider upper limit', 'linear' ),
			array( $this, 'range_option_callback' ),
			'linear-settings-admin',
			$section_key,
			array( 'key' => 'price_upper', 'default' => '750000', 'post_str' => '€' )
		);

		add_settings_field(
			'range_slider_rent_lower',
			__( 'Rent range-slider lower limit', 'linear' ),
			array( $this, 'range_option_callback' ),
			'linear-settings-admin',
			$section_key,
			array( 'key' => 'rent_lower', 'default' => '0', 'post_str' => '€' )
		);

		add_settings_field(
			'range_slider_rent_upper',
			__( 'Rent range-slider upper limit', 'linear' ),
			array( $this, 'range_option_callback' ),
			'linear-settings-admin',
			$section_key,
			array( 'key' => 'rent_upper', 'default' => '2500', 'post_str' => '€' )
		);

		add_settings_field(
			'range_slider_area_lower',
			__( 'Area range-slider lower limit', 'linear' ),
			array( $this, 'range_option_callback' ),
			'linear-settings-admin',
			$section_key,
			array( 'key' => 'area_lower', 'default' => '0', 'post_str' => 'm<sup>2</sup>' )
		);

		add_settings_field(
			'range_slider_area_upper',
			__( 'Area range-slider upper limit', 'linear' ),
			array( $this, 'range_option_callback' ),
			'linear-settings-admin',
			$section_key,
			array( 'key' => 'area_upper', 'default' => '500', 'post_str' => 'm<sup>2</sup>' )
		);

		if( $languages && is_array($languages) && count( $languages ) > 1 ){
			foreach( $languages as $lang ){

				$required = ' <span style="color:red">*</span>';
				if( !$admin_language && isset( $TRP_LANGUAGE ) ){
					if( $TRP_LANGUAGE !== $lang ){
						$required = '';
					}
				}

				add_settings_field(
					'listings_page_' . $lang,
					__( 'Listing page', 'linear' ) . ' - ' . $lang . $required,
					array( $this, 'select_page_callback' ),
					'linear-settings-admin',
					$section_key,
					array( 'id' => 'listings_page', 'lang' => $lang, 'class' => 'listings_page_' . $lang )
				);
	
				add_settings_field(
					'rentals_page_' . $lang,
					__( 'Rentals page', 'linear' ) . ' - ' . $lang,
					array( $this, 'select_page_callback' ),
					'linear-settings-admin',
					$section_key,
					array( 'id' => 'rentals_page', 'lang' => $lang, 'class' => 'rentals_page_' . $lang )
				);
	
				add_settings_field(
					'workplace_page_' . $lang,
					__( 'Business premises page', 'linear' ) . ' - ' . $lang,
					array( $this, 'select_page_callback' ),
					'linear-settings-admin',
					$section_key,
					array( 'id' => 'workplace_page', 'lang' => $lang, 'class' => 'workplace_page_' . $lang )
				);
	
				add_settings_field(
					'buy_commissions_page_' . $lang,
					__( 'Buy commissions page', 'linear' ) . ' - ' . $lang,
					array( $this, 'select_page_callback' ),
					'linear-settings-admin',
					$section_key,
					array( 'id' => 'buy_commissions_page', 'lang' => $lang, 'class' => 'buy_commissions_page_' . $lang )
				);
			}
		} else {
			add_settings_field(
				'listings_page',
				__( 'Listing page', 'linear' ) . ' <span style="color:red">*</span>',
				array( $this, 'select_page_callback' ),
				'linear-settings-admin',
				$section_key,
				array( 'id' => 'listings_page' )
			);

			add_settings_field(
				'rentals_page',
				__( 'Rentals page', 'linear' ),
				array( $this, 'select_page_callback' ),
				'linear-settings-admin',
				$section_key,
				array( 'id' => 'rentals_page' )
			);

			add_settings_field(
				'workplace_page',
				__( 'Business premises page', 'linear' ),
				array( $this, 'select_page_callback' ),
				'linear-settings-admin',
				$section_key,
				array( 'id' => 'workplace_page' )
			);

			add_settings_field(
				'buy_commissions_page',
				__( 'Buy commissions page', 'linear' ),
				array( $this, 'select_page_callback' ),
				'linear-settings-admin',
				$section_key,
				array( 'id' => 'buy_commissions_page' )
			);
		}



		// Debug fields

		add_settings_field(
			'debug_reset_transients',
			__( 'Reset transients', 'linear' ),
			array( $this, 'reset_transients_callback' ),
			'linear-settings-debug',
			$section_key
		);
	}

	/**
	 * Returns sanitized and validated settings values.
	 * 
	 * @return array
	 */
	public function sanitize( $input ) {
		$languages = self::$linear->get_languages();

		$sanitary_values = array();
		$value = null;
		foreach ( $input as $name => $value ) {
			if( $languages && is_array($languages) && count( $languages ) > 1 ){
				foreach( $languages as $lang ){
					if ( 'search_options_' . $lang === $name ) {
						// Remove unnecessary multiple spaces
						$value = preg_replace( '/\h+/', ' ', $value );
						// Remove empty lines of text and split to array of lines
						$value = preg_split( "/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", $value );
						// Sanitize text fields
						$value = array_map( 'sanitize_text_field', $value );
					}
					
					if ( in_array( $name, array( 'listings_page_' . $lang, 'rentals_page_' . $lang, 'workplace_page_' . $lang, 'buy_commissions_page_' . $lang ), true ) && is_numeric( $value ) ) {
						$value = absint( $value );
					}
				}
			}

			if ( 'search_options' === $name ) {

				if( empty( $value ) || ( is_array($value) && $value[0] === '' ) ){
					$value = "";
				}

				// Remove unnecessary multiple spaces
				$value = preg_replace( '/\h+/', ' ', $value );
				// Remove empty lines of text and split to array of lines
				$value = preg_split( "/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", $value );
				// Sanitize text fields
				$value = array_map( 'sanitize_text_field', $value );
			}
			
			if ( in_array( $name, ['api_url', 'contact_api_url']) ) {
				$value = esc_url_raw( rtrim( strtok( $value, '?'), '/') );

				$ending = '/api';
				if (substr($value, -strlen($ending)) === $ending) {
					$value = rtrim($value, $ending);
				}
			}
			
			if ( 'contact_url' === $name ) {
				$value = esc_url_raw( $value );
			}
			
			if ( 'api_key' === $name ) {
				$value = str_replace( 'LINEAR-API-KEY ', '', $value );
			}
			
			if ( in_array( $name, array( 'outline_color', 'solid_color', 'primary_color' ), true ) ){
				$value = sanitize_hex_color( $value );
			}
			
			if ( 'theme_fonts' === $name || 'terms_of_service' === $name || 'loan_request' === $name ) {
				$value = isset( $value ) && true == $value ? 1 : 0;
			}
			
			if ( in_array( $name, array( 'listings_page', 'company_id', 'company_logo', 'rentals_page', 'workplace_page', 'buy_commissions_page' ), true ) && is_numeric( $value ) ) {
				$value = absint( $value );
			}
			
			/* if ( in_array( $name, array( 'leadoo_bot' ), true ) ) {
				$value = sanitize_text_field( $value );
			} */


			if( $value ){
				$sanitary_values[$name] = $value;
			} else {
				// Jump to next input if name is unknown and value is not sanitized.
				continue;
			}
		}

		return $sanitary_values;
	}

	public function linear_settings_pre_update_callback( $new_value, $old_value ) {
		if (! isset( $old_value['company_id'] ) || ( isset( $new_value['company_id'] ) && $new_value['company_id'] === $old_value['company_id'] ) ) {
			$company_id = self::$linear->get_option( 'company_id' );
		} else {
			$company_id = $new_value['company_id'];
			// Force refresh of leadoo_bot data if company_id changed.
			/*
			if ( isset( $old_value['leadoo_bot'] ) ) {
				unset( $old_value['leadoo_bot'] );
				$new_value['leadoo_bot'] = 1;
			}
			*/
		}

		/*
		if ( ( isset( $new_value['leadoo_bot'] ) xor isset( $old_value['leadoo_bot'] ) ) ) {
			if ( isset( $new_value['leadoo_bot'] ) ) {
				unset( $new_value['leadoo_bot'] );
				$request = self::$linear->get_option( 'api_url' ) . '/plugin/company/' . (string) $company_id;
				$request = wp_remote_get( $request );
				if ( is_wp_error( $request ) ) {
					error_log( $request->get_error_message() );
				} else {
					$request = json_decode( wp_remote_retrieve_body( $request ), true );
					if ( isset( $request['pluginLeadooUrl'] ) && ! empty( $request['pluginLeadooUrl'] ) ) {
						$new_value['leadoo_bot'] = sanitize_text_field( $request['pluginLeadooUrl'] );
					} else {
						add_settings_error( 'linear_settings', "leadoo_bot_error", esc_html( 'Leadoo InPageBot is not currently confiugred on your Linear profile.', 'linear' ), 'warning' );
					}
				}
			} else {
				unset( $new_value['leadoo_bot'] );
			}
		}
		*/

		// Each listing page must be different page, remove duplicates.
		$listing_pages = array();
		$languages = self::$linear->get_languages();

		// TranslatePress workaround, don't limit IDs
		if( in_array( 
			'translatepress-multilingual/index.php', 
			apply_filters( 'active_plugins', get_option( 'active_plugins' ) )
		) ){
			return $new_value;
		}

		if( $languages && is_array($languages) && count($languages) > 1 ){
			foreach( $languages as $lang ){
				foreach ( array( 'listings_page_' . $lang, 'rentals_page_' . $lang, 'workplace_page_' . $lang, 'buy_commissions_page_' . $lang ) as $key ) {
					if ( isset( $new_value[$key] ) && $new_value[$key] ) {
						if ( in_array( $new_value[$key], $listing_pages ) ) {
							unset( $new_value[$key] );
						} else {
							$listing_pages[] = $new_value[$key];
						}
					}
				}
			}
		} else {
			foreach ( array( 'listings_page', 'rentals_page', 'workplace_page', 'buy_commissions_page' ) as $key ) {
				if ( isset( $new_value[$key] ) && $new_value[$key] ) {
					if ( in_array( $new_value[$key], $listing_pages ) ) {
						unset( $new_value[$key] );
					} else {
						$listing_pages[] = $new_value[$key];
					}
				}
			}
		}

		return $new_value;
	}

	public function linear_settings_updated_callback( $old_value, $new_value ) {
		$flush_rewrite_rules = false;

		$languages = self::$linear->get_languages();

		// Validate if we need permalink flush
		if( $languages && is_array($languages) && count($languages) > 1 ){
			foreach( $languages as $lang ){
				foreach ( array( 'listings_page_' . $lang, 'rentals_page_' . $lang, 'workplace_page_' . $lang, 'buy_commissions_page_' . $lang ) as $key ) {
					if ( 
						( isset( $old_value[$key] ) && isset( $new_value[$key] ) && $old_value[$key] !== $new_value[$key] ) ||
						( !isset( $old_value[$key] ) && isset( $new_value[$key] ) ) ||
						( !isset( $new_value[$key] ) && isset( $old_value[$key] ) )
					) {
						$flush_rewrite_rules = true;
					}
				}
			}
		} else {
			foreach ( array( 'listings_page', 'rentals_page', 'workplace_page', 'buy_commissions_page' ) as $key ) {
				if ( isset( $old_value[$key] ) && isset( $new_value[$key] ) && $old_value[$key] !== $new_value[$key] ) {
					$flush_rewrite_rules = true;
				}
			}
		}

		if ( $flush_rewrite_rules ) {
			Linear::listings_page_rewrite_rules( false );
			Linear::buy_commissions_page_rewrite_rules( false );
			flush_rewrite_rules();
		}

		// Validate if we need to set page-templates for new set pages
		/*
		if( count($languages) > 1 ){
			foreach( $languages as $lang ){
				foreach ( array( 'listings_page_' . $lang, 'rentals_page_' . $lang, 'workplace_page_' . $lang, 'buy_commissions_page_' . $lang ) as $key ) {
					if( isset($old_value[$key]) && isset($new_value[$key]) ){
						if( $old_value[$key] !== $new_value[$key] ){
							$this->set_linear_page_template( $new_value[$key] );
						}
					} else if ( !isset($old_value[$key]) && isset($new_value[$key]) ){
						$this->set_linear_page_template( $new_value[$key] );
					}
				}
			}
		} else {
			foreach ( array( 'listings_page', 'rentals_page', 'workplace_page', 'buy_commissions_page' ) as $key ) {
				if( isset($old_value[$key]) && isset($new_value[$key]) ){
					if( $old_value[$key] !== $new_value[$key] ){
						$this->set_linear_page_template( $new_value[$key] );
					}
				} else if ( !isset($old_value[$key]) && isset($new_value[$key]) ){
					$this->set_linear_page_template( $new_value[$key] );
				}
			}
		}
		*/
	}

	/**
	 * Display info for settings section.
	 */
	public function section_info() {
		
	}

	/**
	 * Reset cache
	 */
	public function reset_transients_callback() {
		echo '<button class="button" name="my_plugin_reset_cache">Reset Cache</button>';
	}

	/**
	 * Display company_id field.
	 */
	public function company_id_callback() {
		printf(
			'<input class="regular-text" type="text" name="linear_settings[company_id]" id="company_id" value="%s" required placeholder="123">',
			esc_attr( self::$linear->get_option( 'company_id' ) )
		);
	}

	/**
	 * Display api_url field.
	 */
	public function listings_api_url_callback() {
		printf(
			'<input class="regular-text" type="url" name="linear_settings[api_url]" id="api_url" value="%s" required placeholder="https://domain.net">',
			esc_attr( self::$linear->get_option( 'api_url' ) )
		);
	}

	/**
	 * Display listings_api_url field.
	 */
	public function contact_api_url_callback() {
		$required = isset( $args['required'] ) ? $args['required'] : false;

		printf(
			'<input class="regular-text" type="url" name="linear_settings[contact_api_url]" id="contact_api_url" value="%s" ' . ($required ? 'required' : '') . ' placeholder="https://domain.net">',
			esc_attr( self::$linear->get_option( 'contact_api_url' ) )
		);
	}

	/**
	 * Display api_key field.
	 */
	public function api_key_callback() {
		printf(
			'<input class="regular-text" type="text" name="linear_settings[api_key]" id="api_key" value="%s" required placeholder="XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXX">',
			esc_attr( self::$linear->get_option( 'api_key' ) )
		);
	}

	/**
	 * Display contact_url field.
	 */
	public function contact_url_callback() {
		printf(
			'<input class="regular-text" type="url" name="linear_settings[contact_url]" id="contact_url" value="%s">',
			esc_attr( self::$linear->get_option( 'contact_url' ) )
		);
	}

	/**
	 * Display company_logo field.
	 */
	public function company_logo_callback() {
		if ( $image_url = wp_get_attachment_url( self::$linear->get_option( 'company_logo' ) ) ) :
			?>
			<button data-upload="company_logo" style="height:auto; min-height:32px; line-height:0; padding:10px" type="button" class="button media-button  select-mode-toggle-button">
				<img height="100" src="<?php echo esc_url( $image_url ); ?>" />
			</button>
			<p><a href="#" data-remove="company_logo"><?php esc_html_e( 'Remove image', 'linear' ); ?></a></p>
			<input type="hidden" name="linear_settings[company_logo]" value="<?php echo esc_attr( self::$linear->get_option( 'company_logo' ) ); ?>">
			<?php
		else :
			?>
			<button data-upload="company_logo" style="height:auto; min-height:32px; line-height:0; padding:10px" type="button" class="button media-button  select-mode-toggle-button">
				<?php esc_html_e( 'Upload image', 'linear' ); ?>
			</button>
			<p><a href="#" data-remove="company_logo" style="display:none;"><?php esc_html_e( 'Remove image', 'linear' ); ?></a></p>
			<input type="hidden" name="linear_settings[company_logo]">
			<?php
		endif;
	}

	/**
	 * Display select color fields.
	 * 
	 * @param array $args Array of arguments, must contain 'id' and 'default_color' keys with values.
	 */
	public function select_color_callback( $args ) {
		extract( $args );
		printf(
			'<input data-default-color="%2$s" type="text" name="linear_settings[%1$s]" id="%1$s" value="%3$s">',
			$id,
			$default_color,
			esc_attr( self::$linear->get_option( $id, $default_color ) )
		);
	}

	/**
	 * Display select page fields.
	 * 
	 * @param array $args Array of arguments, must contain 'id' key with value.
	 */
	public function select_page_callback( $args ) {
		global $TRP_LANGUAGE;

		extract( $args );

		$plugins = apply_filters( 'active_plugins', get_option( 'active_plugins' ) );

		$languages = self::$linear->get_languages();
		if( $languages && is_array($languages) && count( $languages ) > 1 ){
			$id = $id . '_' . $lang;
		}

		$wpml = false;
		$translatepress = false;
		if( in_array( 
			'sitepress-multilingual-cms/sitepress.php', 
			$plugins
		) ) {
			$wpml = true;
		}

		if( in_array( 
			'translatepress-multilingual/index.php',
			$plugins
		) ) {
			$translatepress = true;
		}

		// WPML pages fetch fix
		if( isset( $lang ) && $wpml ){
			do_action( 'wpml_switch_language', $lang );
		}

		ob_start();
		
			$dropdown_pages_args = [
				'selected'         	=> self::$linear->get_option( $id ),
				'name'             	=> "linear_settings[$id]",
				'show_option_none' 	=> __( 'None', 'linear' ),
				'sort_column'      	=> 'post_title'
			];

			if( isset( $lang ) ){
				$dropdown_pages_args['lang'] = $lang;
			}

			wp_dropdown_pages( $dropdown_pages_args );
		
		$renderable = ob_get_clean();

		// Reset WPML pages fetch fix
		if( isset( $lang ) && $wpml ){
			do_action( 'wpml_switch_language', null );
		}

		$admin_language = self::$linear->get_admin_language();
		$required = '';

		if( isset( $lang ) && $admin_language ){
			if( $admin_language === $lang ){
				$required = 'required';
			}
		} else {
			$required = 'required';
		}

		if( $translatepress ){
			$required = '';
			if( $lang === $TRP_LANGUAGE ){
				$required = 'required';
			}
		}

		if( str_contains($id, 'listings_page') && $required ){
			$select = '<select ' . $required . ' ';
			echo str_replace( "<select ", $select, $renderable );
		} else {
			echo $renderable;
		}
	}

	/**
	 * Display search_options field.
	 */
	public function search_options_callback( $args ) {
		extract( $args );

		$option_name = "search_options";
		$languages = self::$linear->get_languages();
		if( $languages && is_array($languages) && count( $languages ) > 1 ){
			$option_name = "search_options_" . $lang;
		}

		printf(
			'<textarea style="white-space: pre-wrap;" rows="5" name="linear_settings[' . $option_name . ']" id="' . $option_name . '">%s</textarea><p class="description" id="tagline-description">%s</p>',
			empty( self::$linear->get_option( $option_name ) ) ?  '' : esc_textarea( implode( PHP_EOL, self::$linear->get_option( $option_name ) ) ),
			__( 'Each line of text will be used as one of default search options. You can use either city names, districts or addresses, eg. "Helsinki, Espoo, Vantaa"', 'linear' )
		);
	}

	/**
	 * Display Linear contact for embed
	 */
	public function set_contant_form_embed( $args ) { // TODO
		extract( $args );

		$option_name = "contact_form_embed";

		printf(
			'<p class="" id="tagline-description">%s</p><textarea style="white-space: pre-wrap;" rows="10" name="linear_settings[' . $option_name . ']" id="' . $option_name . '">%s</textarea>',
			__( 'Add in here the Linear contact form embed script, it should automaticly work with all listings. You can get this by contacting your Linear sales representative.', 'linear' ),
			empty( self::$linear->get_option( $option_name ) ) ?  '' : esc_textarea( self::$linear->get_option( $option_name ) )
		);
	}

	/**
	 * Display theme_fonts field.
	 */
	public function theme_fonts_callback() {
		$theme_fonts = self::$linear->get_option( 'theme_fonts' );
		printf(
			'<input type="checkbox" id="theme_fonts" name="linear_settings[theme_fonts]" value="1" %s /> %s',
			checked( '1', $theme_fonts, false ),
			__( 'Check to use current WordPress theme fonts instead of Linear plugin fonts.', 'linear' )
		);
	}

	/**
	 * Display loan_request field.
	 */
	public function loan_request_callback() {
		printf(
			'<input type="checkbox" id="loan_request" name="linear_settings[loan_request]" value="1" %s />',
			checked( '1', self::$linear->get_option( 'loan_request' ), false )
		);
	}

	/**
	 * Display leadoo_bot field.
	 */
	/*
	public function leadoo_bot_callback() {
		printf(
			'<input type="checkbox" id="leadoo_bot" name="linear_settings[leadoo_bot]" value="%s" %s /> %s',
			self::$linear->get_option( 'leadoo_bot', 1 ),
			checked( true, (bool) self::$linear->get_option( 'leadoo_bot' ), false ),
			__( 'Check this to use Linear-provided Leadoo robot, if available.', 'linear' )
		);
	}
	*/

	/**
	 * Display terms_of_service field.
	 */
	public function terms_of_service_callback() {
		printf(
			'<input type="checkbox" id="terms_of_service" name="linear_settings[terms_of_service]" required value="1" %s /> %s <a target="_blank" href="%s">%s<a>.',
			checked( '1', self::$linear->get_option( 'terms_of_service' ), false ),
			__( 'Before using Linear service you need to accept our', 'linear' ),
			esc_url( 'https://linear.fi/wp-content/uploads/2023/05/Linear-Yleiset-Toimitusehdot_220523-1.pdf' ),
			__( 'terms of service', 'linear' )
		);
	}

	/**
	 * Display range-field setting.
	 */
	public function range_option_callback( $args ) {
		extract( $args );

		$db_val = self::$linear->get_option( $key );

		printf(
			'<input style="max-width: 150px;" class="regular-text" type="text" name="linear_settings[' . $key . ']" id="' . $key . '" value="%s" required><span>' . $post_str . '</span>',
			($db_val ? esc_attr( self::$linear->get_option( $key ) ) : $default )
		);
	}

	/**
	 * Display listing column count
	 */
	public function select_listing_columns_callback( $args ) {
		extract( $args );

		$db_val = intval( self::$linear->get_option( $key ) );
		if( !$db_val ){
			$db_val = $default;
		}

		$values = [1,2,3,4];

		echo '<select name="linear_settings[' . $key . ']">';
			foreach( $values as $value ){
				echo '<option ' . selected($db_val, $value) . ' value="' . $value . '">' . $value . '</option>';
			}
		echo '</select>';
	}

	/**
	 * Select contact method
	 */
	public function select_contact_method_callback( $args ) {
		extract( $args );

		$db_val = self::$linear->get_option( $key );

		if( !$db_val ){
			$db_val = $default;
		}

		echo __('Choose wether you want to use in the single listings the Linear contact form (Requires contact API URL), a custom "Contact us" button URL or nothing. You can also add something else onto this section with hooks.', 'linear');
		echo "<br><br>";
		echo '<select name="linear_settings[' . $key . ']">';
			echo '<option ' . selected($db_val, "custom_url") . ' value="custom_url">' . __('Custom "Contact us" button URL', 'linear') . '</option>';
			echo '<option ' . selected($db_val, "form") . ' value="form">' . __('Linear embed form', 'linear') . '</option>';
			echo '<option ' . selected($db_val, "none") . ' value="none">' . __('None', 'linear') . '</option>';
		echo '</select>';
	}

	/**
	 * Select rendering method
	 */
	public function select_render_method_callback( $args ) {
		extract( $args );

		$db_val = self::$linear->get_option( $key );

		if( !$db_val ){
			$db_val = $default;
		}

		echo __('"Content" renders content via "the_content" hook. If you want to have more controll on where the Linear elements are rendered, you can replace the "Content" rendering method with manually inserted shortcode, hence the option "Shortcode".', 'linear');
		echo "<br><br>";
		echo '<select name="linear_settings[' . $key . ']">';
			echo '<option ' . selected($db_val, "content") . ' value="content">' . __('Content population (Default)', 'linear') . '</option>';
			echo '<option ' . selected($db_val, "shortcode") . ' value="shortcode">' . __('Shortcode', 'linear') . '</option>';
		echo '</select>';

		//if( $db_val === 'shortcode' ){
echo('<pre><code class="language-html" style="display: inline-block;">'
. __('If you use the shortcode-setting, please add this to your page/template', 'linear') . '

[linear_listings_content]


' . __('For buy commissions, please add this to your page/template', 'linear') . '

[linear_buy_commission_content]
</code></pre>');
		//}
	}

	/**
	 * Display shortcode usage
	 */
	public function shortcode_guide( $anchor_id = '' ) {
		echo('
			<div class="linear__advanced__section" id="' . $anchor_id . '">
				<h2>' . __( 'Shortcodes', 'linear' ) . '</h2>
				<div>' .
				__('You can add listings and buy commissions according to your own liking wherever you want on the site with the help of shortcodes.', 'linear') . 
				' ' . __('This is handy for sites which are not using the Gutenberg editor as the various listings can be also utilized with Shortcodes.', 'linear') . 
				' ' . __('The shortcodes support some additional settings which affect how the listing is displayed.', 'linear') . '</div>
				<br>
				
				<div class="linear__advanced__shortcode__section">
					<h2 id="listings">' . __( 'Listings', 'linear' ) . '</h2>
					<p><strong>' . __('Simple listing example:', 'linear') . '</strong></p>
					<pre><code class="language-html">
[linear_block_listings]
					</code></pre>
				</div>

				<div class="linear__advanced__shortcode__section">
					<p><strong>' . __('Full listing example:', 'linear') . '</strong></p>
					<pre><code class="language-html">
[linear_block_listings
	type="all"
	per_page="4"
	loadmore="true"
	classes=""
	filters="true"
	range_sliders="true"
	order_by="true"

	commission_type="true"
	search="true"
	product_group="true"
	room_count="true"
	listing_type="true"
	specifications="true"
	business_listing_type="true"
	price_range="true"
	rent_range="true"
	area_range="false"

	filter_commission_type="sell"
	filter_search="helsinki"
	filter_product_group="plot"
	filter_listing_type="rowhouse"

	filter_realtors=""

	price_range_lower="20000"
	price_range_upper="750000"
	rent_range_lower="0"
	rent_range_upper="1500"
	area_range_lower="0"
	area_range_upper="500"
]
					</code></pre>
					<br><br>
					<p><strong>' . __('Supported arguments:', 'linear') . '</strong></p>
					<ol>
						<li>
							<p><strong>' . __('Type', 'linear') . ' (type)</strong></p>
							<p>' . __('The type indicates if we want to show apartment, rentals or business premises. Default is "all"', 'linear') . '</p>
							<p>' . __('Acceptable values:', 'linear') . ' <code>"all", "apartments", "rent_apartments", "business_premises"</code></p>
						</li>

						<li>
							<p><strong>' . __('Per page', 'linear') . ' (per_page)</strong></p>
							<p>' . __('The per page indicates how many listings we want to show, and how many more are shown upon "Load more"-button click. Default is "8"', 'linear') . '</p>
							<p>' . __('Accpeting positive numeric values:', 'linear') . ' <code>' . __('*Any positive number from 1-999*', 'linear') . '</code></p>
						</li>

						<li>
							<p><strong>' . __('Load more', 'linear') . ' (loadmore)</strong></p>
							<p>' . __('The options to enable/disable the "Load more"-button. Default is "true"', 'linear') . '</p>
							<p>' . __('Acceptable values:', 'linear') . ' <code>"true", "false"</code></p>
						</li>

						<li>
							<p><strong>' . __('Classes', 'linear') . ' (classes)</strong></p>
							<p>' . __('Add one or several CSS classes to add to the block. Default is ""', 'linear') . '</p>
							<p>' . __('Accepting string value:', 'linear') . ' - ' . __('eg.', 'linear') . ' <code>"class_name_one class_name_two"</code></p>
						</li>

						<li>
							<p><strong>' . __('Filters', 'linear') . ' (filters)</strong></p>
							<p>' . __('You can chose if you want to show or hide the filters for the listing. Default is "true"', 'linear') . '</p>
							<p>' . __('Acceptable values:', 'linear') . ' <code>"true", "false"</code></p>
						</li>

						<li>
							<p><strong>' . __('Range sliders', 'linear') . ' (range_sliders)</strong></p>
							<p>' . __('Some filterings support range sliders for eg. price or rent. You can decide if you want to show these or not. Requires that filters are used! Default is "true"', 'linear') . '</p>
							<p>' . __('Acceptable values:', 'linear') . ' <code>"true", "false"</code></p>
						</li>

						<li>
							<p><strong>' . __('Orderby', 'linear') . ' (order_by)</strong></p>
							<p>' . __('You can chose if you want ordering option displayed. Default is true aka. "true"', 'linear') . '</p>
							<p>' . __('Acceptable values:', 'linear') . ' <code>"true", "false"</code></p>
						</li>

						<li>
							<p><strong>' . __('Show commission type filter', 'linear') . ' (commission_type)</strong></p>
							<p>' . __('You can choose if you want the commission type filter to be shown or not. Default is true aka. "true"', 'linear') . '</p>
							<p>' . __('Acceptable values:', 'linear') . ' <code>"true", "false"</code></p>
						</li>

						<li>
							<p><strong>' . __('Show search filter', 'linear') . ' (search)</strong></p>
							<p>' . __('You can decide if you want to hide or show the search-field. Default is "true" meaning it is shown.', 'linear') . '</p>
							<p>' . __('Acceptable values:', 'linear') . ' <code>"true", "false"</code></p>
						</li>

						<li>
							<p><strong>' . __('Show product group filter', 'linear') . ' (product_group)</strong></p>
							<p>' . __('You can decide if you want to hide or show the product group filter. Default is "true" meaning it is shown.', 'linear') . '</p>
							<p>' . __('Acceptable values:', 'linear') . ' <code>"true", "false"</code></p>
						</li>

						<li>
							<p><strong>' . __('Show room count filter', 'linear') . ' (room_count)</strong></p>
							<p>' . __('You can decide if you want to hide or show the room count filter. Default is "true" meaning it is shown.', 'linear') . '</p>
							<p>' . __('Acceptable values:', 'linear') . ' <code>"true", "false"</code></p>
						</li>

						<li>
							<p><strong>' . __('Show listing type filter', 'linear') . ' (listing_type)</strong></p>
							<p>' . __('You can decide if you want to hide or show the listing type filter. Default is "true" meaning it is shown.', 'linear') . '</p>
							<p>' . __('Acceptable values:', 'linear') . ' <code>"true", "false"</code></p>
						</li>

						<li>
							<p><strong>' . __('Show specifications filter', 'linear') . ' (specifications)</strong></p>
							<p>' . __('You can decide if you want to hide or show the specifications filter. This includes eg. "sauna" and "elevator". Default is "true" meaning it is shown.', 'linear') . '</p>
							<p>' . __('Acceptable values:', 'linear') . ' <code>"true", "false"</code></p>
						</li>

						<li>
							<p><strong>' . __('Show business listing type filter', 'linear') . ' (business_listing_type)</strong></p>
							<p>' . __('You can decide if you want to hide or show the business listing type filter. Default is "true" meaning it is shown.', 'linear') . '</p>
							<p>' . __('Acceptable values:', 'linear') . ' <code>"true", "false"</code></p>
						</li>

						<li>
							<p><strong>' . __('Show price range filter', 'linear') . ' (price_range)</strong></p>
							<p>' . __('You can decide if you want to hide or show the rent range filter. Default is "true" meaning it is shown.', 'linear') . '</p>
							<p>' . __('Acceptable values:', 'linear') . ' <code>"true", "false"</code></p>
						</li>

						<li>
							<p><strong>' . __('Show area range filter', 'linear') . ' (area_range)</strong></p>
							<p>' . __('You can decide if you want to hide or show the area range filter. Default is "false" meaning it is shown.', 'linear') . '</p>
							<p>' . __('Acceptable values:', 'linear') . ' <code>"true", "false"</code></p>
						</li>

						<li>
							<p><strong>' . __('Show rent range filter', 'linear') . ' (rent_range)</strong></p>
							<p>' . __('You can decide if you want to hide or show the price range filter. Default is "true" meaning it is shown.', 'linear') . '</p>
							<p>' . __('Acceptable values:', 'linear') . ' <code>"true", "false"</code></p>
						</li>

						<li>
							<p><strong>' . __('Commission type filter value', 'linear') . ' (filter_commission_type)</strong></p>
							<p>' . __('You can pre-set the commission type filter value. Default is none aka. "all"', 'linear') . '</p>
							<p>' . __('Acceptable values:', 'linear') . ' <code>"all", "sell", "rent"</code></p>
						</li>

						<li>
							<p><strong>' . __('Search filter value', 'linear') . ' (filter_search)</strong></p>
							<p>' . __('You can pre-set the search filter value. This is handy if you want to limit the listings shown on a page with a keyword e.g. a city name or area. Default is empty aka. ""', 'linear') . '</p>
							<p>' . __('Acceptable values:', 'linear') . ' <code>' . __('*Any searchterms you want to use separate with a space*', 'linear') . '</code></p>
						</li>

						<li>
							<p><strong>' . __('Product group filter value', 'linear') . ' (filter_product_group)</strong></p>
							<p>' . __('You can pre-set the product group filter value. Default is empty aka. ""', 'linear') . '</p>
							<p>' . __('Acceptable values:', 'linear') . ' <code>"apartments", "plots", "farms", "garages"</code></p>
						</li>

						<li>
							<p><strong>' . __('Listing type filter value', 'linear') . ' (filter_listing_type)</strong></p>
							<p>' . __('You can pre-set the listing type filter value. Default is empty aka. ""', 'linear') . '</p>
							<p>' . __('Acceptable values:', 'linear') . ' <code>"flat", "rowhouse", "pairhouse", "detachedhouse"</code></p>
						</li>

						<li>
							<p><strong>' . __('Realtors filterer', 'linear') . ' (filter_realtors)</strong></p>
							<p>' . __('You can pre-set the realtors to filter listings depending on the realtors. Default is empty aka. ""', 'linear') . '</p>
							<p>' . __('Acceptable values:', 'linear') . ' <code>' . __('*Any emails you want to use limit listings to separated by a space*', 'linear') . '</code></p>
							<p>' . __('Example:', 'linear') . ' <code>"realtor-one@agency.com realtor-two@anotheragency.org"</code></p>
						</li>

						<li>
							<p><strong>' . __('Price range filter lower limit', 'linear') . ' (price_range_lower)</strong></p>
							<p>' . __('You can define the lowest value for the price range filter. Default is what is set in Linear plugin settings and it can be overridden with this.', 'linear') . '</p>
							<p>' . __('Acceptable values:', 'linear') . ' <code>' . __('*Any reasonable number* eg. "20000"', 'linear') . '</code></p>
						</li>

						<li>
							<p><strong>' . __('Price range filter upper limit', 'linear') . ' (price_range_upper)</strong></p>
							<p>' . __('You can define the highest value for the price range filter. Default is what is set in Linear plugin settings and it can be overridden with this.', 'linear') . '</p>
							<p>' . __('Acceptable values:', 'linear') . ' <code>' . __('*Any reasonable number* eg. "750000"', 'linear') . '</code></p>
						</li>

						<li>
							<p><strong>' . __('Rent range filter lower limit', 'linear') . ' (rent_range_lower)</strong></p>
							<p>' . __('You can define the lowest value for the rent range filter. Default is what is set in Linear plugin settings and it can be overridden with this.', 'linear') . '</p>
							<p>' . __('Acceptable values:', 'linear') . ' <code>' . __('*Any reasonable number* eg. "0"', 'linear') . '</code></p>
						</li>

						<li>
							<p><strong>' . __('Rent range filter upper limit', 'linear') . ' (rent_range_upper)</strong></p>
							<p>' . __('You can define the highest value for the rent range filter. Default is what is set in Linear plugin settings and it can be overridden with this.', 'linear') . '</p>
							<p>' . __('Acceptable values:', 'linear') . ' <code>' . __('*Any reasonable number* eg. "1500"', 'linear') . '</code></p>
						</li>

						<li>
							<p><strong>' . __('Area range filter lower limit', 'linear') . ' (area_range_lower)</strong></p>
							<p>' . __('You can define the lowest value for the area range filter. Default is what is set in Linear plugin settings and it can be overridden with this.', 'linear') . '</p>
							<p>' . __('Acceptable values:', 'linear') . ' <code>' . __('*Any reasonable number* eg. "0"', 'linear') . '</code></p>
						</li>

						<li>
							<p><strong>' . __('Rent range filter upper limit', 'linear') . ' (area_range_upper)</strong></p>
							<p>' . __('You can define the highest value for the area range filter. Default is what is set in Linear plugin settings and it can be overridden with this.', 'linear') . '</p>
							<p>' . __('Acceptable values:', 'linear') . ' <code>' . __('*Any reasonable number* eg. "500"', 'linear') . '</code></p>
						</li>
					</ol>
				</div>
			</div>
			
			<div class="linear__advanced__section">
				<div class="linear__advanced__shortcode__section">
					<h2 id="buy-commissions">' . __( 'Buy commissions', 'linear' ) . '</h2>
					<p><strong>' . __('Simple buy commissions example:', 'linear') . '</strong></p>
					<pre><code class="language-html">
[linear_block_buy_commissions]
					</code></pre>
				</div>

				<div class="linear__advanced__shortcode__section">
					<p><strong>' . __('Advanced buy commissions example:', 'linear') . '</strong></p>
					<pre><code class="language-html">
[linear_block_buy_commissions 
	per_page="4" 
	loadmore="false" 
	classes="text-color-brand" 
	filters="false" 
	range_sliders="true" 
	order_by="true"

	filter_search="helsinki"
]
					</code></pre>
					<br><br>
					<p><strong>' . __('Supported arguments:', 'linear') . '</strong></p>
					<ol>

						<li>
							<p><strong>' . __('Per page', 'linear') . ' (per_page)</strong></p>
							<p>' . __('The per page indicates how many buy commissions we want to show, and how many more are shown upon "Load more"-button click. Default is "8"', 'linear') . '</p>
							<p>' . __('Accpeting positive numeric values:', 'linear') . ' <code>' . __('*Any positive number from 1-999*', 'linear') . '</code></p>
						</li>

						<li>
							<p><strong>' . __('Load more', 'linear') . ' (loadmore)</strong></p>
							<p>' . __('The options to enable/disable the "Load more"-button. Default is "true"', 'linear') . '</p>
							<p>' . __('Acceptable values:', 'linear') . ' <code>"true", "false"</code></p>
						</li>

						<li>
							<p><strong>' . __('Classes', 'linear') . ' (classes)</strong></p>
							<p>' . __('Add one or several classes to add to the block. Default is ""', 'linear') . '</p>
							<p>' . __('Accepting string value:', 'linear') . ' - ' . __('eg.', 'linear') . ' <code>"class_name_one class_name_two"</code></p>
						</li>

						<li>
							<p><strong>' . __('Filters', 'linear') . ' (filters)</strong></p>
							<p>' . __('You can chose if you want to show or hide the filters for the buy commissions. Default is "true"', 'linear') . '</p>
							<p>' . __('Acceptable values:', 'linear') . ' <code>"true", "false"</code></p>
						</li>

						<li>
							<p><strong>' . __('Range sliders', 'linear') . ' (range_sliders)</strong></p>
							<p>' . __('Some filterings support range sliders for eg. price. You can decide if you want to show these or not. Requires that filters are used! Default is "true"', 'linear') . '</p>
							<p>' . __('Acceptable values:', 'linear') . ' <code>"true", "false"</code></p>
						</li>

						<li>
							<p><strong>' . __('Show search field', 'linear') . '(search)</strong></p>
							<p>' . __('You can decide if you want to hide or show the search-field. Default is "true" meaning it is shown.', 'linear') . '</p>
							<p>' . __('Acceptable values:', 'linear') . ' <code>"true", "false"</code></p>
							<p>
								<span> ' . __('eg.', 'linear') . ' </span>
							</p>
						</li>

						<li>
							<p><strong>' . __('Search field value', 'linear') . '(filter_search)</strong></p>
							<p>' . __('You can pre-set the search field value. This is handy if you want to limit the listings shown on a page with a keyword e.g. a city name or area. Default is empty aka. ""', 'linear') . '</p>
							<p>' . __('Acceptable values:', 'linear') . ' <code>' . __('*Any searchterms you want to use separate with a space*', 'linear') . '</code></p>
						</li>
					</ol>
				</div>
			</div>
		');
	}

	/**
	 * Display rest API guide
	 */
	public function rest_api_guide( $anchor_id = '' ) {
		$version = 'v' . str_replace( '.', '_', $this->api_version );
		$rest_endpoint = get_home_url() . '/wp-json/linear/' . $version . '/';
		$locale = substr( get_locale(), 0, 2 );

		echo('
			<div class="linear__advanced__section" id="' . $anchor_id . '">
				<h2>' . __('REST-API usage:', 'linear') . '</h2>
				<div>' . __('The Linear plugin exposes a few rest endpoints which allow you to fetch listings. The plugin handles everything with fetching the data and saving it in a transient so that you don\'t have to worry about that.', 'linear') . '</div>
				
				<h3>' . __('Listings:', 'linear') . '</h3>

				<div>
					<p><strong>' . __('Endpoints:', 'linear') . ' <a href="' . $rest_endpoint . '">' . $rest_endpoint . '</a></strong></p>
					<pre><code class="language-json">
{
    "namespace": "linear/' . $version . '",
    "routes": {
        "/linear/' . $version . '": {},
        "/linear/' . $version . '/listings/all": {},
        "/linear/' . $version . '/listings/apartments": {},
        "/linear/' . $version . '/listings/rentals": {},
        "/linear/' . $version . '/listings/business_premises": {},
        "/linear/' . $version . '/listing/SINGLE_LISTING_ID": {}
    }
}
					</code></pre>
				</div>

				<div>' . __('If you want you can also access the same data via WordPress filters.', 'linear') . '</div>
				
				<div>
					<p><strong>' . __('Available filters:', 'linear') . '</strong></p>
					<pre><code class="language-php">
$lang = null; // ' . __( 'Can be 2-letter language code or null, null returning site locale', 'linear' ) . '

$type = "rentals"; // ' . sprintf( __( 'Can be "%s", "%s" or "%s"', 'linear' ), 'apartments', 'rentals', 'buy_commissions' ) . '

apply_filters( "linear_listings", $lang );
apply_filters( "linear_listings_by_type", $type, $lang );
apply_filters( "linear_listing", "SINGLE_LISTING_ID", $lang );
					</code></pre>
				</div>

				<div>
					<p>' . __( 'And you can use them straight like this', 'linear' ) . ':</p>
					<pre><code class="language-php">
$listings = apply_filters( "linear_listings", "fi" );

foreach( $listings as $listing ){
    echo $listing["address"];
}
					</code></pre>
				</div>

				<div>
					<p>' . __( 'If you want to dynamically do things depending on the listing, you can get the current listing this way', 'linear' ) . ':</p>
					<pre><code class="language-php">
global $wp_query;

$linear_data_id = $wp_query->get( \'linear_data_id\', false );
					</code></pre>
				</div>


				<div>
					<p>' . __( 'And use it like this', 'linear' ) . ':</p>
					<pre><code class="language-php">
global $wp_query;

$linear_data_id = $wp_query->get( \'linear_data_id\', false );

$locale = substr( get_locale(), 0, 2 );

$single_listing = apply_filters( "linear_listing", $linear_data_id, $locale );

if( $single_listing["rawDataForFiltering"]["commissionType"] === "SELL" ){
	var_dump( "is sales listing" );
}
					</code></pre>
				</div>
				<br><br>

				<h3>' . __('Buy commissions:', 'linear') . '</h3>

				<div>
					<pre><code class="language-json">
{
    "namespace": "linear/' . $version . '",
    "routes": {
        "/linear/' . $version . '": {},
        "/linear/' . $version . '/buy-commissions/all": {},
        "/linear/' . $version . '/buy-commission/SINGLE_BUY_COMMISSION_ID": {}
    }
}
					</code></pre>
				</div>

				<div>' . __('You can also access the same data via WordPress filters.', 'linear') . '</div>
				
				<div>
					<p><strong>' . __('Available filters:', 'linear') . '</strong></p>
					<pre><code class="language-php">
apply_filters( "linear_buy_commissions_all", null )
apply_filters( "linear_buy_commission", "SINGLE_BUY_COMMISSION_ID" )
					</code></pre>
				</div>

				<div>
					<p>' . __( 'And you can use them straight like this', 'linear' ) . ':</p>
					<pre><code class="language-php">
$buy_commissions = apply_filters( "linear_buy_commissions_all", null );

foreach( $buy_commissions as $buy_commission ){
    echo $buy_commission["data"]["location"]["' . $locale . '"]["value"];
}
					</code></pre>
				</div>
			</div>
		');
	}


	/**
	 * Display custom CSS guide
	 */
	public function custom_css_guide( $anchor_id = '' ) {
		$locale = substr( get_locale(), 0, 2 );

		echo('
			<div class="linear__advanced__section" id="' . $anchor_id . '">
				<h2>' . __('Custom CSS usage:', 'linear') . '</h2>
				<div>' . __('The Linear plugin adds additional classes to several elements so that you can more easily target them', 'linear') . 
			'</div>
				
				<div>
					<p>' . __( 'Example how to target the product group filter group', 'linear' ) . ':</p>
					<pre><code class="language-css">
.linear-listings__filters__group__productGroup {
	background-color: red;
}
					</code></pre>
				</div>
				<br><br>

				<div>
					<p>' . __( 'Example how to target the a single product group filter', 'linear' ) . ':</p>
					<pre><code class="language-css">
.linear-listings__filters__productGroup__apartments {
	background-color: blue;
}
					</code></pre>
				</div>
				<br><br>

				<div>
					<p>' . __( 'Example how to target a single listing with the identifier 12345', 'linear' ) . ':</p>
					<pre><code class="language-css">
.linear-listings-12345 {
	border: 1px solid black;
}
					</code></pre>
				</div>
				<br><br>

				<p>' . __('There are also some other classes for your use. We try to not remove any CSS filters unless there is a major rework so you should be quite safe using them. Please use dev tools to investigate what potential classes you can use, those which have no set CSS-values are most likely meant as entry points for your custom styles.', 'linear') . '</p>
			</div>
		');
	}

	/**
	 * Display content hooks
	 */
	public function content_hooks_guide( $anchor_id = '' ) {
		$locale = substr( get_locale(), 0, 2 );

		echo('
			<div class="linear__advanced__section" id="' . $anchor_id . '">
				<h2>' . __('Content hooks usage:', 'linear') . '</h2>
				<div>' . __('The Linear plugin adds WordPress hooks in different parts of the content so that you can include your own custom solutions easily.', 'linear') . 
			'</div>
				
				<div>
					<p>' . __( 'Available hooks', 'linear' ) . ':</p>
					<pre><code class="language-html">
linear_listing_introduction
linear_listing_actions
linear_listing_realtor
linear_buy_commission_realtor
linear_realtor
linear_buy_commissions_introduction
linear_buy_commissions_content
linear_edit_listings
					</code></pre>
				</div>
				<br><br>

				<div>
					<p>' . __( 'And here is how you use them', 'linear' ) . ':</p>
					<pre><code class="language-php">
add_action( "linear_listing_introduction", function( $listing ){
	var_dump( $listing["id"] );
}, 10, 1 );
					</code></pre>
				</div>
				<br><br>

				<div>
					<p>' . __( 'Except "linear_edit_listings" is used like so', 'linear' ) . ':</p>
					<pre><code class="language-php">
add_filter( "linear_edit_listings", function( $listings ){

	foreach( $listings as &$listing ){
		$listing["card_title"] = $listing["card_title"] . " - this is a test";
	}

	return $listings;
}, 10, 1 );
					</code></pre>
				</div>
				<br><br>

			</div>
		');
	}

	public function admin_body_class( $classes ){

		$admin_language = self::$linear->get_admin_language();

		if( !$admin_language ){

			// translatepress workaround
			if( $languages = self::$linear->get_languages() ){
				$additional_classes = '';

				if( $languages && is_array($languages) && count( $languages ) > 1 ){
					foreach( $languages as $lang ){
						$additional_classes .= ' linear-' . $lang . ' ';
					}
				}

				return $classes . ' - ' . $additional_classes;
			}

			// fallback
			return $classes;
		}

		$classes = $classes . ' linear-' . $admin_language . ' ';

		return $classes;
	}

	/**
	 * Sets page-template for $post_id
	 */
	/*
	private function set_linear_page_template( $post_id ){
		if( !$post_id || !intval($post_id) ){
			return;
		}

		update_post_meta( intval($post_id), '_wp_page_template', 'plain-listings.php' );
	}
	*/

	public function linear_admin_debug_page_notices() {
		if (isset($_GET['linear_transient_cache_reset_success']) && $_GET['linear_transient_cache_reset_success'] == '1') {
			echo '<div class="updated"><p>' . __('Linear cache has been reset successfully.', 'linear') . '</p></div>';
		}

		if (isset($_GET['linear_transient_cache_reset_failed']) && $_GET['linear_transient_cache_reset_failed'] == '1') {
			echo '<div class="error"><p>' . __('Linear cache reset failed.', 'linear') . '</p></div>';
		}

		if (isset($_GET['linear_transient_cache_reset_empty']) && $_GET['linear_transient_cache_reset_empty'] == '1') {
			echo '<div class="notice notice-warning"><p>' . __('There was nothing to remove from the cache.', 'linear') . '</p></div>';
		}
	}

	public function linear_admin_debug_page_reset() {

		if (isset($_GET['linear_transient_cache_reset']) && $_GET['linear_transient_cache_reset'] == '1') {
			global $wpdb;

			$prefix = '_transient_linear_middleware_api_';
			
			$removable_transients = $wpdb->get_col($wpdb->prepare("
				SELECT option_name
				FROM {$wpdb->options}
				WHERE option_name LIKE %s
			", $wpdb->esc_like($prefix) . '%'));

			$transient_deletion_success = true;
			
			if( count( $removable_transients ) === 0 ){
				wp_redirect(admin_url('admin.php?page=linear-debug&linear_transient_cache_reset_empty=1'));
				exit;
			}

			// delete listings and buy commissions
			if( $removable_transients ){
				foreach ($removable_transients as $transient) {
					$transient_deletion = delete_transient( str_replace( '_transient_', '', $transient ) );
	
					if( !$transient_deletion ){
						$transient_deletion_success = false;
					}
				}
			}

			// delete fetch flags
			delete_transient( 'linear_running_listings_update' );
			delete_transient( 'linear_running_buy_commissions_update' );
	
			if( $transient_deletion_success ){
				wp_redirect(admin_url('admin.php?page=linear-debug&linear_transient_cache_reset_success=1'));
			} else {
				wp_redirect(admin_url('admin.php?page=linear-debug&linear_transient_cache_reset_failed=1'));
			}

			exit;
		}
	}
}
