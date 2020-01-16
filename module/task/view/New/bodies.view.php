<?php

namespace task_manager;

if ( ! empty( $contents['bodies'] ) ) :
	foreach ( $contents['bodies'] as $key => $data_def ) :
		?>
		<div class="table-row <?php echo esc_attr( $data_def['classes'] ); ?>"
			<?php echo ! empty( $data_def['attrs'] ) ? implode( ' ', $data_def['attrs'] ) : ''; ?>>

			<?php
			foreach ( $data_def['values'] as $order => $data ) :
				?>
				<div data-key="<?php echo esc_attr( $data['key'] ); ?>"  class="table-cell <?php echo esc_attr( $data['classes'] ); ?>"
				     style="<?php echo Follower_Class::g()->user_columns_def[ $data['key'] ]['displayed'] ? '' : 'display: none;'; ?>"
	<?php echo ! empty( $data['attrs'] ) ? implode( ' ', $data['attrs'] ) : ''; ?>>
					<?php
					\eoxia\View_Util::exec( 'task-manager', 'task', 'New/render/' . $data['type'] . '-' . $data['key'], array(
						'data_def' => $data_def,
						'data'     => $data,
						'key'      => $data['key'],
					) );
					?>
				</div>
			<?php
			endforeach;
			?>
		</div>
	<?php

	endforeach;
endif;
