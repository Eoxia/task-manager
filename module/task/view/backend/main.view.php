<?php
/**
 * La vue principale des tâches dans le backend.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.0.0.0
 * @version 1.3.6.0
 * @copyright 2015-2017 Eoxia
 * @package task
 * @subpackage view
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; } ?>
<?php $comment = Task_Comment_Class::g()->get(
	array(
		'parent' => 402,
	),
	true
);
//echo '<pre>'; print_r( $comment ); echo '</pre>'; exit; ?>
<div class="list-task">
	<div class="grid-col grid-col--1"></div>
	<div class="grid-col grid-col--2"></div>

	<?php Task_Class::g()->display_tasks( $tasks ); ?>
	<div class="load_more_task_here"></div>
</div>

<div class="load-more"><?php esc_html_e( 'Load more task...', 'task-manager' ); ?></div>
