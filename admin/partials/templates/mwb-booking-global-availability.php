<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to set the global rules for Availability
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Mwb_Wc_Bk
 * @subpackage Mwb_Wc_Bk/admin/partials/templates
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {

	exit;
}
$rule_arr = array();

$availability_rules = get_option( 'mwb_global_avialability_rules', array() );
$rule_count         = ! empty( $availability_rules['rule_name'] ) ? count( $availability_rules['rule_name'] ) : 0;


if ( isset( $_POST['mwb_booking_global_availability_rules_save'] ) ) {

	$rule_count = isset( $_POST['mwb_availability_rule_count'] ) ? $_POST['mwb_availability_rule_count'] : $rule_count;

	if ( $rule_count > 0 ) {
		$rule_arr['rule_switch']     = isset( $_POST['mwb_global_availability_rule_heading_switch'] ) ? $_POST['mwb_global_availability_rule_heading_switch'] : array();
		$rule_arr['rule_name']       = isset( $_POST['mwb_global_availability_rule_name'] ) ? $_POST['mwb_global_availability_rule_name'] : array();
		$rule_arr['rule_type']       = isset( $_POST['mwb_global_availability_rule_type'] ) ? $_POST['mwb_global_availability_rule_type'] : array();
		$rule_arr['rule_range_from'] = isset( $_POST['mwb_global_availability_rule_range_from'] ) ? $_POST['mwb_global_availability_rule_range_from'] : array();
		$rule_arr['rule_range_to']   = isset( $_POST['mwb_global_availability_rule_range_to'] ) ? $_POST['mwb_global_availability_rule_range_to'] : array();

		for ( $count = 1; $count <= $rule_count; $count++ ) {
			$rule_arr['rule_switch'][ $count ] = isset( $rule_arr['rule_switch'][ $count ] ) ? $rule_arr['rule_switch'][ $count ] : 'off';
		}
		update_option( 'mwb_global_avialability_rules', $rule_arr );
	} else {
		echo esc_html__( 'Add a new Availability rule', 'mwb-wc-bk' );
	}
}

$availability_rules = get_option( 'mwb_global_avialability_rules', array() );
echo '<pre>';
echo $rule_count;
print_r( $availability_rules );
echo '</pre>';
?>

<!-- For Global options Setting -->
<form id="mwb_global_availability_form" action="" method="POST">
	<div class="mwb_booking_global_availability_rules">
		<div id="mwb_global_availability_rules">
		<?php
		if ( ! empty( $availability_rules ) ) {
			$mwb_availability_rule_switch     = ! empty( $availability_rules['rule_switch'] ) ? $availability_rules['rule_switch'] : array();
			$mwb_availability_rule_name       = ! empty( $availability_rules['rule_name'] ) ? $availability_rules['rule_name'] : array();
			$mwb_availability_rule_type       = ! empty( $availability_rules['rule_type'] ) ? $availability_rules['rule_type'] : array();
			$mwb_availability_rule_range_from = ! empty( $availability_rules['rule_range_from'] ) ? $availability_rules['rule_range_from'] : array();
			$mwb_availability_rule_range_to   = ! empty( $availability_rules['rule_range_to'] ) ? $availability_rules['rule_range_to'] : array();

			for ( $count = 1; $count <= $rule_count; $count++ ) {
				// $mwb_availability_rule_switch[ $count ] = isset( $mwb_availability_rule_switch[ $count ] ) ? $mwb_availability_rule_switch[ $count ] : 'off';
				?>
			<div id="mwb_global_availability_rule_<?php echo esc_html( $count ); ?>" data-id="<?php echo esc_html( $count ); ?>">
				<table class="form-table mwb_global_availability_rule_fields" >
					<tbody>
						<div class="mwb_global_availability_rule_heading">
							<h2>
							<label><?php echo ! empty( $mwb_availability_rule_name[ $count ] ) ? esc_html( $mwb_availability_rule_name[ $count ] ) : esc_html__( 'Rule No- ', 'mwb-wc-bk' ) . esc_html( $count ); ?></label>
							<input type="hidden" name="mwb_availability_rule_count" value="<?php echo esc_html( $count ); ?>" >
							<input type="checkbox" class="mwb_global_availability_rule_heading_switch" name="mwb_global_availability_rule_heading_switch[<?php echo esc_html( $count ); ?>]" <?php checked( 'on', $mwb_availability_rule_switch[ $count ] ); ?>>
							</h2>
						</div>
						<tr valign="top">
							<th scope="row" class="">
								<label><?php esc_html_e( 'Rule Name', 'mwb-wc-bk' ); ?></label>
							</th>
							<td class="forminp forminp-text">
								<input type="text" class="mwb_global_availability_rule_name" name="mwb_global_availability_rule_name[<?php echo esc_html( $count ); ?>]" value="<?php echo esc_html( $mwb_availability_rule_name[ $count ] ); ?>">
							</td>
						</tr>
						<tr valign="top">
							<th scope="row" class="">
								<label><?php esc_html_e( 'Rule Type', 'mwb-wc-bk' ); ?></label>
							</th>
							<td class="forminp forminp-text">
								<input type="radio" class="mwb_global_availability_rule_type_specific" name="mwb_global_availability_rule_type[<?php echo esc_html( $count ); ?>]" value="specific" <?php checked( 'specific', $mwb_availability_rule_type[ $count ] ); ?> >
								<label><?php esc_html_e( 'Specific Dates', 'mwb-wc-bk' ); ?></label><br>
								<input type="radio" class="mwb_global_availability_rule_type_generic" name="mwb_global_availability_rule_type[<?php echo esc_html( $count ); ?>]" value="generic" <?php checked( 'generic', $mwb_availability_rule_type[ $count ] ); ?>>
								<label><?php esc_html_e( 'Generic Dates', 'mwb-wc-bk' ); ?></label><br>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row" class="">
								<label><?php esc_html_e( 'From', 'mwb-wc-bk' ); ?></label>
							</th>
							<td class="forminp forminp-text">
								<p>
									<input type="date" class="mwb_global_availability_rule_range_from" name="mwb_global_availability_rule_range_from[<?php echo esc_html( $count ); ?>]" value="<?php echo esc_html( $mwb_availability_rule_range_from[ $count ] ); ?>" >
									<label><?php esc_html_e( 'To', 'mwb-wc-bk' ); ?></label>
									<input type="date" class="mwb_global_availability_rule_range_to" name="mwb_global_availability_rule_range_to[<?php echo esc_html( $count ); ?>]" value="<?php echo esc_html( $mwb_availability_rule_range_to[ $count ] ); ?>" >
								</p>
							</td>
						</tr>
					</tbody>
				</table>
				<div id="mwb_delete_availability_rule_button">
					<button type="button" id="mwb_delete_avialability_rule" class="button"><?php esc_html_e( 'Delete Rule', 'mwb-wc-bk' ); ?></button>
				</div>
			</div>
				<?php
			}
		}
		?>
		</div>
		<div id="mwb_global_availability_button">
			<button type="button" id="mwb_add_avialability_rule" class="button" rule_count="<?php echo esc_html( $rule_count ); ?>" ><?php esc_html_e( 'Add New Avialability Rule', 'mwb-wc-bk' ); ?></button>
		</div>
	</div>
	<!-- Save Settings -->
	<p class="submit">
		<input type="submit" value="<?php esc_html_e( 'Save Changes', 'mwb-wc-bk' ); ?>" class="button-primary woocommerce-save-button" name="mwb_booking_global_availability_rules_save" id="mwb_booking_global_availability_rules_save" >
	</p>
</form>
