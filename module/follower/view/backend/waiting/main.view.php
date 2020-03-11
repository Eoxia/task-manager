<?php
/**
 * Vue pour afficher la liste des followers dans une tâche.
 *
 * @package Task Manager
 * @subpackage Module/Tag
 *
 * @since 1.0.0
 * @version 1.6.0
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
				'backend/waiting/follower',
				array(
					'user' => $follower,
				)
			);
		endforeach;
	endif;
	?>

	<li class="action-attribute follower add wpeo-button button-grey button-square-40 button-rounded"
	    data-action="load_waiting_for"
	    data-nonce="<?php echo esc_attr( wp_create_nonce( 'waiting_for' ) ); ?>"
	    data-id="<?php echo esc_attr( $task->data['id'] ); ?>">

		<i class="fas fa-user-plus"></i>
	</li>
</ul>
