<?php
/**
 * Hooks for ease of use
 *
 * @package Linear
 */

/**
 * Defines all code necessary to run during the plugin's activation.
 */
class Linear_Shortcodes {

	protected static $linear;

	public function __construct() {
		if ( is_null( self::$linear ) ) {
			self::$linear = Linear::get_instance();
		}
	}

	public function setup_shortcodes() {
		global $post, $wp_query;

		if( !$post ){
			return;
		}

		$render_content = false;
		$languages = self::$linear->get_languages();
		$content = '';

		// Validate
		if( $languages && is_array($languages) && count($languages) > 1 ){
			foreach( $languages as $lang ){
				if( in_array( $post->ID , [
					intval( self::$linear->get_option( 'listings_page_' . $lang ) ),
					intval( self::$linear->get_option( 'rentals_page_' . $lang ) ),
					intval( self::$linear->get_option( 'workplace_page_' . $lang ) )
				]) ){
					$render_content = "listings";
				}

				if( intval( self::$linear->get_option( 'buy_commissions_page_' . $lang ) ) === $post->ID ){
					$render_content = "buy_commission";
				}
			}
		} else {
			if( in_array( $post->ID , [
				intval( self::$linear->get_option( 'listings_page' ) ),
				intval( self::$linear->get_option( 'rentals_page' ) ),
				intval( self::$linear->get_option( 'workplace_page' ) )
			]) ){
				$render_content = "listings";
			}

			if( intval( self::$linear->get_option( 'buy_commissions_page' ) ) === $post->ID ){
				$render_content = "buy_commission";
			}
		}

		$id = $wp_query->get( 'linear_data_id', false );
		if ( $id === false && isset( $_GET['linear_data_id'] ) ) {
			$id = sanitize_key( $_GET['linear_data_id'] );
		}

		if( $render_content === 'listings' ){

			if ( $id === false ) {
				$content = $this->listings_content();
			} else {
				$content = $this->single_content( $id );
			}

			add_shortcode('linear_listings_content', function() use ( $content ){
				echo $content;
			});

		} else if( $render_content === 'buy_commission' ){

			if ( $id === false ) {
				$content = $this->buy_commissions_content();
			} else {
				$content = $this->single_content( $id );
			}

			add_shortcode('linear_buy_commission_content', function() use ( $content ){
				echo $content;
			});

		}
	}

	private static function listings_content(){
		$templater = new Linear_Templater();

		$content = $templater->include_listings_content('', true);

		return $content;
	}

	private static function buy_commissions_content(){
		$templater = new Linear_Templater();

		return $templater->include_buy_commissions_content('');
	}

	private static function single_content( $id = false ){
		if( !$id ){
			return "";
		}

		$templater = new Linear_Templater();

		return $templater->include_single_content('');
	}

	private static function buy_commission_content( $id = null ){
		if( !$id ){
			return "";
		}
		
		return "<h1>test4</h1>";
	}
}
