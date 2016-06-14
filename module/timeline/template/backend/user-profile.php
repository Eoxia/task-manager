<?php if ( !defined( 'ABSPATH' ) ) exit; ?>

<h3><?php _e( 'Task Management', 'wpeotimeline-i18n' ); ?></h3>

<table class="form-table">
	<tr>
		<th><label for="working-time"><?php _e( 'Working time', 'wpeotimeline-i18n' ); ?></label></th>

		<td>
			<input type="text" name="working_time" id="working-time" placeholder="02:30" value="<?php echo esc_attr( taskmanager\util\wpeo_util::convert_to_hours_minut( get_the_author_meta( 'working_time', $user->ID ) ) ); ?>" class="regular-text" /><br />
			<span class="description"><?php _e( 'Please enter your time per week in hour.', 'wpeotimeline-i18n' ); ?></span>
		</td>
	</tr>
</table>
