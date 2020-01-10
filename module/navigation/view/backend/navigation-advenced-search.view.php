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
	<div class="tm-advanced-search wpeo-gridlayout grid-6 grid-padding-1">
		<div class="form-element header-searchbar">
			<i class="fas fa-search"></i>
			<label class="general-search form-field-container">
				<input type="text" class="form-field" name="term"  value="<?php echo esc_attr( $param['term'] ); ?>" placeholder="<?php echo esc_html_e( 'Keyword', 'task-manager' ); ?>"/>
			</label>
		</div>

		<div class="form-element">
			<i class="fas fa-thumbtack"></i>
			<label class="form-field-container">
				<input type="text" class="form-field" name="task_id" placeholder="<?php echo esc_html_e( 'ID/Project Name', 'task-manager' ); ?>"/>
			</label>
		</div>

		<div class="form-element">
			<i class="fas fa-check-square"></i>
			<label class="form-field-container">
				<input type="text" class="form-field" name="point_id" placeholder="<?php echo esc_html_e( 'ID/Task Name', 'task-manager' ); ?>"/>
			</label>
		</div>

		<div class="form-element">
			<i class="fas fa-user"></i>
			<label class="form-field-container">
				<input type="text" class="form-field" name="point_id" placeholder="<?php echo esc_html_e( 'Users', 'task-manager' ); ?>"/>
			</label>
		</div>

		<div class="form-element">
			<i class="fas fa-link"></i>
			<label class="form-field-container">
				<input type="text" class="form-field" name="point_id" placeholder="<?php echo esc_html_e( 'Affliated', 'task-manager' ); ?>"/>
			</label>
		</div>

		<div class="form-element">
			<i class="fas fa-tag"></i>
			<label class="form-field-container">
				<input type="text" class="form-field" name="point_id" placeholder="<?php echo esc_html_e( 'Categories', 'task-manager' ); ?>"/>
			</label>
		</div>
	</div>
		<a class="action-input search-button wpeo-button button-main"
			data-loader="form"
			data-namespace="taskManager"
			data-module="navigation"
			data-before-method="checkDataBeforeSearch"
			data-action="search"
			data-parent="form"><i class="fas fa-filter"></i></a>
</div>
