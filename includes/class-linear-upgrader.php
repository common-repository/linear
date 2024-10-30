<?php
/**
 * Fired on plugin update
 *
 * @package Linear
 */

class Linear_Upgrader {
	public static function upgrade_tasks() {

		// Disabled for now
		return;

		// Adjust the used page templates
		$plugin_version = defined( 'LINEAR_VERSION' ) ? LINEAR_VERSION : '';
		$version_split = explode(".", $plugin_version);

		// Affect other versions of necessary
		if( 
			$version_split[0] === '2' &&
			$version_split[1] === '1' &&
			$version_split[2] >= '12'
		){
			$options = get_option( 'linear_settings' );

			$linear_pages = [
				'listings_page',
				'rentals_page',
				'workplace_page',
				'buy_commissions_page'
			];

			/*
			foreach( $linear_pages as $linear_page ){
				if( isset( $options[$linear_page] ) ){
					$page = get_post( $options[$linear_page] );

					if( $page ){
						if( get_page_template_slug( $page->ID ) === "" ){
							update_post_meta( $page->ID, '_wp_page_template', 'plain-listings.php' );
						}
					}
				}
			}
			*/

		}
	}
}
