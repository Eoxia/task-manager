<?php
/**
 * Gestion de la popup et de la notification pour les notes de versions.
 *
 * @author Jimmy Latour <jimmy@eoxia.com>
 * @since 1.5.0
 * @version 1.6.0
 * @copyright 2015-2018 Eoxia
 * @package Eoxia
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$result = Task_Manager_Class::g()->get_patch_note(); ?>

<?php if ( true === $result['status'] && ! empty( $result['content'] ) ) : ?>
	<div class="notification patch-note active">
		<span class="thumbnail"><img src="<?php echo esc_attr( PLUGIN_TASK_MANAGER_URL . 'core/assets/images/favicon_hd.png' ); ?>" /></span>
		<span class="title">Note de mise à jour de la <a href="#">version <?php echo esc_attr( \eoxia\Config_Util::$init['task-manager']->version ); ?></a></span>
		<span class="close action-attribute"
					data-action="close_change_log"
					data-nonce="<?php echo esc_attr( wp_create_nonce( 'close_change_log' ) ); ?>"
					data-version="<?php echo esc_attr( \eoxia\Config_Util::$init['task-manager']->version ); ?>"><i class="icon fa fa-times-circle"></i></span>
	</div>

	<div class="popup patch-note">
		<div class="container">
			<div class="header">
				<h2 class="title"><?php echo esc_html( 'Note de version: ' . $result['content']->numero_de_version ); ?></h2>
				<i class="close fa fa-times"></i>
			</div>
			<div class="content">
				<?php
				if ( ! empty( $result['content']->note_de_version ) ) :
					foreach ( $result['content']->note_de_version as $element ) :
						?>
						<div class="note">
							<div class="entry-title"><?php echo esc_html( $element->numero_de_suivi ); ?></div>
							<div class="entry-content"><?php echo $element->description; ?></div>
								<?php
								if ( ! empty( $element->illustration ) && ! empty( $element->illustration->url ) ) :
									?>
									<img src="<?php echo esc_attr( $element->illustration->url ); ?>" alt="<?php echo esc_attr( $element->numero_de_suivi ); ?>" />
									<?php
								endif;
								?>
						</div>
						<?php
					endforeach;
				endif;
				?>
			</div>
		</div>
	</div>
<?php
endif;
