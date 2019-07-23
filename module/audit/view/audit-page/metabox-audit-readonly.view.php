<?php
/**
 * le mode édition qui permet de modifier la tache
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.10.0
 * @version 1.10.0
 * @copyright 2019 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div class="audit-title action-attribute" contenteditable="false"
	data-action="edit_audit"
	data-page="audit-page/metabox-audit-edit"
	data-nonce="<?php echo esc_attr( wp_create_nonce( 'edit_audit' ) ); ?>"
	data-parentpage="<?php echo isset( $parent_page ) ? esc_attr( $parent_page ) : 0 ?>"
	data-id="<?= $audit->data[ 'id' ] ?>"
	style="font-size: 24px;">
	<span class="wpeo-tooltip-event" aria-label="<?php esc_html_e( 'Click to open audit', 'task-manager' ); ?>">
		<?= ! empty( $audit->data[ 'title' ] ) ? $audit->data[ 'title' ] : esc_html_e( 'No name Audit', 'task-manager' );  ?></div>
	</span>

<ul class="audit-summary">
	<li class="audit-summary-id wpeo-tooltip-event"
	aria-label="<?php esc_html_e( 'ID', 'task-manager' ); ?>"><i class="fas fa-hashtag"></i><?= $audit->data[ 'id' ] ?></li>
	<li class="audit-summary-date">
		<span class="summary-created wpeo-tooltip-event" aria-label="<?php esc_html_e( 'Created date', 'task-manager' ); ?>">
			<i class="fas fa-calendar-alt"></i> <?= $audit->data[ 'date' ][ 'rendered' ][ 'date' ] ?>
		</span>
		<?php /*<span class="summary-rendered wpeo-tooltip-event" aria-label="<?php esc_html_e( 'Due date', 'task-manager' ); ?>">
			<i class="fas fa-calendar-alt"></i> <?= $audit->data[ 'deadline' ][ 'rendered' ][ 'date' ] ?>
		</span>*/ ?>
	</li>
		<?php if( isset( $audit->data[ 'parent_id' ] ) && $audit->data[ 'parent_id' ] ): ?>
			<span class="summary-rendered">
				<a class="wpeo-tooltip-event wpeo-tooltip-event" aria-label="<?php esc_html_e( 'Audit Parent', 'task-manager' ); ?>"
					href="<?php echo admin_url( 'post.php?post=' . $audit->data[ 'parent_id' ] . '&action=edit' ); ?>" style="text-decoration: none;">
					<i class="fas fa-clone"></i>
					#<?php echo esc_html( $audit->data[ 'parent_id' ] ); ?> -
					<?php echo esc_html( $audit->data[ 'parent_title' ] ); ?>
				</a>
			</span>
		<?php endif; ?>
	</li><?php echo esc_attr( $audit->data[ 'status_audit' ] ); ?>
</ul>
