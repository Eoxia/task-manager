<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<li class="wpshop-search-customer">
	<div>
		<i class="dashicons dashicons-search"></i>
		<input data-nonce="<?php echo wp_create_nonce( 'ajax_search_customer' ); ?>" type="text" placeholder="<?php _e( 'Search WPshop customer', 'task-manager' ); ?>" class="auto-complete-user" />
		<a href="#"class="clean-wpshop-search"><i class="dashicons dashicons-no-alt"></i></a>
	</div>
</li>
