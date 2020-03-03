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

window.eoxiaJS.taskManager.indicator.event = function( event ) {
	jQuery( document ).on( 'click', '.clickonfollower', window.eoxiaJS.taskManager.indicator.addFollower );
	jQuery( document ).on( 'click', '.clickontypechart', window.eoxiaJS.taskManager.indicator.modifyTypeChart );
	jQuery( document ).on( 'click', '.display_this_point', window.eoxiaJS.taskManager.indicator.displayThisPoint );
	jQuery( document ).on( 'click', '.page-indicator button.handlediv', window.eoxiaJS.taskManager.indicator.toggleMetabox );
	jQuery( document ).on( 'click', '#tm-indicator-stats-client-displaybutton div', window.eoxiaJS.taskManager.indicator.displayDeadlineRecusiveStats );
	jQuery( document ).on( 'click', '#indicator-page-client h2', window.eoxiaJS.taskManager.indicator.preventDefaultHeader );
	jQuery( document ).on( 'click', '#indicator-page-id h2', window.eoxiaJS.taskManager.indicator.preventDefaultHeader );
	jQuery( document ).on( 'click', '#indicator-page-listtag h2', window.eoxiaJS.taskManager.indicator.preventDefaultHeader );

	jQuery( document ).on( 'click', '.select-tags-indicator .tags .wpeo-tag-search', window.eoxiaJS.taskManager.indicator.selectTag );
	jQuery( document ).on( 'click', '.select-tags-indicator .tags .wpeo-tag-search', window.eoxiaJS.taskManager.indicator.sendRequestTagsStats );

	jQuery( document ).on( 'click', '.wpeo-wrap .tm-dashboard-wrap .project-archive', window.eoxiaJS.taskManager.indicator.unpackTask );
};

window.eoxiaJS.taskManager.indicator.preventDefaultHeader = function( event ){
	if( jQuery( this ).parent().hasClass( 'closed' ) ){
		jQuery( this ).parent().removeClass( 'closed' );
	}else{
		jQuery( this ).parent().addClass( 'closed' );
	}
}

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
 * Fonction principal qui génére les canvas de type Bar et Doghnut
 * Elle est lancé par une fonction php '/activity/action'
 *
 * @param  {[type]} triggeredElement [ ]
   @param  {[type]} response         [ donnés reçues par la requete ajax ]
 *
 * @author Corentin Eoxia
 * @since 1.10.0 - BETA
 */

window.eoxiaJS.taskManager.indicator.loadedCustomerActivity = function( triggeredElement, response ) {
	window.eoxiaJS.taskManager.indicator.isSelectOneUser( response.data.user_select, response.data.user_id );
	window.eoxiaJS.taskManager.indicator.updateTimeChoose( response.data.time_choose, response.data.date_start, response.data.date_end );
	jQuery( '#tm-indicator-activity .inside' ).html( response.data.view );
	jQuery( '#displaycanvas' ).html( '' ); // reset les affichages de Canvas
	jQuery( '#displaycanvas_specific_week' ).html( '' ); // reset le second affichage de la semaine
	jQuery( '#displaymodal' ).html( '' );

	jQuery( '#tm_indicator_chart_display' ).replaceWith( response.data.view_button );
	jQuery( '#tm_indicator_chart_display' ).show();
	jQuery( '#displaycanvas_specific_week' ).hide();

	var data = response.data.object;

	jQuery( '#tm_redirect_settings_user' ).css('display', 'none');
	//jQuery( '#tm_indicator_chart_display' ).css( 'display', 'none' );

	jQuery( '#display_modal' ).html( '' );

	if( data.length != 0 ){
		if( response.data.display_specific_week == true ){
			window.eoxiaJS.taskManager.indicator.displaySpecificChartForWeek( data );
		}

		jQuery("#horizontalChart").css('display','block');
		jQuery("#doghnutChart").css('display','block');
		jQuery("#displaycanvas").css('display','block');

		var total_time_work = 0; // Pour l'affichage
		var total_time_elapsed = 0; // Du premier Canvas
		var total_donut_duree = [];
		var total_donut_point = [];
		var total_donut_title  = [];
		jQuery( "#displaycanvas_specific_week" ).append( '<div class="wpeo-grid grid-2"><div class="grid-1"><canvas id="canvasHorizontalBarAll"></canvas></div><div class="grid-1"><canvas id="canvasDoghnutChartAll" width="400" height="225" class="wpeo-modal-event" ></canvas></div></div>' ); // Qui resume TOUT

			for ( var i = 0; i < data.length ; i++ ){
				total_time_work += data[i]['duree_travail'];
				total_time_elapsed += data[i]['duree_journée'];


				jQuery( "#displaycanvas" ).append( '<div class="wpeo-grid grid-2"><div class="grid-1"><canvas id="canvasHorizontalBar' + i + '"></canvas></div><div class="grid-1"><canvas id="canvasDoghnutChart' + i + '" width="400" height="225" class="wpeo-modal-event" ></canvas></div></div>' );
				var canvasHorizontal = document.getElementById( "canvasHorizontalBar" + i ).getContext('2d');

				var data_canvas_horizontalBar = {
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
				};

				var option_canvas_horizontalbar = {
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
				};

				window.eoxiaJS.taskManager.indicator.generateCanvasDynamic( canvasHorizontal, 'horizontalBar', data_canvas_horizontalBar, option_canvas_horizontalbar ); // Génération du canvas de type horizontalBar

				// - - - - -

				var canvasDonut = document.getElementById( "canvasDoghnutChart" + i).getContext('2d');

				if( data[ i ][ 'tache_effectue' ] != undefined && data[ i ][ 'tache_effectue' ].length > 0 ){
					jQuery( '#canvasDoghnutChart' + i ).css( 'cursor', 'pointer' );
					jQuery( '#canvasDoghnutChart' + i ).addClass( 'display_all_point' );
					jQuery( '#canvasDoghnutChart' + i ).attr( "data-canvas-focus", i );

					window.eoxiaJS.taskManager.indicator.generateModalContent( i, data[ i ] );

					var donutduree = [];
					var donutpoint = [];
					var donutitle  = [];
					var dayfocus   = '';

					for (var v = 0; v < data[ i ][ 'tache_effectue' ].length; v++) {
						donutduree[ v ] = data[ i ][ 'tache_effectue' ][ v ][ 'duree' ];
						donutpoint[ v ] = data[ i ][ 'tache_effectue' ][ v ][ 'point_id' ];
						donutitle[ v ]  = data[ i ][ 'tache_effectue' ][ v ][ 'tache_title' ];

						dayfocus        = data[ i ][ 'jour' ];



						total_donut_duree[ total_donut_duree.length ]  = data[ i ][ 'tache_effectue' ][ v ][ 'duree' ];
						total_donut_point[ total_donut_point.length ]  = data[ i ][ 'tache_effectue' ][ v ][ 'point_id' ];
						total_donut_title[ total_donut_title.length ] = data[ i ][ 'tache_effectue' ][ v ][ 'tache_title' ];
					}

					var data_canvas_doghnut = {
						labels : donutpoint,
						datasets: [
				        {
				          label: window.indicatorString.planning,
				          backgroundColor: ["#800000", "#9A6324","#808000","#469990","#000075", "#e6194B", "#f58231", "#ffe119", "#bfef45", "#3cb44b", "#42d4f4", "#4363d8", "#911eb4", "#f032e6", "#a9a9a9", "#fabebe", "#ffd8b1", "#fffac8", "#aaffc3", "#e6beff"],
				          data: donutduree,
				        }
				      ],
						dataset : donutitle,
					};

					var option_canvas_doghnut =  {
						onClick: function( event, info ) {
							var numline = -1;
							if( info.length != 0 ){
								numline = info[0]['_index'];
							}
							window.eoxiaJS.taskManager.indicator.displayAllPoint( numline, this['canvas'] );
						},
			      title: {
			        display: true,
			        text: data[ i ][ 'jour' ]
			      },
						tooltips: {
              callbacks: {
                title: function( item, data_indicator ) {
									return data_indicator[ 'dataset' ][ item[ 0 ][ 'index' ] ];
                },
              }
            },
						legend: {
              onClick: (e) => e.stopPropagation() // Block click
            }
			    };

					window.eoxiaJS.taskManager.indicator.generateCanvasDynamic( canvasDonut, 'doughnut', data_canvas_doghnut, option_canvas_doghnut ); // Génération du canvas de type doghnut
				}
			}

			window.eoxiaJS.taskManager.indicator.generateSummaryCanvas( total_time_work, total_time_elapsed, total_donut_duree, total_donut_point, total_donut_title )

		jQuery( '#information_canvas' ).css('display', 'none');
	}else{

		if( response.data.error == 'date_error' ){ // Date invalid

			jQuery( '#information_canvas' ).html( window.indicatorString.date_error );

		}else if( response.data.error == 'person_error' ){ // User don't choose person

			jQuery( '#information_canvas' ).html( window.indicatorString.person_error );

		}else{ // No data found
			jQuery( '#information_canvas' ).html( window.indicatorString.nodata );
			jQuery( '#tm_redirect_settings_user' ).css('display', 'block');
		}

		jQuery( '#information_canvas' ).css('display', 'block');
	}
};

/**
 * Fonction qui génère (TOUS) les canvas
 * @param  {String} [elementbyid='']  [Recupère l'élement créé, qui contiendra le canvas]
 * @param  {String} [typelabel='bar'] [type de canvas à afficher] => 3 types : 'bar', 'horizontalBar','doughnut'
 * @param  {Object} [data={}]         [liste des données]
 * @param  {Object} [option={}]       [liste des options / fonctions à éxecuter]
 * @since 1.9.0 - BETA
 */
window.eoxiaJS.taskManager.indicator.generateCanvasDynamic = function( elementbyid = '', typelabel = 'bar', data = {}, option = {} ){
	new Chart(elementbyid, {
    type: typelabel,
    data: data,
    options: option
	});
}

window.eoxiaJS.taskManager.indicator.updateTimeChoose = function( time = '', day_start = '', day_end = '' ){
	jQuery( '#tm_indicator_date_start_id' ).val( day_start );
	jQuery( '#tm_indicator_date_end_id' ).val( day_end );
}



/**
 * Modifie le css d'un utilisateur suite à une action utilisateur
 * La fonction peut etre lancé par un clic sur un cadre utilisateur, ou lors de l'affichage de l'emploi du temps
 * @param  {[type]} event       [action utilisateur]
 * @param  {Number} [user_id=0] [utilisateur actuel]
 * @return {[type]}             [description]
 *
 * @since 1.9.0 - BETA
 */
window.eoxiaJS.taskManager.indicator.addFollower = function( event, user_id = 0 ) {
	if( user_id == 0 ){

		var addFollower = jQuery( this ).attr( "data-user-id" );
	}else{
		var addFollower = user_id;
	}

	var value_input = document.getElementById( "tm_indicator_list_followers" ).value;
	var list_follower = value_input.toString() ? value_input.toString() : '';

	if( list_follower == '' ){
		var arrayFollowers = addFollower;
		if( document.getElementById( 'tm_user_indicator_' + addFollower ) ){
			jQuery( '#tm_user_indicator_' + addFollower ).addClass( 'active' );
		}
		// active addFollower
	}else{
		if( list_follower == addFollower ){ // Desactive list
			var arrayFollowers = '';
			if( document.getElementById( 'tm_user_indicator_' + list_follower ) ){ // Desactive list
				jQuery( '#tm_user_indicator_' + list_follower ).removeClass( 'active' );
			}
		}else{

			var arrayFollowers = addFollower;
			if( document.getElementById( 'tm_user_indicator_' + addFollower ) ){ // active add
				jQuery( '#tm_user_indicator_' + addFollower ).addClass( 'active' );
			}

			if( document.getElementById( 'tm_user_indicator_' + list_follower ) ){ // Desactive list
				jQuery( '#tm_user_indicator_' + list_follower ).removeClass( 'active' );
			}
		}
	}

	document.getElementById( "tm_indicator_list_followers" ).value = arrayFollowers;
};

window.eoxiaJS.taskManager.indicator.markedAsReadSuccess = function ( triggeredElement, response ) {
	triggeredElement.closest( '.activity' ).hide();
};

/**
 * Affichage de deux canvas supplémaitres lors de la sélection 'semaine'
 * ces canvas résument la semaine, en traitant les données contenu dans le parametre 'data'
 * @param  {[type]} data [données des taches effectuées]
 * @return {[type]}      [description]
 *
 * @since 1.9.0 - BETA
 */
window.eoxiaJS.taskManager.indicator.displaySpecificChartForWeek = function( data ){
	jQuery( '#tm_indicator_chart_display' ).css( 'display', 'block' );

	var data_work_time = [];
	var data_day_time = [];
	var data_day = [];
	var data_color = [ "#800000", "#9A6324","#808000","#469990","#000075", "#e6194B", "#f58231" ]
	var data_time_work = [];
	var data_point_id_work_ = [];

		for( var i = 0; i < data.length; i++ ){
			data_work_time.push( data[i]['duree_travail'] );
			data_day_time.push( data[i]['duree_journée'] );
			data_day.push( data[ i ][ 'date_fr' ] );

			if( data[ i ][ 'tache_effectue' ] != undefined && data[ i ][ 'tache_effectue' ].length > 0 ){
				for (var v = 0; v < data[ i ][ 'tache_effectue' ].length; v++) {
					var point_already_create = false;

					for( var x = 0; x < data_point_id_work_.length; x++ ){
						if( data_point_id_work_[ x ] == data[ i ][ 'tache_effectue' ][ v ][ 'point_id' ] ){
							data_time_work[ x ] = data_time_work[ x ] + data[ i ][ 'tache_effectue' ][ v ][ 'duree' ];
							point_already_create = true;
							break;
						}
					}
					if( ! point_already_create ){
						data_point_id_work_.push( data[ i ][ 'tache_effectue' ][ v ][ 'point_id' ] );
						data_time_work.push( data[ i ][ 'tache_effectue' ][ v ][ 'duree' ] );
					}
				}
			}
		}

		data_time_work.push( 0 ); // Pour l'affichage un bel affichage canvas, en ajoutant un 0 à la fin

	jQuery( "#displaycanvas_specific_week" ).append( '<div class="wpeo-grid grid-2"><div class="grid-1"><canvas id="tm_indicator_canvasbar_week"></canvas></div><div class="grid-1"><canvas id="tm_indicator_canvasdoghnut_week" width="400" height="225"></canvas></div></div>' );
	var canvasbar = document.getElementById( "tm_indicator_canvasbar_week" ).getContext('2d');

		var data_canvas_bar = {
			labels: data_day,
			datasets: [{
				label: window.indicatorString.time_work,//window.indicator.time_work,
				backgroundColor: "#3e95cd",
				data: data_work_time,
				borderWidth: 1
			}, {
				label: window.indicatorString.time_day,//window.indicator.time_day,
				backgroundColor: "#8e5ea2",
				data: data_day_time,
				borderWidth: 1
			}]
		};

		var options_canvas_bar = {
			plugins: {
				labels: {
					render: 'label'
				}
			},
			legend: { display: true },
			title: {
				display: true,
				text:  window.indicatorString.from + ' ' + data[ 0 ]['jour'] + ' ' + window.indicatorString.to + ' ' + data[ data.length - 1 ]['jour']
			},
			scales: {
				yAxes: [{
					ticks: {
						beginAtZero: true
					}
				}]
			}
		};

		window.eoxiaJS.taskManager.indicator.generateCanvasDynamic( canvasbar, 'bar', data_canvas_bar, options_canvas_bar );

// - - - - -

	var canvasdonut = document.getElementById( "tm_indicator_canvasdoghnut_week" ).getContext('2d');

	var data_canvas_donut = {
			labels: data_point_id_work_,
			datasets: [
				{
					label: window.indicatorString.planning,
					backgroundColor: ["#800000", "#9A6324","#808000","#469990","#000075", "#e6194B", "#f58231", "#ffe119", "#bfef45", "#3cb44b", "#42d4f4", "#4363d8", "#911eb4", "#f032e6", "#a9a9a9", "#fabebe", "#ffd8b1", "#fffac8", "#aaffc3", "#e6beff"],
					data: data_time_work,
				}
			]
		};

		var options_canvas_donut = {
			title: {
				display: true,
				text: window.indicatorString.plan_week
			}
		};

	window.eoxiaJS.taskManager.indicator.generateCanvasDynamic( canvasdonut, 'doughnut', data_canvas_donut, options_canvas_donut );
}

/**
 * Modifie l'affichage des boutons lors de la sélection 'semaine'
 * Et modifie l'affichage des canvas
 * @param  {[type]} triggeredElement [ ]
 * @param  {[type]} response         [ ]
 *
 * @since 1.9.0 - BETA
 */
window.eoxiaJS.taskManager.indicator.modifyTypeChart = function( triggeredElement, response ) {

	var chart_selected = jQuery( this ).attr( "data-chart-type" );
	var data_chart_display = jQuery( '#tm_indicator_chart_display' ).attr( "data-chart-display" );

	if( chart_selected != data_chart_display ){ // on change d'affichage
		if( chart_selected == 'bar' ){

			jQuery( '#tm_indicator_chart_bar' ).removeClass( 'button-grey' );
			jQuery( '#tm_indicator_chart_horizontalBar' ).addClass( 'button-grey' );
			jQuery( '#tm_indicator_chart_display' ).attr( "data-chart-display", "bar" );
			jQuery( '#displaycanvas' ).css( "display", "none" );
			jQuery( '#displaycanvas_specific_week' ).css( "display", "block" );
		}else{

			jQuery( '#tm_indicator_chart_horizontalBar' ).removeClass( 'button-grey' );
			jQuery( '#tm_indicator_chart_bar' ).addClass( 'button-grey' );
			jQuery( '#tm_indicator_chart_display' ).attr( "data-chart-display", "horizontalBar" );
			jQuery( '#displaycanvas' ).css( "display", "block" );
			jQuery( '#displaycanvas_specific_week' ).css( "display", "none" );
		}
	}
};

/**
 * Sélectionne un utilisateur par défaut, si aucun n'a était choisis.
 * Ici l'utilisateur actuel est pris en compte;
 * @param  {[type]} user_select [Utilisateur sélectionné ] // null si aucun
 * @param  {[type]} user_id     [id de l'utilisateur actuel]
 *
 * @since 1.9.0 - BETA
 */
window.eoxiaJS.taskManager.indicator.isSelectOneUser = function( user_select, user_id ){
	if( ! user_select ){
		window.eoxiaJS.taskManager.indicator.addFollower( null, user_id );
	}
};

/**
 * [Créer le contenu du modal :
 * => Génération du tableau
 * => Génération de l'explication de chaque point (il suffirat de display block pour l'affichage)]
 * @param  {[type]} num_modal [Numéro du modal, chacun modal représente un canvas doghnut ]
 * @param  {[type]} data      [toutes les données récupérés par le php => Liste des jours => liste des taches effectués]
 *
 * @since 1.9.0 - BETA
 */
window.eoxiaJS.taskManager.indicator.generateModalContent = function ( num_modal, data ){

	if( ! jQuery( '#tm_indicator_modal_active_canvas .modal-container' ).data()[ 'update' ] ){

		jQuery( '#tm_indicator_modal_active_canvas .modal-container' ).data('update', 'true' );

		var oldwidth = jQuery( '#tm_indicator_modal_active_canvas .modal-container' ).css('max-width');
		var oldheight = jQuery( '#tm_indicator_modal_active_canvas .modal-container' ).css('max-height');

		oldwidth = parseInt( oldwidth ) * 1.5;
		oldheight = parseInt( oldheight ) * 1.5;

		jQuery( '#tm_indicator_modal_active_canvas .modal-container' ).css('max-width', oldwidth + 'px' );
		jQuery( '#tm_indicator_modal_active_canvas .modal-container' ).css('max-height', oldheight + 'px' );
		jQuery( '#tm_indicator_modal_active_canvas .modal-container .modal-content' ).css('height', '86%' );
	}


	jQuery( '#tm_indicator_day_taches' ).html( data['jour'] );

	jQuery( '#display_modal' ).append( '<div id="tm_indicator_modal_block' + num_modal + '" style="display : none"></div>');
	var array_top = '<table class="wpeo-table" id="chart_selected_' + num_modal + '"><thead><tr><th data-title="point_id">ID</th><th data-title="tache_title">Tache TITLE</th><th data-title="point_title">Point TITLE</th><th data-title="time">Duree</th></tr></thead><tbody id="tm_indicator_table_' + num_modal + '_" data-chartselect=' + num_modal +'>';
	var array_content = '';
	var block_text = '';

	for( var i = 0; i < data['tache_effectue'].length; i++ ){
		var withoutbackline = '/<br\s*[\/]?>/gi';

		var task_title = data[ 'tache_effectue' ][ i ][ 'tache_title' ];
		if( task_title.length > 40 ){
			task_title = task_title.substring( 0, 40 ) + ' ...';
		}
		task_title = task_title.replace(/(<br ?\/?>)*/g,"");

		var point_title = data['tache_effectue'][ i ]['point_title'];
		if( point_title.length > 20 ){
			point_title = point_title.substring( 0, 20 ) + ' ...';
		}
		point_title = point_title.replace(/(<br ?\/?>)*/g,"");

		client_name = '';
		if( data[ 'tache_effectue' ][ i ][ 'pt_title' ] != null ){
			client_name = data[ 'tache_effectue' ][ i ][ 'pt_title' ];
		}


		var array_content = array_content + '<tr id="tm_indicator_task_' + num_modal + '_' + i + '" class="display_this_point" data-tmindicatorpointid="' + data[ 'tache_effectue' ][ i ][ 'point_id' ] + '" style="cursor : pointer"><th data-title="point_id">' + data['tache_effectue'][ i ]['point_id'] + '</th><th data-title="tache_title">' + task_title + '</th><th data-title="point_title">' + point_title + '</th><th data-title="time">' + data['tache_effectue'][ i ]['duree'] + '</th></tr>';


		var block_task_date = '<h2 style="float : left" title="Date"><i>' + data[ 'tache_effectue' ][ i ][ 'com_date' ] + '</i></h2>';
		var block_task_time = '<h2  style="float : right" title="Time spend"><i>' + data[ 'tache_effectue' ][ i ][ 'duree' ] + '</i> minutes </h2>' + client_name;
		var block_task_title =  '<h1 title="Task TITLE" style="text-align : center">' + data[ 'tache_effectue' ][ i ][ 'tache_title' ] +'<br></h1><span title="Task ID"><i>( #' + data[ 'tache_effectue' ][ i ][ 'tache_id' ] + ' )</i></span>';
		var block_text_point =  '<h3 title="Point TITLE">' + data[ 'tache_effectue' ][ i ][ 'point_title' ] +'</h3><br><span title="Point ID"><i>( #' + data[ 'tache_effectue' ][ i ][ 'point_id' ] + ' )</i></span>';

		var block_text_com = '';
		var num_commentary = 0;
		for( var t = data[ 'tache_effectue' ][ i ][ 'commentary' ].length - 1; t > -1 ; t-- ){
			var num_commentary = num_commentary + 1;
			var block_text_com = block_text_com + '<div style="margin-bottom : 10px; border : 2px solid #0d262c; margin-left: 14%; width : 72%; border-radius: 5px; padding : 5px;  padding-bottom : 25px;"><h4 title="Com TITLE"><span style="font-size : 15px"><b> ' + num_commentary + '.</b></span><div style="float : right"><h2><i>' + data[ 'tache_effectue' ][ i ][ 'commentary' ][ t ][ 'com_time' ] +'</i> minutes</h2></div> <br> ' + data[ 'tache_effectue' ][ i ][ 'commentary' ][ t ][ 'com_title' ] +'</h4><span style="float : right "title="com ID"><i>( #' + data[ 'tache_effectue' ][ i ][ 'commentary' ][ t ][ 'com_id' ] + ' )</i></span></div>';
		}

		var block_text_content = '<div style="border : 2px solid #6f2e2e; margin-left : 15%; width : 70%; border-radius: 10px;"></div><br><div style="border : 2px solid #6f2e2e; border-radius: 5px; margin-bottom : 20px; padding : 8px; margin-left: 10%; width : 80%;">' + block_task_date + block_task_time + block_task_title + '</div>' + '<div style="border : 2px solid #0d262c; border-radius: 5px; margin-bottom : 20px; margin-left: 12%; width : 76%; padding : 8px">' + block_text_point + '</div>'+ block_text_com;

		var block_text = block_text + '<div id="tm_indicator_point_' + data[ 'tache_effectue' ][ i ][ 'point_id' ] + '_' + num_modal + '" style="display : none">' + block_text_content + '</div>';
	}

	var array_bot =  '</tbody></table>';
	var array_full = array_top + array_content + array_bot + block_text;
	jQuery( '#tm_indicator_modal_block' + num_modal).append( array_full );
}

/**
 * Affiche tous les points du canvas ciblé (display block le modal généré à l'avance)
 * Cette fonction est lancé lorsque l'utilisateur clic sur un canvas
 * @param  {[type]} numline   [position de l'element cliqué dans le tableau principal] // peut etre null si l'utilisateur clic dans le vide
 * @param  {[type]} divcanvas [div de la canvas]
 *
 * @since 1.9.0 - BETA
 */
window.eoxiaJS.taskManager.indicator.displayAllPoint = function( numline, divcanvas ){

	jQuery( '#display_modal' ).children().css( 'display', 'none' );
	var chart_selected = jQuery( divcanvas ).attr( "data-canvas-focus" );
	jQuery( '#displaycanvas_modal' ).css( 'display', 'block' );

	jQuery( '#tm_indicator_modal_block' + chart_selected ).css( 'display' ,'block' );

	jQuery( '#tm_indicator_modal_active_canvas' ).addClass( 'modal-active' );

	jQuery( '#tm_indicator_table_' + chart_selected + '_' ).children().css( 'backgroundColor', '' );

	var divmodal = jQuery( '#tm_indicator_modal_block' + chart_selected ).children().not('.wpeo-table');
	divmodal.css( 'display' , 'none' );

	if( numline != -1 ){

		jQuery( '#tm_indicator_task_' + chart_selected + '_' + numline ).css( 'backgroundColor', 'grey' );
		var data_attribute         = jQuery( '#tm_indicator_task_' + chart_selected + '_' + numline ).data();
		var data_attribute_pointid = data_attribute[ 'tmindicatorpointid' ];
		jQuery( '#tm_indicator_point_' + data_attribute_pointid + '_' + chart_selected ).css( 'display', 'block' );
	}
}

/**
 * Affiche la div relié au point ciblé (sur le tableau)
 *
 * @param  {[type]} event [ ]
 *
 * @since 1.9.0 - BETA
 */
window.eoxiaJS.taskManager.indicator.displayThisPoint = function( event ){

	var data_attribute = jQuery( this ).data();
	var data_attribute_pointid = data_attribute[ 'tmindicatorpointid' ];


	var divtable = jQuery( this ).parent();
	divtable.children().css( "backgroundColor", '' );
	jQuery( this ).css( 'backgroundColor', 'grey' );

	num_modal = divtable.data()[ 'chartselect' ];
	var divindicatormodal = jQuery( '#tm_indicator_point_' + data_attribute_pointid + '_' + num_modal ).parent();
	var divmodal = divindicatormodal.children().not('.wpeo-table');
	divmodal.css( 'display' , 'none' );

 	jQuery( '#tm_indicator_point_' + data_attribute_pointid + '_' + num_modal ).css( 'display', 'block' );

}

	window.eoxiaJS.taskManager.indicator.generateSummaryCanvas = function( time_work, time_elasped, total_donut_duree, total_donut_point, total_donut_title ){

		var canvasHorizontal = document.getElementById( "canvasHorizontalBarAll" ).getContext('2d');

		var data_canvas_horizontalBar = {
			labels: [ window.indicatorString.minute ],
			datasets: [
			{
				label: window.indicatorString.time_work,//window.indicator.time_work,
				backgroundColor: "#3e95cd",
				data: [ time_work ],
				borderWidth: 1
			}, {
				label: window.indicatorString.time_day,//window.indicator.time_day,
				backgroundColor: "#8e5ea2",
				data: [ time_elasped, 0 ],
				borderWidth: 1
			}]
		};

		var option_canvas_horizontalbar = {
			plugins: {
				labels: {
					render: 'label'
				}
			},
			legend: { display: true },
			title: {
				display: true,
				text:  window.indicatorString.resume_bar
			},
			scales: {
				yAxes: [{
					ticks: {
						beginAtZero: true
					}
				}]
			}
		};


		window.eoxiaJS.taskManager.indicator.generateCanvasDynamic( canvasHorizontal, 'horizontalBar', data_canvas_horizontalBar, option_canvas_horizontalbar ); // Génération du canvas de type horizontalBar

		// - - - - -

		var canvasDonut = document.getElementById( "canvasDoghnutChartAll" ).getContext('2d');

		if( time_work > 0 && time_elasped > 0 ){
			jQuery( 'canvasDoghnutChartAll' ).css( 'cursor', 'pointer' );
			jQuery( 'canvasDoghnutChartAll' ).addClass( 'display_all_point' );
			// jQuery( 'canvasDoghnutChartAll' ).attr( "data-canvas-focus", i );

			// window.eoxiaJS.taskManager.indicator.generateModalContent( i, data[ i ] );

			var data_canvas_doghnut = {
				labels : total_donut_point,
				datasets: [
						{
							label: window.indicatorString.planning,
							backgroundColor: ["#800000", "#9A6324","#808000","#469990","#000075", "#e6194B", "#f58231", "#ffe119", "#bfef45", "#3cb44b", "#42d4f4", "#4363d8", "#911eb4", "#f032e6", "#a9a9a9", "#fabebe", "#ffd8b1", "#fffac8", "#aaffc3", "#e6beff"],
							data: total_donut_duree,
						}
					],
				dataset : total_donut_title,
			};

			var option_canvas_doghnut =  {
				title: {
					display: true,
					text: window.indicatorString.resume_dog
				},
				tooltips: {
					callbacks: {
						title: function( item, data_indicator ) {
							return data_indicator[ 'dataset' ][ item[ 0 ][ 'index' ] ];
						},
					}
				},
				legend: {
					// onClick: (e) => e.stopPropagation() // Block click
				}
			};

			window.eoxiaJS.taskManager.indicator.generateCanvasDynamic( canvasDonut, 'doughnut', data_canvas_doghnut, option_canvas_doghnut ); // Génération du canvas de type doghnut
		}
	}

window.eoxiaJS.taskManager.indicator.updateIndicatorClientSuccess = function( element, response ){
	if( response.data.view == "" ){
		jQuery( element ).closest( '.tab-content' ).find( '.tm_indicator_stats' ).html( '<h1>Customer\'s tasks doesn\'t \'ve category</h1>' );
	}else{
		jQuery( element ).closest( '.tab-content' ).find( '.tm_indicator_stats' ).replaceWith( response.data.view );
	}
	//replaceWith( response.data.view );
}

window.eoxiaJS.taskManager.indicator.updateStatsClient = function( element, response ){
	if( response.data.view != "" ){
		jQuery( '#indicator-page-client .inside' ).html( response.data.view );
	}
}

window.eoxiaJS.taskManager.indicator.displayDeadlineRecusiveStats = function( event ){
	var focus = jQuery( this ).parent().data( 'focus' );
	var element = jQuery( this ).data( 'element' );
	if( focus == element ){ // Element actuel deja ok

	}else{
		var other_button = jQuery( this ).closest( '#tm-indicator-stats-client-displaybutton' ).find( '.button-blue' );

		jQuery( this ).find( 'i' ).removeClass( 'fa-square' ).addClass( 'fa-check-square' ); // Update Button click
		jQuery( this ).removeClass( 'button-grey' ).addClass( 'button-blue' );

		other_button.find( 'i' ).removeClass( 'fa-check-square' ).addClass( 'fa-square' );
		other_button.removeClass( 'button-blue' ).addClass( 'button-grey' );

		jQuery( this ).parent().data( 'focus', element );
		window.eoxiaJS.loader.display( jQuery( this ) );
		// jQuery( '.load-more' ).show();
		var data = {};

		data.action   = jQuery( this ).data( 'action' );
		data.type     = element;
		data.month    = jQuery( this ).parent().data( 'date' );
		data._wpnonce = jQuery( this ).data( 'nonce' );

		window.eoxiaJS.request.send( jQuery( this ), data );
	}
}

window.eoxiaJS.taskManager.indicator.selectTag = function( event ){
	jQuery( this ).parent().find( '.wpeo-tag-search' ).each( function( element ){
		jQuery( this ).removeClass( 'active' );
	})

	jQuery( this ).closest( '.select-tags-indicator' ).find( 'input[type="hidden"]' ).val( jQuery( this ).data( 'tag-id' ) );
}

window.eoxiaJS.taskManager.indicator.sendRequestTagsStats = function( event ){
	var data = {};
	data.action = 'load_tags_stats';
	data.tag_id = jQuery( this ).data( 'tag-id' );
	//data._wpnonce = jQuery( this ).data( 'nonce' );

	window.eoxiaJS.loader.display( jQuery( this ).closest( '.form' ) );
	window.eoxiaJS.request.send( jQuery( this ), data );
}

window.eoxiaJS.taskManager.indicator.updateIndicatorTag = function( element, response ){
	jQuery( '.tm_tag_indicator_update_body' ).html( response.data.view );

	jQuery( element ).closest( '.inside' ).find( '.tm-simple-task' ).each( function( element ){
		jQuery( this ).hide();
	})

	var i = 0;
	jQuery( element ).closest( '.inside' ).find( '.tm_client_indicator' ).each( function( element ){
		if( i % 2 == 1 ){
			jQuery( this ).css( 'background', '#3F403F' );
			jQuery( this ).css( 'color', 'white' );
		}else{
			jQuery( this ).css( 'background', '#E6E8E6' );
		}
		i++;
	})

	if( response.data.content_empty == "false" ){
		jQuery( '.tm-display-year-indicator' ).show();
		jQuery( '.tm_client_indicator_update' ).replaceWith( response.data.header_view );
	}else{
		jQuery( '.tm-display-year-indicator' ).hide();
	}

	// jQuery( '.tm_client_indicator_update #tm_client_indicator_header_minus' ).attr( 'data-year', response.data.year - 1 );
	// jQuery( '.tm_client_indicator_update #tm_client_indicator_header_actual' ).attr( 'data-year', response.data.year );
	// jQuery( '.tm_client_indicator_update #tm_client_indicator_header_display' ).html( response.data.year );
	// jQuery( '.tm_client_indicator_update #tm_client_indicator_header_plus' ).attr( 'data-year', response.data.year + 1 );
}

window.eoxiaJS.taskManager.indicator.unpackTask = function( event ){
	var data = {};
	data.action = 'tm_unpack_task';
	data.id = jQuery( this ).data( 'id' );
	data._wpnonce = jQuery( this ).data( 'nonce' );

	window.eoxiaJS.loader.display( jQuery( this ) );
	window.eoxiaJS.request.send( jQuery( this ), data );

	jQuery( this ).closest( '.table-row' ).fadeOut(400, function() { jQuery( this ).remove(); })
};
