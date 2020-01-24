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

	<input type="hidden" name="term" value="<?php echo ! empty( $term ) ? esc_attr( $term ) : ''; ?>" />
	<input type="hidden" name="task_id" value="<?php echo ! empty( $task_id ) ? esc_attr( $task_id ) : ''; ?>" />
	<input type="hidden" name="point_id" value="<?php echo ! empty( $point_id ) ? esc_attr( $point_id ) : ''; ?>" />
	<input type="hidden" name="user_id" value="<?php echo ! empty( $user_id ) ? esc_attr( $user_id ) : ''; ?>" />
	<input type="hidden" name="categories_id" value="<?php echo ! empty( $categories_id ) ? esc_attr( $categories_id ) : ''; ?>" />
	<input type="hidden" name="post_parent" value="<?php echo ! empty( $post_parent ) ? esc_attr( $post_parent ) : ''; ?>" />

<div class="form-element">
	<span class="form-label"><?php esc_html_e( 'Shortcut name', 'task-manager' ); ?></span>
	<label class="form-field-container">
		<input type="text" class="form-field" name="shortcut_name" />
		</label>
	</div>
</div>
