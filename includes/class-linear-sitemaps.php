<?php 

/**
 * Handles custom sitemap for listings
 */
class Linear_Sitemaps extends WP_Sitemaps_Provider {
	public $name;
	protected static $linear;

	public function __construct() {

		if ( is_null( self::$linear ) ) {
			self::$linear = Linear::get_instance();
		}

		$this->name        = 'listings';
		$this->object_type = 'listings';
	}

	public function get_url_list( $page_num, $subtype = '' ) {

		$listings = apply_filters( "linear_listings", $lang );

		$url_list = [];

		foreach ( $listings as $listing ) {
			$sitemap_entry = [
				'loc' => $listing['permalink']
			];

			$url_list[] = $sitemap_entry;
		}

		return $url_list;
	}

	public function get_max_num_pages( $subtype = '' ) {
		return 1;
	}

    public function register_sitemap_provider() {
		$provider = new Linear_Sitemaps();
		wp_register_sitemap_provider( $provider->name, $provider );

		$languages = self::$linear->get_languages();
		$lang = '';

		if( $languages && is_array($languages) && count($languages) > 1 ){
			foreach( $languages as $lang ){
				$this->yoast_exception( $lang );
				$this->seopress_exception( $lang );
				$this->the_seo_framework_exception( $lang );
			}
		} else {
			$this->yoast_exception( null );
			$this->seopress_exception( null );
			$this->the_seo_framework_exception( null );
		}
	}

	private function yoast_exception( $lang = null ){
		add_filter( 'wpseo_sitemap_index', function( $sitemap_custom_items ) use ($lang){

			$listings = apply_filters( "linear_listings", $lang );

			if( $listings ){
				foreach ( $listings as $listing ) {
					if( isset( $listing['permalink'] ) && $listing['permalink'] ){
						$sitemap_custom_items .= '
							<sitemap>
								<loc>' . $listing['permalink'] . '</loc>
							</sitemap>';
					}
				}	
			}
			
			return $sitemap_custom_items;

		} );
	}

	private function seopress_exception( $lang = null ){
		add_filter('seopress_sitemaps_external_link', function( $custom_sitemap = [] ) use ($lang) {

			$listings = apply_filters( "linear_listings", $lang );

			if( $listings ){
				foreach ( $listings as $listing ) {
					if( isset( $listing['permalink'] ) && $listing['permalink'] ){
						$custom_sitemap[] = [
							'sitemap_url' 		=> $listing['permalink']
						];
					};
				}	
			}

			return $custom_sitemap;

		});
	}

	private function the_seo_framework_exception( $lang = null ){
		add_filter( 'the_seo_framework_sitemap_additional_urls', function( $custom_urls = [] ) use ($lang) {

			$listings = apply_filters( "linear_listings", $lang );

			if( $listings ){
				foreach ( $listings as $listing ) {
					if( isset( $listing['permalink'] ) && $listing['permalink'] ){
						$custom_urls[ $listing['permalink'] ] = [
							'lastmod' => null,
						];
					};
				}	
			}
		
			return $custom_urls;
		} );
	}

}