<?php if ( !defined( 'ABSPATH' ) ) exit; ?>

<?php if ( !empty( $user->id ) ): ?>
	<li title="<?php echo $user->email; ?>" style="background-color: #<?php echo $user->option['user_info']['avatar_color']; ?>; width: <?php echo !empty( $size ) ? $size : 50; ?>px; height: <?php echo !empty( $size ) ? $size : 50; ?>px;" class='user <?php echo !empty( $active ) ? $active : ''; ?>  wpeo-user-<?php echo $user->id; ?>' data-id="<?php echo $user->id; ?>" <?php echo !empty( $nonce ) ? "data-nonce='" . wp_create_nonce( $nonce ) . "'" : ''; ?>>
		<?php echo get_avatar( $user->id, 50, 'blank' ); ?>
		<div class="wpeo-avatar-initial"><span><?php echo strtoupper( $user->option['user_info']['initial'] ); ?></span></div>
	</li>
	<?php if (!empty( $display_name ) ): ?><li class="name"><span><?php echo $user->displayname; ?></span></li><?php endif; ?>
<?php else: ?>
	<li>
		<?php echo get_avatar( 0, 50 ); ?>
	</li>
<?php endif; ?>
