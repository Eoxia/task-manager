<?php
/**
 * Follower en mode lecture..
 *
 * @package Task Manager
 * @subpackage Module/Follower
 *
 * @since 1.0.0.0
 * @version 1.3.6.0
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {	exit; } ?>

<li class="user" style="width: 50px; height: 50px;">
	<img class="avatar avatar-32" src="<?php echo esc_attr( get_avatar_url( $user->id, array(
		'size' => 32,
		'default' => 'blank',
	)	) ); ?>" />
	<div class="wpeo-avatar-initial"><span><?php echo esc_html( $user->initial ); ?></span></div>
</li>
