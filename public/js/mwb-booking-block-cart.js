

jQuery(document).ready( function() {

  


    var hasExecuted = false;
    var myArray = [];
    var myArray_data = [];
    if (  window.wc != undefined ) {

    
    const { registerCheckoutFilters } = window.wc.blocksCheckout;
    // Executes when the HTML document is loaded and the DOM is ready.
        const data_modifySubtotalPriceFormat = (
            defaultValue,
            extensions,
            args,
            validation
        ) => {
            
            const isCartContext = args?.context === 'cart';
            const product_id = args?.cartItem.id;
          const product_name = args?.cartItem.name;
          const type = args?.cartItem.type;
          const quantity = args?.cartItem.quantity;

          if (! myArray.includes(product_id)) {
            if ( type == "mwb_booking" ) {
 
                myArray_data['product_name_'+product_id] = product_name;
               
                myArray.push(product_id);
            }
           
        }
                
          
          if ( args?.context !== 'cart' ) {
            return value;
          }
          return '<price/> ';
           
        };
    registerCheckoutFilters( 'example-extension', {
        saleBadgePriceFormat: data_modifySubtotalPriceFormat,
    } );

}

setTimeout(() => {
    debugger;
    var all_booking_product_length = booking_block_public_param.quantity__check.length;
    var all_booking_product = booking_block_public_param.quantity__check;

   

   var booking_minus = jQuery('.wc-block-components-quantity-selector__button--minus');


   if ( booking_block_public_param.not_fixed_value != "not" ){

   
   if ( booking_minus.length > 0 ) {

    for (let index = 0; index < booking_minus.length; index++) {
        if (  all_booking_product_length > 0 ) {

            for (let index_booking = 0; index_booking < all_booking_product_length; index_booking++) {
                var minus_product = 'Reduce quantity of '+all_booking_product[index_booking];
                var minus_product_selector = jQuery(jQuery('.wc-block-components-quantity-selector__button--minus')[index]).attr('aria-label');
                if (minus_product == minus_product_selector ) {
                    jQuery(jQuery('.wc-block-components-quantity-selector__button--minus')[index]).attr('disabled','disabled');
                }   
            }
        

        }
       
    
    }

   }
}
   
  }, "1000");


});