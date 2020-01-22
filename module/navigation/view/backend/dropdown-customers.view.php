<div class="form-element search-categories tm-search">
	<label class="form-field-container">
		<span class="form-field-icon-prev"><i class="fas fa-tag"></i></span>
			<div class="wpeo-dropdown dropdown-right">
				<input type="hidden" name="post_parent" value="" />
				<input type="text" autocomplete="nope" class="form-field tm-filter-customer" value="" style="height: 100%;" placeholder="<?php echo esc_html_e( 'Customer', 'task-manager' ); ?>"/>
				<ul class="dropdown-content dropdown-customers">
					<?php
					if ( ! empty( $customers ) ) :
						foreach ( $customers as $customer ) :
							?>
							<li class="dropdown-item" data-id="<?php echo esc_attr( $customer->ID ); ?>">
								<span class="title"><?php echo esc_html( $customer->post_title ); ?></span>
								<ul>
									<?php
									if ( ! empty( $customer->users ) ) :
										foreach ( $customer->users as $user ) :
											?>
											<li><?php echo $user->data->display_name . ' (' . $user->data->user_email . ')'; ?></li>
											<?php
										endforeach;
									endif;
									?>
								</ul>
							</li>
							<?php
						endforeach;
					endif;
					?>
				</ul>
			</div>
	</label>
</div>
