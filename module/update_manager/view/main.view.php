<?php
/**
 * La vue principale pour les mises à jour.
 *
 * @author Jimmy Latour <jimmy@eoxia.com>
 * @since 1.6.0
 * @version 1.6.0
 * @copyright 2015-2017 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<h1>
	<?php esc_html_e( 'Update Manager', 'eoxia' ); ?>
</h1>

<?php if ( ! empty( $waiting_updates ) ) : ?>
	<?php foreach ( $waiting_updates as $version => $data ) : ?>
		<input type="hidden" name="version_available[]" value="<?php echo esc_attr( $version ); ?>" />

		<?php foreach ( $data as $index => $def ) : ?>
			<input type="hidden" name="version[<?php echo esc_attr( $version ); ?>][action][]" value="<?php echo esc_attr( $def['action'] ); ?>" />
			<input type="hidden" name="version[<?php echo esc_attr( $version ); ?>][description][]" value="<?php echo esc_attr( $def['description'] ); ?>" />
		<?php endforeach; ?>
	<?php endforeach; ?>
<?php else : ?>
	<?php esc_html_e( 'Rien à faire ici', 'digirisk' ); ?>
	<strong><a href="<?php echo esc_attr( admin_url( '?page=wpeomtm-dashboard' ) ); ?>"><?php echo esc_html_e( 'retour à l\'application.', 'task-manager' ); ?></a></strong>
<?php endif; ?>

<ul class="log"></ul>
