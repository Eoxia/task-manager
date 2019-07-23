<?php
/**
 * Affichage des charts des utilisateurs selon un lapse de temps préfédini
 *
 * @author anonymous
 * @since Before 1.9.0 - BETA
 * @copyright 2015-2019 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<ul class="wpeo-pagination">
	<!-- Bouton précédent -->
	<li class="pagination-element pagination-prev">
		<a href="#monlien"><i class="pagination-icon fas fa-long-arrow-alt-left fa-fw"></i><span><?php esc_html_e( 'Previous', 'task-manager' ); ?></span></a>
	</li>
	<!-- Element simple -->
	<li class="pagination-element">
		<a href="#monlien">1</a>
	</li>
	<!-- Element simple -->
	<li class="pagination-element">
		<a href="#monlien">2</a>
	</li>
	<!-- Element actif -->
	<li class="pagination-element pagination-current">
		<a href="#monlien">3</a>
	</li>
	<!-- Element inactif -->
	<li class="pagination-element">...</li>
	<!-- Element simple -->
	<li class="pagination-element pagination-current">
		<a href="#monlien">10</a>
	</li>
	<!-- Bouton suivant -->
	<li class="pagination-element pagination-next">
		<a href="#monlien"><span><?php esc_html_e( 'Next', 'task-manager' ); ?></span><i class="pagination-icon fas fa-long-arrow-alt-right fa-fw"  ></i></a>
	</li>
</ul>
