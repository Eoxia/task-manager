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

<div class="tm-advanced-search form wpeo-form form-light" action="<?php echo esc_attr( admin_url( 'admin-ajax.php' ) ); ?>" method="POST" >
	<div class="form-element header-searchbar">
		<label class="general-search form-field-container">
			<span class="form-field-icon-prev"><i class="fas fa-search"></i></span>
			<input type="text" class="form-field" name="term"  value="<?php echo esc_attr( $param['term'] ); ?>" placeholder="<?php echo esc_html_e( 'Keyword', 'task-manager' ); ?>"/>
		</label>
	</div>

	<div class="form-element">
		<label class="form-field-container">
			<span class="form-field-icon-prev"><i class="fas fa-thumbtack"></i></span>
			<input type="text" class="form-field" name="task_id" placeholder="<?php echo esc_html_e( 'ID/Project Name', 'task-manager' ); ?>"/>
		</label>
	</div>

	<div class="form-element">
		<label class="form-field-container">
			<span class="form-field-icon-prev"><i class="fas fa-check-square"></i></span>
			<input type="text" class="form-field" name="point_id" placeholder="<?php echo esc_html_e( 'ID/Task Name', 'task-manager' ); ?>"/>
		</label>
	</div>

	<div class="form-element">
		<label class="form-field-container">
			<span class="form-field-icon-prev"><i class="fas fa-user"></i></span>
			<input type="text" class="form-field" name="point_id" placeholder="<?php echo esc_html_e( 'Users', 'task-manager' ); ?>"/>
		</label>
	</div>

	<div class="form-element">
		<label class="form-field-container">
			<span class="form-field-icon-prev"><i class="fas fa-link"></i></span>
			<input type="text" class="form-field" name="point_id" placeholder="<?php echo esc_html_e( 'Affliated', 'task-manager' ); ?>"/>
		</label>
	</div>

	<div class="form-element">
		<label class="form-field-container">
			<span class="form-field-icon-prev"><i class="fas fa-tag"></i></span>
			<input type="text" class="form-field" name="point_id" placeholder="<?php echo esc_html_e( 'Categories', 'task-manager' ); ?>"/>
		</label>
	</div>
	<div class="search-action">
		<a class="action-input search-button wpeo-button button-main button-square-40"
		   data-loader="form"
		   data-namespace="taskManager"
		   data-module="navigation"
		   data-before-method="checkDataBeforeSearch"
		   data-action="search"
		   data-parent="form">

			<i class="fas fa-filter"></i></a>
	</div>
</div>
