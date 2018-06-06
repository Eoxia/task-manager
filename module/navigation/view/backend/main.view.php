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

<div class="form alignleft" action="<?php echo esc_attr( admin_url( 'admin-ajax.php' ) ); ?>" method="POST" >
	<header class="wpeo-header-bar wpeo-general-search">
		<input type="hidden" name="action" value="search" />
		<label for="general-search">
			<i class="dashicons dashicons-search"></i>
			<input type="text" name="term" value="<?php echo esc_attr( $param['term'] ); ?>" placeholder="<?php esc_attr_e( 'Search...', 'task-manager' ); ?>" />
		</label>
		<span class="wpeo-button button-light more-search-options"><?php esc_html_e( 'More options', 'task-manager' ); ?></span>
		<div class="wpeo-header-search hidden active">
			<?php \eoxia\View_Util::exec( 'task-manager', 'navigation', 'backend/followers', array( 'followers' => $followers ) ); ?>

			<ul><li class="tag-search"><?php \eoxia\View_Util::exec( 'task-manager', 'navigation', 'backend/tags', array( 'categories' => $categories ) );?></li></ul>

			<?php echo apply_filters( 'tm_search_options_bottom', '' ); // WPCS: XSS ok. ?>

			<label>
				<input type="checkbox" name="tm-dashboard-archives-include" value="include-archive" />
				<?php esc_html_e( 'Include archives', 'task-manager' ); ?>
			</label>

			<a class="action-input search-button"
				data-loader="form"
				data-namespace="taskManager"
				data-module="navigation"
				data-before-method="checkDataBeforeSearch"
				data-parent="form"><?php esc_html_e( 'Search', 'task-manager' ); ?></a>
		</div>
	</header>
</div>
<?php //Navigation_Class::g()->display_search_result( $param['term'], $param['status'], $param['categories_id_selected'], $param['follower_id_selected'] );
