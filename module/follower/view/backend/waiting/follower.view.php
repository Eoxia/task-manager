<?php
/**
 * Follower en mode lecture.
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

<li class="follower active" style="width: 35px; height: 35px;">
	<?php echo do_shortcode( '[task_avatar ids="' . $user->data['id'] . '" size="35"]' ); ?>
</li>
