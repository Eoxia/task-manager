<?php
/**
 * La vue d'une tâche dans le backend.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.9.0
 * @version 1.9.0
 * @copyright 2019 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div class="wpeo-dropdown wpeo-comment-setting"
		data-parent="toggle"
		data-target="content"
		data-mask="wpeo-project-task">

	<span class="wpeo-button button-bordered button-dark dropdown-toggle wpeo-tooltip-event" aria-label="<?= $title ?>">
		<!-- <i class="fa fa-ellipsis-v"></i> -->
		<span> #<?= $id ?> </span>
	</span>

</div>
