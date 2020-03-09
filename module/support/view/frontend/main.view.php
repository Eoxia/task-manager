<?php
/**
 * Le contenu la page "Support" de WPShop.
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

	<div class="tm-open-ticket"><?php \eoxia\View_Util::exec( 'task-manager', 'support', 'frontend/form-create-ticket' ); ?></div>
	<div class="tm-list-ticket">
		<h3>Mes tickets</h3>
		<?php Support_Class::g()->display_projects( false ); ?>

		<h3>Mes contrats</h3>
		<?php Support_Class::g()->display_projects( true ); ?>
	</div>

</div>
