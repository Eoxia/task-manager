<?php
/**
 * La vue principale des commentaires dans le backend.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.0.0
 * @version 1.6.0
 * @copyright 2015-2018 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div class="wpeo-table table-flex table-task">
	<div class="table-row table-header">
		<div class="table-cell">Commentaire</div>
		<div class="table-cell">Auteur</div>
		<div class="table-cell">Date création</div>
		<div class="table-cell">Temps</div>
		<div class="table-cell"></div>
	</div>

	<div class="table-row">
		<div class="table-cell">
			- Rééalisation de la v1.<br />
			- Validation auprès de Laurent, Cédric<br />
			- Lorem ipsum dolor site amet
		</div>

		<div class="table-cell">-</div>
		<div class="table-cell">26/11/2019 10h12</div>
		<div class="table-cell">30</div>
		<div class="table-cell"><span><i class="fas fa-ellipsis-v"></i></span></div>
	</div>
