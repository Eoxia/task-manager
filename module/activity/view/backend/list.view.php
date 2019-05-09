<?php
/**
 * Affichage des points en mode 'grille'.
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
}
if ( ! empty( $datas ) ) :
	foreach ( $datas as $date => $data ) :
		if ( ! empty( $data ) && is_array( $data ) ) :
			if ( $date !== $last_date || 5 === $offset ) :
				?>
				<div class="day">
					<span class="label"><?php echo esc_html( ucfirst( mysql2date( 'l', $date ) ) . ' ' . mysql2date( 'd/m/Y', $date ) ); ?></span>
				</div>
				<?php
				$last_date = $date;
			endif;
			foreach ( $data as $time => $elements ) :
				if ( ! empty( $elements ) ) :
					foreach ( $elements as $element ) :
						?>
						<div class="activity <?php echo esc_attr( $element->data['view'] ); ?>">
							<div class="information">
								<?php echo do_shortcode( '[task_avatar ids="' . $element->data['displayed_author_id'] . '" size="30"]' ); ?>
								<span class="type <?php echo $element->data['view']; ?>">
									<?php
									switch ( $element->data['view'] ) {
										case 'created-comment':
											echo '<i class="fas fa-comments"></i>';
											break;
										case 'created-point':
											echo '<i class="fas fa-list-ul"></i>';
											break;
										case 'completed-point':
											echo '<i class="fas fa-check"></i>';
											break;
									}
									?>
								</span>
								<span class="time-posted"><?php echo esc_html( substr( $time, 0, -3 ) ); ?></span>
							</div>
							<div class="content"> 
							<?php
								\eoxia\View_Util::exec(
									'task-manager',
									'activity',
									'backend/' . $element->data['view'],
									array(
										'element' => $element,
									)
								);
							?>
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
