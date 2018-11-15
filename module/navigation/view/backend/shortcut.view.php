<?php
/**
 * Vue pour afficher un raccourcis
 *
 * @since 1.8.0
 *
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} 

$active = '';

if ( $shortcut['link'] == $url || $new ) :
	$active = ' active ';
endif;

?>
<li data-key="<?php echo esc_attr( $key ); ?>" class="dashboard-shortcut <?php echo esc_attr( $active ); ?>">
	<a class="wpeo-button button-size-small button-transparent" href="<?php echo admin_url( $shortcut['page'] . $shortcut['link'] ); ?>">
		<?php echo esc_html( $shortcut['label'] ); ?>
	</a>
</li>

