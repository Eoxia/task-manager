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

<div class="wpeo-form">
	<div class="form-element">
		<span class="form-label"><?php esc_html_e( 'Shortcut name', 'task-manager' ); ?></span>
		<label class="form-field-container">
			<input type="text" class="form-field" name="shortcut-name" />
		</label>
	</div>
</div>