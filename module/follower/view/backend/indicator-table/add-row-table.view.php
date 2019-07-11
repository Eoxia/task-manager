<?php
/**
 * Options de la ligne d'ajout dans le tableau des indicators dans le profil utilisateur.
 *
 * @since 1.10.0
 * @version 1.10.0
 *
 * @author Corentin Eoxia
 *
 * @package TaskManager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div class="table-row tm-planning-add-row" style="background-color : paleturquoise">
  <div class="table-cell tm-planning-custom-name" data-class="tm-planning-custom-name">
    <input type="text" name="name" placeholder="<?php esc_html_e( 'Custom Name', 'task-manager' ); ?>"/>
  </div>
  <div class="table-cell tm-planning-dropdown-day" data-class="tm-planning-dropdown-day">
		<input type="hidden" name="day" value="monday"/>
		<div class="form-element">
			<label class="form-field-container">
				<div class="wpeo-dropdown">
					<span class="dropdown-toggle form-field">
						<i class="fas fa-caret-down"></i>
						<span class="tm-planning-display-day">
							<?php esc_html_e( 'Monday', 'task-manager' ); ?>
						</span>
						<i class="fas fa-caret-down"></i>
					</span>
					<ul class="dropdown-content" data-direction="right" name="day-name">
						<li class="dropdown-item" data-select="monday"><?php esc_html_e( 'Monday', 'task-manager' ); ?></li>
						<li class="dropdown-item" data-select="tuesday"><?php esc_html_e( 'Tuesday', 'task-manager' ); ?></li>
						<li class="dropdown-item" data-select="wednesday"><?php esc_html_e( 'Wednesday', 'task-manager' ); ?></li>
						<li class="dropdown-item" data-select="thursday"><?php esc_html_e( 'Thursday', 'task-manager' ); ?></li>
						<li class="dropdown-item" data-select="friday"><?php esc_html_e( 'Friday', 'task-manager' ); ?></li>
						<li class="dropdown-item" data-select="saturday"><?php esc_html_e( 'Saturday', 'task-manager' ); ?></li>
						<li class="dropdown-item" data-select="sunday"><?php esc_html_e( 'Sunday', 'task-manager' ); ?></li>
					</ul>
				</div>
			</label>
		</div>
	</div>
	<div class="table-cell tm-planning-period" data-class="tm-planning-period">
		<input type="hidden" name="period" value="morning"/>
		<div class="form-element">
			<label class="form-field-container">
				<div class="wpeo-dropdown">
					<span class="dropdown-toggle form-field">
						<i class="fas fa-caret-down"></i>
						<span class="tm-planning-display-day">
							<?php esc_html_e( 'Morning (AM)', 'task-manager' ); ?>
						</span>
						<i class="fas fa-caret-down"></i>
					</span>
					<ul class="dropdown-content" name="day-name">
						<li class="dropdown-item" data-select="morning"><?php esc_html_e( 'Morning (AM)', 'task-manager' ); ?></li>
						<li class="dropdown-item" data-select="afternoon"><?php esc_html_e( 'Afternoon (PM)', 'task-manager' ); ?></li>
					</ul>
				</div>
			</label>
		</div>
	</div>
  <div class="table-cell tm-planning-work-from" data-class="tm-planning-work-from">
		<input class="wpeo-tooltip-event" type="time" name="work_from" aria-label="<?php esc_html_e( 'mm:HH', 'task-manager' ); ?>" value="09:00" />
	</div>
  <div class="table-cell tm-planning-work-to" data-class="tm-planning-work-to">
		<input class="wpeo-tooltip-event" type="time" name="work_to" aria-label="<?php esc_html_e( 'mm:HH', 'task-manager' ); ?>" value="12:00" />
	</div>
  <div class="table-cell tm-planning-day-start" data-class="tm-planning-day-start">
	    <div class="form-element group-date">
	      <label class="form-field-container wpeo-tooltip-event" aria-label="<?php esc_html_e( 'DD/MM/YYYY', 'task-manager' ); ?>">
	        <input type="hidden" class="mysql-date" name="day_start" value="<?php echo date( 'Y-m-d' ); ?>">
	        <input class="date form-field" type="text" value="<?php echo date( 'd/m/Y' ); ?>">
	      </label>
	    </div>
	</div>

  <div class="table-cell table-end tm-planning-action"
		data-parent="tm-planning-add-row"
		data-action="add_element_to_planning_user"
		data-class="tm-planning-action-add"
		data-wpnonce="<?php echo esc_attr( wp_create_nonce( 'add_element_to_planning_user' ) ); ?>"
		style="cursor : pointer">
		<i class="table-icon fas fa-plus fa-2x" style="color : blue"></i>
	</div>
</div>
