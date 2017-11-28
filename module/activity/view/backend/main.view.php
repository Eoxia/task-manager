<?php
/**
 * Affichage des points en mode 'grille'.
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

<div class="activities hidden">
	<input type="hidden" class="offset-event" value="<?php echo esc_attr( \eoxia\Config_Util::$init['task-manager']->activity->activity_per_page ); ?>" />
	<input type="hidden" class="last-date" value="" />

	<div class="content">
	</div>

	<span class="load-more-history"><?php esc_html_e( 'Load more', 'task-manager' ); ?></span>
</div>
