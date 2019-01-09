/**
 * Initialise l'objet "indicator" ainsi que la méthode "init" obligatoire pour la bibliothèque EoxiaJS.
 *
 * @since 1.5.0
 * @version 1.5.0
 */
window.eoxiaJS.taskManager.indicator = {};

window.eoxiaJS.taskManager.indicator.init = function() {
	window.eoxiaJS.taskManager.indicator.event();
};

window.eoxiaJS.taskManager.indicator.event = function() {
	jQuery( document ).on( 'click', '.page-indicator button.handlediv', window.eoxiaJS.taskManager.indicator.toggleMetabox );
};

window.eoxiaJS.taskManager.indicator.toggleMetabox = function( event ) {
	// var data = {
	// 	"action": ":closed-postboxes",
	// 	"closed": ":wpeo-task-metabox",
	// 	"hidden": "slugdiv",
	// 	"closedpostboxesnonce": "nonce",
	// };
  //
	// window.eoxiaJS.request.send( jQuery( this ), data, function( triggeredElement, response ) {
  //
	// } );
}

/**
 * Le callback en cas de réussite à la requête Ajax "load_customer_activity".
 *
 * Affiche les canvas
 *
 * @author Corentin Eoxia
 * @since 1.8.0
 */
window.eoxiaJS.taskManager.indicator.loadedCustomerActivity = function( triggeredElement, response ) {
	jQuery( '#tm-indicator-activity .inside' ).html( response.data.view );
	jQuery( '#displaycanvas' ).html( '' );
	var data = response.data.object;

	if( data.length != 0 ){
		jQuery("#horizontalChart").css('display','block');
		jQuery("#doghnutChart").css('display','block');

			for ( var i = 0; i < data.length ; i++ ){

				jQuery( "#displaycanvas" ).append( '<div class="wpeo-grid grid-2"><div class="grid-1"><canvas id="canvasHorizontalBar' + i + '"></canvas></div><div class="grid-1"><canvas id="canvasDoghnutChart' + i + '" width="400" height="225"></canvas></div></div>' );
				var canvasHorizontal = document.getElementById( "canvasHorizontalBar" + i ).getContext('2d');

				new Chart(canvasHorizontal, {
			    type: 'horizontalBar', // bar = Vertical | horizontalBar = Horizontal
			    data: {
		        labels: [ window.indicatorString.minute ],
						datasets: [
		        {
		          label: window.indicatorString.time_work,//window.indicator.time_work,
		          backgroundColor: "#3e95cd",
		          data: [ data[i]['duree_travail'] ],
							borderWidth: 1
		        }, {
		          label: window.indicatorString.time_day,//window.indicator.time_day,
		          backgroundColor: "#8e5ea2",
		          data: [ data[i]['duree_journée'], 0 ],
							borderWidth: 1
		        }]
					},
			    options: {
						plugins: {
							labels: {
						    render: 'label'
						  }
						},
						legend: { display: true },
			      title: {
			        display: true,
			        text:  data[i]['jour']
		      	},
		        scales: {
	            yAxes: [{
	              ticks: {
	                beginAtZero: true
	              }
	            }]
	      		}
		    	}
				});

				var canvasDonut = document.getElementById( "canvasDoghnutChart" + i).getContext('2d');

				if( data[ i ][ 'tache_effectue' ] != undefined && data[ i ][ 'tache_effectue' ].length > 0 ){

					var donutduree = [];
					var donutpoint = [];
					var dayfocus = '';

					for (var v = 0; v < data[ i ][ 'tache_effectue' ].length; v++) {
						donutduree[ v ] = data[ i ][ 'tache_effectue' ][ v ][ 'duree' ];
						donutpoint[ v ] = data[ i ][ 'tache_effectue' ][ v ][ 'point_id' ];
						dayfocus = data[ i ][ 'jour' ];
					}

					new Chart(canvasDonut, {
				    type: 'doughnut',
				    data: {
				      labels: donutpoint,
				      datasets: [
				        {
				          label: window.indicatorString.planning,
				          backgroundColor: ["#3e95cd", "#8e5ea2","#3cba9f","#e8c3b9","#c45850"],
				          data: donutduree,
				        }
				      ]
				    },
				    options: {
				      title: {
				        display: true,
				        text: data[ i ][ 'jour' ]
				      }
				    }
					});
				}
			}

		jQuery( '#information_canvas' ).css('display', 'none');
	}else{

		if( response.data.error == 'date_error' ){ // Date invalid

			jQuery( '#information_canvas' ).html( window.indicatorString.date_error );

		}else if( response.data.error == 'person_error' ){ // User don't choose person

			jQuery( '#information_canvas' ).html( window.indicatorString.person_error );

		}else{ // No data found

		}

		jQuery( '#information_canvas' ).css('display', 'block');
	}
};

window.eoxiaJS.taskManager.indicator.event = function( event ) {
	jQuery( document ).on( 'click', '.clickonfollower', window.eoxiaJS.taskManager.indicator.addFollower );
};

window.eoxiaJS.taskManager.indicator.addFollower = function( event) {
	var addFollower = jQuery( this ).attr( "data-user-id" );

	var value_input = document.getElementById( "tm_indicator_list_followers" ).value;
	var list_follower = value_input.toString() ? value_input.toString() : '';

	if( list_follower == '' ){
		var arrayFollowers = [];
	}else{
		var arrayFollowers = list_follower.split( ',' );
	}

	if( jQuery( this ).attr( "data-user-choose" ) === 'false' ){ // On ajoute une personne
		jQuery( this ).attr( "data-user-choose", "true" );
		arrayFollowers.push( addFollower );


		jQuery( this ).animate({
	    top: "+=10",
	  }, 0, "linear", function() {

	 });
	}else{ // on retire la personne
		jQuery( this ).attr( "data-user-choose", "false" );

		for( var i = 0; i < arrayFollowers.length ; i++ ){
			if( addFollower == arrayFollowers[i] ){
				arrayFollowers.splice( i, 1 );
			}
		}

		jQuery( this ).animate({
	    top: "-=10",
	  }, 0, "linear", function() {

	 });
	}





	document.getElementById( "tm_indicator_list_followers" ).value = arrayFollowers.join();
};

window.eoxiaJS.taskManager.indicator.createCanvas = function( triggeredElement, response ) {
	console.log( 'Create Canvas' );
};

window.eoxiaJS.taskManager.indicator.markedAsReadSuccess = function ( triggeredElement, response ) {
	triggeredElement.closest( '.activity' ).hide();
};
