<?php
/**
 * Follower en mode édition.
 *
 * @package Task Manager
 * @subpackage Module/Follower
 *
 * @since 1.0.0.0
 * @version 1.3.6.0
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {	exit; } ?>

<li class="action-attribute user <?php echo in_array( $user->id, $task->user_info['affected_id'], true ) ? 'active' : ''; ?>" style="width: 50px; height: 50px;"
	data-id="<?php echo esc_attr( $user->id ); ?>"
	data-parent-id="<?php echo esc_attr( $task->id ); ?>"
	data-action="<?php echo in_array( $user->id, $task->user_info['affected_id'], true ) ? 'follower_unaffectation' : 'follower_affectation'; ?>"
	data-nonce="<?php echo esc_attr( wp_create_nonce( in_array( $user->id, $task->user_info['affected_id'], true ) ? 'follower_unaffectation' : 'follower_affectation' ) ); ?>">

	<img class="avatar avatar-32" src="<?php echo esc_attr( get_avatar_url( $user->id, array(
		'size' => 50,
		'default' => 'blank',
	) ) ); ?>" />
	<div class="wpeo-avatar-initial"><span><?php echo esc_html( $user->initial ); ?></span></div>
</li>
