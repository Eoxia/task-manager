<?php
/**
 * Gestion des raccourcis.
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

<div class="wpeo-button button-main action-input"
	 data-parent="wpeo-modal"
	data-action="tm_save_order_shortcut">
	<span><?php esc_html_e( 'Save changes', 'task-manager' ); ?></span>
</div>

<div class="wpeo-button button-grey modal-close">
	<span><?php esc_html_e( 'Cancel', 'task-manager' ); ?></span>
</div>
