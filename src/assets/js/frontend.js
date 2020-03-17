jQuery( document ).ready( function ( e ) {
	
});

window.ocvb = {
    init: function () {
        if ( jQuery( '#ocvb-container' ).size() > 0 ) {
            var container_height = jQuery( '#ocvb-container' ).outerHeight();
            jQuery( 'body' ).prepend( jQuery( '#ocvb-container' ) );
            jQuery( '#ocvb-container' ).css( 'height', container_height ).removeClass( 'not-ready' ).addClass( 'ready' );
            setTimeout( function () { 
                jQuery( '#ocvb-container' ).removeClass( 'ready' ).addClass( 'ready-and-display' );
                window.scrollTo({top: 0, left: 0, behavior: 'auto'});
            }, 100 );
        }
    }
}