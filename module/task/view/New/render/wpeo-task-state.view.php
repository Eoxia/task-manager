
<?php

if ( $data['value'] == 'archive' ) :
	?>
	<i class="fas fa-archive wpeo-tooltip-event" aria-label="<?php esc_attr_e( 'Archived', 'task-manager' ); ?>"></i>
	<?php
else :
	?>
	<?php
endif;
