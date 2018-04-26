<?php
/**
 * Follower en mode édition.
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

<li class="action-attribute follower <?php echo in_array( $user->data['id'], $task->data['user_info']['affected_id'], true ) ? 'active' : ''; ?>" style="width: 50px; height: 50px;"
	data-id="<?php echo esc_attr( $user->data['id'] ); ?>"
	data-parent-id="<?php echo esc_attr( $task->data['id'] ); ?>"
	data-action="<?php echo in_array( $user->data['id'], $task->data['user_info']['affected_id'], true ) ? 'follower_unaffectation' : 'follower_affectation'; ?>"
	data-nonce="<?php echo esc_attr( wp_create_nonce( in_array( $user->data['id'], $task->data['user_info']['affected_id'], true ) ? 'follower_unaffectation' : 'follower_affectation' ) ); ?>"
	data-namespace="taskManager"
	data-module="follower"
	data-before-method="<?php echo in_array( $user->data['id'], $task->data['user_info']['affected_id'], true ) ? 'beforeUnaffectFollower' : 'beforeAffectFollower'; ?>">

	<?php echo do_shortcode( '[task_avatar ids="' . $user->data['id'] . '" "size="50"]' ); ?>
</li>
