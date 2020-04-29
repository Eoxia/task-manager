<?php
/**
 * Définis le tableau principale.
 *
 * Créer la ligne du header et gère l'affichage de toutes les cellules.
 *
 * Appel les élements du body.
 *
 * @package   Task_Manager
 * @author    Jimmy Latour <jimmy@evarisk.com> && Nicolas Domenech <nicolas@eoxia.com>
 * @copyright 2020 Eoxia
 * @since     3.0.1
 */

namespace task_manager;

use eoxia\View_Util;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Documentation des variables utilisées dans la vue.
 *
 * @var array  $headers Les données du Header.
 */
?>
<div class="wpeo-table table-flex table-projects">
	<div class="table-row table-header">
		<?php
		if ( isset( $headers ) ) :
			foreach ( $headers as $order => $header ) :
				?>
				<div data-order="<?php echo esc_attr( $order ); ?>" data-key="<?php echo $header['key']; ?>" class="table-cell
					<?php echo esc_attr( $header['classes'] ); ?>"
						style="<?php echo Follower_Class::g()->user_columns_def[ $header['key'] ]['displayed'] ? '' : 'display: none;'; ?>">
					<div class="wpeo-util-hidden input-header">

						<?php
						if ( $header['can_be_hidden'] ) :
							?>
							<input type="checkbox" checked name="columns[<?php echo $header['key']; ?>][displayed]" />
							<input type="hidden" value="<?php echo $order; ?>" name="columns[<?php echo $header['key']; ?>][order]" />
							<?php
						else:
							?>
							<input type="hidden" name="columns[<?php echo $header['key']; ?>][displayed]" value="true" />
							<input type="hidden" value="<?php echo $order; ?>" name="columns[<?php echo $header['key']; ?>][order]" />
						<?php
						endif;
						?>
					</div>
					<i class="<?php echo $header['icon']; ?>"></i>
					<span class="title"><?php echo esc_html( $header['title'] ); ?></span>
				</div>
				<?php
			endforeach;
		endif;
		?>
		<div data-parent="table-header" class="table-header-edit action-input wpeo-button button-grey button-square-30 button-rounded"
			data-action="tm_edit_columns">
			<i class="button-icon fas fa-list"></i>
		</div>
	</div>

	<?php Task_Class::g()->display_bodies( $contents ); ?>

<!--	--><?php
//	if ( isset( $_GET['notification'] ) && isset( $_GET['point_id'] ) && isset( $_GET['task_id'] )  ) :
//		Point_Class::g()->display( $_GET['task_id'], false, 0, false );
//	endif;
//	?>
<!---->
<!--	--><?php
//	if ( isset( $_GET['notification'] ) && isset( $_GET['point_id'] ) && isset( $_GET['comment_id'] ) ) :
//		Task_Comment_Class::g()->display( $_GET['task_id'], $_GET['point_id'], false );
//	endif;
//	?>

</div>
