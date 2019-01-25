<?php
/**
 * La liste des points dans le <select>.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.6.0
 * @version 1.6.0
 * @copyright 2015-2017 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<?php
if ( ! empty( $points ) ) :
	foreach ( $points as $point ) :
		$displayed_point_content = $point->data['content'];
		if ( strlen( $displayed_point_content ) > 50 ) :
			$displayed_point_content  = substr( $displayed_point_content, 0, 50 );
			$displayed_point_content .= '...';
		endif;

		?>
		<option title="<?php echo esc_attr( $point->data['content'] ); ?>" value="<?php echo esc_attr( $point->data['id'] ); ?>"><?php echo esc_html( '#' . $point->data['id'] . ' ' . $displayed_point_content ); ?></option>
		<?php
	endforeach;
endif;
