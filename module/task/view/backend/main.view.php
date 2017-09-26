<?php
/**
 * La vue principale des tâches dans le backend.
 *
 * @author Jimmy Latour <jimmy.eoxia@gmail.com>
 * @since 1.0.0.0
 * @version 1.3.6.0
 * @copyright 2015-2017 Eoxia
 * @package task
 * @subpackage view
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) { exit; } ?>

<?php if ( $with_wrapper ) : ?><div class="wpeo-project-wrap"><?php endif; ?>
	<div class="list-task">
		<?php Task_Class::g()->display_tasks( $tasks ); ?>
	</div>

	<div class="load-more"><?php esc_html_e( 'Load more task...', 'task-manager' ); ?></div>
<?php if ( $with_wrapper ) : ?></div><?php endif; ?>
