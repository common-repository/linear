<?php
/**
 * Linear content template functionality
 *
 * @package Linear
 */

/**
 * Controls the use of custom templates for linear plugin content.
 */
class Linear_Templater {

	/**
	 * Core plugin class instance.
	 *
	 * @var Linear
	 */
	protected static $linear;

	/**
	 * Defines class properties.
	 */
	public function __construct() {
		if ( is_null( self::$linear ) ) {
			self::$linear = Linear::get_instance();
		}
	}

	/**
	 * Return special template for list and single listing pages.
	 * 
	 * @return string
	 */
	public function include_templates( $template ) {
		global $wp_query, $post;

		// if current page is a Linear-handled page
		$id = $wp_query->get( 'linear_data_id', false );
		if ( $id === false && isset( $_GET['linear_data_id'] ) ) {
			$id = sanitize_key( $_GET['linear_data_id'] );
		}

		if( !$id || !$post ){
			return $template;
		}

		$lang = self::$linear->get_language();
		$languages = self::$linear->get_languages();

		// if buy-commissions
		if( $languages && is_array($languages) && count($languages) === 1 ){
			if( $post->ID === intval( self::$linear->get_option( 'buy_commissions_page' ) ) ){
				$this->buy_commissions_meta_seo_handler( $id, $lang );
			}
		} else {
			if( $post->ID === intval( self::$linear->get_option( 'buy_commissions_page_' . $lang ) ) ){
				$this->buy_commissions_meta_seo_handler( $id, $lang );
			}
		}

		// if listings
		if( $languages && is_array($languages) && count($languages) === 1 ){
			if( 
				$post->ID === intval( self::$linear->get_option( 'listings_page' ) ) ||
				$post->ID === intval( self::$linear->get_option( 'rentals_page' ) ) ||
				$post->ID === intval( self::$linear->get_option( 'workplace_page' ) )
			){
				$this->listings_meta_seo_handler( $id, $lang );
			}
		} else {
			if( 
				$post->ID === intval( self::$linear->get_option( 'listings_page_' . $lang ) ) ||
				$post->ID === intval( self::$linear->get_option( 'rentals_page_' . $lang ) ) ||
				$post->ID === intval( self::$linear->get_option( 'workplace_page_' . $lang ) )
			){
				$this->listings_meta_seo_handler( $id, $lang );
			}
		}

		return $template;
	}

	/*
	 * Helper to see if we are on a single linear dynamic page in the frontend
	 */
	public function is_linear_frontend_page() {
		if( is_admin() ){
			return false;
		}

		global $wp_query, $post;

		// if current page is a Linear-handled page
		$id = $wp_query->get( 'linear_data_id', false );
		if ( $id === false && isset( $_GET['linear_data_id'] ) ) {
			$id = sanitize_key( $_GET['linear_data_id'] );
		}

		if( !$id || !$post ){
			return false;
		}

		$lang = self::$linear->get_language();
		$languages = self::$linear->get_languages();

		// if buy-commissions
		if( $languages && is_array($languages) && count($languages) === 1 ){
			if( $post->ID === intval( self::$linear->get_option( 'buy_commissions_page' ) ) ){
				return true;
			}
		} else {
			if( $post->ID === intval( self::$linear->get_option( 'buy_commissions_page_' . $lang ) ) ){
				return true;
			}
		}

		// if listings
		if( $languages && is_array($languages) && count($languages) === 1 ){
			if( 
				$post->ID === intval( self::$linear->get_option( 'listings_page' ) ) ||
				$post->ID === intval( self::$linear->get_option( 'rentals_page' ) ) ||
				$post->ID === intval( self::$linear->get_option( 'workplace_page' ) )
			){
				return true;
			}
		} else {
			if( 
				$post->ID === intval( self::$linear->get_option( 'listings_page_' . $lang ) ) ||
				$post->ID === intval( self::$linear->get_option( 'rentals_page_' . $lang ) ) ||
				$post->ID === intval( self::$linear->get_option( 'workplace_page_' . $lang ) )
			){
				return true;
			}
		}

		return false;
	}

	/**
	 * Add and enqueue necessary style for special templates.
	 */
	public function enqueue_style() {
		global $post;

	}

	public function enqueue_block_editor_assets() {
		wp_enqueue_script(
			'linear-editor', 
			LINEAR_PLUGIN_URL . Linear\Utils\get_asset( 'editor.js', 'admin' ),
			[ 'wp-blocks', 'wp-dom-ready', 'wp-edit-post' ],
			LINEAR_VERSION
		);
	}

	public function enqueue_editor_styles() {
		add_editor_style( Linear\Utils\get_asset( 'editor.css', 'admin' ) );
	}

	public function enqueue_frontend_scripts() {
		global $post;

		// wp_deregister_script('jquery');
		// wp_enqueue_script('jquery', '/wp-includes/js/jquery/jquery.min.js', [], null, false);		

		wp_enqueue_script(
			'linear-frontend',
			LINEAR_PLUGIN_URL . Linear\Utils\get_asset( 'frontend.js' ),
			[ 'wp-polyfill' ],
			LINEAR_VERSION,
			true
		);
	
		wp_enqueue_style(
			'linear-frontend',
			LINEAR_PLUGIN_URL . Linear\Utils\get_asset( 'frontend.css' ),
			[],
			LINEAR_VERSION
		);

		$current_rest_url = get_rest_url();
		$languages = apply_filters('linear_languages', '');
	
		if( $languages && is_array($languages) ){
			if( count($languages) > 1 ){
				foreach( $languages as $lang ){
					if ( strpos( $current_rest_url, $lang . '/wp-json/' ) !== false ) {
						$current_rest_url = str_replace( '/' . $lang . '/wp-json/' , '/wp-json/', $current_rest_url );
					}
				}
			}
		}

		wp_localize_script(
			'linear-frontend',
			'linearFrontend',
			[
				'restBase' => $current_rest_url,
				'version' => '1_1',
				'listingId' => $this->get_current_listing_id()
			]
		);

		// Legacy color mappings
		$primary_color = self::$linear->get_option( 'primary_color', '#1890ff' );
		$solid_color   = self::$linear->get_option( 'solid_color', '#096dd9' );
		$outline_color = self::$linear->get_option( 'outline_color', '#0050b3' );

		// Other styles related
		$columns = self::$linear->get_option( 'listing_columns', '2' );

		wp_add_inline_style(
			'linear-frontend',

			"
			:root {
				--linear-color-primary: $primary_color;
				--linear-color-primary-light: " . Linear\Templates\transform_hex_brightness( $primary_color, "38" ) . ";
				--linear-color-primary-dark: " . Linear\Templates\transform_hex_brightness( $primary_color, "-38" ) . ";

				--linear-color-solid: $solid_color;
				--linear-color-solid-light: " . Linear\Templates\transform_hex_brightness( $solid_color, "38" ) . ";
				--linear-color-solid-dark: " . Linear\Templates\transform_hex_brightness( $solid_color, "-38" ) . ";

				--linear-color-outline: $outline_color;
				--linear-color-outline-light: " . Linear\Templates\transform_hex_brightness( $outline_color, "38" ) . ";
				--linear-color-outline-dark: " . Linear\Templates\transform_hex_brightness( $outline_color, "-38" ) . ";
			}

			@media screen and (min-width: 992px){
				body div.linear-listings__container {
					grid-template-columns: repeat(" . $columns . ", 1fr);
				}
			}
			"
		);

		if( $this->is_linear_frontend_page() ){
			wp_enqueue_style('wp-block-library');
			wp_register_style( 'global-styles', false );
			wp_add_inline_style( 'global-styles', wp_get_global_stylesheet() );
			wp_enqueue_style( 'global-styles' );
		
			// Add each block as an inline css.
			wp_add_global_styles_for_blocks();
		}
	}

	public function enqueue_admin_scripts() {
		wp_enqueue_style( 'wp-color-picker' );
		
		if ( ! did_action( 'wp_enqueue_media' ) ) {
			wp_enqueue_media();
		}

		wp_enqueue_script(
			'linear-admin',
			LINEAR_PLUGIN_URL . Linear\Utils\get_asset( 'admin.js', 'admin' ),
			[ 'jquery', 'wp-color-picker' ],
			LINEAR_VERSION,
			true
		);

		wp_enqueue_style(
			'linear-admin',
			LINEAR_PLUGIN_URL . Linear\Utils\get_asset( 'admin.css', 'admin' ),
			[],
			LINEAR_VERSION
		);
	}

	/**
	 * Enqueue necessary script for special template.
	 */
	public function enqueue_script() {
		global $post;

		$languages = self::$linear->get_languages();

		// Validate
		if( $languages && is_array($languages) && count($languages) === 1 ){
			if ( 
				$post && 
				in_array( $post->ID, [
					self::$linear->get_option( 'listings_page' ),
					self::$linear->get_option( 'rentals_page' ),
					self::$linear->get_option( 'workplace_page' ),
					self::$linear->get_option( 'buy_commissions_page' )
				] ) ) {
				wp_localize_script(
					'linear_main',
					'linear_data',
					array( 
						'ajax_url'          => admin_url( 'admin-ajax.php' ),
						'loading'           => __( 'Loading more', 'linear' )
					)
				);
			}
		} else {
			foreach( $languages as $lang ){
				if ( 
					$post && 
					in_array( $post->ID, [
						self::$linear->get_option( 'listings_page_' . $lang ),
						self::$linear->get_option( 'rentals_page_' . $lang ),
						self::$linear->get_option( 'workplace_page_' . $lang ),
						self::$linear->get_option( 'buy_commissions_page_' . $lang )
					] ) ) {
					wp_localize_script(
						'linear_main',
						'linear_data',
						array( 
							'ajax_url'          => admin_url( 'admin-ajax.php' ),
							'loading'           => __( 'Loading more', 'linear' )
						)
					);
				}
			}
		}
	}

	public function include_listings_content( $content, $render_override = false ) {
		global $post, $wp_query;

		$api_version = apply_filters( 'linear_get_api_version', null );
		if( $api_version === 'v1' ){
			return '<h2>' . __( 'Error: Please check Linear plugin settings','' ) . '</h2>';
		}

		if ( !$post ) {
			return $content;
		}

		$should_render = false;
		$current_language = self::$linear->get_language();
		$languages = self::$linear->get_languages();

		// Validate
		if( count($languages) > 1 ){
			foreach( $languages as $lang ){
				if( in_array( $post->ID , [
					intval( self::$linear->get_option( 'listings_page_' . $lang ) ),
					intval( self::$linear->get_option( 'rentals_page_' . $lang ) ),
					intval( self::$linear->get_option( 'workplace_page_' . $lang ) )
				]) ){
					$should_render = true;
				}
			}
		} else {
			if( in_array( $post->ID , [
				intval( self::$linear->get_option( 'listings_page' ) ),
				intval( self::$linear->get_option( 'rentals_page' ) ),
				intval( self::$linear->get_option( 'workplace_page' ) )
			]) ){
				$should_render = true;
			}
		}

		if( !$should_render && !$render_override ){
			return $content;
		}

		$id = $wp_query->get( 'linear_data_id', false );
		if ( $id === false && isset( $_GET['linear_data_id'] ) ) {
			$id = sanitize_key( $_GET['linear_data_id'] );
		}

		// Show listing
		if ( $id === false ) {

			$perPage = 8;
			if( self::$linear->get_option( 'listing_columns' ) && intval( self::$linear->get_option( 'listing_columns' ) ) === 3 ){
				$perPage = 9;
			}

			$defaults = [
				'type' => 'all',
				'per_page' => $perPage,
				'filters' => 'true',
				'loadmore' => 'true',
				'classes' => '',
				'range_sliders' => 'true',
				'order_by' => 'true',
			];

			if ( 
				intval( self::$linear->get_option( 'rentals_page' ) ) == $post->ID ||
				intval( self::$linear->get_option( 'rentals_page_' . $current_language ) ) == $post->ID
			) {

				$defaults = array_merge( $defaults, [
					'type' => 'rent_apartments',
				]);

			} elseif ( 
				intval( self::$linear->get_option( 'workplace_page' ) ) == $post->ID ||
				intval( self::$linear->get_option( 'workplace_page_' . $current_language ) ) == $post->ID
			) {

				$defaults = array_merge( $defaults, [
					'type' => 'business_premises',
				]);

			} elseif(
				intval( self::$linear->get_option( 'listings_page' ) ) == $post->ID ||
				intval( self::$linear->get_option( 'listings_page_' . $current_language ) ) == $post->ID
			) {

				$defaults = array_merge( $defaults, [
					'type' => 'apartments',
				]);

			}

			ob_start();

				echo do_shortcode('[linear_block_listings 
					type="' . $defaults['type'] . '" 
					per_page="' . $defaults['per_page'] . '" 
					loadmore="' . $defaults['loadmore'] . '" 
					classes="' . $defaults['classes'] . '" 
					filters="' . $defaults['filters'] . '"
					range_sliders="' . $defaults['range_sliders'] . '"
					order_by="' . $defaults['order_by'] . '"
				]');

			$content =  $content . ob_get_clean();
		}
	
		return $content;
	}

	public function include_buy_commissions_content( $content ) {
		global $post, $wp_query;

		$api_version = apply_filters( 'linear_get_api_version', null );
		if( $api_version === 'v1' ){
			return '<h2>' . __( 'Error: Please check Linear plugin settings','' ) . '</h2>';
		}

		if ( $post ) {

			$should_render = false;
			$current_language = self::$linear->get_language();
			$languages = self::$linear->get_languages();

			// Validate
			if( $languages && is_array($languages) && count($languages) > 1 ){
				foreach( $languages as $lang ){
					if( $post->ID === intval( self::$linear->get_option( 'buy_commissions_page_' . $lang ) ) ){
						$should_render = true;
					}
				}
			} else {
				if( $post->ID === intval( self::$linear->get_option( 'buy_commissions_page' ) ) ){
					$should_render = true;
				}
			}

			// If not right template
			if( !$should_render ){
				return $content;
			}

			// Build shortcode
			$id = $wp_query->get( 'linear_data_id', false );
			if ( $id === false && isset( $_GET['linear_data_id'] ) ) {
				$id = sanitize_key( $_GET['linear_data_id'] );
			}

			if ( $id === false ) {

				if ( 
					intval( self::$linear->get_option( 'buy_commissions_page' ) ) == $post->ID ||
					intval( self::$linear->get_option( 'buy_commissions_page_' . $current_language ) ) == $post->ID
				) {

					$perPage = 8;
					if( self::$linear->get_option( 'listing_columns' ) && intval( self::$linear->get_option( 'listing_columns' ) ) === 3 ){
						$perPage = 9;
					}

					$defaults = [
						'per_page' => $perPage,
						'filters' => 'true',
						'loadmore' => 'true',
						'classes' => '',
						'range_sliders' => 'true',
						'order_by' => 'true',
					];

					ob_start();
					
					echo '<div class="wp-block-group linear-wp-block-group elementor-section elementor-section-boxed">';
						echo do_shortcode('[linear_block_buy_commissions 
							per_page="' . $defaults['per_page'] . '" 
							loadmore="' . $defaults['loadmore'] . '" 
							classes="' . $defaults['classes'] . '" 
							filters="' . $defaults['filters'] . '"
							range_sliders="' . $defaults['range_sliders'] . '"
							order_by="' . $defaults['order_by'] . '"
						]');
					echo '</div>';

					$content =  $content . ob_get_clean();

				}
			}
		}
	
		return $content;
	}

	// Hide the template title
	public function hide_title( $title ){
		global $post, $wp_query;

		if( is_admin() ){
			return $title;
		}

		if ( $post ) {

			$linear_page = false;
			$current_language = self::$linear->get_language();
			$languages = self::$linear->get_languages();

			// Validate
			if( $languages && is_array($languages) && count($languages) > 1 ){
				foreach( $languages as $lang ){
					if( in_array( $post->ID , [
						intval( self::$linear->get_option( 'listings_page_' . $lang ) ),
						intval( self::$linear->get_option( 'rentals_page_' . $lang ) ),
						intval( self::$linear->get_option( 'workplace_page_' . $lang ) )
					]) || intval( self::$linear->get_option( 'buy_commissions_page_' . $lang ) ) === $post->ID ){
						$linear_page = true;
					}
				}
			} else {
				if( in_array( $post->ID , [
					intval( self::$linear->get_option( 'listings_page' ) ),
					intval( self::$linear->get_option( 'rentals_page' ) ),
					intval( self::$linear->get_option( 'workplace_page' ) )
				]) || intval( self::$linear->get_option( 'buy_commissions_page' ) ) === $post->ID ){
					$linear_page = true;
				}
			}

			if( $linear_page ){
				return "";
			}
		}

		return $title;
	}

	public function include_single_content( $content ){
		global $post, $wp_query;

		$linear_data_id = $wp_query->get( 'linear_data_id', false );

		if( !$linear_data_id ){
			return $content;
		}

		$current_language = self::$linear->get_language();
		$languages = self::$linear->get_languages();

		ob_start();

			echo "<div class='wp-block-group linear-wp-block-group elementor-section elementor-section-boxed'>";

			if( $languages && is_array($languages) && count($languages) > 1 ){
				foreach( $languages as $lang ){
					if( $current_language === $lang ){
						if(
							$post->ID === intval( self::$linear->get_option( 'listings_page_' . $lang ) ) ||
							$post->ID === intval( self::$linear->get_option( 'rentals_page_' . $lang ) ) ||
							$post->ID === intval( self::$linear->get_option( 'workplace_page_' . $lang ) )
						){
							echo Linear\Templates\template_single_listing( $linear_data_id );
							continue;
						} else if( $post->ID === intval( self::$linear->get_option( 'buy_commissions_page_' . $lang ) ) ){
							echo Linear\Templates\template_single_buy_commission( $linear_data_id );
							continue;
						}
					}
				}
			} else {
				if( 
					$post->ID === intval( self::$linear->get_option( 'listings_page' ) ) ||
					$post->ID === intval( self::$linear->get_option( 'rentals_page' ) ) ||
					$post->ID === intval( self::$linear->get_option( 'workplace_page' ) )
				){
					echo Linear\Templates\template_single_listing( $linear_data_id );
				} else if( $post->ID === intval( self::$linear->get_option( 'buy_commissions_page') ) ){
					echo Linear\Templates\template_single_buy_commission( $linear_data_id );
				}
			}

		return $content . ob_get_clean();
	}

	public function maybe_use_fonts_frontend( $classes ){
		// Add additional class to body to declare if CSS should use plugin fonts
		if ( !self::$linear->get_option( 'theme_fonts' ) ) {
			$classes[] = 'use-linear-fonts';
		}

		return $classes;
	}

	public function maybe_use_fonts_admin( $classes ){
		if ( !self::$linear->get_option( 'theme_fonts' ) ) {
			$classes .= ' use-linear-fonts';
		}

		return $classes;
	}

	// Load fonts if settings demand it
	public function enqueue_global_styles(){
		global $post;

		if ( !self::$linear->get_option( 'theme_fonts' ) ) {
			wp_enqueue_style( 'linear-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap' );
		}
	}

	// Linear::get_template_path( plain-listings.php );
	public function template_creation( $page_template ){
		if ( get_page_template_slug() == 'plain-listings.php' ) {
			$page_template = Linear::get_template_path( 'plain-listings.php' );
		}
		return $page_template;
	}

	public function template_population( $post_templates, $wp_theme, $post, $post_type ){
		$post_templates['plain-listings.php'] = __('Linear plain');

		return $post_templates;
	}

	private function listings_meta_seo_handler( $id = null, $lang = null ){
		if( !$id ){
			return null;
		}

		$api_version = apply_filters( 'linear_get_api_version', null );
		if( $api_version === 'v1' ){
			return $this->null_template_handler();
		}

		$listing_data = null;
		if ( Linear::validate_listing_id( $id ) || Linear::validate_listing_short_id( $id ) ) {
			$linear_middleware = self::$linear->get_middleware();
			$listing_data = $linear_middleware->get_listing( $id, $lang );
		}

		// Check if listing exists, if not redirect to 404
		if ( empty( $listing_data ) ) {
			return $this->null_template_handler();
		}

		// Set listing data to global query
		// set_query_var('linear_listing', $listing_data );

		/**
		 * Commons things between API-versions before handling separately edge cases
		 */

		// SEO optimisation
		$title = trim( $listing_data['address'] . ( isset( $listing_data['gate'] ) ? ' ' . $listing_data['gate'] : '' ), ' ');

		// SOME-preview workaround
		$description = $title;
		$image_url = '';

		/**
		 * Handle certain data in different ways depending on which API version is used
		 */

		$price = ( isset( $listing_data['formatted_debtFreePrice'] ) ? $listing_data['formatted_debtFreePrice'] : ( isset( $listing_data['formatted_rent'] ) ? $listing_data['formatted_rent'] : '') );

		// SEO optimisation
		$title = implode(
			', ', 
			array_filter(
				array( 
					isset( $title ) ? $title : false,
					isset( $listing_data['districtFree'] ) ? $listing_data['districtFree'] : false,
					isset( $listing_data['city'] ) ? $listing_data['city'] : false,
					isset( $price ) ? $price : false
				)
			)
		);

		// Add clients sitename
		$title = $title . ' | ' . get_bloginfo( 'name' );

		// SOME-preview workaround
		if ( isset( $listing_data['districtFree'] ) && isset( $listing_data['typeOfApartment'] ) ) {
			$description .= ($listing_data['typeOfApartment'] ? ' - ' . $listing_data['typeOfApartment'] : '');
		}
		if ( isset( $listing_data['thumbnails'] ) && !empty( $listing_data['thumbnails'] ) ) {
			$image_url = $listing_data['thumbnails'][0];
		} else if( isset( $listing_data['images'] ) && !empty( $listing_data['images'] ) ){
			$image_url = $listing_data['images'][0]->compressed;
		}

		$this->handle_external_seo( $title, $description, $image_url );
		$this->handle_meta_tags( $title, $description, $image_url );
	}

	private function buy_commissions_meta_seo_handler( $id, $lang = null ){
		if( !$id ){
			return null;
		}

		if( !$lang ){
			$lang = self::$linear->get_language();
		}

		$buy_commission = null;
		if ( Linear::validate_listing_id( $id ) || Linear::validate_listing_short_id( $id ) ) {
			$linear_middleware = self::$linear->get_middleware();
			$buy_commission = $linear_middleware->get_buy_commission( $id, $lang );
		}

		// Check if buy commission exists, if not redirect to 404
		if ( empty( $buy_commission ) ) {
			return $this->null_template_handler();
		}

		$wanted_listing_type 		= $this->get_buy_commission_locale_data( $buy_commission, 'wantedListingType', $lang, 'value' );
		$location 					= $this->get_buy_commission_locale_data( $buy_commission, 'location', $lang, 'value' );
		$room_count					= $this->get_buy_commission_locale_data( $buy_commission, 'roomCount', $lang, 'value' );
		$living_area_lower_bound	= $this->get_buy_commission_locale_data( $buy_commission, 'livingAreaLowerBound', $lang, 'value' );
		$living_area_upper_bound	= $this->get_buy_commission_locale_data( $buy_commission, 'livingAreaUpperBound', $lang, 'value' );

		$title = $wanted_listing_type . 
			', ' . $location .
			' | ' . get_bloginfo( 'name' );
		$description = $wanted_listing_type . ', ' . $location . 
			( $room_count ? ', ' . $room_count . ' ' . __('rooms', 'linear') : '' );
		$image_url = LINEAR_PLUGIN_URL . 'dist/bg-dixu.png';

		// SEO for buy commissions
		$this->handle_external_seo( $title, $description, $image_url );
		$this->handle_meta_tags( $title, $description, $image_url );
	}

	private function get_buy_commission_locale_data( $data, $key, $lang = 'fi', $value = 'value' ){
		if( !$data || !$key || !$value ){
			return '';
		}

		if( isset( $data[$key] ) && isset( $data[$key][$lang] ) ){
			if( isset( $data[$key][$lang][$value] ) ){
				return $data[$key][$lang][$value];
			}
		}
	}

	private function null_template_handler(){
		global $wp_query;
		$wp_query->set_404();
		status_header( 404 );
		return Linear::get_template_path( 'linear-404.php' );	
	}

	private function handle_external_seo( $title, $description, $image_url ){

		// hacky bugfix: listing page always has no canonical URL (WP generated it wrong, which caused issues with facebook preview links)
		add_filter( 'get_canonical_url', function( $canonical_url, $post ) {
			global $wp;
			return get_home_url() . '/' . $wp->request;
		}, 10000, 200 );

		// Yoast SEO exceptions
		add_filter( 'wpseo_title', function( $yoast_title ) use ( $title ){
			return $title;
		});
		add_filter( 'wpseo_opengraph_title', function( $yoast_title ) use ( $title ){
			return $title;
		});
		add_filter( 'wpseo_canonical', function( $canonical ) {
			global $wp;
			return get_home_url() . '/' . $wp->request;
		}, 20 );
		add_filter( 'wpseo_opengraph_url', function( $canonical ) {
			global $wp;
			return get_home_url() . '/' . $wp->request;
		}, 20 );
		add_filter( 'wpseo_twitter_image', function( $image ) use ( $image_url ){
			return esc_url( $image_url );
		}, 20 );

		// The SEO Framework exceptions
		add_filter( 'the_seo_framework_title_from_custom_field', function( $tsf_title, $args ) use ( $title ) {
			return $title;
		}, 10, 2 );

		// All in one SEO Framework exception
		add_filter( 'aioseo_title', function( $aioseo_title ) use ( $title ) {
			return $title;
		});

		 // Rank Math exception
		 add_filter( 'rank_math/frontend/title', function( $rank_math_title ) use ( $title ) {
			return $title;
		});

		// Seopress exceptions
		add_filter('seopress_titles_title', function ( $html ) use ( $title ) { 
			return $title;
		});

		add_filter('seopress_social_twitter_card_thumb', function ( $html ) use ( $image_url ) {
			$html = '<meta name="twitter:image:src" content="' . $image_url . '" />'; 
			return $html;
		});

		add_filter('seopress_oembed_thumbnail', function ( $thumbnail ) use ( $image_url ) { 
			$thumbnail = ['url' => $image_url, 'width' => '1920', 'height' => '1080'];
			return $thumbnail;
		});

	add_filter('seopress_social_og_thumb', function( $html ) use ( $image_url ) {
		$html = '<meta property="og:image" content="' . $image_url . '" />'; 
		return $html;
	});

		// Squirrly SEO exception
		add_filter('sq_title', function ( $sq_title ) use ( $title ) {
			return $title;
		});
	}

	private function handle_meta_tags( $title, $description, $image_url ){

		// "$title_parts" contains " - linear-plugin" which might not be favored by some clients.
		add_filter(
			'document_title_parts',
			function ( $title_parts ) use ( $title ){
				if ( ! empty( $title ) ) {
					$title_parts['title'] = $title;
				}
				return $title_parts;
			}
		);

		add_action(
			'wp_head',
			function () use ( $title, $description, $image_url ) {
				global $wp;

				if ( ! empty( $title ) ) {
					echo '<meta property="og:title" content="' . esc_attr( $title ) . '">';
					echo '<meta name="twitter:title" content="' . esc_attr( $title ) . '">';
				}

				if ( ! empty( $description ) ) {
					echo '<meta name="description" content="' . esc_attr( $description ) . '">';
					echo '<meta property="og:description" content="' . esc_attr( $description ) . '">';
					echo '<meta name="twitter:description" content="' . esc_attr( $description ) . '">';
				}

				echo '<meta property="og:url" content="' . get_home_url() . '/' . $wp->request . '">';
				echo '<meta property="og:type" content="website">';

				if ( ! empty( $image_url ) ) {
					echo '<meta property="og:image" content="' . esc_url( $image_url ) . '">';
					echo '<meta property="twitter:image" content="' . esc_url( $image_url ) . '">';
					echo '<meta name="twitter:card" content="summary_large_image">';
				}
			},
			50
		);
	}

	public function specify_linear_page( $classes ){
		global $post;

		if( !$post ){
			return $classes;
		}

		$current_language = self::$linear->get_language();
		$languages = self::$linear->get_languages();

		if( $languages && is_array($languages) && count($languages) > 1 ){
			foreach( $languages as $lang ){
				if( $current_language === $lang ){
					if(
						$post->ID === intval( self::$linear->get_option( 'listings_page_' . $lang ) ) ||
						$post->ID === intval( self::$linear->get_option( 'rentals_page_' . $lang ) ) ||
						$post->ID === intval( self::$linear->get_option( 'workplace_page_' . $lang ) )
					){
						return array_merge( $classes, array( 'linear-page' ) );
					}
				}
			}
		} else {
			if( 
				$post->ID === intval( self::$linear->get_option( 'listings_page' ) ) ||
				$post->ID === intval( self::$linear->get_option( 'rentals_page' ) ) ||
				$post->ID === intval( self::$linear->get_option( 'workplace_page' ) )
			){
				return array_merge( $classes, array( 'linear-page' ) );
			}
		}

		return $classes;
	}

	public function get_current_listing_id(){
		global $wp_query;

		$listing_id = $wp_query->get( 'linear_data_id', false );

		if( !$listing_id ){
			return  null;
		}

		$listing = apply_filters( "linear_listing", $listing_id, substr( get_locale(), 0, 2 ) );

		if( $listing ){
			return $listing['id'];
		}

		return null;
	}
}