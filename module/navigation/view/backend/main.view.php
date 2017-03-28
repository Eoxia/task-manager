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

<header class="wpeo-header-bar">
	<ul>
		<li class="action-attribute" data-action="load_all_task" data-nonce="<?php echo esc_attr( wp_create_nonce( 'load_all_task' ) ); ?>">Toutes les tâches</li>
		<li class="action-attribute" data-action="load_my_task" data-nonce="<?php echo esc_attr( wp_create_nonce( 'load_my_task' ) ); ?>">Mes tâches</li>
		<li class="action-attribute" data-action="load_archived_task" data-nonce="<?php echo esc_attr( wp_create_nonce( 'load_archived_task' ) ); ?>">Tâches archivées</li>
		<li class="wpeo-general-search">
			<label for="general-search">
				<i class="dashicons dashicons-search"></i>
				<input type="text" placeholder="<?php esc_attr_e( 'Search', 'task-manager' ); ?>" />
			</label>
			<button><?php esc_html_e( 'Search', 'task-manager' ); ?></button>

			<span class="more-search-options">+</span>
		</li>
	</ul>
</header>

<div class="wpeo-header-search active">
	<ul>
		<li class="tag-search">
			<?php View_Util::exec( 'navigation', 'backend/tag', array(
				'categories' => $categories,
			) ); ?>
		</li>
	</ul>
</div>
