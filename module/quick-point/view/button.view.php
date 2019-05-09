<?php
/**
 * Le bouton "point rapide".
 *
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2006-2018 Eoxia <dev@eoxia.com>.
 *
 * @license   AGPLv3 <https://spdx.org/licenses/AGPL-3.0-or-later.html>
 *
 * @package   TaskManager\Templates
 *
 * @since     1.8.0
 */

namespace task_manager;

defined( 'ABSPATH' ) || exit; ?>

<div class="wpeo-modal-event wpeo-tooltip-event wpeo-button button-grey button-square-30 button-rounded quick-point-event"
		data-action="load_modal_quick_point"
		data-title="<?php echo esc_attr_e( 'Quick point add', 'task-manager' ); ?>"
		aria-label="<?php echo esc_attr_e( 'Quick point add', 'task-manager' ); ?>"
		data-task-id="<?php echo esc_attr( $parent_id ); ?>"
		data-nonce="<?php echo esc_attr( wp_create_nonce( 'load_modal_quick_point' ) ); ?>"
		data-quick="true"
		data-class="tm-wrap quick-point">


		<i class="button-icon fas fa-list-ul"></i>
		<i class="button-icon fas fa-plus-circle"></i>
</div>
