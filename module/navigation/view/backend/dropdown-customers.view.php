<div class="form-element search-customers tm-search">
	<label class="form-field-container">
		<span class="form-field-icon-prev"><i class="fas fa-tag"></i></span>
			<div class="wpeo-dropdown dropdown-right">
				<input type="hidden" name="post_parent" value="" />
				<input type="text" autocomplete="nope" class="form-field tm-filter-customer" value="" style="height: 100%;" placeholder="<?php echo esc_html_e( 'Customer', 'task-manager' ); ?>"/>
				<ul class="dropdown-content dropdown-customers">
					<div class="dropdown-item me item-info">
						<span class="dropdown-result-title">3 characters min</span>
					</div>

					<div class="dropdown-item item-nothing me wpeo-util-hidden">
						<span class="dropdown-result-title">Nothing found</span>
					</div>

					<?php
					if ( ! empty( $customers ) ) :
						foreach ( $customers as $customer ) :
							?>
							<div class="dropdown-item wpeo-util-hidden" data-content="<?php echo $customer->content; ?>" data-id="<?php echo esc_attr( $customer->ID ); ?>">
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
				</ul>
			</div>
	</label>
</div>
