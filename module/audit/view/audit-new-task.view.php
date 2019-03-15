<?php
/**
 * Les bouttons pour importer ou créer une tache dans une audit
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.9.0
 * @version 1.9.0
 * @copyright 2019 Eoxia
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div class="tm-audit-new-task wpeo-button button-green action-attribute"
data-parent-id="<?php echo esc_attr( $parent_id ); ?>"
data-action="audit_created_task"
data-nonce="<?php echo esc_attr( wp_create_nonce( 'audit_created_task' ) ); ?>"
style="float: left;">
	<i class="fas fa-plus-square"></i>
</div>
