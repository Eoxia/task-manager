<?php
/**
 * Affiche la pagination des commentaires (Par tranche de 10)
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.10.0
 * @version 1.10.0
 * @copyright 2019 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<?php if( $count_tasks > 1 ): ?>
	<ul class="wpeo-pagination pagination-task" style="margin : 10px; cursor : pointer"
	data-page="<?php echo esc_html( $offset ) ?>"
	data-post-id="<?php echo esc_html( $post_id ) ?>">
		<!-- Bouton précédent -->
		<li class="pagination-element pagination-prev" data-pagination="<?php echo esc_html( $offset - 1 > 0 ? $offset - 1 : 1 ); ?>">
			<a>
				<i class="pagination-icon fas fa-long-arrow-alt-left fa-fw"></i>
				<span><?php echo esc_html( 'Previous' ) ?></span>
			</a>
		</li>

		<?php for( $i = 1; $i <= $count_tasks; $i ++ ): ?>
			<!-- Element simple -->
			<li class="pagination-element <?php echo esc_html( $i == $offset ? 'pagination-current' : '' ); ?>"
				data-pagination="<?php echo esc_html( $i ) ?>">
				<a><?php echo esc_html( $i ); ?></a>
			</li>
		<?php endfor; ?>

		<!-- Bouton suivant -->
		<li class="pagination-element pagination-next"
		data-pagination="<?php echo esc_html( $offset + 1 < $count_tasks ? $offset + 1 : $count_tasks ) ?>">
			<a>
				<span><?php echo esc_html( 'Next' ) ?></span>
				<i class="pagination-icon fas fa-long-arrow-alt-right fa-fw"  ></i>
			</a>
		</li>
	</ul>
<?php endif; ?>
