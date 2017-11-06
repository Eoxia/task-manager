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

<div>
	<h3><?php esc_html_e( 'Last activities on your support', 'task-manager' ); ?></<h3>

	<?php
	if ( ! empty( $datas ) ) :
		foreach ( $datas as $date => $data ) :
			if ( ! empty( $data ) && is_array( $data ) ) :
				foreach ( $data as $time => $elements ) :
					if ( ! empty( $elements ) ) :
						foreach ( $elements as $element ) :
							?>
							<div>
								<span><?php echo esc_html( ucfirst( mysql2date( 'l', $date ) ) . ' ' . mysql2date( 'd/m/Y', $date ) ); echo esc_html( ' ' . substr( $time, 0, -3 ) ); echo esc_html( ' ' . $element->displayed_username );
										\eoxia\View_Util::exec( 'task-manager', 'activity', 'backend/mail/action-' . $element->view, array(
											'element' => $element,
										) ); ?></span>
							</div>
							<div>
								<?php
								\eoxia\View_Util::exec( 'task-manager', 'activity', 'backend/' . $element->view, array(
									'element' => $element,
								) );
								?>
							</div>
							<?php

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
