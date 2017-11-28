<?php
/**
 * Affichage des points en mode 'grille'.
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

<?php
if ( ! empty( $datas ) ) :
	foreach ( $datas as $date => $data ) :
		// if ( $date !== $last_date ) :
		if ( ! empty( $data ) && is_array( $data ) ) :
			?>
			<div class="day">
				<span class="label"><?php echo esc_html( ucfirst( mysql2date( 'l', $date ) ) . ' ' . mysql2date( 'd/m/Y', $date ) ); ?></span>
			</div>
			<?php
		// endif;
			foreach ( $data as $time => $elements ) :
				if ( ! empty( $elements ) ) :
					foreach ( $elements as $element ) : ?>
						<div class="activity <?php echo esc_attr( $element->view ); ?>">
							<div class="information">
								<?php echo do_shortcode( '[task_avatar ids="' . $element->displayed_author_id . '" size="30"]' ); ?>
								<span class="type"></span>
								<span class="time-posted"><?php echo esc_html( substr( $time, 0, -3 ) ); ?></span>
							</div>
							<div class="content"> <?php
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
