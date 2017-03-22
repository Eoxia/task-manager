<?php
/**
 * La liste des catégories en mode édition.
 *
 * @package Task Manager
 * @subpackage Module/Tag
 *
 * @since 1.0.0.0
 * @version 1.3.6.0
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {	exit; }

if ( ! empty( $tags ) ) :
	foreach ( $tags as $tag ) :
		View_Util::exec( 'tag', 'backend/tag-edit', array(
			'tag' => $tag,
			'task_id' => $task->id,
		) );
	endforeach;
endif;
