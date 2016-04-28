<?php if ( !defined( 'ABSPATH' ) ) exit; ?>

<div class="wrap wpeo-project-timeline">
	<h2><?php _e( 'Timeline', 'wpeotimeline-i18n' ); ?></h2>
	
	<header class="wpeo-header-bar">
		<ul>
			<li>
				<select>
					<?php if ( !empty( $list_user ) ): ?>
						<?php foreach ( $list_user as $user ): ?>
							<option <?php selected( $user->id, get_current_user_id(), true ); ?> value="<?php echo $user->id; ?>"><?php echo $user->email; ?></option>
						<?php endforeach; ?>
					<?php endif; ?>
				</select>
			</li>
		</ul>
	</header>

	<?php require_once( wpeo_template_01::get_template_part( WPEOMTM_TIMELINE_DIR, WPEOMTM_TIMELINE_TEMPLATES_MAIN_DIR, 'backend', 'year' ) ); ?>
</div>