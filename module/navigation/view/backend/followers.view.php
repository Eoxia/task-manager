<?php
/**
 * Vue pour afficher les folllowers dans la barre de recherche.
 *
 * @package Task Manager
 * @subpackage Module/Tag
 *
 * @since 1.0.0.0
 * @version 1.3.6.0
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {	exit; } ?>

<ul class="wpeo-follower-search">
	<li>
		<select name="follower_id_selected">
			<?php if ( ! empty( $followers ) ) :
				foreach ( $followers as $follower ) :
					?><option value="<?php echo esc_attr( $follower->id ); ?>"><?php echo esc_html( $follower->displayname ); ?></option><?php
				endforeach;
			endif; ?>
		</select>
	</li>
</ul>
