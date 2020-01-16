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
			foreach ( $headers as $order => $header ) :
				?>
				<div data-order="<?php echo esc_attr( $order ); ?>" data-key="<?php echo $header['key']; ?>" class="table-cell
					<?php echo esc_attr( $header['classes'] ); ?>
					<?php echo Follower_Class::g()->user_columns_def[ $header['key'] ]['displayed'] ? '' : 'wpeo-util-hidden'; ?>">
					<span>
						<div class="wpeo-util-hidden">
							<input type="checkbox" checked name="columns[<?php echo $header['key']; ?>][displayed]" />
							<input type="text" value="<?php echo $order; ?>" name="columns[<?php echo $header['key']; ?>][order]" />
						</div>
						<i class="<?php echo $header['icon']; ?>"></i>
						<?php echo esc_html( $header['title'] ); ?>
					</span>
				</div>
				<?php
			endforeach;
		endif;
		?>
		<div data-parent="table-header" class="action-input wpeo-button button-blue button-square-40 button-rounded"
			data-action="tm_edit_columns">
			<span>
				<i class="fas fa-pencil-alt"></i>
			</span>
		</div>
	</div>

	<?php Task_Class::g()->display_bodies( $contents ); ?>
</div>
