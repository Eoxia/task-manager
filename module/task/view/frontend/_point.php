<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<li class="point-<?php echo $point->comment_ID; ?> wpeo-task-point" data-id="<?php echo $point->comment_ID; ?>" >
	<p>
		<?php echo '<span>' . $point->comment_ID . '</span> - ' . htmlspecialchars( $point->comment_content ); ?> 
		<?php if( $task->informations->setting['time'] ) :?>
			<?php echo $point->informations->comment_time; ?>m
		<?php endif; ?>
		<?php if( $task->informations->setting['user'] ) :?>
			<?php if( !empty( $point->informations->users ) ):?>
				<?php foreach( $point->informations->users as $user_id ): ?>
					<?php $user = get_userdata( $user_id ); ?>
					<span title='<?php echo $user->display_name; ?>'><?php echo get_avatar( $user_id, 24 ); ?></span>
				<?php endforeach; ?>
			<?php endif; ?>
		<?php endif; ?>
	</p>
</li>