<?php
/**
 * Affichage des "followers" qui peuvent être notifié.
 *
 * @author Jimmy Latour <jimmy.eoxia@gmail.com>
 * @since 1.5.0
 * @version 1.5.0
 * @copyright 2015-2017 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<li class="follower <?php echo ( in_array( $user->id, $task->user_info['affected_id'], true ) || $user->id === $task->user_info['owner_id'] ) ? 'active' : ''; ?>" data-id="<?php echo esc_attr( $user->id ); ?>" style="width: 50px; height: 50px;">
	<?php echo do_shortcode( '[task_avatar ids="' . $user->id . '" "size="50"]' ); ?>
</li>
