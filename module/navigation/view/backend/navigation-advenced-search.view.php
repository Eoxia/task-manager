<?php
/**
 * Vue pour afficher la barre de recherche.
 *
 * @since 1.0.0
 * @version 1.6.0
 *
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div class="form wpeo-form" action="<?php echo esc_attr( admin_url( 'admin-ajax.php' ) ); ?>" method="POST" >
	<header class="wpeo-header-bar wpeo-general-search">
		<div class="tm-advanced-search">
			<div class="form-element">
				<span class="form-label"><i class="fas fa-th-large fa-fw"></i> <?php esc_html_e( 'ID Task', 'task-manager' ); ?></span>
				<label class="form-field-container">
					<input type="text" class="form-field" name="task_id" />
				</label>
			</div>

			<div class="form-element">
				<span class="form-label"><i class="fas fa-list-ul fa-fw"></i> <?php esc_html_e( 'ID Point', 'task-manager' ); ?></span>
				<label class="form-field-container">
					<input type="text" class="form-field" name="point_id" />
				</label>
			</div>

			<?php $eo_search->display( 'tm_search_admin' ); ?>

			<?php $eo_search->display( 'tm_search_customer' ); ?>

			<?php $eo_search->display( 'tm_search_order' ); ?>

			<?php
			\eoxia\View_Util::exec(
				'task-manager',
				'navigation',
				'backend/tags',
				array(
					'categories' => $categories,
				)
			);
			?>

			<div class="form-element">
				<input type="checkbox" id="include_archive" class="form-field" name="tm_dashboard_archives_include" value="include_archive">
				<label for="include_archive"><?php esc_attr_e( 'Include archive', 'task-manager' ); ?></label>
			</div>

			<a class="action-input search-button wpeo-button button-main"
				data-loader="form"
				data-namespace="taskManager"
				data-module="navigation"
				data-before-method="checkDataBeforeSearch"
				data-action="search"
				data-parent="form"><?php esc_html_e( 'Search', 'task-manager' ); ?></a>
		</div>
	</header>
</div>
