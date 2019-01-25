<?php
/**
 * Le contenu de la popup contenant les derniers commentaires des clients WPShop.
 *
 * @author Jimmy Latour <jimmy@eoxia.com>
 * @since 1.0.1
 * @version 1.3.0
 * @copyright 2017 Eoxia
 * @package Task_Manager_WPShop
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div class="daily-activity activities">
	<div class="content">
		<?php
		if ( ! empty( $datas ) ) :
			foreach ( $datas as $date => $data ) :
				if ( ! empty( $data ) && is_array( $data ) ) :
					?>
					<div class="day">
						<span class="label"><?php echo esc_html( ucfirst( mysql2date( 'l', $date ) ) . ' ' . mysql2date( 'd/m/Y', $date ) ); ?></span>
					</div>

					<?php
					if ( ! empty( $data ) ) :
						foreach ( $data as $time => $comments ) :
							if ( ! empty( $comments ) ) :
								foreach ( $comments as $comment ) :
									\eoxia\View_Util::exec(
										'task-manager',
										'indicator',
										'backend/item',
										array(
											'time'    => $time,
											'comment' => $comment,
										)
									);
								endforeach;
							endif;
						endforeach;
					endif;
				endif;
			endforeach;
			else :
				esc_html_e( 'No pending requests', 'task-manager' );
			endif;
			?>
	</div><!-- .content -->
</div>
