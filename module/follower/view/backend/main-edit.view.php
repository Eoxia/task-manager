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
			\eoxia\View_Util::exec( 'task-manager', 'follower', 'backend/follower-edit', array(
				'user' => $follower,
				'task' => $task,
			) );
		endforeach;
	endif;
	?>

	<?php
	if ( ! empty( $followers_no_role ) ) :
		foreach ( $followers_no_role as $follower ) :
			\eoxia\View_Util::exec( 'task-manager', 'follower', 'backend/follower-edit', array(
				'user' => $follower,
				'task' => $task,
			) );
		endforeach;
	endif;
	?>

	<li class="follower edit action-attribute"
				data-action="close_followers_edit_mode"
				data-nonce="<?php echo esc_attr( wp_create_nonce( 'close_followers_edit_mode' ) ); ?>"
				data-id="<?php echo esc_attr( $task->data['id'] ); ?>">
		<i class="far fa-save" ></i>
	</li>
</ul>
