<?php
/**
 * Vue des informations lors qu'une tache possède un parent Digirisk Risque.
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

?>

<ul class="wpeo-ul-parent" style="display: flex;">
  <li class="wpeo-task-parent">
    <?php if( class_exists( '\digi\Risk_Class' ) ): ?>
      <span class="wpeo-task-link">
        <i class="fas fa-link"></i>
      </span>

	    <?php $postrisk = \digi\Risk_Class::g()->get( array( 'id' => $task->data['parent_id'] ), true ); ?>

      <span><?php echo esc_attr( $postrisk->data[ 'unique_identifier' ] ); ?></span>

      <?php

      \eoxia\View_Util::exec(
  			'task-manager',
  			'task',
  			'backend/parent-item-digirisk-option',
  			array(
  				'risk' => $postrisk,
  			)
  		);
	  ?>
	    <span class="affiliated-label wpeo-tooltip-event" aria-label="<?php echo esc_attr( $postrisk->data[ 'parent' ]->data[ 'title' ] ); ?>"><?php echo esc_attr( $postrisk->data[ 'parent' ]->data[ 'title' ] ); ?></span>

	  <?php else: ?>
	    <?php esc_html_e( 'Please activate Digirisk to see this parent', 'task-manager' ); ?>
	  <?php endif; ?>
  </li>
  <li style="margin: auto auto auto 10px;">
    <span class="wpeo-button button-square-30 button-rounded button-grey wpeo-task-link tm-task-delink-parent" data-id="<?php echo esc_html( $task->data[ 'id' ] ); ?>">
      <i class="fas fa-unlink"></i>
    </span>
  </li>
</ul>
