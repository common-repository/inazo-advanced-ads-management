function changeAdds(currentWidget){
								
	
	idOfCurrentAdds = jQuery("#inazo_adds_current_"+currentWidget).val();
	
	nextAdds = parseInt(idOfCurrentAdds) + 1;
	
	if( nextAdds > jQuery("#inazo_adds_maximum_"+currentWidget).val() )
		nextAdds = 0;
	
	jQuery("#inazo_adds_current_"+currentWidget).val(nextAdds);
	
	jQuery( "div.inazo_adds_"+currentWidget+"_"+idOfCurrentAdds ).delay(5000).fadeOut( 800 );
	jQuery( "div.inazo_adds_"+currentWidget+"_"+nextAdds ).delay(5000).fadeIn( 300, function(){

		changeAdds(currentWidget);
	} );
}
	
jQuery(document).ready(function() {
	
	if ( jQuery( ".inazo_ads_multi_need" ).length ){
		
		jQuery('.inazo_ads_multi_need').each(function(){
	
			var currentWidget = jQuery(this).val();
			
			changeAdds(currentWidget);
		});
	}
});