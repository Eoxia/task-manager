<?php
/**
 * Affichages des dernierès activités pour le mail de support.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.5.0
 * @version 1.6.0
 * @copyright 2015-2018 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div style="width: 100%;text-align:center;">
	<a style="text-decoration: none; background: #389af6;border: 0;-webkit-box-shadow: none;box-shadow: none;display: inline-block;margin: .4em auto;color: #fff;padding: 1em;" href="<?php echo esc_attr( $permalink . '?account_dashboard_part=support' ); ?>"><?php esc_html_e( 'Access my full support', 'task-manager' ); ?></a>
</div>

<div>
	<h3 style="color: rgba(0,0,0,0.6);"><?php esc_html_e( 'Last activities on your support', 'task-manager' ); ?></h3>

	<?php
	if ( ! empty( $datas ) ) :
		$last_date = null;
		foreach ( $datas as $activity ) :
			?>
			<div style="display: block; margin: 2em 0; border-bottom: 1px solid rgba(0,0,0,0.1);">
				<p style="font-weight: 700;">
					<span>
						<?php echo esc_html( ucfirst( mysql2date( 'l', $activity->com_date ) ) . ' ' . mysql2date( 'd/m/Y', $activity->com_date ) ); ?>
						&nbsp;à <?php echo esc_html( mysql2date( 'H\hi', $activity->com_date, true ) ); ?> sur le point
						<?php echo '#' . $activity->point_id . ' ' . $activity->point_title; ?>
					</span>
				</p>
				<div>
					<?php echo $activity->com_title; ?>
				</div>
			</div>
			<?php
			$last_date = mysql2date( 'd/m/Y', $activity->com_date );
		endforeach;
	else :
		echo esc_html_e( 'No activity for now', 'task-manager' );
	endif;
	?>
</div>
