<?php
/**
 * Affichage du tableau pour les cohérences des points.
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

<div class="wrap">
	<h1><?php esc_html_e( 'Task Manager Tools', 'task-manager' ); ?></h1>

	<div class="wpeo-project-wrap">
		<table style="width: 100%; border-collapse: collapse;">
			<tr style="text-align: left;">
				<th style="padding: 2px; border: solid black 1px; width: 10%">Task ID</th>
				<th style="padding: 2px; border: solid black 1px; width: 10%">Point ID</th>
				<th style="padding: 2px; border: solid black 1px; width: 10%">Point (comment_post_ID)</th>
				<th style="padding: 2px; border: solid black 1px; width: 10%">comment_parent</th>
				<th style="padding: 2px; border: solid black 1px; width: 5%;">Status</th>
				<th style="padding: 2px; border: solid black 1px; width: 5%;">Type</th>
				<th style="padding: 2px; border: solid black 1px; width: 10%">Date</th>
				<th style="padding: 2px; border: solid black 1px;">Avis</th>
			</tr>

			<?php
			if ( ! empty( $tasks ) ) :
				foreach ( $tasks as $task ) :
					if ( ! empty( $task->points ) && count( $task->points ) ) :
						?>
						<tr>
							<td colspan="7" style="border: solid black 1px;background-color:#CCC;" ><?php echo esc_html( $task->id ); ?></td>
						</tr>
						<?php
						foreach ( $task->points as $point ) :
							if ( 0 !== $point->id ) :
								$coherent_messages = array();
								if ( $point->post_id !== $task->id ) :
									$coherent_messages[] = 'The comment_post_ID of this point is not okay.';
								endif;

								if ( $point->post_id === 0 ) :
									$coherent_messages[] = 'The comment_post_ID is 0.';
								endif;

								if ( '01/01/1970' === $point->date['date_input']['fr_FR']['date'] || '00/00/0000' === $point->date['date_input']['fr_FR']['date'] ) :
									$coherent_messages[] = 'The comment_date is broken.';
								endif;
								?>
								<tr>
									<td>&nbsp;</td>
									<td style="border: solid black 1px;"><?php echo esc_html( $point->id ); ?></td>
									<td style="border: solid black 1px;"><?php echo esc_html( $point->post_id ); ?></td>
									<td style="border: solid black 1px;"><?php echo esc_html( 0 ); ?></td>
									<td style="border: solid black 1px;"><?php echo esc_html( $point->status ); ?></td>
									<td style="border: solid black 1px;">Point</td>

									<td style="border: solid black 1px;"><?php echo esc_html( $point->date['date_input']['fr_FR']['date'] ); ?></td>
									<td style="border: solid black 1px;">
										<?php
										if ( ! empty( $coherent_messages ) ) :
											?>
											<ul>
												<?php foreach( $coherent_messages as $message ) : ?>
													<li><?php echo esc_html( $message ); ?></li>
												<?php endforeach; ?>
											</ul>
											<?php
										endif;
										?>
									</td>
								</tr>
							<?php
							if ( ! empty( $point->comments ) ) :
								foreach ( $point->comments as $comment ) :
									if ( 0 !== $comment->id ) :
										$coherent_messages = array();
										if ( $comment->post_id !== $task->id ) :
											$coherent_messages[] = 'The comment_post_ID of this point is not okay.';
										endif;

										if ( $comment->post_id === 0 ) :
											$coherent_messages[] = 'The comment_post_ID is 0.';
										endif;

										if ( '01/01/1970' === $comment->date['date_input']['fr_FR']['date'] || '00/00/0000' === $comment->date['date_input']['fr_FR']['date'] ) :
											$coherent_messages[] = 'The comment_date is broken.';
										endif;
										?>
										<tr>
											<td>&nbsp;</td>
											<td style="border: solid black 1px;"><?php echo esc_html( $comment->id ); ?></td>
											<td style="border: solid black 1px;"><?php echo esc_html( $comment->post_id ); ?></td>
											<td style="border: solid black 1px;"><?php echo esc_html( $comment->parent_id ); ?></td>
											<td style="border: solid black 1px;"><?php echo esc_html( $comment->status ); ?></td>
											<td style="border: solid black 1px;">Comment</td>

											<td style="border: solid black 1px;"><?php echo esc_html( $comment->date['date_input']['fr_FR']['date'] ); ?></td>
											<td style="border: solid black 1px;">
												<?php
												if ( ! empty( $coherent_messages ) ) :
													?>
													<ul>
														<?php foreach( $coherent_messages as $message ) : ?>
															<li><?php echo esc_html( $message ); ?></li>
														<?php endforeach; ?>
													</ul>
													<?php
												endif;
												?>
											</td>
										</tr>
								<?php
							endif;
								endforeach;
							endif;
						endif;
						endforeach;
					endif;
				endforeach;
			endif;
			?>
		</table>

		<!-- Pagination -->
	<?php if ( !empty( $current_page ) && !empty( $number_page ) ): ?>
		<div class="wp-digi-pagination">
			<?php
			$big = 999999999;
			echo paginate_links( array(
				'base' => admin_url( 'tools.php?page=taskmanager-tools&current_page=%_%' ),
				'format' => '%#%',
				'current' => $current_page,
				'total' => $number_page,
				'before_page_number' => '<span class="screen-reader-text">'. __( 'Page', 'digirisk' ) .' </span>',
				'type' => 'plain',
				'next_text' => '<i class="dashicons dashicons-arrow-right"></i>',
				'prev_text' => '<i class="dashicons dashicons-arrow-left"></i>'
			) );
			?>
		</div>
	<?php endif; ?>
	</div>
</div>
