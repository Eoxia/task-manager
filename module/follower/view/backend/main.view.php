<?php
/**
 * Vue pour afficher la liste des followers dans une tâche.
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
			\eoxia\View_Util::exec( 'task-manager', 'follower', 'backend/follower', array(
				'user' => $follower,
			) );
		endforeach;
	endif;
	?>

	<li class="action-attribute follower add"
			data-action="load_followers"
			data-nonce="<?php echo esc_attr( wp_create_nonce( 'load_followers' ) ); ?>"
			data-id="<?php echo esc_attr( $task->id ); ?>">
		<span class="dashicons dashicons-plus-alt"></span>
		<?php esc_html_e( 'Followers', 'task-manager' ); ?>
	</li>
</ul>
