/*
 * Appel de l'ajax NE PAS FAIRE UN APPEL DIRECT !
 */


jQuery(document).ready(function($) {

	function actionImageAdds(){
		
		var data = {
			'action': 'inazo_wp_adds_manager_ajax_add_callback',
			'whatever': 1234
		};
	
		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(ajaxurl, data, function(response) {
			alert('Got this from the server: ' + response);
		});
	}

	
});

jQuery(document).ready(function() {
    jQuery('.dateField').datepicker({
        dateFormat : 'dd-mm-yy'
    });
    
    jQuery('#codeHtmlOfAdds').change(function(){
    	
    	
    	if( jQuery(this).val() != null && jQuery(this).val() != "" ){
    		
    		removeImage();
    	}
    });
    
});

function removeImage(){
	
	jQuery('#id_attach_of_adds').val(0);
	jQuery('#imageOfAdds').html(" ");
	jQuery('#removeImage').remove();
}

/*
 * Appel de Media Library
 */

(function($) {
	var frame;

	$( function() {
		// Fetch available headers and apply jQuery.masonry
		// once the images have loaded.
		var $headers = $('.available-headers');

		$headers.imagesLoaded( function() {
			$headers.masonry({
				itemSelector: '.default-header',
				isRTL: !! ( 'undefined' != typeof isRtl && isRtl )
			});
		});

		// Build the choose from library frame.
		$('#inazo-choose-from-library-link').click( function( event ) {
			
			var $el = $(this);
			event.preventDefault();

			// If the media frame already exists, reopen it.
			if ( frame ) {
				frame.open();
				return;
			}

			// Create the media frame.
			frame = wp.media.frames.customHeader = wp.media({
				// Set the title of the modal.
				title: $el.data('choose'),

				// Tell the modal to show only images.
				library: {
					type: 'image'
				},

				multiple: false,

				// Customize the submit button.
				button: {
					// Set the text of the button.
					text: $el.data('update'),
					// Tell the button not to close the modal, since we're
					// going to refresh the page when the image is selected.
					close: false
				}
			});

			frame.on('open',function() {
                var selection = frame.state().get('selection');

                //Get ids array from
                idOfAdds = $('#id_attach_of_adds').val();

                if( idOfAdds > 0 && idOfAdds != ''){
                    
                    attachment = wp.media.attachment(idOfAdds);
                    attachment.fetch();
                    selection.add( attachment ? [ attachment ] : [] );
                }
            });

			// When an image is selected, run a callback.
			frame.on( 'select', function() {
				// Grab the selected attachment.
				var attachment = frame.state().get('selection').first(),
					link = $el.data('updateLink');

				//$.each(attachment.attributes, function(key, value){ alert(key+"__"+value); })

				//- URL de l'image
				
				$('#id_attach_of_adds').val(attachment.id);
				
				if( attachment.id != null || attachment.id != 0 ){
					
					$('#imageOfAdds').html('<strong>'+attachment.attributes.title+'</strong><br /><img src="'+attachment.attributes.url+'" style="width:80%;"/>');
					$('#inazo-choose-from-library-link').html('Update image');
					$('#zoneBtnImage').append('<a href="javascript:removeImage();" id="removeImage" class="button">Remove this image</a>');
					$('#codeHtmlOfAdds').val(null);
					
				}
				else{
					
					$('#imageOfAdds').html(' ');
				}
				
				frame.close();
			});
			
			frame.open();
		});
	});
}(jQuery));