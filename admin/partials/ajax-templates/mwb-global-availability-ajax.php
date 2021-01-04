<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to set ajax handler fields for the global rules for Availability
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Mwb_Wc_Bk
 * @subpackage Mwb_Wc_Bk/admin/partials/ajax-templates
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {

	exit;
}

$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
if ( ! wp_verify_nonce( $nonce, 'ajax-nonce' ) ) {
	die( 'Nonce value cannot be verified' );
}
$rule_count = ! empty( $_POST['rule_count'] ) ? sanitize_text_field( wp_unslash( $_POST['rule_count'] ) ) : 0;

?>

<div id="mwb_global_availability_rule_<?php echo esc_html( $rule_count ); ?>" data-id="<?php echo esc_html( $rule_count ); ?>">
	<table class="form-table mwb_global_availability_rule_fields" >
		<tbody>
			<div class="mwb_global_availability_rule_heading">
				<h2>
				<label><?php echo esc_html( sprintf( 'Rule No- %u', $rule_count ) ); ?></label>
				<input type="hidden" name="mwb_availability_rule_count" value="<?php echo esc_html( $rule_count ); ?>" >
				<input type="checkbox" class="mwb_global_availability_rule_heading_switch" name="mwb_global_availability_rule_heading_switch[<?php echo esc_html( $rule_count - 1 ); ?>]" checked  >
				</h2>
			</div>
			<tr valign="top">
				<th scope="row" class="">
					<label><?php esc_attr_e( 'Rule Name', 'mwb-wc-bk' ); ?></label>
				</th>
				<td class="forminp forminp-text">
					<input type="text" class="mwb_global_availability_rule_name" name="mwb_global_availability_rule_name[<?php echo esc_html( $rule_count - 1 ); ?>]" >
				</td>
			</tr>
			<tr valign="top">
				<th scope="row" class="">
					<label><?php esc_attr_e( 'Rule Type', 'mwb-wc-bk' ); ?></label>
				</th>
				<td class="forminp forminp-text">
					<input type="radio" class="mwb_global_availability_rule_type" name="mwb_global_availability_rule_type[<?php echo esc_html( $rule_count - 1 ); ?>]" value="specific" checked >
					<label><?php esc_attr_e( 'Specific Dates', 'mwb-wc-bk' ); ?></label><br>
					<input type="radio" class="mwb_global_availability_rule_type" name="mwb_global_availability_rule_type[<?php echo esc_html( $rule_count - 1 ); ?>]" value="generic">
					<label><?php esc_attr_e( 'Generic Dates', 'mwb-wc-bk' ); ?></label><br>
				</td>
			</tr>
			<tr valign="top" class="range">
				<th scope="row" class="">
					<label><?php esc_attr_e( 'From', 'mwb-wc-bk' ); ?></label>
				</th>
				<td class="forminp forminp-text specific">
					<p>
						<input type="date" class="mwb_global_availability_rule_range_from" name="mwb_global_availability_rule_range_from[<?php echo esc_html( $rule_count - 1 ); ?>]" >
						<label><?php esc_attr_e( 'To', 'mwb-wc-bk' ); ?></label>
						<input type="date" class="mwb_global_availability_rule_range_to" name="mwb_global_availability_rule_range_to[<?php echo esc_html( $rule_count - 1 ); ?>]" >
					</p>
				</td>
				<td class="forminp forminp-text generic">
					<p>
						<select class="mwb_global_availability_rule_range_from" name="mwb_global_availability_rule_range_from[<?php echo esc_html( $rule_count - 1 ); ?>]">
						<?php foreach ( $this->global_func->booking_months() as $k => $v ) { ?>
							<option value="<?php echo esc_html( $k ); ?>" ><?php echo esc_html( $v ); ?></option>
						<?php } ?>
						</select>
						<label><?php esc_html_e( 'To', 'mwb-wc-bk' ); ?></label>
						<select class="mwb_global_availability_rule_range_to" name="mwb_global_availability_rule_range_to[<?php echo esc_html( $rule_count - 1 ); ?>]">
						<?php foreach ( $this->global_func->booking_months() as $k => $v ) { ?>
							<option value="<?php echo esc_html( $k ); ?>" ><?php echo esc_html( $v ); ?></option>
						<?php } ?>
						</select>
					</p>
				</td>
			</tr>
			<tr valign="top" class="bookable">
				<th scope="row" class=""></th>
				<td class="forminp forminp-text">
					<p>
					<input type="radio" class="mwb_global_availability_rule_bookable" name="mwb_global_availability_rule_bookable[<?php echo esc_html( $rule_count - 1 ); ?>]" value="bookable" checked >
					<label><?php esc_attr_e( 'Bookable', 'mwb-wc-bk' ); ?>Bookable</label><br>
					<input type="radio" class="mwb_global_availability_rule_non_bookable" name="mwb_global_availability_rule_bookable[<?php echo esc_html( $rule_count - 1 ); ?>]" value="non-bookable" >
					<label><?php esc_attr_e( 'Non-Bookable', 'mwb-wc-bk' ); ?></label><br>
					</p>
				</td>
			</tr>
			<tr valign="top" class="weekdays_rule">
				<th scope="row" class=""></th>
				<td class="forminp forminp-text" >
					<p>
						<input type="checkbox" class="mwb_global_availability_rule_weekdays" name="mwb_global_availability_rule_weekdays[<?php echo esc_html( $rule_count - 1 ); ?>]"  >
						<?php esc_attr_e( 'Rules for weekdays', 'mwb-wc-bk' ); ?>
					</p>
				</td>
				<?php foreach ( $this->global_func->booking_search_weekdays() as $key => $values ) { ?>
					<td class="forminp forminp-text mwb_global_availability_rule_weekdays_book" style="display:none">
						<p><?php echo esc_html( $values ); ?></p>
						<input type="hidden" name="mwb_global_availability_rule_weekdays_book[<?php echo esc_html( $rule_count - 1 ); ?>][<?php echo esc_html( $key ); ?>]"  value="bookable" >
						<input type="button" class="mwb_global_availability_rule_weekdays_book_button button" value="bookable">
					</td>
				<?php } ?>
			</tr>
		</tbody>
	</table>
	<button type="button" id="mwb_delete_avialability_rule" class="button" rule_count="<?php echo esc_html( $rule_count ); ?>" ><?php esc_attr_e( 'Delete Rule', 'mwb-wc-bk' ); ?></button>
</div>
	<?php
	wp_die();
