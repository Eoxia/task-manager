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
		<div data-parent="table-header" class="table-header-edit action-input wpeo-button button-blue button-square-30 button-rounded"
			data-action="tm_edit_columns">
			<i class="button-icon fas fa-pencil-alt"></i>
		</div>
	</div>

	<?php Task_Class::g()->display_bodies( $contents ); ?>

	<?php
	if ( isset( $_GET['notification'] ) && isset( $_GET['point_id'] ) && isset( $_GET['task_id'] )  ) :
		Point_Class::g()->display( $_GET['task_id'], false, 0, false );
	endif;
	?>

	<?php
	if ( isset( $_GET['notification'] ) && isset( $_GET['point_id'] ) && isset( $_GET['comment_id'] ) ) :
		Task_Comment_Class::g()->display( $_GET['task_id'], $_GET['point_id'], false );
	endif;
	?>
	<?php if ( get_user_meta( get_current_user_id(), '_tm_project_state', true ) == true ) :
		if ( isset( $contents ) ) :
			foreach ( $contents as $key => $project ) :
				if ( $project->data['count_uncompleted_points'] > 0 ) :
					Point_Class::g()->display( $project->data['id'], false, 0, false );
				endif;
			endforeach;
		endif;
	endif; ?>
</div>
