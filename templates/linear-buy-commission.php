<?php
/**
 * The template for displaying specific apartment on sale.
 *
 * @package Linear
 */

namespace Linear\Templates;

function template_single_buy_commission( $linear_data_id ){
	$buy_commission = apply_filters( "linear_buy_commission", $linear_data_id, substr( get_locale(), 0, 2 ) );
	$use_theme_fonts = boolval( get_linear_option_value( 'theme_fonts' ) );
	$realtor = isset( $buy_commission['realtor'] ) ? $buy_commission['realtor'] : null;
	
	if( !$buy_commission ){
		return '';
	}

	?>
	
	<div id="linear">
		<div class="<?php echo esc_attr( $use_theme_fonts ? '' : 'linear-u-font-base'); ?>">
			<div class="linear-o-container">
	
				<?php
					echo buy_commission_pre_title( $buy_commission['data'] );
					echo buy_commission_introduction( $buy_commission['data'] );
					echo buy_commission_content( $buy_commission['data'], $buy_commission['id'] );
					echo buy_commission_realtor( $buy_commission );
				?>
				
			</div>
		</div>
	</div>
	<?php
}