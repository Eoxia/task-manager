<?php
/**
 * Affichage de l'activité d'un utilisateur pour la journée courante
 *
 * @author Jimmy Latour <jimmy.eoxia@gmail.com>
 * @since 1.5.0
 * @version 1.5.0
 * @copyright 2015-2017 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$total_time = 0;

ob_start();
?>
<table style="width: 100%;" >
	<tr>
		<td colspan="3" class="tm-choose-the-date" >
			<label>
				<?php esc_html_e( 'Start date', 'task-manager' ); ?>
				<input type="date" value="<?php echo esc_attr( $date_start ); ?>" name="tm_abu_date_start" />
			</label>
			<label>
				<?php esc_html_e( 'End date', 'task-manager' ); ?>
				<input type="date" value="<?php echo esc_attr( $date_end ); ?>" name="tm_abu_date_end" />
				<input type="hidden" value="open_popup_user_activity" name="action" />
				<input type="hidden" value="<?php echo esc_attr( wp_create_nonce( 'load_user_activity' ) ); ?>" name="_wpnonce" />
			</label>
			<button class="button-primary action-input" data-parent="tm-choose-the-date" id="tm-user-activity-load-by-date" ><?php esc_html_e( 'View activity', 'task-manager' ); ?></button>
		</td>
		<td>{{ total_time }}</td>
	</tr>
<?php if ( ! empty( $datas ) ) : ?>
	<?php foreach ( $datas as $activity ) : ?>
	<tr>
		<td style="width: 20%; vertical-align: top;" ><?php if ( ! empty( $activity->PT_ID ) ) : ?><?php echo esc_html( $activity->PT_title ); ?><?php else : ?>	- <?php endif; ?></td>
		<td style="width: 20%;vertical-align: top;" ><?php echo esc_html( $activity->T_title ); ?></td>
		<td style="width: 50%;vertical-align: top;" ><?php echo esc_html( $activity->POINT_title ); ?></td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td style="border-bottom: 1px solid #000;" >&nbsp;</td>
		<td style="border-bottom: 1px solid #000;" colspan="2"><?php echo esc_html( $activity->COM_title ); ?></td>
		<td style="border-bottom: 1px solid #000;" ><?php
			$com_details = ( ! empty( $activity->COM_DETAILS ) ? json_decode( $activity->COM_DETAILS ) : '' );
			echo esc_html( $com_details->time_info->elapsed );
			$total_time += $com_details->time_info->elapsed;
		?></td>
	</tr>
	<?php endforeach; ?>
<?php else : ?>
	<tr>
		<td colspan="4"><?php esc_html_e( 'No activity found for now', 'task-manager' ); ?></td>
	</tr>
<?php endif; ?>
</table><?php
$output = ob_get_clean();

echo wp_kses( str_replace( '{{ total_time }}', $total_time, $output ), array(
	'table'  => array(
		'style'	=> array(),
		'class'	=> array(),
	),
	'tr'     => array(
		'class'	=> array(),
	),
	'td'     => array(
		'style'   => array(),
		'class'	=> array(),
		'colspan' => array(),
	),
	'input'  => array(
		'type'  => array(),
		'value' => array(),
		'name'  => array(),
	),
	'button' => array(
		'class'	=> array(),
		'data-parent' => array(),
	),
) );