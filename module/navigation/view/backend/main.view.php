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

		<div class="form-element header-searchbar">
			<label class="general-search form-field-container">
				<span class="form-field-icon-prev"><i class="fas fa-search"></i></span>
				<input type="text" name="term" value="<?php echo esc_attr( $param['term'] ); ?>" class="form-field" placeholder="<?php esc_attr_e( 'Search...', 'task-manager' ); ?>" />
			</label>
		</div>

		<div class="wpeo-dropdown dropdown-force-display dropdown-right dropdown-padding-2 tm-advanced-search">
			<div class="dropdown-toggle wpeo-button button-main"><i class="button-icon fas fa-cog"></i> <span><?php esc_html_e( 'Advanced search', 'task-manager' ); ?></span></div>
			<ul class="dropdown-content">
				<div class="wpeo-gridlayout grid-2">
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
				</div>

				<a class="action-input search-button wpeo-button button-main"
				data-loader="form"
				data-namespace="taskManager"
				data-module="navigation"
				data-before-method="checkDataBeforeSearch"
				data-action="search"
				data-parent="form"><?php esc_html_e( 'Search', 'task-manager' ); ?></a>
			</ul>
		</div>
	</header>
</div>
