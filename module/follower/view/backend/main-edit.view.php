<?php
/**
 * Vue pour afficher la liste des followers en mode "edition" dans une tâche.
 *
 * @package Task Manager
 * @subpackage Module/Tag
 *
 * @since 1.0.0.0
 * @version 1.3.6.0
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {	exit; } ?>

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

	<li class="follower edit action-attribute"
				data-action="close_followers_edit_mode"
				data-nonce="<?php echo esc_attr( wp_create_nonce( 'close_followers_edit_mode' ) ); ?>"
				data-id="<?php echo esc_attr( $task->id ); ?>">
		<i class="fa fa-floppy-o" ></i>
	</li>
</ul>
