$( document ).ready(function() {
	$( "a.confirm" ).click(function( e ) {
		e.preventDefault();

		var url = $( this ).attr( "href" ),
			msg = Mustache.render(
				$( this ).data( "confirm" ),
				{}
			);

		bootbox.confirm(
			msg,
			function( result ) {
				if ( result ) {
					window.location = url;
				}
			}
		);
	});
});