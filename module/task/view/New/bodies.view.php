<?php
if ( ! empty( $contents['bodies'] ) ) :
	foreach ( $contents['bodies'] as $data_def ) :
		?>
		<div class="table-row <?php echo esc_attr( $data_def['classes'] ); ?>"
			<?php echo ! empty( $data_def['attrs'] ) ? implode( ' ', $data_def['attrs'] ) : ''; ?>>

			<?php
			foreach ( $data_def['values'] as $key => $data ) :
				?>
				<div class="table-cell <?php echo esc_attr( $data['classes'] ); ?>"
					<?php echo ! empty( $data['attrs'] ) ? implode( ' ', $data['attrs'] ) : ''; ?>>
					<?php
					\eoxia\View_Util::exec( 'task-manager', 'task', 'New/render/' . $data['type'] . '-' . $key, array(
						'data_def' => $data_def,
						'data'     => $data,
						'key'      => $key,
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
