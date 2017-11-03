<?php
/**
 * La vue principale des indications.
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

<div class="wrap wpeo-project-wrap metabox-holder columns-2">
	<h2><?php esc_html_e( 'Indicator', 'task-manager' ); ?></h2>

	<?php
	do_meta_boxes( 'task-manager-indicator-support', 'normal', '' );
	do_meta_boxes( 'task-manager-indicator-my-activity', 'normal', '' );
	?>
</div>
