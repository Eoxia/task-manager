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

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>
<div class="wpeo-project-wrap tm-wrap">

	<h2><?php esc_html_e( 'Customer support', 'task-manager' ); ?></h2>

	<div class="wpeo-gridlayout grid-2">
		<div>
			<p>Mes tickets</p>
			<?php Support_Class::g()->display_projects( false ); ?>

			<p>Mes contrats</p>
			<?php Support_Class::g()->display_projects( true ); ?>
		</div>

		<div>
			<?php \eoxia\View_Util::exec( 'task-manager', 'support', 'frontend/form-create-ticket' ); ?>
		</div>
	</div>


</div>
