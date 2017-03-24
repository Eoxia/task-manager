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

<li class="action-attribute user" style="width: 50px; height: 50px;"
	data-id="<?php echo esc_attr( $user->id ); ?>"
	data-parent-id="<?php echo esc_attr( $task_id ); ?>"
	data-action="follower_affectation"
	data-nonce="<?php echo esc_attr( wp_create_nonce( 'follower_affectation' ) ); ?>">

	<img class="avatar avatar-32" src="<?php echo esc_attr( get_avatar_url( $user->id, 32 ) ); ?>" />
</li>
