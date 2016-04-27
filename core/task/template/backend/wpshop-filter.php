<?php if ( !defined( 'ABSPATH' ) ) exit; ?>

<select data-placeholder="<?php _e( 'Search by Customer...', 'task-manager' ); ?>" style="width: 350px;" multiple tabindex="3" class="wpshop-customer-filter">
	<?php if ( !empty( $list_customer ) ) : ?>
		<?php foreach ( $list_customer as $customer ) : ?>
			<option data-nonce="<?php echo wp_create_nonce( 'wpeo_nonce_customer_task_' . $customer->ID); ?>" value="<?php echo $customer->ID; ?>"><?php echo (!empty( $customer->display_name ) ? $customer->display_name : '') . ' ' . (!empty( $customer->user_email ) ? $customer->user_email : ''); ?></option>
		<?php endforeach; ?>
	<?php endif; ?>
</select>