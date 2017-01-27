<?php if ( !defined( 'ABSPATH' ) ) exit; ?>

<?php if ( !empty( $list_comment ) ): ?>
	<ul class="wpeo-project-last-comment">
			<?php foreach ( $list_comment as $comment ): ?>
				<li>
					<ul class="wpeo-point-comment wpeo-point-comment-<?php echo $comment->id; ?>" data-id="<?php echo $comment->id; ?>">
						<li>
							<?php echo get_avatar( $comment->author_id, 32 ); ?>
						</li>
						<li>
							<?php 
							echo $list_user_in[$comment->author_id]->user_nicename . ', ';
							_e( 'On', 'task-manager' );
							echo ' '; comment_date( get_option( 'date_format' ), $comment->id ); echo ' ';
							_e( 'at', 'task-manager' ); 
							echo ' '; comment_date( get_option( 'time_format' ), $comment->id ); echo ' ';
							_e( 'to the point', 'task-manager' );
							echo ' <a class="wpeo-last-point" href="#" data-user="' . wps_customer_ctr::get_customer_id_by_author_id( $comment->author_id ) . '" data-id="' . $comment->parent_id . '"#' . $comment->parent_id . ' : <strong>' . substr( point_controller_01::get_point_name_by_id( $comment->parent_id ), 0, 30 ) . '...</a></strong> ';
							_e( 'to the task', 'task-manager' );
							echo '  <a class="wpeo-last-point" href="#" data-user="' . wps_customer_ctr::get_customer_id_by_author_id( $comment->author_id ) . '"#' . $comment->post_id . ' : <strong>' . substr( task_controller_01::get_task_title_by_id( $comment->post_id ), 0, 30 ) . '...</a></strong>';
							?>
						</li>
						<li>
							<?php echo $comment->option['time_info']['elapsed']; ?>
							<span class="dashicons dashicons-clock"></span>
						</li>
						<li>
							<?php echo stripslashes( $comment->content ); ?>
						</li>
					</ul>
				</li>
			<?php endforeach; ?>
		<?php endif; ?>
		<li>
		<!-- Pagination -->	
		<?php if ( !empty( $number_paginate ) ): ?>
			<?php for( $i = 0; $i < $number_paginate; ++$i): ?>
			<?php if( $current_page != $i + 1 ): ?>
				<?php if( ($i + 1 - $current_page < 5 && $i + 1 - $current_page > -5 ) || $i + 1 == $number_paginate ): ?>
			 		<a class="last-comment-page" href="#" data-page="<?php echo $i + 1; ?>"><?php echo $i + 1; ?></a>
			 	<?php elseif ( $i + 1 == $number_paginate - 1 ): ?>
			 		...
			 	<?php elseif( $i + 1 == 1 ): ?>
			 	<a class="last-comment-page" href="#" data-page="<?php echo $i + 1; ?>"><?php echo $i + 1; ?></a> ... 
			 	<?php endif; ?>
			 <?php else: ?>
			 	<?php echo $i + 1; ?>
			 <?php endif; ?>
			<?php endfor; ?>
		</li>
	</ul>
<?php endif; ?>