<?php
/**
 * Template pour l'affichage d'onglets dans le tableau de bord des tâches
 *
 * @package Task Manager
 * @subpackage Module/Tag
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! empty( $list_tag ) ) :
	foreach ( $list_tag as $tag ) :
		if ( 0 !== $tag->id ) :
			$selected = false;
			if ( in_array( $tag->id, $object->taxonomy[ Tag_Class::g()->get_taxonomy() ], true ) ) :
				$selected = true;
			endif;
	?><li data-nonce="<?php echo esc_attr( wp_create_nonce( ( true === $selected ? 'tag_unaffectation' : 'tag_affectation' ) ) ); ?>"
			data-parent-id="<?php echo esc_attr( $object->id ); ?>"
			data-id="<?php echo esc_attr( $tag->id ); ?>"
			data-action="<?php echo esc_attr( true === $selected ? 'tag_unaffectation' : 'tag_affectation' ); ?>" class="action-attribute<?php echo esc_attr( true === $selected ? ' active' : '' ); ?>" ><?php echo esc_attr( $tag->name ); ?></li><?php
		endif;
	endforeach;
endif;
