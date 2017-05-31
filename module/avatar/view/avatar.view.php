<?php
/**
 * Affiches l'avatar et l'initiale des utilisateurs.
 *
 * @package Task Manager
 * @subpackage Module/Avatar
 *
 * @since 1.0.0.0
 * @version 1.3.6.0
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {	exit; } ?>

<?php
if ( ! empty( $users ) ) :
	foreach ( $users as $user ) :
		?>
		<div class="user tooltip hover" aria-label="<?php echo esc_attr( $user->displayname ); ?>" style="width: <?php echo $size; ?>px; height: <?php echo $size; ?>px;">
			<img class="avatar avatar-<?php echo $size; ?>" src="<?php echo $user->avatar_url; ?>" />
			<div class="wpeo-avatar-initial"><span><?php echo esc_html( $user->initial ); ?></span></div>
		</div>
		<?php
	endforeach;
endif;
