<?php
/**
 * La vue de la page quicktime
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.9.0
 * @copyright 2015-2019 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div class="wpeo-wrap tm-wrap">

	<div class='info'>
		<span>
			<h1>
			<?= sprintf( esc_html__( 'The index %s send in URL isn\'t in range' , 'task-manager' ), $index ); ?>
			</h1>
		</span>
	</div>
</div>
