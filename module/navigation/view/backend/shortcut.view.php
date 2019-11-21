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

if ( 'folder' === $shortcut['type'] ) :
	?>
	<li class="wpeo-dropdown">
		<div class="dropdown-toggle wpeo-button button-main">
			<span>
				<i class="fas fa-folder"></i>
				<?php echo esc_html( $shortcut['label'] ); ?>
			<i class="button-icon fas fa-caret-down"></i></div>
		</span>
		<ul class="dropdown-content">
			<?php
			if ( ! empty( $shortcut['shortcuts'] ) ) :
				foreach ( $shortcut['shortcuts'] as $s ) :
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
					<a class="wpeo-button button-size-small button-transparent" href="#">
						<?php echo esc_html_e( '(Empty)', 'task-manager' ); ?>
					</a>
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
		<a class="wpeo-button button-size-small button-transparent" href="<?php echo admin_url( $shortcut['page'] . $shortcut['link'] ); ?>">
			<i class="fas fa-link"></i>
			<?php echo esc_html( $shortcut['label'] ); ?>
		</a>
	</li>
	<?php
endif;

