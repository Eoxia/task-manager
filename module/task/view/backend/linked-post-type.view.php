<?php
/**
 * Vue des informations supplémentaire dans le sommaire des tâches.
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

<ul class="wpeo-ul-parent">
    <li class="wpeo-task-parent">
        <i class="far fa-link"></i>
        <a class="wpeo-tooltip-event"
            aria-label="<?php echo esc_attr( $task->data['parent']->post_title ); ?>"
            target="_blank" href="<?php echo admin_url( 'post.php?post=' . $task->data['parent_id'] . '&action=edit' ); ?>">
            <?php echo esc_html( $task->data['parent']->displayed_post_title ); ?>
        </a>
    </li>
</ul>
