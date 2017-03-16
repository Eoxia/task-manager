<?php
/**
 * La vue principale de la page "wpeomtm-dashboard"
 *
 * @author Jimmy Latour <jimmy.eoxia@gmail.com>
 * @since 1.0.0.0
 * @version 1.3.6.0
 * @copyright 2015-2017 Eoxia
 * @package core
 * @subpackage view
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) { exit; } ?>

<div class="wrap wpeo-project-wrap">

	<div class="wpeo-project-dashboard">
		<h2>
			<?php	esc_html_e( 'Tasks Manager', 'task-manager' ); ?>
		</h2>
	</div>

	<?php do_shortcode( '[task_manager_dashboard_content]' ); ?>
</div>
