<?php
/**
 * Vue pour afficher les bouttons indicators
 *
 * @package Task Manager
 *
 * @since 1.8.0
 * @version 1.8.0
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>


<div id="tm_indicator_chart_display" style="float : right; display : none" data-chart-display="horizontalBar">
	<div id="tm_indicator_chart_horizontalBar" class="wpeo-button button-dark button-square-40 button-rounded clickontypechart"  data-chart-type='horizontalBar'>
		<i class="fas fa-align-left"></i>
	</div>
	<div id="tm_indicator_chart_bar" class="wpeo-button button-grey button-dark button-square-40 button-rounded clickontypechart" data-chart-type='bar'>
		<i class="fas fa-chart-bar"></i>
	</div>
</div>
