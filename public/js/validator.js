$(document).ready(function() {
	$('form').each(function() {
		id = $(this).parent().attr("id");
		$(this).validator({
			events                  : 'submit',
			selector                : '#' + id + ' input[type!=submit], ' + '#' + id + ' select, ' + '#' + id + ' textarea',
			preventDefaultIfInvalid : true,
			callback                : function( elem, valid ) {
				if ( ! valid ) {
					$( elem ).parents(".control-group").addClass( 'error' );
				} else {
					$( elem ).parents(".control-group").removeClass( 'error' );
				}
			}
		});
	});
});
