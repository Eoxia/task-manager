<?php
/**
 * Vue pour afficher la liste des followers en mode "edition" dans une tâche.
 *
 * @since 1.0.0
 * @version 1.6.0
 *
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<ul class="wpeo-ul-users">
	<?php
	if ( ! empty( $followers ) ) :
		foreach ( $followers as $follower ) :
			\eoxia\View_Util::exec(
				'task-manager',
				'follower',
				'backend/waiting/follower-edit',
				array(
					'user' => $follower,
					'task' => $task,
				)
			);
		endforeach;
	endif;
	?>

	<li class="follower edit action-attribute wpeo-button button-grey button-square-40 button-rounded"
	    data-action="close_waiting_for_edit_mode"
	    data-nonce="<?php echo esc_attr( wp_create_nonce( 'close_waiting_for_edit_mode' ) ); ?>"
	    data-id="<?php echo esc_attr( $task->data['id'] ); ?>">

		<i class="fas fa-save" ></i>
	</li>
</ul>
