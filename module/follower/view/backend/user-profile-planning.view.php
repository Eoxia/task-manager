<?php
/**
 * Options dans le profil utilisateur.
 *
 * @since 1.8.0
 * @version 1.8.0
 *
 * @author Corentin Eoxia
 *
 * @package TaskManager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<table class="form-table planninguser">
	<tbody>
		<tr>
			<th><label for="_tm_define_planning"><?php esc_html_e( 'Planning TIME (minute)', 'task-manager' ); ?></label></th>
			<td>
				<table class="wpeo-table">
					<thead>
						<tr>
							<th data-title="Update"><?php esc_html_e( 'Update', 'task-manager' ); ?></th>
							<th data-title="Monday"><?php esc_html_e( 'Monday', 'task-manager' ); ?></th>
							<th data-title="Tuesday"><?php esc_html_e( 'Tuesday', 'task-manager' ); ?></th>
							<th data-title="Wednesday"><?php esc_html_e( 'Wednesday', 'task-manager' ); ?></th>
							<th data-title="Thursday"><?php esc_html_e( 'Thursday', 'task-manager' ); ?></th>
							<th data-title="Friday"><?php esc_html_e( 'Friday', 'task-manager' ); ?></th>
							<th data-title="Saturday"><?php esc_html_e( 'Saturday', 'task-manager' ); ?></th>
							<th data-title="Sunday"><?php esc_html_e( 'Sunday', 'task-manager' ); ?></th>
							<th data-title="Delete"></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<?php if ( null != $time ) : ?>
								<th data-title="Update">
									<input type='date' name='_tm_planning_date' value='<?php echo $time_en; ?>' min="2010-01-01" max="<?php echo $time_en; ?>">
								</th>
							<?php else : ?>
								<th data-title="Update">
									<input type='date' name='_tm_planning_date'>
								</th>
							<?php endif; ?>

							<td data-title="Monday">
								<label class="form-field-container">
									<?php if ( null != $data['Monday'] ) : ?>
										<input type="number" name="_tm_planning_monday" class="form-field" value="<?php echo $data['Monday']; ?>"/>
									<?php else : ?>
										<input type="number" name="_tm_planning_monday" class="form-field" placeholder="0"/>
									<?php endif; ?>
								</label>
							</td>

							<td data-title="Tuesday">
								<label class="form-field-container">
									<?php if ( null != $data['Tuesday'] ) : ?>
										<input type="number" name="_tm_planning_tuesday" class="form-field" value="<?php echo $data['Tuesday']; ?>"/>
									<?php else : ?>
										<input type="number" name="_tm_planning_tuesday" class="form-field" placeholder="0"/>
									<?php endif; ?>
								</label>
							</td>

							<td data-title="Wednesday">
								<label class="form-field-container">
									<?php if ( null != $data['Wednesday'] ) : ?>
										<input type="number" name="_tm_planning_wednesday" class="form-field" value="<?php echo $data['Wednesday']; ?>"/>
									<?php else : ?>
										<input type="number" name="_tm_planning_wednesday" class="form-field" placeholder="0"/>
									<?php endif; ?>
								</label>
							</td>

							<td data-title="Thursday">
								<label class="form-field-container">
									<?php if ( null != $data['Thursday'] ) : ?>
										<input type="number" name="_tm_planning_thursday" class="form-field" value="<?php echo $data['Thursday']; ?>"/>
									<?php else : ?>
										<input type="number" name="_tm_planning_thursday" class="form-field" placeholder="0"/>
									<?php endif; ?>
								</label>
							</td>

							<td data-title="Friday">
								<label class="form-field-container">
									<?php if ( null != $data['Friday'] ) : ?>
										<input type="number" name="_tm_planning_friday" class="form-field" value="<?php echo $data['Friday']; ?>"/>
									<?php else : ?>
										<input type="number" name="_tm_planning_friday" class="form-field" placeholder="0"/>
									<?php endif; ?>
								</label>
							</td>

							<td data-title="Saturday">
								<label class="form-field-container">
									<?php if ( null != $data['Saturday'] ) : ?>
										<input type="number" name="_tm_planning_saturday" class="form-field" value="<?php echo $data['Saturday']; ?>"/>
									<?php else : ?>
										<input type="number" name="_tm_planning_saturday" class="form-field" placeholder="0"/>
									<?php endif; ?>
								</label>
							</td>

							<td data-title="Sunday">
								<label class="form-field-container">
									<?php if ( null != $data['Sunday'] ) : ?>
										<input type="number" name="_tm_planning_sunday" class="form-field" value="<?php echo $data['Sunday']; ?>"/>
									<?php else : ?>
										<input type="number" name="_tm_planning_sunday" class="form-field" placeholder="0"/>
									<?php endif; ?>
								</label>
							</td>
							<td></td>

						</tr>
						<?php if ( ! empty( $data_planning ) ) : ?>
							<?php foreach ( $data_planning as $day => $value ) : ?>
								<tr>
									<td>
										<?php if ( ! empty( $value['lastdate'] ) && '' != $value['lastdate'] ) : ?>
											<?php esc_html_e( 'To', 'task-manager' ); ?> <?php echo $value['lastdate']; ?><br>
											<?php esc_html_e( 'From', 'task-manager' ); ?> <?php echo $value['date']; ?>
										<?php else : ?>
											<?php esc_html_e( 'To now', 'task-manager' ); ?><br>
											<?php esc_html_e( 'From', 'task-manager' ); ?> <?php echo $value['date']; ?>
										<?php endif ?>
									</td>

									<td>
										<?php echo $value['minutary_duration']['Monday']; ?>
									</td>

									<td>
										<?php echo $value['minutary_duration']['Tuesday']; ?>
									</td>

									<td>
										<?php echo $value['minutary_duration']['Wednesday']; ?>
									</td>

									<td>
										<?php echo $value['minutary_duration']['Thursday']; ?>
									</td>

									<td>
										<?php echo $value['minutary_duration']['Friday']; ?>
									</td>

									<td>
										<?php echo $value['minutary_duration']['Saturday']; ?>
									</td>

									<td>
										<?php echo $value['minutary_duration']['Sunday']; ?>
									</td>
									<td class="action-input wpeo-button" style='cursor : pointer'
									data-posarray='<?php echo $day + 1; ?>'
									data-id='<?php echo $id; ?>'
									data-nonce="<?php echo esc_attr( wp_create_nonce( 'deleteplan' ) ); ?>"
									data-action="deleteplan">
										<i class="fas fa-trash-alt"></i>
									</td>
								</tr>
							<?php endforeach; ?>
						<?php endif; ?>
					</tbody>
				</table>

				<div class="parent-container">

	<!-- Le bouton déclenchant louverture de la popup -->
	<a class="wpeo-button button-main wpeo-modal-event"
		data-parent="parent-container"
		data-target="wpeo-modal"><i class="button-icon fal fa-hand-pointer"></i> <span><?php esc_html_e( 'Open archive', 'task-manager' ); ?></span></a>

	<!-- Structure -->
	<div class="wpeo-modal">
		<div class="modal-container">

			<!-- Entête -->
			<div class="modal-header">
				<h2><?php esc_html_e( 'List of archives', 'task-manager' ); ?></h2>
				<div class="modal-close"><i class="fal fa-times"></i></div>
			</div>

			<!-- Corps -->


			<div class="modal-content">
				<table class="wpeo-table">
					<thead>
						<tr>
							<th data-title="date_delete"><?php esc_html_e( 'Day delete', 'task-manager' ); ?></th>
							<th data-title="date"><?php esc_html_e( 'Date', 'task-manager' ); ?></th>
							<th data-title="Monday"><?php esc_html_e( 'Monday', 'task-manager' ); ?></th>
							<th data-title="Tuesday"><?php esc_html_e( 'Tuesday', 'task-manager' ); ?></th>
							<th data-title="Wednesday"><?php esc_html_e( 'Wednesday', 'task-manager' ); ?></th>
							<th data-title="Thursday"><?php esc_html_e( 'Thursday', 'task-manager' ); ?></th>
							<th data-title="Friday"><?php esc_html_e( 'Friday', 'task-manager' ); ?></th>
							<th data-title="Saturday"><?php esc_html_e( 'Saturday', 'task-manager' ); ?></th>
							<th data-title="Sunday"><?php esc_html_e( 'Sunday', 'task-manager' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php if ( '' != $list_archive ) : ?>
								<?php foreach ( $list_archive as $key => $value ) : ?>


						<tr>
							<td data-title="date_delete">
									<?php echo $value['day_delete']; ?>
							</td>

							<td data-title="date">
									<?php echo $value['date']; ?>
							</td>

							<td data-title="Monday">
									<?php echo $value['minutary_duration']['Monday']; ?>
							</td>

							<td data-title="Tuesday">
									<?php echo $value['minutary_duration']['Tuesday']; ?>
							</td>

							<td data-title="Wednesday">
									<?php echo $value['minutary_duration']['Wednesday']; ?>
							</td>

							<td data-title="Thursday">
									<?php echo $value['minutary_duration']['Thursday']; ?>
							</td>

							<td data-title="Friday">
									<?php echo $value['minutary_duration']['Friday']; ?>
							</td>

							<td data-title="Saturday">
									<?php echo $value['minutary_duration']['Saturday']; ?>
							</td>

							<td data-title="Sunday">
									<?php echo $value['minutary_duration']['Sunday']; ?>
							</td>

						</tr>

									<?php
							endforeach;
						endif;
						?>
					</tbody>
				</table>
		</div>



			<!-- Footer -->
			<div class="modal-footer">
				<a class="wpeo-button button-grey button-uppercase modal-close"><span>Fermer</span></a>
			</div>
		</div>
	</div>
</div>
			</td>
		</tr>
	</tbody>
</table>
