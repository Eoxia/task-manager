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
		if ( $date !== $last_date ) :
			?>
			<h3><?php echo esc_html( ucfirst( mysql2date( 'l', $date ) ) . ' ' . mysql2date( 'd/m/Y', $date ) ); ?></h3>
			<?php
		endif;
		if ( ! empty( $data ) ) :
			foreach ( $data as $time => $elements ) :
				if ( ! empty( $elements ) ) :
					foreach ( $elements as $element ) :
						echo do_shortcode( '[task_avatar ids="' . $element->author_id . '" size="20"]' );
						echo esc_html( $element->date['date_input']['fr_FR']['time'] );
						\eoxia\View_Util::exec( 'task-manager', 'point', 'backend/grid/' . $element->view, array(
							'element' => $element,
						) );
					endforeach;
				endif;
			endforeach;
		endif;
	endforeach;
else :
	echo esc_html_e( 'End of history', 'task-manager' );
endif;
?>
