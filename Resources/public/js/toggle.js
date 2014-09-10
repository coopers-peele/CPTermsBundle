$( ".description a.toggle" ).click(function( e ) {
	e.preventDefault();
	$( this ).find( "i.fa" ).toggleClass( "fa-chevron-up" ).toggleClass( "fa-chevron-down" );
	$( this ).closest( ".description" ).find( ".panel-collapse" ).toggleClass( "in" );
});
