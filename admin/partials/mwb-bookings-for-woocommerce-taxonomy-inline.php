<?php
/**
 * Inline taxonomy management for Additional Costs and Additional Services.
 *
 * Renders the taxonomy Add New / Edit form and term listing table directly
 * within the plugin admin panel (Configuration Settings tab) without
 * redirecting to edit-tags.php or term.php.
 *
 * @link       https://wpswings.com/
 * @since      3.11.6
 *
 * @package    Mwb_Bookings_For_Woocommerce
 * @subpackage Mwb_Bookings_For_Woocommerce/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! isset( $taxonomy ) || ! taxonomy_exists( $taxonomy ) ) {
	return;
}

if ( ! current_user_can( 'manage_woocommerce' ) ) {
	wp_die( esc_html__( 'Permission denied.', 'mwb-bookings-for-woocommerce' ) );
}

$page_url    = admin_url( 'admin.php?page=mwb_bookings_for_woocommerce_menu&mbfw_tab=mwb-bookings-for-woocommerce-configuration&bfw_sub_nav=' . $taxonomy );
$notice      = '';
$notice_type = '';

// --- Handle Add New Term ---
if ( isset( $_POST['mwb_inline_tax_action'] ) && 'add-tag' === $_POST['mwb_inline_tax_action'] ) {
	if ( ! isset( $_POST['_mwb_inline_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_mwb_inline_nonce'] ) ), 'mwb_inline_add_term_' . $taxonomy ) ) {
		wp_die( esc_html__( 'Security check failed.', 'mwb-bookings-for-woocommerce' ) );
	}

	$term_name = isset( $_POST['tag-name'] ) ? sanitize_text_field( wp_unslash( $_POST['tag-name'] ) ) : '';
	$term_slug = isset( $_POST['slug'] ) ? sanitize_title( wp_unslash( $_POST['slug'] ) ) : '';
	$term_desc = isset( $_POST['description'] ) ? sanitize_textarea_field( wp_unslash( $_POST['description'] ) ) : '';

	if ( empty( $term_name ) ) {
		$notice      = __( 'Term name is required.', 'mwb-bookings-for-woocommerce' );
		$notice_type = 'error';
	} else {
		$insert_args = array( 'description' => $term_desc );
		if ( ! empty( $term_slug ) ) {
			$insert_args['slug'] = $term_slug;
		}
		$result = wp_insert_term( $term_name, $taxonomy, $insert_args );

		if ( ! is_wp_error( $result ) ) {
			mwb_tax_inline_save_meta( $result['term_id'], $taxonomy );
			wp_safe_redirect( add_query_arg( 'mwb_tax_notice', 'added', $page_url ) );
			exit;
		} else {
			$notice      = $result->get_error_message();
			$notice_type = 'error';
		}
	}
}

// --- Handle Update Term ---
if ( isset( $_POST['mwb_inline_tax_action'] ) && 'edit-tag' === $_POST['mwb_inline_tax_action'] ) {
	if ( ! isset( $_POST['_mwb_inline_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_mwb_inline_nonce'] ) ), 'mwb_inline_edit_term_' . $taxonomy ) ) {
		wp_die( esc_html__( 'Security check failed.', 'mwb-bookings-for-woocommerce' ) );
	}

	$edit_id   = isset( $_POST['mwb_tax_term_id'] ) ? absint( $_POST['mwb_tax_term_id'] ) : 0;
	$term_name = isset( $_POST['tag-name'] ) ? sanitize_text_field( wp_unslash( $_POST['tag-name'] ) ) : '';
	$term_slug = isset( $_POST['slug'] ) ? sanitize_title( wp_unslash( $_POST['slug'] ) ) : '';
	$term_desc = isset( $_POST['description'] ) ? sanitize_textarea_field( wp_unslash( $_POST['description'] ) ) : '';

	if ( $edit_id && ! empty( $term_name ) ) {
		$update_args = array( 'name' => $term_name, 'description' => $term_desc );
		if ( ! empty( $term_slug ) ) {
			$update_args['slug'] = $term_slug;
		}
		$result = wp_update_term( $edit_id, $taxonomy, $update_args );

		if ( ! is_wp_error( $result ) ) {
			mwb_tax_inline_save_meta( $edit_id, $taxonomy );
			wp_safe_redirect( add_query_arg( 'mwb_tax_notice', 'updated', $page_url ) );
			exit;
		} else {
			$notice      = $result->get_error_message();
			$notice_type = 'error';
		}
	}
}

// --- Handle Delete from edit page ---
if ( isset( $_GET['mwb_tax_delete'] ) && isset( $_GET['_wpnonce'] ) ) {
	$del_id = absint( $_GET['mwb_tax_delete'] );
	if ( wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'mwb_delete_term_' . $del_id ) ) {
		wp_delete_term( $del_id, $taxonomy );
		wp_safe_redirect( add_query_arg( 'mwb_tax_notice', 'deleted', $page_url ) );
		exit;
	}
}

// --- Handle Bulk Delete ---
if ( isset( $_POST['mwb_bulk_action'] ) && 'delete' === $_POST['mwb_bulk_action'] && ! empty( $_POST['delete_tags'] ) ) {
	if ( isset( $_POST['_mwb_bulk_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_mwb_bulk_nonce'] ) ), 'mwb_bulk_action_terms_' . $taxonomy ) ) {
		foreach ( (array) $_POST['delete_tags'] as $bulk_term_id ) {
			wp_delete_term( absint( $bulk_term_id ), $taxonomy );
		}
		wp_safe_redirect( add_query_arg( 'mwb_tax_notice', 'deleted', $page_url ) );
		exit;
	}
}

// --- Notices ---
if ( isset( $_GET['mwb_tax_notice'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
	$notice_map = array(
		'added'   => __( 'New item added successfully.', 'mwb-bookings-for-woocommerce' ),
		'updated' => __( 'Item updated successfully.', 'mwb-bookings-for-woocommerce' ),
		'deleted' => __( 'Item deleted successfully.', 'mwb-bookings-for-woocommerce' ),
	);
	$nkey = sanitize_key( $_GET['mwb_tax_notice'] ); // phpcs:ignore WordPress.Security.NonceVerification
	if ( isset( $notice_map[ $nkey ] ) ) {
		$notice      = $notice_map[ $nkey ];
		$notice_type = 'updated';
	}
}

// --- Edit mode ---
$editing_term = null;
$edit_id_get  = isset( $_GET['mwb_tax_edit'] ) ? absint( $_GET['mwb_tax_edit'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification
if ( $edit_id_get ) {
	$maybe = get_term( $edit_id_get, $taxonomy );
	if ( $maybe && ! is_wp_error( $maybe ) ) {
		$editing_term = $maybe;
	}
}

// --- Search + terms (only for listing mode) ---
$search = isset( $_GET['s'] ) ? sanitize_text_field( wp_unslash( $_GET['s'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification

$query_args  = array( 'taxonomy' => $taxonomy, 'hide_empty' => false );
if ( ! empty( $search ) ) {
	$query_args['search'] = $search;
}
$terms       = get_terms( $query_args );
$total_items = is_array( $terms ) ? count( $terms ) : 0;

// --- Labels ---
$tax_obj  = get_taxonomy( $taxonomy );
$tax_sing = isset( $tax_obj->labels->singular_name ) ? $tax_obj->labels->singular_name : $tax_obj->label;

if ( 'mwb_booking_cost' === $taxonomy ) {
	$add_new_label = __( 'Add New Booking Cost', 'mwb-bookings-for-woocommerce' );
	$search_label  = __( 'Search Booking Cost', 'mwb-bookings-for-woocommerce' );
	$tax_desc      = __( 'This setting tab allows you to create different types of additional booking costs for your booking products i.e. the part of your booking product\'s additional resources or addons.', 'mwb-bookings-for-woocommerce' );
} elseif ( 'mwb_booking_people' === $taxonomy ) {
	$add_new_label = __( 'Add New People Type', 'mwb-bookings-for-woocommerce' );
	$search_label  = __( 'Search People Type', 'mwb-bookings-for-woocommerce' );
	$tax_desc      = __( 'You can create different types of people for your booking requests by using this setting tab. This is entirely optional; still, if your business requires type flexibility when bookings, then employ it.', 'bookings-for-woocommerce-pro' );
} else {
	$add_new_label = __( 'Add New Booking Service', 'mwb-bookings-for-woocommerce' );
	$search_label  = __( 'Search Booking Service', 'mwb-bookings-for-woocommerce' );
	$tax_desc      = __( 'This setting tab allows you to create different types of additional services for your booking products.', 'mwb-bookings-for-woocommerce' );
}

// --- Icons ---
$icon_yes = '<span class="mwb-tax-icon mwb-tax-icon--yes">&#10003;</span>';
$icon_no  = '<span class="mwb-tax-icon mwb-tax-icon--no">&#10005;</span>';

/**
 * Save custom term meta from POST.
 *
 * @param int    $term_id  Term ID.
 * @param string $taxonomy Taxonomy slug.
 */
function mwb_tax_inline_save_meta( $term_id, $taxonomy ) {
	if ( 'mwb_booking_cost' === $taxonomy ) {
		update_term_meta( $term_id, 'mwb_mbfw_booking_cost', isset( $_POST['mwb_mbfw_booking_cost'] ) ? sanitize_text_field( wp_unslash( $_POST['mwb_mbfw_booking_cost'] ) ) : '' ); // phpcs:ignore WordPress.Security.NonceVerification
		update_term_meta( $term_id, 'mwb_mbfw_is_booking_cost_multiply_people', isset( $_POST['mwb_mbfw_is_booking_cost_multiply_people'] ) ? 'yes' : '' ); // phpcs:ignore WordPress.Security.NonceVerification
		update_term_meta( $term_id, 'mwb_mbfw_is_booking_cost_multiply_duration', isset( $_POST['mwb_mbfw_is_booking_cost_multiply_duration'] ) ? 'yes' : '' ); // phpcs:ignore WordPress.Security.NonceVerification
	} elseif ( 'mwb_booking_service' === $taxonomy ) {
		update_term_meta( $term_id, 'mwb_mbfw_service_cost', isset( $_POST['mwb_mbfw_service_cost'] ) ? sanitize_text_field( wp_unslash( $_POST['mwb_mbfw_service_cost'] ) ) : '' ); // phpcs:ignore WordPress.Security.NonceVerification
		update_term_meta( $term_id, 'mwb_mbfw_is_service_cost_multiply_people', isset( $_POST['mwb_mbfw_is_service_cost_multiply_people'] ) ? 'yes' : '' ); // phpcs:ignore WordPress.Security.NonceVerification
		update_term_meta( $term_id, 'mwb_mbfw_is_service_cost_multiply_duration', isset( $_POST['mwb_mbfw_is_service_cost_multiply_duration'] ) ? 'yes' : '' ); // phpcs:ignore WordPress.Security.NonceVerification
		update_term_meta( $term_id, 'mwb_mbfw_is_service_optional', isset( $_POST['mwb_mbfw_is_service_optional'] ) ? 'yes' : '' ); // phpcs:ignore WordPress.Security.NonceVerification
		update_term_meta( $term_id, 'mwb_mbfw_is_service_hidden', isset( $_POST['mwb_mbfw_is_service_hidden'] ) ? 'yes' : '' ); // phpcs:ignore WordPress.Security.NonceVerification
		update_term_meta( $term_id, 'mwb_mbfw_is_service_has_quantity', isset( $_POST['mwb_mbfw_is_service_has_quantity'] ) ? 'yes' : '' ); // phpcs:ignore WordPress.Security.NonceVerification
	} elseif ( 'mwb_booking_people' === $taxonomy ) {
		update_term_meta( $term_id, 'mwb_bfwp_booking_people_unit_cost', isset( $_POST['mwb_bfwp_booking_people_unit_cost'] ) ? sanitize_text_field( wp_unslash( $_POST['mwb_bfwp_booking_people_unit_cost'] ) ) : '' ); // phpcs:ignore WordPress.Security.NonceVerification
		update_term_meta( $term_id, 'mwb_bfwp_booking_people_base_cost', isset( $_POST['mwb_bfwp_booking_people_base_cost'] ) ? sanitize_text_field( wp_unslash( $_POST['mwb_bfwp_booking_people_base_cost'] ) ) : '' ); // phpcs:ignore WordPress.Security.NonceVerification
		update_term_meta( $term_id, 'mwb_mbfw_minimum_people_per_booking', isset( $_POST['mwb_mbfw_minimum_people_per_booking'] ) ? sanitize_text_field( wp_unslash( $_POST['mwb_mbfw_minimum_people_per_booking'] ) ) : '' ); // phpcs:ignore WordPress.Security.NonceVerification
		update_term_meta( $term_id, 'mwb_bfwp_booking_people_maximum_quantity', isset( $_POST['mwb_bfwp_booking_people_maximum_quantity'] ) ? sanitize_text_field( wp_unslash( $_POST['mwb_bfwp_booking_people_maximum_quantity'] ) ) : '' ); // phpcs:ignore WordPress.Security.NonceVerification
	}
}
?>

<div class="mwb-inline-taxonomy">

	<?php if ( $notice ) : ?>
		<div class="notice notice-<?php echo esc_attr( $notice_type ); ?> is-dismissible mwb-inline-tax-notice">
			<p><?php echo esc_html( $notice ); ?></p>
		</div>
	<?php endif; ?>

	<?php if ( $editing_term ) : ?>

		<!-- ============================= -->
		<!-- EDIT FORM (full-width)        -->
		<!-- ============================= -->
		<div class="mwb-inline-taxonomy__edit-wrap">

			<div class="mwb-inline-taxonomy__edit-title-row">
				<h2 class="mwb-inline-taxonomy__edit-title">
					<?php
					/* translators: %s: taxonomy singular name */
					printf( esc_html__( 'Edit %s', 'mwb-bookings-for-woocommerce' ), esc_html( $tax_sing ) );
					?>
				</h2>
				<a href="<?php echo esc_url( $page_url ); ?>" class="mwb-tax-back-link">
					&larr; <?php esc_html_e( 'Back to list', 'mwb-bookings-for-woocommerce' ); ?>
				</a>
			</div>

			<form method="post" action="<?php echo esc_url( $page_url ); ?>" class="mwb-inline-taxonomy__edit-form">
				<?php wp_nonce_field( 'mwb_inline_edit_term_' . $taxonomy, '_mwb_inline_nonce' ); ?>
				<input type="hidden" name="mwb_inline_tax_action" value="edit-tag" />
				<input type="hidden" name="mwb_tax_term_id" value="<?php echo esc_attr( $editing_term->term_id ); ?>" />

				<!-- Name -->
				<div class="mwb-edit-field">
					<label for="mwb-edit-name"><?php esc_html_e( 'Name', 'mwb-bookings-for-woocommerce' ); ?></label>
					<div class="mwb-edit-field__control">
						<input type="text" id="mwb-edit-name" name="tag-name" value="<?php echo esc_attr( $editing_term->name ); ?>" class="mwb-edit-field__text" />
						<p class="description"><?php esc_html_e( 'The name is how it appears on your site.', 'mwb-bookings-for-woocommerce' ); ?></p>
					</div>
				</div>

				<!-- Slug -->
				<div class="mwb-edit-field">
					<label for="mwb-edit-slug"><?php esc_html_e( 'Slug', 'mwb-bookings-for-woocommerce' ); ?></label>
					<div class="mwb-edit-field__control">
						<input type="text" id="mwb-edit-slug" name="slug" value="<?php echo esc_attr( $editing_term->slug ); ?>" class="mwb-edit-field__text" />
						<p class="description"><?php esc_html_e( 'The &ldquo;slug&rdquo; is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.', 'mwb-bookings-for-woocommerce' ); ?></p>
					</div>
				</div>

				<!-- Description -->
				<div class="mwb-edit-field">
					<label for="mwb-edit-desc"><?php esc_html_e( 'Description', 'mwb-bookings-for-woocommerce' ); ?></label>
					<div class="mwb-edit-field__control">
						<textarea id="mwb-edit-desc" name="description" rows="5" class="mwb-edit-field__textarea"><?php echo esc_textarea( $editing_term->description ); ?></textarea>
						<p class="description"><?php esc_html_e( 'The description is not prominent by default; however, some themes may show it.', 'mwb-bookings-for-woocommerce' ); ?></p>
					</div>
				</div>

				<?php if ( 'mwb_booking_cost' === $taxonomy ) : ?>

					<!-- Booking Cost -->
					<div class="mwb-edit-field">
						<label for="mwb-edit-cost"><?php esc_html_e( 'Booking Cost', 'mwb-bookings-for-woocommerce' ); ?></label>
						<div class="mwb-edit-field__control">
							<input type="number" id="mwb-edit-cost" name="mwb_mbfw_booking_cost" min="0" step="0.01"
								value="<?php echo esc_attr( get_term_meta( $editing_term->term_id, 'mwb_mbfw_booking_cost', true ) ); ?>"
								style="width:10em;" />
							<p class="description"><?php esc_html_e( 'Please Add booking cost here.', 'mwb-bookings-for-woocommerce' ); ?></p>
						</div>
					</div>

					<!-- Multiply by No. of People -->
					<div class="mwb-edit-field mwb-edit-field--toggle">
						<label for="mwb-edit-people"><?php esc_html_e( 'Multiply by No. of People', 'mwb-bookings-for-woocommerce' ); ?></label>
						<div class="mwb-edit-field__control">
							<input type="checkbox" class="mwb-tax-toggle" id="mwb-edit-people"
								name="mwb_mbfw_is_booking_cost_multiply_people" value="yes"
								<?php checked( get_term_meta( $editing_term->term_id, 'mwb_mbfw_is_booking_cost_multiply_people', true ), 'yes' ); ?> />
							<p class="description"><?php esc_html_e( 'Either to multiply by number of people.', 'mwb-bookings-for-woocommerce' ); ?></p>
						</div>
					</div>

					<!-- Multiply by Duration of Booking -->
					<div class="mwb-edit-field mwb-edit-field--toggle">
						<label for="mwb-edit-duration"><?php esc_html_e( 'Multiply by Duration of Booking', 'mwb-bookings-for-woocommerce' ); ?></label>
						<div class="mwb-edit-field__control">
							<input type="checkbox" class="mwb-tax-toggle" id="mwb-edit-duration"
								name="mwb_mbfw_is_booking_cost_multiply_duration" value="yes"
								<?php checked( get_term_meta( $editing_term->term_id, 'mwb_mbfw_is_booking_cost_multiply_duration', true ), 'yes' ); ?> />
							<p class="description"><?php esc_html_e( 'Either to multiply by Duration of Booking.', 'mwb-bookings-for-woocommerce' ); ?></p>
						</div>
					</div>

				<?php elseif ( 'mwb_booking_service' === $taxonomy ) : ?>

					<!-- Service Cost -->
					<div class="mwb-edit-field">
						<label for="mwb-edit-cost"><?php esc_html_e( 'Service Cost', 'mwb-bookings-for-woocommerce' ); ?></label>
						<div class="mwb-edit-field__control">
							<input type="number" id="mwb-edit-cost" name="mwb_mbfw_service_cost" min="0" step="0.01"
								value="<?php echo esc_attr( get_term_meta( $editing_term->term_id, 'mwb_mbfw_service_cost', true ) ); ?>"
								style="width:10em;" />
							<p class="description"><?php esc_html_e( 'Please Add service cost here.', 'mwb-bookings-for-woocommerce' ); ?></p>
						</div>
					</div>

					<!-- Multiply by Number of People -->
					<div class="mwb-edit-field mwb-edit-field--toggle">
						<label for="mwb-edit-people"><?php esc_html_e( 'Multiply by Number of People', 'mwb-bookings-for-woocommerce' ); ?></label>
						<div class="mwb-edit-field__control">
							<input type="checkbox" class="mwb-tax-toggle" id="mwb-edit-people"
								name="mwb_mbfw_is_service_cost_multiply_people" value="yes"
								<?php checked( get_term_meta( $editing_term->term_id, 'mwb_mbfw_is_service_cost_multiply_people', true ), 'yes' ); ?> />
							<p class="description"><?php esc_html_e( 'Either to multiply by number of people.', 'mwb-bookings-for-woocommerce' ); ?></p>
						</div>
					</div>

					<!-- Multiply by Booking Duration -->
					<div class="mwb-edit-field mwb-edit-field--toggle">
						<label for="mwb-edit-duration"><?php esc_html_e( 'Multiply by Booking Duration', 'mwb-bookings-for-woocommerce' ); ?></label>
						<div class="mwb-edit-field__control">
							<input type="checkbox" class="mwb-tax-toggle" id="mwb-edit-duration"
								name="mwb_mbfw_is_service_cost_multiply_duration" value="yes"
								<?php checked( get_term_meta( $editing_term->term_id, 'mwb_mbfw_is_service_cost_multiply_duration', true ), 'yes' ); ?> />
							<p class="description"><?php esc_html_e( 'Either to multiply by Booking Duration.', 'mwb-bookings-for-woocommerce' ); ?></p>
						</div>
					</div>

					<!-- If Optional -->
					<div class="mwb-edit-field mwb-edit-field--toggle">
						<label for="mwb-edit-optional"><?php esc_html_e( 'If Optional', 'mwb-bookings-for-woocommerce' ); ?></label>
						<div class="mwb-edit-field__control">
							<input type="checkbox" class="mwb-tax-toggle" id="mwb-edit-optional"
								name="mwb_mbfw_is_service_optional" value="yes"
								<?php checked( get_term_meta( $editing_term->term_id, 'mwb_mbfw_is_service_optional', true ), 'yes' ); ?> />
							<p class="description"><?php esc_html_e( 'Either the Service is Optional.', 'mwb-bookings-for-woocommerce' ); ?></p>
						</div>
					</div>

					<!-- If Hidden -->
					<div class="mwb-edit-field mwb-edit-field--toggle">
						<label for="mwb-edit-hidden"><?php esc_html_e( 'If Hidden', 'mwb-bookings-for-woocommerce' ); ?></label>
						<div class="mwb-edit-field__control">
							<input type="checkbox" class="mwb-tax-toggle" id="mwb-edit-hidden"
								name="mwb_mbfw_is_service_hidden" value="yes"
								<?php checked( get_term_meta( $editing_term->term_id, 'mwb_mbfw_is_service_hidden', true ), 'yes' ); ?> />
							<p class="description"><?php esc_html_e( 'Either the Service is Hidden.', 'mwb-bookings-for-woocommerce' ); ?></p>
						</div>
					</div>

					<!-- If Has Quantity -->
					<div class="mwb-edit-field mwb-edit-field--toggle">
						<label for="mwb-edit-qty"><?php esc_html_e( 'If has Quantity', 'mwb-bookings-for-woocommerce' ); ?></label>
						<div class="mwb-edit-field__control">
							<input type="checkbox" class="mwb-tax-toggle" id="mwb-edit-qty"
								name="mwb_mbfw_is_service_has_quantity" value="yes"
								<?php checked( get_term_meta( $editing_term->term_id, 'mwb_mbfw_is_service_has_quantity', true ), 'yes' ); ?> />
							<p class="description"><?php esc_html_e( 'Either the service has quantity.', 'mwb-bookings-for-woocommerce' ); ?></p>
						</div>
					</div>


				<?php elseif ( 'mwb_booking_people' === $taxonomy ) : ?>

					<div class="mwb-edit-field">
						<label for="mwb-edit-unit-cost"><?php esc_html_e( 'Unit Cost', 'bookings-for-woocommerce-pro' ); ?></label>
						<div class="mwb-edit-field__control">
							<input type="number" id="mwb-edit-unit-cost" name="mwb_bfwp_booking_people_unit_cost" min="0" step="0.01"
								value="<?php echo esc_attr( get_term_meta( $editing_term->term_id, 'mwb_bfwp_booking_people_unit_cost', true ) ); ?>"
								style="width:10em;" />
							<p class="description"><?php esc_html_e( 'Enter unit cost i.e. the booking unit cost for the people type that you’re creating to book for.', 'bookings-for-woocommerce-pro' ); ?></p>
						</div>
					</div>

					<div class="mwb-edit-field">
						<label for="mwb-edit-base-cost"><?php esc_html_e( 'Base Cost', 'bookings-for-woocommerce-pro' ); ?></label>
						<div class="mwb-edit-field__control">
							<input type="number" id="mwb-edit-base-cost" name="mwb_bfwp_booking_people_base_cost" min="0" step="0.01"
								value="<?php echo esc_attr( get_term_meta( $editing_term->term_id, 'mwb_bfwp_booking_people_base_cost', true ) ); ?>"
								style="width:10em;" />
							<p class="description"><?php esc_html_e( 'Enter base cost i.e. the base rental cost for the people type that you’re creating to book for.', 'bookings-for-woocommerce-pro' ); ?></p>
						</div>
					</div>

					<div class="mwb-edit-field">
						<label for="mwb-edit-min-qty"><?php esc_html_e( 'Minimum Quantity', 'bookings-for-woocommerce-pro' ); ?></label>
						<div class="mwb-edit-field__control">
							<input type="number" id="mwb-edit-min-qty" name="mwb_mbfw_minimum_people_per_booking"
								value="<?php echo esc_attr( get_term_meta( $editing_term->term_id, 'mwb_mbfw_minimum_people_per_booking', true ) ); ?>"
								style="width:10em;" />
							<p class="description"><?php esc_html_e( 'Minimum Quantity of peoples allowed for respective people type that you’re creating.', 'bookings-for-woocommerce-pro' ); ?></p>
						</div>
					</div>

					<div class="mwb-edit-field">
						<label for="mwb-edit-max-qty"><?php esc_html_e( 'Maximum Quantity', 'bookings-for-woocommerce-pro' ); ?></label>
						<div class="mwb-edit-field__control">
							<input type="number" id="mwb-edit-max-qty" name="mwb_bfwp_booking_people_maximum_quantity"
								value="<?php echo esc_attr( get_term_meta( $editing_term->term_id, 'mwb_bfwp_booking_people_maximum_quantity', true ) ); ?>"
								style="width:10em;" />
							<p class="description"><?php esc_html_e( 'Maximum Quantity of peoples allowed for respective people type that you’re creating.', 'bookings-for-woocommerce-pro' ); ?></p>
						</div>
					</div>
				<?php endif; ?>

				<!-- Actions -->
				<div class="mwb-edit-field mwb-edit-field--actions">
					<label></label>
					<div class="mwb-edit-field__control mwb-edit-actions">
						<button type="submit" class="mwb-btn mwb-btn--primary"><?php esc_html_e( 'Update', 'mwb-bookings-for-woocommerce' ); ?></button>
						<a href="<?php echo esc_url( add_query_arg( array( 'mwb_tax_delete' => $editing_term->term_id, '_wpnonce' => wp_create_nonce( 'mwb_delete_term_' . $editing_term->term_id ) ), $page_url ) ); ?>"
							class="mwb-edit-delete-link"
							onclick="return confirm('<?php esc_attr_e( 'Are you sure you want to delete this item?', 'mwb-bookings-for-woocommerce' ); ?>')">
							<?php esc_html_e( 'Delete', 'mwb-bookings-for-woocommerce' ); ?>
						</a>
					</div>
				</div>

			</form>
		</div><!-- /.mwb-inline-taxonomy__edit-wrap -->

	<?php else : ?>

		<!-- ============================= -->
		<!-- ADD NEW + LISTING (two-col)   -->
		<!-- ============================= -->
		<div class="mwb-inline-taxonomy__layout">

			<!-- LEFT: Add New Form -->
			<div class="mwb-inline-taxonomy__add-col">
				<p class="mwb-inline-taxonomy__desc"><?php echo esc_html( $tax_desc ); ?></p>

				<h3 class="mwb-inline-taxonomy__form-title"><?php echo esc_html( $add_new_label ); ?></h3>

				<form method="post" action="<?php echo esc_url( $page_url ); ?>" class="mwb-inline-taxonomy__form">
					<?php wp_nonce_field( 'mwb_inline_add_term_' . $taxonomy, '_mwb_inline_nonce' ); ?>
					<input type="hidden" name="mwb_inline_tax_action" value="add-tag" />

					<div class="mwb-tax-field">
						<label for="mwb-tax-name"><?php esc_html_e( 'Name', 'mwb-bookings-for-woocommerce' ); ?></label>
						<div class="mwb-tax-field__input">
							<input type="text" id="mwb-tax-name" name="tag-name" value="" />
							<p class="description"><?php esc_html_e( 'The name is how it appears on your site.', 'mwb-bookings-for-woocommerce' ); ?></p>
						</div>
					</div>

					<div class="mwb-tax-field">
						<label for="mwb-tax-slug"><?php esc_html_e( 'Slug', 'mwb-bookings-for-woocommerce' ); ?></label>
						<div class="mwb-tax-field__input">
							<input type="text" id="mwb-tax-slug" name="slug" value="" />
							<p class="description"><?php esc_html_e( 'The &ldquo;slug&rdquo; is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.', 'mwb-bookings-for-woocommerce' ); ?></p>
						</div>
					</div>

					<div class="mwb-tax-field">
						<label for="mwb-tax-desc"><?php esc_html_e( 'Description', 'mwb-bookings-for-woocommerce' ); ?></label>
						<div class="mwb-tax-field__input">
							<textarea id="mwb-tax-desc" name="description" rows="5"></textarea>
							<p class="description"><?php esc_html_e( 'The description is not prominent by default; however, some themes may show it.', 'mwb-bookings-for-woocommerce' ); ?></p>
						</div>
					</div>

					<?php if ( 'mwb_booking_cost' === $taxonomy ) : ?>

						<div class="mwb-tax-field">
							<label for="mwb_mbfw_booking_cost"><?php esc_html_e( 'Booking Cost', 'mwb-bookings-for-woocommerce' ); ?></label>
							<div class="mwb-tax-field__input">
								<input type="number" id="mwb_mbfw_booking_cost" name="mwb_mbfw_booking_cost" min="0" step="0.01" style="width:10em;" />
								<p class="description"><?php esc_html_e( 'Please Add Booking cost here.', 'mwb-bookings-for-woocommerce' ); ?></p>
							</div>
						</div>

						<div class="mwb-tax-field mwb-tax-field--toggle">
							<label for="mwb_mbfw_is_booking_cost_multiply_people"><?php esc_html_e( 'Multiply by No. of People', 'mwb-bookings-for-woocommerce' ); ?></label>
							<div class="mwb-tax-field__input">
								<input type="checkbox" class="mwb-tax-toggle" id="mwb_mbfw_is_booking_cost_multiply_people" name="mwb_mbfw_is_booking_cost_multiply_people" value="yes" />
								<p class="description"><?php esc_html_e( 'Either to multiply by number of people.', 'mwb-bookings-for-woocommerce' ); ?></p>
							</div>
						</div>

						<div class="mwb-tax-field mwb-tax-field--toggle">
							<label for="mwb_mbfw_is_booking_cost_multiply_duration"><?php esc_html_e( 'Multiply by Duration', 'mwb-bookings-for-woocommerce' ); ?></label>
							<div class="mwb-tax-field__input">
								<input type="checkbox" class="mwb-tax-toggle" id="mwb_mbfw_is_booking_cost_multiply_duration" name="mwb_mbfw_is_booking_cost_multiply_duration" value="yes" />
								<p class="description"><?php esc_html_e( 'Either to multiply by Duration of Booking.', 'mwb-bookings-for-woocommerce' ); ?></p>
							</div>
						</div>

					<?php elseif ( 'mwb_booking_service' === $taxonomy ) : ?>

						<div class="mwb-tax-field">
							<label for="mwb_mbfw_service_cost"><?php esc_html_e( 'Service Cost', 'mwb-bookings-for-woocommerce' ); ?></label>
							<div class="mwb-tax-field__input">
								<input type="number" id="mwb_mbfw_service_cost" name="mwb_mbfw_service_cost" min="0" step="0.01" style="width:10em;" />
								<p class="description"><?php esc_html_e( 'Please Add service cost here.', 'mwb-bookings-for-woocommerce' ); ?></p>
							</div>
						</div>

						<div class="mwb-tax-field mwb-tax-field--toggle">
							<label for="mwb_mbfw_is_service_cost_multiply_people"><?php esc_html_e( 'Multiply by Number of People', 'mwb-bookings-for-woocommerce' ); ?></label>
							<div class="mwb-tax-field__input">
								<input type="checkbox" class="mwb-tax-toggle" id="mwb_mbfw_is_service_cost_multiply_people" name="mwb_mbfw_is_service_cost_multiply_people" value="yes" />
								<p class="description"><?php esc_html_e( 'Either to multiply by number of people.', 'mwb-bookings-for-woocommerce' ); ?></p>
							</div>
						</div>

						<div class="mwb-tax-field mwb-tax-field--toggle">
							<label for="mwb_mbfw_is_service_cost_multiply_duration"><?php esc_html_e( 'Multiply by Booking Duration', 'mwb-bookings-for-woocommerce' ); ?></label>
							<div class="mwb-tax-field__input">
								<input type="checkbox" class="mwb-tax-toggle" id="mwb_mbfw_is_service_cost_multiply_duration" name="mwb_mbfw_is_service_cost_multiply_duration" value="yes" />
								<p class="description"><?php esc_html_e( 'Either to multiply by Booking Duration.', 'mwb-bookings-for-woocommerce' ); ?></p>
							</div>
						</div>

						<div class="mwb-tax-field mwb-tax-field--toggle">
							<label for="mwb_mbfw_is_service_optional"><?php esc_html_e( 'If Optional', 'mwb-bookings-for-woocommerce' ); ?></label>
							<div class="mwb-tax-field__input">
								<input type="checkbox" class="mwb-tax-toggle" id="mwb_mbfw_is_service_optional" name="mwb_mbfw_is_service_optional" value="yes" />
								<p class="description"><?php esc_html_e( 'Either the Service is Optional.', 'mwb-bookings-for-woocommerce' ); ?></p>
							</div>
						</div>

						<div class="mwb-tax-field mwb-tax-field--toggle">
							<label for="mwb_mbfw_is_service_hidden"><?php esc_html_e( 'If Hidden', 'mwb-bookings-for-woocommerce' ); ?></label>
							<div class="mwb-tax-field__input">
								<input type="checkbox" class="mwb-tax-toggle" id="mwb_mbfw_is_service_hidden" name="mwb_mbfw_is_service_hidden" value="yes" />
								<p class="description"><?php esc_html_e( 'Either the Service is Hidden.', 'mwb-bookings-for-woocommerce' ); ?></p>
							</div>
						</div>

						<div class="mwb-tax-field mwb-tax-field--toggle">
							<label for="mwb_mbfw_is_service_has_quantity"><?php esc_html_e( 'If has Quantity', 'mwb-bookings-for-woocommerce' ); ?></label>
							<div class="mwb-tax-field__input">
								<input type="checkbox" class="mwb-tax-toggle" id="mwb_mbfw_is_service_has_quantity" name="mwb_mbfw_is_service_has_quantity" value="yes" />
								<p class="description"><?php esc_html_e( 'Either the service has quantity.', 'mwb-bookings-for-woocommerce' ); ?></p>
							</div>
						</div>


					<?php elseif ( 'mwb_booking_people' === $taxonomy ) : ?>

						<div class="mwb-tax-field">
							<label for="mwb_bfwp_booking_people_unit_cost"><?php esc_html_e( 'Unit Cost', 'bookings-for-woocommerce-pro' ); ?></label>
							<div class="mwb-tax-field__input">
								<input type="number" id="mwb_bfwp_booking_people_unit_cost" name="mwb_bfwp_booking_people_unit_cost" min="0" step="0.01" style="width:10em;" />
								<p class="description"><?php esc_html_e( 'Enter unit cost i.e. the booking unit cost for the people type that you’re creating to book for.', 'bookings-for-woocommerce-pro' ); ?></p>
							</div>
						</div>

						<div class="mwb-tax-field">
							<label for="mwb_bfwp_booking_people_base_cost"><?php esc_html_e( 'Base Cost', 'bookings-for-woocommerce-pro' ); ?></label>
							<div class="mwb-tax-field__input">
								<input type="number" id="mwb_bfwp_booking_people_base_cost" name="mwb_bfwp_booking_people_base_cost" min="0" step="0.01" style="width:10em;" />
								<p class="description"><?php esc_html_e( 'Enter base cost i.e. the base rental cost for the people type that you’re creating to book for.', 'bookings-for-woocommerce-pro' ); ?></p>
							</div>
						</div>

						<div class="mwb-tax-field">
							<label for="mwb_mbfw_minimum_people_per_booking"><?php esc_html_e( 'Minimum Quantity', 'bookings-for-woocommerce-pro' ); ?></label>
							<div class="mwb-tax-field__input">
								<input type="number" id="mwb_mbfw_minimum_people_per_booking" name="mwb_mbfw_minimum_people_per_booking" style="width:10em;" />
								<p class="description"><?php esc_html_e( 'Minimum Quantity of peoples allowed for respective people type that you’re creating.', 'bookings-for-woocommerce-pro' ); ?></p>
							</div>
						</div>

						<div class="mwb-tax-field">
							<label for="mwb_bfwp_booking_people_maximum_quantity"><?php esc_html_e( 'Maximum Quantity', 'bookings-for-woocommerce-pro' ); ?></label>
							<div class="mwb-tax-field__input">
								<input type="number" id="mwb_bfwp_booking_people_maximum_quantity" name="mwb_bfwp_booking_people_maximum_quantity" style="width:10em;" />
								<p class="description"><?php esc_html_e( 'Maximum Quantity of peoples allowed for respective people type that you’re creating.', 'bookings-for-woocommerce-pro' ); ?></p>
							</div>
						</div>
					<?php endif; ?>

					<div class="mwb-tax-field mwb-tax-field--submit">
						<label></label>
						<div class="mwb-tax-field__input">
							<button type="submit" class="mwb-btn mwb-btn--primary"><?php echo esc_html( $add_new_label ); ?></button>
						</div>
					</div>

				</form>
			</div><!-- /.mwb-inline-taxonomy__add-col -->

			<!-- RIGHT: Terms Listing Table -->
			<div class="mwb-inline-taxonomy__table-col">

				<!-- Search -->
				<form method="get" action="<?php echo esc_url( admin_url( 'admin.php' ) ); ?>" class="mwb-inline-taxonomy__search-form">
					<input type="hidden" name="page" value="mwb_bookings_for_woocommerce_menu" />
					<input type="hidden" name="mbfw_tab" value="mwb-bookings-for-woocommerce-configuration" />
					<input type="hidden" name="bfw_sub_nav" value="<?php echo esc_attr( $taxonomy ); ?>" />
					<input type="text" name="s" value="<?php echo esc_attr( $search ); ?>" class="mwb-inline-taxonomy__search-input" />
					<button type="submit" class="button"><?php echo esc_html( $search_label ); ?></button>
				</form>

				<!-- Bulk Action Form -->
				<form method="post" action="<?php echo esc_url( $page_url ); ?>" class="mwb-inline-taxonomy__bulk-form">
					<?php wp_nonce_field( 'mwb_bulk_action_terms_' . $taxonomy, '_mwb_bulk_nonce' ); ?>

					<div class="mwb-inline-taxonomy__bulk-bar">
						<select name="mwb_bulk_action">
							<option value="-1"><?php esc_html_e( 'Bulk actions', 'mwb-bookings-for-woocommerce' ); ?></option>
							<option value="delete"><?php esc_html_e( 'Delete', 'mwb-bookings-for-woocommerce' ); ?></option>
						</select>
						<button type="submit" class="button mwb-inline-taxonomy__apply-btn"><?php esc_html_e( 'Apply', 'mwb-bookings-for-woocommerce' ); ?></button>
						<span class="mwb-inline-taxonomy__count">
							<?php echo esc_html( sprintf( _n( '%d item', '%d items', $total_items, 'mwb-bookings-for-woocommerce' ), $total_items ) ); ?>
						</span>
					</div>

					<table class="mwb-inline-taxonomy__table widefat">
						<thead>
							<tr>
								<td class="check-column"><input type="checkbox" id="mwb-tax-select-all" /></td>
								<th><?php esc_html_e( 'Name', 'mwb-bookings-for-woocommerce' ); ?></th>
								<?php if ( 'mwb_booking_cost' === $taxonomy ) : ?>
									<th><?php esc_html_e( 'Cost', 'mwb-bookings-for-woocommerce' ); ?></th>
									<th><?php esc_html_e( 'People', 'mwb-bookings-for-woocommerce' ); ?></th>
									<th><?php esc_html_e( 'Duration', 'mwb-bookings-for-woocommerce' ); ?></th>
								<?php elseif ( 'mwb_booking_people' === $taxonomy ) : ?>
									<th><?php esc_html_e( 'Unit Cost', 'bookings-for-woocommerce-pro' ); ?></th>
									<th><?php esc_html_e( 'Base Cost', 'bookings-for-woocommerce-pro' ); ?></th>
									<th><?php esc_html_e( 'Minimum Quantity', 'bookings-for-woocommerce-pro' ); ?></th>
									<th><?php esc_html_e( 'Maximum Quantity', 'bookings-for-woocommerce-pro' ); ?></th>
								<?php else : ?>
									<th><?php esc_html_e( 'Cost', 'mwb-bookings-for-woocommerce' ); ?></th>
									<th><?php esc_html_e( 'People', 'mwb-bookings-for-woocommerce' ); ?></th>
									<th><?php esc_html_e( 'Duration', 'mwb-bookings-for-woocommerce' ); ?></th>
									<th><?php esc_html_e( 'Optional', 'mwb-bookings-for-woocommerce' ); ?></th>
									<th><?php esc_html_e( 'Hidden', 'mwb-bookings-for-woocommerce' ); ?></th>
									<th><?php esc_html_e( 'Quantity', 'mwb-bookings-for-woocommerce' ); ?></th>
								<?php endif; ?>
							</tr>
						</thead>
						<tbody>
							<?php if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) : ?>
								<?php foreach ( $terms as $term ) : ?>
									<?php
									$row_edit_url = add_query_arg( 'mwb_tax_edit', $term->term_id, $page_url );
									$row_del_url  = add_query_arg(
										array(
											'mwb_tax_delete' => $term->term_id,
											'_wpnonce'       => wp_create_nonce( 'mwb_delete_term_' . $term->term_id ),
										),
										$page_url
									);
									?>
									<tr class="mwb-inline-taxonomy__row">
										<th class="check-column">
											<input type="checkbox" name="delete_tags[]" value="<?php echo esc_attr( $term->term_id ); ?>" />
										</th>
										<td class="column-name">
											<strong><a href="<?php echo esc_url( $row_edit_url ); ?>"><?php echo esc_html( $term->name ); ?></a></strong>
											<div class="row-actions">
												<span class="edit"><a href="<?php echo esc_url( $row_edit_url ); ?>"><?php esc_html_e( 'Edit', 'mwb-bookings-for-woocommerce' ); ?></a> | </span>
												<span class="delete">
													<a href="<?php echo esc_url( $row_del_url ); ?>" class="mwb-tax-delete-link"
														onclick="return confirm('<?php esc_attr_e( 'Are you sure you want to delete this item?', 'mwb-bookings-for-woocommerce' ); ?>')">
														<?php esc_html_e( 'Delete', 'mwb-bookings-for-woocommerce' ); ?>
													</a>
												</span>
											</div>
										</td>
										<?php if ( 'mwb_booking_cost' === $taxonomy ) : ?>
											<td><?php echo esc_html( get_term_meta( $term->term_id, 'mwb_mbfw_booking_cost', true ) ); ?></td>
											<td><?php echo ( 'yes' === get_term_meta( $term->term_id, 'mwb_mbfw_is_booking_cost_multiply_people', true ) ) ? wp_kses_post( $icon_yes ) : wp_kses_post( $icon_no ); ?></td>
											<td><?php echo ( 'yes' === get_term_meta( $term->term_id, 'mwb_mbfw_is_booking_cost_multiply_duration', true ) ) ? wp_kses_post( $icon_yes ) : wp_kses_post( $icon_no ); ?></td>
										<?php elseif ( 'mwb_booking_people' === $taxonomy ) : ?>
											<td><?php echo esc_html( get_term_meta( $term->term_id, 'mwb_bfwp_booking_people_unit_cost', true ) ); ?></td>
											<td><?php echo esc_html( get_term_meta( $term->term_id, 'mwb_bfwp_booking_people_base_cost', true ) ); ?></td>
											<td><?php echo esc_html( get_term_meta( $term->term_id, 'mwb_mbfw_minimum_people_per_booking', true ) ); ?></td>
											<td><?php echo esc_html( get_term_meta( $term->term_id, 'mwb_bfwp_booking_people_maximum_quantity', true ) ); ?></td>
										<?php else : ?>
											<td><?php echo esc_html( get_term_meta( $term->term_id, 'mwb_mbfw_service_cost', true ) ); ?></td>
											<td><?php echo ( 'yes' === get_term_meta( $term->term_id, 'mwb_mbfw_is_service_cost_multiply_people', true ) ) ? wp_kses_post( $icon_yes ) : wp_kses_post( $icon_no ); ?></td>
											<td><?php echo ( 'yes' === get_term_meta( $term->term_id, 'mwb_mbfw_is_service_cost_multiply_duration', true ) ) ? wp_kses_post( $icon_yes ) : wp_kses_post( $icon_no ); ?></td>
											<td><?php echo ( 'yes' === get_term_meta( $term->term_id, 'mwb_mbfw_is_service_optional', true ) ) ? wp_kses_post( $icon_yes ) : wp_kses_post( $icon_no ); ?></td>
											<td><?php echo ( 'yes' === get_term_meta( $term->term_id, 'mwb_mbfw_is_service_hidden', true ) ) ? wp_kses_post( $icon_yes ) : wp_kses_post( $icon_no ); ?></td>
											<td><?php echo ( 'yes' === get_term_meta( $term->term_id, 'mwb_mbfw_is_service_has_quantity', true ) ) ? wp_kses_post( $icon_yes ) : wp_kses_post( $icon_no ); ?></td>
										<?php endif; ?>
									</tr>
								<?php endforeach; ?>
							<?php else : ?>
								<tr>
									<td colspan="<?php echo 'mwb_booking_cost' === $taxonomy ? 5 : ( 'mwb_booking_people' === $taxonomy ? 6 : 8 ); ?>" class="mwb-inline-taxonomy__no-items">
										<?php esc_html_e( 'No items found.', 'mwb-bookings-for-woocommerce' ); ?>
									</td>
								</tr>
							<?php endif; ?>
						</tbody>
						<tfoot>
							<tr>
								<td class="check-column"><input type="checkbox" /></td>
								<th><?php esc_html_e( 'Name', 'mwb-bookings-for-woocommerce' ); ?></th>
								<?php if ( 'mwb_booking_cost' === $taxonomy ) : ?>
									<th><?php esc_html_e( 'Cost', 'mwb-bookings-for-woocommerce' ); ?></th>
									<th><?php esc_html_e( 'People', 'mwb-bookings-for-woocommerce' ); ?></th>
									<th><?php esc_html_e( 'Duration', 'mwb-bookings-for-woocommerce' ); ?></th>
								<?php elseif ( 'mwb_booking_people' === $taxonomy ) : ?>
									<th><?php esc_html_e( 'Unit Cost', 'bookings-for-woocommerce-pro' ); ?></th>
									<th><?php esc_html_e( 'Base Cost', 'bookings-for-woocommerce-pro' ); ?></th>
									<th><?php esc_html_e( 'Minimum Quantity', 'bookings-for-woocommerce-pro' ); ?></th>
									<th><?php esc_html_e( 'Maximum Quantity', 'bookings-for-woocommerce-pro' ); ?></th>
								<?php else : ?>
									<th><?php esc_html_e( 'Cost', 'mwb-bookings-for-woocommerce' ); ?></th>
									<th><?php esc_html_e( 'People', 'mwb-bookings-for-woocommerce' ); ?></th>
									<th><?php esc_html_e( 'Duration', 'mwb-bookings-for-woocommerce' ); ?></th>
									<th><?php esc_html_e( 'Optional', 'mwb-bookings-for-woocommerce' ); ?></th>
									<th><?php esc_html_e( 'Hidden', 'mwb-bookings-for-woocommerce' ); ?></th>
									<th><?php esc_html_e( 'Quantity', 'mwb-bookings-for-woocommerce' ); ?></th>
								<?php endif; ?>
							</tr>
						</tfoot>
					</table>

					<div class="mwb-inline-taxonomy__bulk-bar mwb-inline-taxonomy__bulk-bar--bottom">
						<select name="mwb_bulk_action">
							<option value="-1"><?php esc_html_e( 'Bulk actions', 'mwb-bookings-for-woocommerce' ); ?></option>
							<option value="delete"><?php esc_html_e( 'Delete', 'mwb-bookings-for-woocommerce' ); ?></option>
						</select>
						<button type="submit" class="button mwb-inline-taxonomy__apply-btn"><?php esc_html_e( 'Apply', 'mwb-bookings-for-woocommerce' ); ?></button>
						<span class="mwb-inline-taxonomy__count">
							<?php echo esc_html( sprintf( _n( '%d item', '%d items', $total_items, 'mwb-bookings-for-woocommerce' ), $total_items ) ); ?>
						</span>
					</div>

				</form>
			</div><!-- /.mwb-inline-taxonomy__table-col -->

		</div><!-- /.mwb-inline-taxonomy__layout -->

	<?php endif; ?>

</div><!-- /.mwb-inline-taxonomy -->

<script>
( function() {
	// Wrap every .mwb-tax-toggle checkbox in a label+span for the custom switch UI.
	// The real checkbox is hidden; the <span> track is styled via CSS.
	document.querySelectorAll( '.mwb-tax-toggle' ).forEach( function( checkbox ) {
		var wrapper = document.createElement( 'label' );
		wrapper.className = 'mwb-tax-switch';

		var track = document.createElement( 'span' );
		track.className = 'mwb-tax-switch__track';

		checkbox.parentNode.insertBefore( wrapper, checkbox );
		wrapper.appendChild( checkbox );
		wrapper.appendChild( track );
	} );

	// Wrap bulk-action table checkboxes with .wps-checkbox structure (orange box + blue halo).
	document.querySelectorAll( '.mwb-inline-taxonomy__table .check-column input[type="checkbox"]' ).forEach( function( checkbox ) {
		var wrapper = document.createElement( 'label' );
		wrapper.className = 'wps-checkbox mwb-tax-table-cb';

		var box = document.createElement( 'span' );
		box.className = 'wps-checkbox__box';

		checkbox.classList.add( 'wps-checkbox__input' );
		checkbox.parentNode.insertBefore( wrapper, checkbox );
		wrapper.appendChild( checkbox );
		wrapper.appendChild( box );
	} );

	// Select-all checkbox for bulk actions.
	var selectAll = document.getElementById( 'mwb-tax-select-all' );
	if ( selectAll ) {
		selectAll.addEventListener( 'change', function() {
			document.querySelectorAll( '.mwb-inline-taxonomy__bulk-form input[name="delete_tags[]"]' ).forEach( function( cb ) {
				cb.checked = selectAll.checked;
			} );
		} );
	}
} )();
</script>
