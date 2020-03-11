<?php
/**
 * Vue pour afficher la liste des followers dans une tâche.
 *
 * @package Task Manager
 * @subpackage Module/Tag
 *
 * @since 1.0.0
 * @version 1.6.0
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div class="wpeo-dropdown" data-to-element="232">
	<ul class="dropdown-content">
		<?php
		if ( ! empty( $users ) ) :
			foreach ( $users as $user ) :
				?>
				<li class="dropdown-item" data-id="<?php echo esc_attr( $user->ID ); ?>">
					<?php echo do_shortcode( '[task_avatar ids="' . $user->ID . '" size="30"]' ); ?>
					<div class="content-text"><?php echo esc_attr( $user->display_name ); ?></div>
					<div class="tm-user-data">
						<input type="hidden" value="<?php echo esc_attr( $user->display_name . '#' . $user->ID ); ?>" />
					</div>
				</li>
				<?php
			endforeach;
		endif;
		?>
	</ul>
</div>
