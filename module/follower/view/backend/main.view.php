<?php
/**
 * Vue pour afficher la liste des followers dans une tâche.
 *
 * @package Task Manager
 * @subpackage Module/Tag
 *
 * @since 1.0.0.0
 * @version 1.3.6.0
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {	exit; } ?>

<ul class="wpeo-tag-users action-attribute"
	data-action="load_followers"
	data-nonce="<?php echo esc_attr( wp_create_nonce( 'load_followers' ) ); ?>"
	data-id="<?php echo esc_attr( $task->id ); ?>"
	data-module="followers"
	data-before-method="before_load_followers">

	<?php
	if ( ! empty( $followers ) ) :
		foreach ( $followers as $follower ) :

		endforeach;
	else :
		?>ok<?php
	endif;
	?>
</ul>
