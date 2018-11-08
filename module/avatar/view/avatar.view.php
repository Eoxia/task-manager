<?php
/**
 * Affiches l'avatar et l'initiale des utilisateurs.
 *
 * @since 1.0.0
 * @version 1.6.0
 *
 * @package Task Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<?php
if ( ! empty( $users ) ) :
	foreach ( $users as $user ) :
		?>
		<div class="tm-avatar wpeo-tooltip-event" aria-label="<?php echo esc_attr( $user->data['displayname'] ); ?>" style="width: <?php echo esc_attr( $size ); ?>px; height: <?php echo esc_attr( $size ); ?>px;">
			<img class="avatar avatar-<?php echo esc_attr( $size ); ?>" src="<?php echo esc_url( $user->data['avatar_url'] ); ?>" />
			<div class="wpeo-avatar-initial"><span><?php echo esc_html( $user->data['initial'] ); ?></span></div>
		</div>
		<?php
	endforeach;
endif;
