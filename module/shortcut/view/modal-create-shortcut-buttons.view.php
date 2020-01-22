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

<a class="wpeo-button button-grey button-uppercase modal-close"><span>Annuler</span></a>
<a class="wpeo-button button-main button-uppercase action-input"
	data-loader="wpeo-modal"
	data-parent="wpeo-modal"
	data-action="create_shortcut">
	<span><?php esc_html_e( 'Create shortcut', 'task-manager' ); ?></span>
</a>
