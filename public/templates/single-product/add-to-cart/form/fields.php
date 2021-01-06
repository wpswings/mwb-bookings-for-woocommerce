<?php
	global $product;
	$product_data = array(
		'product_id' => $product->get_id(),
	);

?>
<div class="mwb-wc-bk-form-section" product-data = "<?php echo esc_html( htmlspecialchars( wp_json_encode( $product_data ) ) ); ?>">
	<label for="duration"><?php esc_html_e( 'Duration', 'mwb-wc-bk' ); ?></label>
	<input id="mwb-wc-bk-duration-input" class="mwb-wc-bk-form-input mwb-wc-bk-form-input-number" type="number" name="duration" value="1" step="1" min="1">
</div>
