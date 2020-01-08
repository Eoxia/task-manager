<?php
/**
 * La vue principale qui s'occupe de la Navigation des EPI + Affichage du tableau et son contene.
 *
 * @package   TheEPI
 * @author    Jimmy Latour <jimmy@evarisk.com> && Nicolas Domenech <nicolas@eoxia.com>
 * @copyright 2019 Evarisk
 * @since     0.2.0
 * @version   0.7.0
 */

namespace task_manager;

use eoxia\View_Util;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Documentation des variables utilisées dans la vue.
 *
 * @var array  $tasks    Les données des Tâches.
 */
?>
<div class="wpeo-table table-flex table-projects">
	<div class="table-row table-header">
		<?php
		if ( isset( $headers ) ) :
			foreach ( $headers as $key => $header ) :
				?>
				<div class="table-cell <?php echo esc_attr( $header['classes'] ); ?>">
					<span>
						<i class="fas fa-thumbtack"></i>
						<?php echo esc_html( $header['title'] ); ?>
					</span>
				</div>
				<?php
			endforeach;
		endif;
		?>
	</div>

	<?php Task_Class::g()->display_bodies( $contents ); ?>
</div>
