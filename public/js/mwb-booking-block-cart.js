

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

});