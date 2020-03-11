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
}
?>

<div class="tm-advanced-search form wpeo-form form-light" action="<?php echo esc_attr( admin_url( 'admin-ajax.php' ) ); ?>" method="POST" >
	<div class="form-element header-searchbar">
		<label class="general-search form-field-container">
			<span class="form-field-icon-prev"><i class="fas fa-search"></i></span>
			<input type="text" class="form-field" name="term"  value="<?php echo esc_attr( $param['term'] ); ?>" placeholder="<?php echo esc_html_e( 'Keyword', 'task-manager' ); ?>"/>
		</label>
	</div>

	<div class="form-element search-customers tm-search " >
		<label class="form-field-container">
			<span class="form-field-icon-prev"><i class="far fa-user"></i></span>
			<div class="wpeo-dropdown dropdown-right">
				<input type="hidden" name="post_parent" value="<?php echo ! empty( $data['post_parent_id'] ) ? esc_attr( $data['post_parent_id'] ) : ''; ?>" />

				<div class="form-field" style="height: 100%; display: flex;">
					<?php
					if ( ! empty( $data['post_parent'] ) ) :
						?>
						<div data-id="<?php echo esc_attr( $data['post_parent_id'] ); ?>" class="wpeo-button button-grey button-radius-2" style="display: flex;">
							<span><?php echo $data['post_parent']->post_title; ?></span>
							<i class="fas fa-times"></i>
						</div>
						<?php
					endif;
                    ?>

					<div class="content tm-filter tm-filter-customer field-elements" contenteditable="true" style="width: 100%"></div>
				</div>

				<ul class="dropdown-content dropdown-customers">
					<?php Navigation_Class::g()->dropdown_customer(); ?>
				</ul>
			</div>
		</label>
	</div>

	<?php $eo_search->display( 'tm_search_admin' ); ?>

	<div class="form-element search-categories tm-search " >
		<label class="form-field-container">
			<span class="form-field-icon-prev"><i class="fas fa-tag"></i></span>
			<?php
			\eoxia\View_Util::exec(
				'task-manager',
				'navigation',
				'backend/navigation-tag',
				array(
					'categories' => $categories,
					'data'       => $data,
				)
			);
			?>
		</label>
	</div>

	<div class="form-element">
		<input type="checkbox" id="include_archive" class="form-field" <?php checked( ! empty( $_GET['tm_dashboard_archives_include'] ) ? $_GET['tm_dashboard_archives_include'] : false ); ?> name="tm_dashboard_archives_include" value="include_archive">
		<label for="include_archive"><i class="fas fa-archive"></i> <?php esc_attr_e( 'Include archive', 'task-manager' ); ?></label>

		<input type="checkbox" id="include_completed_task" class="form-field" <?php checked( ! empty( $_GET['tm_completed_task'] ) ? $_GET['tm_completed_task'] : false ); ?> name="tm_completed_task" value="include_completed_task">
		<label for="include_completed_task"><i class="far fa-check-square"></i> <?php esc_attr_e( 'Include completed task', 'task-manager' ); ?></label>

		<input type="checkbox" id="include_uncompleted_task" class="form-field" <?php checked( ! empty( $_GET['tm_uncompleted_task'] ) ? $_GET['tm_uncompleted_task'] : false ); ?> name="tm_uncompleted_task" value="include_completed_task">
		<label for="include_uncompleted_task"><i class="far fa-square"></i> <?php esc_attr_e( 'Include uncompleted task', 'task-manager' ); ?></label>
	</div>

	<div class="search-action" style="display: flex">
		<a class="action-input search-button wpeo-button button-main button-square-40" style="margin-right: 10px;"
			data-loader="form"
			data-action="search"
			data-parent="form">
			<i class="fas fa-filter"></i>
		</a>

		<a class="action-input wpeo-tooltip-event wpeo-button button-main button-square-40"
			data-parent="form"
			data-action="load_modal_create_shortcut"
			data-target="wpeo-modal"
			aria-label="Save Shortcut"
			data-direction="left"
			data-title="<?php esc_html_e( 'Create shortcut', 'task-manager' ); ?>"><i class="button-icon fas fa-star"></i>
		</a>
	</div>
</div>
