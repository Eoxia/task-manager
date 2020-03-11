<?php
/**
 * Le contenu la page "mon-compte" de WPShop.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.0.0
 * @version 1.2.0
 * @copyright 2015-2017 Eoxia
 * @package Task_Manager_WPShop
 */

namespace task_manager;

use eoxia\View_Util;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div class="wpeo-project-wrap tm-wrap tm-task-single">
	<input type="hidden" name="action" value="send_response_to_customer">
	<h2><?php esc_html_e( 'Customer support', 'task-manager' ); ?></h2>

	<a href="<?php echo esc_attr( $_SERVER['HTTP_REFERER'] ); ?>"><?php esc_html_e( 'Back to the list', 'task-manager' ); ?></a>

	<div class="task-header">
		<div class="header-nav-1">
			<span class="project-title"><i class="fas fa-hashtag"></i> <?php echo $project->data['id'] . ' ' . $project->data['title']; ?></span>
			<span class="project-time"><i class="far fa-clock"></i> <?php echo $project->data['time_info']['elapsed'] . '/' . $project->data['last_history_time']->data['estimated_time']; ?></span>
			<span class="project-tag"><i class="fas fa-tags"></i> <?php echo $project->readable_tag; ?></span>
		</div>
		<div class="header-nav-2">
			<span class="project-task"><i class="fas fa-hashtag"></i> <?php echo $task->data['id'] . ' ' . $task->data['content']; ?></span>
		</div>
	</div>

	<div class="tm-comment comment-new wpeo-form">
		<div class="comment-content">
			<div class="form-element">
				<label class="form-field-container">
					<textarea id="description" name="description" rows="2" placeholder="<?php esc_html_e( 'Your answer...', 'task-manager' ); ?>" class="form-field"></textarea>
				</label>
			</div>
		</div>
		<div class="comment-footer">
			<?php $current_user = get_userdata( get_current_user_id() ); ?>
			<div class="comment-author"><?php echo get_avatar( $current_user->data->ID, 30 ); ?> <span class="author-name"><?php echo esc_html( $current_user->data->display_name ); ?></span></div>
			<div class="wpeo-button button-blue action-input"
				data-project-id="<?php echo esc_attr ( $project->data['id'] ) ?>"
				data-task-id="<?php echo esc_attr ( $task->data['id'] ) ?>"
				data-loader="wpeo-project-wrap"
				data-parent="wpeo-project-wrap"
				data-nonce="<?php echo esc_attr( wp_create_nonce( 'send_response_to_customer' ) ); ?>">
				<?php esc_html_e( 'Submit', 'task-manager' ); ?>
			</div>
		</div>
	</div>

	<?php
	if ( ! empty( $comments ) ) :
		foreach ( $comments as $comment ) :
			View_Util::exec(
				'task-manager',
				'support',
				'frontend/comment',
				array(
					'comment' => $comment,
				)
			);
		endforeach;
	endif;
	?>
</div>
