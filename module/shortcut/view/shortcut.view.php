<?php
/**
 * Vue pour afficher un raccourcis
 *
 * @since 1.8.0
 *
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$active = '';

if ( $shortcut['link'] == $url || $new ) :
	$active = ' active ';
endif;

if ( isset( $shortcut['type'] ) && 'folder' === $shortcut['type'] ) :
	?>
	<li class="wpeo-dropdown dashboard-shortcut ">
		<div class="dropdown-toggle wpeo-button button-size-small button-grey button-radius-2">
			<i class="button-icon fas fa-folder"></i>
			<span><?php echo esc_html( $shortcut['label'] ); ?></span>
			<i class="button-icon fas fa-caret-down"></i>
		</div>
		<ul class="dropdown-content">
			<?php
			if ( ! empty( $shortcut['child'] ) ) :
				foreach ( $shortcut['child'] as $s ) :
					?>
					<li class="dropdown-item">
						<a class="wpeo-button button-size-small button-transparent" href="<?php echo admin_url( $s['page'] . $s['link'] ); ?>">
							<?php echo esc_html( $s['label'] ); ?>
						</a>
					</li>
					<?php
				endforeach;
			else :
				?>
				<li class="dropdown-item">
					<?php echo esc_html_e( '(Empty)', 'task-manager' ); ?>
				</li>
				<?php
			endif;
			?>
		</ul>
	</li>
	<?php
else :
	?>
	<li data-key="<?php echo esc_attr( $key ); ?>" class="dashboard-shortcut <?php echo esc_attr( $active ); ?>">
		<a class="wpeo-button button-size-small button-grey button-radius-2" href="<?php echo admin_url( $shortcut['page'] . $shortcut['link'] ); ?>">
			<span><?php echo esc_html( $shortcut['label'] ); ?></span>
		</a>
	</li>
	<?php
endif;

