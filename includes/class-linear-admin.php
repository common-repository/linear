<?php
/**
 * Adding admin logic for Linear Plugin
 *
 * @package Linear
 */

/**
 * Any additional admin logic
 */
class Linear_Admin {

	protected static $linear;
    protected $linear_middleware;
    protected $api_key;

	public function __construct() {
		if ( is_null( self::$linear ) ) {
			self::$linear = Linear::get_instance();
		}

		$this->api_version = ( self::$linear->get_option( 'api_key' ) ? 'v1.1' : 'v1' );
	}

    public function user_notify_contact_url() {
		global $pagenow;
		if ( $pagenow == 'admin.php' && isset( $_GET['page'] ) && $_GET['page'] == 'linear-settings' ) {
			if( $this->api_version === 'v1'){
				$email = 'it@linear.fi';
				$link = $this->build_mailto( $email );

				echo '<div class="notice notice-error is-dismissible">
					<p>' . sprintf( __('<br>Please request API credentials by contacting <a href="%s">%s</a>', 'linear'), $link, $email ) . '</p>
				</div>';
			}
		}
	}

	/**
	 * @return string mailto link
	 */
	private function build_mailto( $email ){
		$client_number = self::$linear->get_option( 'company_id' );
		$site_name = get_bloginfo( 'name' );
		$site_link = home_url();
		$linear_plugin_version = LINEAR_VERSION;

		$subject = '';

		if( $site_name ){
			$subject = rawurlencode( sprintf( __('Requesting new Linear API credentials for %s', 'linear'), $site_name ) );
		} else {
			$subject = rawurlencode( __('Requesting new Linear API credentials', 'linear') );
		}

		$introduction_title = __('Hello', 'linear');
		$introduction_content = __('I was informed that I need to request from you the new API credentials, can you provide them to me via email?', 'linear');
		$string_client_number = __('Client number: %s', 'linear');
		$string_sitename = __('Sitename: %s', 'linear');
		$string_permalink = __('Permalink: %s', 'linear');
		$string_plugin_version = __('Linear Plugin version: %s', 'linear');

		$message_header = "mailto:" . $email . "?subject=" . $subject . "&body=";
		$message_content = $introduction_title . "\r\n\r\n" . $introduction_content . "\r\n\r\n" . $string_client_number . "\r\n" . $string_sitename . "\r\n" . $string_permalink . "\r\n" . $string_plugin_version;

		return $message_header . rawurlencode( sprintf( $message_content, $client_number, $site_name, $site_link, $linear_plugin_version ) );
	}
}
