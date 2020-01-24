<div class="dropdown-item me item-info">
	<span class="dropdown-result-title">Please enter three letters</span>
</div>

<div class="dropdown-item item-nothing me wpeo-util-hidden">
	<span class="dropdown-result-title">Nothing found</span>
</div>

<?php
if ( ! empty( $customers ) ) :
	foreach ( $customers as $customer ) :
		?>
		<div class="dropdown-item wpeo-util-hidden" data-title="<?php echo $customer->content_title; ?>" data-content="<?php echo $customer->content; ?>" data-id="<?php echo esc_attr( $customer->ID ); ?>">
			<span class="dropdown-result-title"><?php echo esc_html( $customer->post_title ); ?></span>
			<?php
			if ( ! empty( $customer->users ) ) :
				foreach ( $customer->users as $user ) :
					?>
					<span class="dropdown-result-subtitle"><?php echo $user->data->display_name . ' (' . $user->data->user_email . ')'; ?></span>
					<?php
				endforeach;
			endif;
			?>
		</div>
		<?php

	endforeach;
endif;
?>
