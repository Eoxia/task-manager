<?php
/**
 * Affichage de la liste des utilisateurs pour affecter les capacités
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.5.0
 * @version 1.6.0
 * @copyright 2015-2018 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div class="list-users">
	<table class="wpeo-table users">
		<thead>
			<tr>
				<td class="w50"></td>
				<td class="w50 padding"><?php esc_html_e( 'ID', 'task-manager' ); ?></td>
				<td class="padding"><?php esc_html_e( 'Email', 'task-manager' ); ?></td>
				<td class="padding"><?php esc_html_e( 'Role', 'task-manager' ); ?></td>
				<td class="padding"><?php esc_html_e( 'Right on Task Manager', 'task-manager' ); ?></td>
			</tr>
		</thead>
		<?php
		if ( ! empty( $users ) ) :
			foreach ( $users as $user ) :
				\eoxia\View_Util::exec(
					'task-manager',
					'setting',
					'capability/list-item',
					array(
						'user'                 => $user,
						'has_capacity_in_role' => $has_capacity_in_role,
					)
				);
			endforeach;
		endif;
		?>
	</table>

	<!-- Pagination -->
	<?php if ( ! empty( $current_page ) && ! empty( $number_page ) ) : ?>
		<div class="wp-digi-pagination">
			<?php
			echo paginate_links(
				array(
					'base'               => admin_url( 'admin-ajax.php?action=task-manager-setting&current_page=%_%' ),
					'format'             => '%#%',
					'current'            => $current_page,
					'total'              => $number_page,
					'before_page_number' => '<span class="screen-reader-text">' . __( 'Page', 'task-manager' ) . ' </span>',
					'type'               => 'plain',
					'next_text'          => '<i class="dashicons dashicons-arrow-right"></i>',
					'prev_text'          => '<i class="dashicons dashicons-arrow-left"></i>',
				)
			);
			?>
		</div>
	<?php endif; ?>
</div>
