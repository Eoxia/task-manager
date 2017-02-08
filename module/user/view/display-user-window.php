<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<ul class="wpeo-main-user">
	<?php 
	$array_user_in_id = array();
	
	if ( !empty( $this->list_user ) ) :
		foreach ( $this->list_user as $user ) :
			if ( in_array( $user->id, !empty( $object->option['user_info']['affected_id'] ) ? $object->option['user_info']['affected_id'] : array())):
				?>
				<li data-id="<?php echo $user->id; ?>" title="<?php echo $user->id; ?>" class="wpeo-display-user wpeo-user-<?php echo $user->id; ?>">
					<div>
						<span class="avatar">
							<?php echo get_avatar( $user->id, 50, 'blank' ); ?>
							<span class="wpeo-avatar-initial"><?php echo strtoupper( $user->option['user_info']['initial'] ); ?></span>
						</span>
						<?php echo '<span>' . $user->displayname . '</span>'; ?>
					</div>
				</li>
				<?php
			endif;	
		endforeach;
		
		/** I don't need you anymore */
		unset( $user );
	endif;
	?>
</ul>