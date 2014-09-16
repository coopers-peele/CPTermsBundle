!function( $ ) {
	$.fn.cpterms = function( method ) {

		var settings;
			editing = false; // flag to indcate a section is currently being edited

		var methods = {
			init: function( options ) {
				settings = $.extend( true, {}, this.cpterms.defaults, options );

				return this.each(function() {
					var $this = $( this );

					helpers.initTerms( $this );
					helpers.initSectionToolbar( $this );
				});
			}
		};

		var helpers = {

			loading: function( element, show, after ) {
				if ( show ) {
					var markup = "<div class='loading'><i class='fa fa-spin fa-refresh fa-3x'></i></div>";

					if ( "undefined" != typeof after ) {
						element
							.find( after )
							.hide()
							.last()
							.after( markup );
					} else {
						element
							.append( markup );
					}
				} else {
					$( "div.loading" ).remove();
				}
			},

			initTerms: function( e ) {

				$( ".tos .section" ).hover(
					function( e ) {
						$( this ).parent().closest( ".section" ).removeClass( "hover" );
						$( this ).addClass( "hover" );
						e.stopPropagation();
					},
					function() {
						$( this ).removeClass( "hover" );
						$( this ).parent().closest( ".section" ).addClass( "hover" );
					}
				);

				if ( settings.sortable ) {
					var url = $( ".tos" ).data( "drop-url" );

					$( ".tos > ul" ).nestedSortable({
							handle: 'div',
							items: 'li.section',
							listType: 'ul',
							placeholder: 'placeholder',
							toleranceElement: '> div',
							isAllowed: function(placeholder, placeholderParent, originalItem) {
								return true;
							},
							protectRoot: true,
							relocate: function( event, $item, event3 ) {
								console.log("relocated");
								var dragged_id = $item.item.data( "section-id" ),
									target = $item.item.parent().closest('.section'),
									target_id = target.data( "section-id" );

								$.ajax( url, {
									data: {
										id: dragged_id,
										dest_id: target_id
									}
								});
							},
							change: function() {
								console.log('placeholder changed position');
							},
							sort: function() {
								console.log('dragging');
							}
						}
					);
				}

				if ( $( ".preview", e ).length && !$( ".preview", e ).hasClass( "diff" ) ) {
				}

				$( ".dropdown-menu a" ).click(function() {
					$( this ).closest( ".dropdown-menu" ).prev().dropdown( "toggle" );
				});
			},

			enableDnD: function() {
				$( ".tos > ul" ).sortable( "enable" );
			},

			disableDnD: function() {
				$( ".tos > ul" ).sortable( "disable" );
			},

			initSectionToolbar: function( e ) {
				$( ".section .dropdown-menu a.edit", e ).click(function() {
					if ( editing ) {
						return false;
					}

					var section = $( this ).closest( ".section" );

					helpers.loading( section, true, "> .body" );

					editing = true;

					helpers.disableDnD(); // Disable sortable

					$.ajax({
						type: "GET",
						url: $( this ).attr( "href" ),
						error: function( request, status, error ) {
							if ( settings.debug ) {
								console.log( error );

								helpers.loading( section, false );
							}
							else {
								location.reload( true );
							}
						},
						success: function( data ) {
							section.find( "> .body" )
								.after( data );

							helpers.initSectionForm( section );

							helpers.loading( section, false );
						}
					});

					return false;
				});

				$( ".section .dropdown-menu a.add_child", e ).click(function() {
					if ( editing ) {
						return false;
					}

					var section = $( this ).closest( ".section" );

					helpers.loading( section, true );

					editing = true;

					helpers.disableDnD(); // Disable sortable

					$.ajax({
						type: "GET",
						url: $( this ).attr( "href" ),
						error: function( request, status, error ) {
							if ( settings.debug ) {
								console.log( error );

								helpers.loading( section, false );
							}
							else {
								location.reload( true );
							}
						},
						success: function( data ) {
							section.append( data );

							helpers.initSectionForm( section.find ( ".new" ) );

							helpers.loading( section, false );
						}
					});

					return false;
				});

				$( ".section .dropdown-menu a.add_sibling", e ).click(function() {
					if ( editing ) {
						return false;
					}

					var section = $( this ).closest( ".section" ),
						parent = section.parent().closest( ".section" );

					helpers.loading( parent, true, true );

					editing = true;

					helpers.disableDnD(); // Disable sortable

					$.ajax({
						type: "GET",
						url: $( this ).attr( "href" ),
						error: function( request, status, error ) {
							if ( settings.debug ) {
								console.log( error );

								helpers.loading( section, false );
							}
							else {
								location.reload( true );
							}
						},
						success: function( data ) {
							section.after( data );

							helpers.initSectionForm( section.parent().find ( ".new" ) );

							helpers.loading( section, false );
						}
					});

					return false;
				});

				$( ".section .dropdown-menu a.delete", e ).click(function() {
					if ( editing ) {
						return false;
					}

					var section = $( this ).closest( ".section" ),
						url = $( this ).attr( "href" );

					editing = true;

					helpers.disableDnD(); // Disable sortable

					var msg = Mustache.render(
						settings.i18n.section.delete.confirm,
						{ section: $( this ).closest( "li.section" ).data( "section" ) }
					 );

					bootbox.confirm(
						msg,
						function( result ) {
							if ( result ) {
								helpers.loading( section, true, "> .body, > ul" );

								window.location = url;
							} else {
								editing = false;

								helpers.enableDnD();

								helpers.loading( section, false );
							}
						}
					 );

					return false;
				});
			},
			initSectionForm: function( section ) {

				var editor = new EpicEditor( $.extend( true, {}, settings.epiceditor, {
					textarea: $( "textarea", section )
				}));

				editor.load();

				$( "form .cancel", section ).click(function() {
					if ( section.hasClass( "new" ) ) {
						section.remove();
					}
					else {
						editor.unload();
						$( this ).parent( "form" ).remove();
						section.find( ".body" ).show();
					}

					editing = false;

					sortable = true;

					return false;
				});
			}
		};

		if ( methods[ method ] ) {
			return methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ) );
		}
		else if ( typeof method === "object" || !method ) {
			return methods.init.apply( this, arguments );
		}
		else {
			$.error( "Method " +  method + " does not exist in jQuery.cpterms." );
		}
	};

	$.fn.cpterms.defaults = {
		debug: true,
		sortable: true,
		epiceditor: {
			autogrow: {
				minHeight: 200
			},
			basePath: "/bundles/cpterms/vendor/epiceditor/epiceditor",
			button: {
				fullscreen: false
			},
			theme: {
				base: "/themes/base/epiceditor.css",
				preview: "/themes/preview/bartik.css",
				editor: "/themes/editor/epic-light.css"
			}
		}
	};
} ( window.jQuery );

$( "document" ).ready(function() {
	$( ".cpterms" ).cpterms( cpterms );
});
