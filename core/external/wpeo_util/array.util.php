<?php
/**
 * Méthodes utilitaires pour les tableaux.
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

if ( ! class_exists( '\eoxia\Array_Util' ) ) {
	/**
	 * Gestion des tableaux
	 *
	 * @author Jimmy Latour <jimmy.eoxia@gmail.com>
	 * @version 1.1.0.0
	 */
	class Array_Util extends \eoxia\Singleton_Util {
		/**
		 * Le constructeur obligatoirement pour utiliser la classe \eoxia\Singleton_Util
		 *
		 * @return void nothing
		 */
		protected function construct() {}

		/**
		 * Compte le nombre de valeur dans un tableau avec récursivité en vérifiant que $match_element soit dans la valeur
		 *
		 * @param  array   $array         Les données à compter.
		 * @param  boolean $start         ?.
		 * @param  array   $match_element Les données à vérifier.
		 * @return int                 		Le nombre d'entrée
		 */
		public function count_recursive( $array, $start = true, $match_element = array() ) {
			$count = 0;

			if ( $start ) {
				$count = count( $array );
			}

			if ( ! empty( $array ) ) {
				foreach ( $array as $id => $_array ) {
					if ( is_array( $_array ) ) {
						if ( is_string( $id ) && ! empty( $match_element ) && in_array( $id, $match_element, true ) ) {
							$count += count( $_array );
						}

						$count += $this->count_recursive( $_array, false, $match_element );
					}
				}
			}

			return $count;
		}

		/**
		 * Forces à convertir les valeurs d'un tableau en integer.
		 *
		 * @param  array $array Le tableau à convertir.
		 * @return array        Le tableau converti.
		 *
		 * @since 0.1.0
		 * @version 0.5.0
		 */
		public function to_int( $array ) {
			if ( ! empty( $array ) ) {
				foreach ( $array as &$element ) {
					$element = (int) $element;
				}
			}
			return $array;
		}

		/**
		 * Déplaces l'index du tableau vers l'index $to_key.
		 *
		 * @since 0.5.0
		 * @version 0.5.0
		 * @param  Array   $array Les valeurs contenu dans le tableau. Le tableau ne doit pas être 2D.
		 * @param  mixed   $value Tous types de valeurs.
		 * @param  integer $to_key La clé qui vas être déplacer.
		 * @return Array   Le tableau.
		 */
		public function switch_key( $array, $value, $to_key = 0 ) {
			if ( empty( $array[ $to_key ] ) ) {
				return $array;
			}

			$index_founded = array_search( $value, $array, true );

			if ( false === $index_founded ) {
				return $array;
			}

			$tmp_val = $array[ $to_key ];
			$array[ $to_key ] = $array[ $index_founded ];
			$array[ $index_founded ] = $tmp_val;

			return $array;
		}
	}
} // End if().
