<?php
/**
 * Méthodes utilitaires pour les dates.
 *
 * @author Jimmy Latour <dev@eoxia.com>
 * @since 0.1.0
 * @version 0.5.0
 * @copyright 2015-2017 Eoxia
 * @package WPEO_Util
 */

namespace eoxia;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( '\eoxia\Date_Util' ) ) {
	/**
	 * Gestion des dates
	 */
	class Date_Util extends \eoxia\Singleton_Util {
		/**
		 * Le constructeur obligatoirement pour utiliser la classe \eoxia\Singleton_Util
		 *
		 * @return void nothing
		 */
		protected function construct() {}

		/**
		 * Renvoie la date au format SQL
		 *
		 * @param  string $date La date à formater.
		 * @return string      	La date formatée au format SQL
		 */
		public function formatte_date( $date ) {
			if ( strlen( $date ) === 10 ) {
				$date .= ' 00:00:00';
			}

			$date = str_replace( '/', '-', $date );
			$date = date( 'Y-m-d h:i:s', strtotime( $date ) );
			return $date;
		}

		public function mysqldate2wordpress( $date, $with_time = true ) {
			$format = get_option( 'date_format' );
			if ( $with_time ) {
				$format .= ' ' . get_option( 'time_format' );
			}

			return mysql2date( $format, $date );
		}
	}
} // End if().
