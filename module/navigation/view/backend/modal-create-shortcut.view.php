<?php
/**
 * Création de raccourcis
 *
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2015-2018 Eoxia <dev@eoxia.com>.
 *
 * @license   GPLv3 <https://spdx.org/licenses/GPL-3.0-or-later.html>
 *
 * @package   EO_Framework\EO_Search\Template
 *
 * @since     1.8.0
 */

namespace task_manager;

defined( 'ABSPATH' ) || exit; ?>

<div class="wpeo-modal">
	<div class="modal-container">

		<!-- Entête -->
		<div class="modal-header">
			<h2 class="modal-title"><?php esc_html_e( 'Create shortcut', 'task-manager' ); ?></h2>
			<div class="modal-close"><i class="fal fa-times"></i></div>
		</div>

		<!-- Corps -->
		<div class="modal-content wpeo-form">
			<div class="form-element">
				<span class="form-label"><?php esc_html_e( 'Shortcut name', 'task-manager' ); ?>></span>
				<label class="form-field-container">
					<input type="text" class="form-field" name="shortcut-name" />
				</label>
			</div>
		</div>

		<!-- Footer -->
		<div class="modal-footer">
			<a class="wpeo-button button-grey button-uppercase modal-close"><span>Annuler</span></a>
			<a class="wpeo-button button-main button-uppercase action-input"
				data-loader="wpeo-modal"
				data-namespace="taskManager"
				data-module="navigation"
				data-before-method="checkDataBeforeSearch"
				data-parent="form"
				data-action="create_shortcut">
				<span><?php esc_html_e( 'Create shortcut', 'task-manager' ); ?></span>
			</a>
		</div>
	</div>
</div>