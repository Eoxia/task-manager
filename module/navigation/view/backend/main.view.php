<?php
/**
 * Vue pour afficher la barre de recherche.
 *
 * @package Task Manager
 * @subpackage Module/Tag
 *
 * @since 1.0.0.0
 * @version 1.3.6.0
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {	exit; } ?>

<div class="form" action="<?php echo esc_attr( admin_url( 'admin-ajax.php' ) ); ?>" method="POST">
	<header class="wpeo-header-bar">
		<ul>

			<li class="action-attribute active" data-action="load_all_task" data-nonce="<?php echo esc_attr( wp_create_nonce( 'load_all_task' ) ); ?>"><?php esc_html_e( 'All tasks', 'task-manager' ); ?></li>
			<li class="action-attribute" data-action="load_my_task" data-nonce="<?php echo esc_attr( wp_create_nonce( 'load_my_task' ) ); ?>"><?php esc_html_e( 'My task', 'task-manager' ); ?></li>
			<li class="action-attribute" data-action="load_archived_task" data-nonce="<?php echo esc_attr( wp_create_nonce( 'load_archived_task' ) ); ?>"><?php esc_html_e( 'Archived task', 'task-manager' ); ?></li>

			<?php echo apply_filters( 'task_manager_navigation_after', '' ); ?>

			<li class="wpeo-general-search">
				<input type="hidden" name="action" value="search" />
				<label for="general-search">
					<i class="dashicons dashicons-search"></i>
					<input type="text" name="term" value="<?php echo esc_attr( $param['term'] ); ?>" placeholder="<?php esc_attr_e( 'Search...', 'task-manager' ); ?>" />
				</label>
				<span class="more-search-options"><?php esc_html_e( 'More options', 'task-manager' ); ?></span>
			</li>

		</ul>
	</header>

	<div class="wpeo-header-search hidden active">

		<?php \eoxia\View_Util::exec( 'task-manager', 'navigation', 'backend/followers', array(
			'followers' => $followers,
		) ); ?>

		<ul>
			<li class="tag-search">
				<?php \eoxia\View_Util::exec( 'task-manager', 'navigation', 'backend/tags', array(
					'categories' => $categories,
				) ); ?>
			</li>
		</ul>

		<a class="action-input search-button" data-loader="form" data-namespace="taskManager" data-module="navigation" data-before-method="checkDataBeforeSearch" data-parent="form"><?php esc_html_e( 'Search', 'task-manager' ); ?></a>
	</div>
</div>

<?php Navigation_Class::g()->display_search_result( $param['term'], $param['categories_id_selected'], $param['follower_id_selected'] );
