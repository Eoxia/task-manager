<?php if ( !defined( 'ABSPATH' ) ) exit; ?>

<div class="wpeo-bloc-user" data-id="<?php echo $element->id; ?>">
	<ul class="wpeo-main-user">
		<!-- Responsable de la tâche -->
		<li>
			<ul data-nonce='<?php echo wp_create_nonce( 'wp_nonce_render_edit_owner_user_' . $element->id ); ?>' class="wpeo-user-owner wpeo-current-user">
			<?php
			if ( !empty( $owner_id ) ):
				$user = $owner_user;
				$nonce 			= 'wpeo_nonce_edit_task_owner_user';
				require( wpeo_template_01::get_template_part( WPEO_USER_DIR, WPEO_USER_TEMPLATES_MAIN_DIR, 'backend', 'user-gravatar' ) );
			else:
				$nonce = 'wpeo_nonce_edit_task_owner_user';
				require( wpeo_template_01::get_template_part( WPEO_USER_DIR, WPEO_USER_TEMPLATES_MAIN_DIR, 'backend', 'user-gravatar' ) );
			endif;
			?>
			</ul>
		</li>

		<!-- Affectés à la tâche -->
		<li>
			<ul class="wpeo-ul-user wpeo-current-user">
				<?php
				$array_user_in_id = array();

				if ( !empty( $this->list_user ) ) :
					foreach ( $this->list_user as $user ) :
						if ( in_array( $user->id, !empty( $element->option['user_info']['affected_id'] ) ? $element->option['user_info']['affected_id'] : array( ) ) ):
							require( wpeo_template_01::get_template_part( WPEO_USER_DIR, WPEO_USER_TEMPLATES_MAIN_DIR, 'backend', 'user-gravatar' ) );
						endif;
					endforeach;

					/** I don't need you anymore */
					unset( $user );
				endif;
				?>
			</ul>
		</li>

		<li>
			<a href="#" data-nonce="<?php echo wp_create_nonce( 'wpeo_nonce_view_user_' . $element->id ); ?>" class="wpeo-user-add dashicons dashicons-plus"></a>
		</li>
	</ul>
</div>
