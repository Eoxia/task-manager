<?php if ( !defined( 'ABSPATH' ) ) exit; ?>

<li class="user-search">
	<select data-placeholder="<?php _e( 'Search by User...', 'wpeouser-i18n' ); ?>" style="width: 350px;" multiple tabindex="3" class="wpeo-user-filter">
		<?php if ( !empty( $this->list_user ) ) : ?>
			<?php foreach ( $this->list_user as $user ) : ?>
				<!-- On n'affiche pas l'utilisateur courant -->
				<?php if ( $user->id != get_current_user_id() ): ?>
					<option data-id="<?php echo $user->id; ?>" value="<?php echo $user->id; ?>"><?php echo $user->displayname; ?></option>
				<?php endif; ?>
			<?php endforeach; ?>
		<?php endif; ?>
	</select>
</li>
