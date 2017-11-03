<?php
/**
 * Affichages des dernierès activités pour le mail de support.
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
} ?>

<div style="width: 100%;text-align:center;">
	<a style="width: 50%;background: #389af6;border: 0;-webkit-box-shadow: none;box-shadow: none;display: block;margin: .4em auto;color: #fff;padding: .6em;"href="<?php echo esc_attr( $permalink . '?account_dashboard_part=support' ); ?>"><?php esc_html_e( 'Access my full support', 'task-manager' ); ?></a>
</div>

<div class="wpeo-project-wrap activities">
	<div class="content" style="">
		<p><?php esc_html_e( 'Last activities on your support:', 'task-manager' ); ?></p>

		<?php
		if ( ! empty( $datas ) ) :
			foreach ( $datas as $date => $data ) :
				// if ( $date !== $last_date ) :
				if ( ! empty( $data ) && is_array( $data ) ) :
					?>

					<?php
				// endif;
					foreach ( $data as $time => $elements ) :
						if ( ! empty( $elements ) ) :
							foreach ( $elements as $element ) :
								?>
								<div class="day" style="position: relative;clear: both;">
									<span class="label" style="font-size: 12px;font-weight:900;color:#000;text-transform:uppercase;display:inline-block;position:relative;z-index:50;padding-right:1em;">
										<?php echo esc_html( ucfirst( mysql2date( 'l', $date ) ) . ' ' . mysql2date( 'd/m/Y', $date ) ); ?>
										- <span class="time-posted"><?php echo esc_html( substr( $time, 0, -3 ) ); ?></span>
									</span>
								</div>
								<div class="activity <?php echo esc_attr( $element->view ); ?>" style="padding:.6em 0;display:flex;display:-ms-flexbox;display:-moz-box;display:-webkit-flex;display:-webkit-box;-webkit-box-orient: horizontal;-webkit-box-direction: normal;-webkit-flex-direction: row;-moz-box-orient: horizontal;-moz-box-direction: normal;-ms-flex-direction: row;flex-direction: row;">
									<div class="information" style="width: 100%;max-width: 100px;position: relative;">
										<span><?php echo esc_html( $element->displayed_username ); ?></span>
									</div>
									<div class="content" style="width: 100%;padding-left: 1em;margin: auto 0;"> <?php
										\eoxia\View_Util::exec( 'task-manager', 'activity', 'backend/' . $element->view, array(
											'element' => $element,
										) ); ?>
									</div>
								</div> <?php

							endforeach;
						endif;
					endforeach;
				endif;
			endforeach;
		else :
			echo esc_html_e( 'End of history', 'task-manager' );
		endif;
		?>
	</div>
</div>
