<?php
/**
 * La vue principale de la page des clients WPShop.
 *
 * @author Jimmy Latour <jimmy.eoxia@gmail.com>
 * @since 1.0.0
 * @version 1.5.0
 * @copyright 2015-2017 Eoxia
 * @package core
 * @subpackage view
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// List tags for adding quick links button into post types.
$tag_list = \eoxia\Config_Util::$init['task-manager-wpshop']->quick_task_tags;
?>
<?php if ( ! empty( $tag_list ) ) : ?>
	<?php foreach ( $tag_list as $slug ) : ?>
	<?php
	$tag_title = '';
	if ( ! empty( $slug ) ) :
		$tag_def = get_term_by( 'slug', $slug, Tag_Class::g()->get_taxonomy() );

		if ( ! $tag_def ) :
			$tag_title = ' "' . $slug . '"';
		elseif ( ! is_wp_error( $tag_def ) ) :
			$tag_title = ' "' . $tag_def->name . '"';
		endif;
	endif;
	?>
	<a href="#"
		class="action-attribute page-title-action"
		data-action="create_task"
		data-parent-id="<?php echo esc_attr( $parent_id ); ?>"
<?php if ( ! empty( $slug ) ) : ?>
		data-tag="<?php echo esc_attr( $slug ); ?>"
<?php endif; ?>
		data-nonce="<?php echo esc_attr( wp_create_nonce( 'create_task' ) ); ?>"><?php echo esc_html( sprintf( __( 'New task%1$s', 'task-manager' ), $tag_title ) ); ?></a>

	<?php endforeach; ?>
<?php endif; ?>
