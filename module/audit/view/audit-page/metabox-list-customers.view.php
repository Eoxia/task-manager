<?php
/**
 * Vue pour la création d'un nouvel audit
 *
 * @author <dev@eoxia.com>
 * @since 1.9.0
 * @version 1.9.0
 * @copyright 2019 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<?php foreach( $data as $value ): ?>

	<option value="<?php echo esc_html( $value->ID ); ?>">
		#<?php echo esc_html( $value->ID ); ?> -
		<?php echo esc_html( $value->post_title ); ?>

	</option>

<?php endforeach; ?>
