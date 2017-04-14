(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

})( jQuery );

google.maps.event.addDomListener( window, 'load', gmaps_results_initialize );		

/**		
* Renders a Google Maps centered on Atlanta, Georgia. This is done by using		 
* the Latitude and Longitude for the city.		 
*		 
* Getting the coordinates of a city can easily be done using the tool availabled		 
* at: http://www.latlong.net		 
*		 
* @since    1.0.0		 
*		 
*/		

function gmaps_results_initialize() {		
	if ( null === document.getElementById( 'map-canvas' ) ) {				
		return;			
	}		
	var map, marker, infowindow, i;		
	map = new google.maps.Map( document.getElementById( 'map-canvas' ), {		
		zoom:           7,				
		center:         new google.maps.LatLng( 33.748995, -84.387982 ),		
	});		
	// Place a marker in Atlanta			
	marker = new google.maps.Marker({		
		position: new google.maps.LatLng( 33.748995, -84.387982 ),				
		map:      map,				
		content:  "Atlanta, Georgia"		
	});		
	// Add an InfoWindow for Atlanta			
	infowindow = new google.maps.InfoWindow();			
	google.maps.event.addListener( marker, 'click', ( function( marker ) {		
		return function() {		
			infowindow.setContent( marker.content );					
			infowindow.open( map, marker );		
		}		
	})( marker ));		
	// Place a marker in Alpharetta			
	marker = new google.maps.Marker({		
		position: new google.maps.LatLng( 34.075376, -84.294090 ),				
		map:      map,				
		content:  "Alpharetta, Georgia"		
	});		
	// Add an InfoWindow for Alpharetta			
	infowindow = new google.maps.InfoWindow();			
	google.maps.event.addListener( marker, 'click', ( function( marker ) {		
		return function() {		
			infowindow.setContent( marker.content );					
			infowindow.open( map, marker );		
		}		
	})( marker ));		
}