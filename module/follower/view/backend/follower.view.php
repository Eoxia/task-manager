<?php
/**
 * Follower en mode lecture.
 *
 * @package Task Manager
 * @subpackage Module/Follower
 *
 * @since 1.0.0
 * @version 1.5.0
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<li class="follower active" style="width: 50px; height: 50px;">
	<?php echo do_shortcode( '[task_avatar ids="' . $user->id . '" "size="50"]' ); ?>
</li>
