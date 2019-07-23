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

<ul class="wpeo-ul-parent">
  <li class="wpeo-task-parent">
    <?php if( class_exists( '\digi\Risk_Class' ) ): ?>
      <span class="wpeo-task-link">
        <i class="fas fa-link"></i>
      </span>


      <?php
        $postrisk = \digi\Risk_Class::g()->get( array( 'id' => $task->data['parent_id'] ), true );
     ?>


      <a class="wpeo-tooltip-event"
      style="font-size: 18px"
      aria-label="<?php echo esc_attr( $postrisk->data[ 'unique_key' ] ) . ' - ' . esc_attr( $postrisk->data[ 'unique_identifier' ] ); ?>"
      target="_blank">
        <?php echo esc_attr( $postrisk->data[ 'unique_key' ] ) . ' - ' . esc_attr( $postrisk->data[ 'unique_identifier' ] ); ?>
      </a>

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

    <a class="wpeo-tooltip-event"
    style="font-size: 18px"
    aria-label="<?php echo esc_attr( $postrisk->data[ 'parent' ]->data[ 'title' ] ); ?>"
    target="_blank">
      <?php echo esc_attr( $postrisk->data[ 'parent' ]->data[ 'title' ] ); ?>
    </a>

    <div>
      <i>
        <?php echo esc_attr( date( 'd-m-Y', strtotime( $postrisk->data[ 'date' ][ 'rendered' ][ 'mysql' ] ) ) ); ?> -
        <?php $name_posttype = get_post_type_object( $task->data['parent']->post_type ); ?>
        <?php echo esc_html( $name_posttype->label ) ?>
      </i>
    </div>
  <?php else: ?>
    <?php esc_html_e( 'Please activate Digirisk to see this parent', 'task-manager' ); ?>
  <?php endif; ?>
  </li>
  <li style="float: right; margin-top: -28px; cursor : pointer">
    <span class="wpeo-task-link tm-task-delink-parent" data-id="<?php echo esc_html( $task->data[ 'id' ] ); ?>">
      <i class="fas fa-unlink"></i>
    </span>
  </li>
</ul>
