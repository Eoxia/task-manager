<?php
/**
 * Affichage de la popup pour gérer les notifications.
 *
 * @author Jimmy Latour <jimmy.eoxia@gmail.com>
 * @since 1.5.0
 * @version 1.5.0
 * @copyright 2015-2017 Eoxia
 * @package Task_Manager
 */

namespace task_manager; ?>

<ul class="wpeo-ul-users">
	<?php
	if ( ! empty( $followers ) ) :
		foreach ( $followers as $follower ) :
			\eoxia\View_Util::exec( 'task-manager', 'notify', 'backend/list-admin-item', array(
				'user' => $follower,
				'task' => $task,
			) );
		endforeach;
	endif;
	?>
</ul>

<input type="hidden" name="users_id" value="<?php echo esc_attr( implode( ',', $affected_id ) ); ?>" />
