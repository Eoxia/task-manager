<?php
/**
 * Fonction gérant les dates pour les modèles.
 *
 * @author Eoxia <dev@geoxia.com>
 * @since 1.0.0
 * @version 1.6.0
 * @copyright 2015-2018 Eoxia
 * @package TaskManager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Convertis les minutes en un format spécial sur 7h = 1 jour.
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 * @param  integer $min               Le nombre de minute.
 * @param  boolean $display_full_min  Si oui, affiches $min entre paranthèse.
 *
 * @return string                     La date formatée.
 */
function convert_to_custom_hours( $min, $display_full_min = true ) {
	$minut_for_one_day = \eoxia\Config_Util::$init['eo-framework']->hour_equal_one_day * 60;
	$day               = intval( $min / $minut_for_one_day );
	$sub_min           = $min - ( $day * $minut_for_one_day );
	$hour              = intval( $sub_min / 60 );
	$clone_min         = intval( $sub_min - ( $hour * 60 ) );
	$display           = '';

	if ( ! empty( $day ) ) {
		$display .= $day . 'j ';
	}
	if ( ! empty( $hour ) ) {
		$display .= $hour . 'h ';
	}
	$display .= $clone_min . 'min';
	if ( $display_full_min ) {
		$display .= ' (' . $min . 'min)';
	}

	return $display;
}
