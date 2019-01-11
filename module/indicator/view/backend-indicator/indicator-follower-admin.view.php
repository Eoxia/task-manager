<?php
/**
 * Vue pour afficher la liste des catégories dans une tâche.
 *
 * @package Task Manager
 *
 * @since 1.0.0
 * @version 1.6.0
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>


<ul class="wpeo-tag-wrap">
 <?php

 if ( ! empty( $followers ) ) :
   foreach ( $followers as $follower ):
     ?>
    <li class="wpeo-tag add wpeo-button button-grey button-radius-3 tm_indicator_avatar clickonfollower"
		id="tm_user_indicator_<?= $follower->data['id'] ?>"
      data-user-id="<?= $follower->data['id'] ?>">
			<span>
        <?= do_shortcode( '[task_avatar ids="' . $follower->data['id'] . '" size="40"]' ); ?>
			</span>
    </li>

     <?php
   endforeach;
 endif;
 ?>
</ul>
