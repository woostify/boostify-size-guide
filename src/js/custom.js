(function ($) {
    var category = $( '.display-on' ),
        categoryInput = $( '.product-category-data' );

    // Get defalt value.
    category.val( categoryInput.val().split( ',' ) );

    // Select 2 init.
    category.select2({
        placeholder: 'Select Product Category'
    });

    // Update product category value.
    category.on( 'change', function( e ) {
        var inputVal = '';
        if ( $( this ).val() ) {
            inputVal = $( this ).val().join( ',' );
        }
        categoryInput.val( inputVal );
    } );



    // Product filter.
    var product = $( '.display-product-on' ),
        productInput = $( '.product-data' );

    // Get defalt value.
    product.val( productInput.val().split( ',' ) );

    // Select 2 init.
    product.select2({
        placeholder: 'Select Product',
        allowClear: true,
        closeOnSelect: false,
    });

    // Update product value.
    product.on( 'change', function( e ) {
        var inputVal = '';
        if ( $( this ).val() ) {
            inputVal = $( this ).val().join( ',' );
        }
        productInput.val( inputVal );
    } );
} )( jQuery );