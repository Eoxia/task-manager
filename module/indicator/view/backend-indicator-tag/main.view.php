<?php
/**
 * La vue principale des indicators TAG.
 *
 * @author Eoxia <dev@eoxia.com>
 * @version 1.11.0
 * @copyright 2019 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>


<div class="wpeo-wrap tm-wrap">
	<div class="tm-dashboard-surheader">
		<div class="form wpeo-form">
			<ul class="dropdown-content select-tags-indicator">
				<?php
					\eoxia\View_Util::exec(
						'task-manager',
						'navigation',
						'backend/tags',
						array(
							'categories' => Tag_Class::g()->get( array() ),
						)
					);
					?>
			</ul>
		</div>
	</div>
</div>

<div class="tm-display-year-indicator" style="margin-top : 20px; display : none">
	<?php
		\eoxia\View_Util::exec(
			'task-manager',
			'indicator',
			'backend-indicator-tag/header',
			array(
				'year' => isset( $year ) ? $year : date( 'Y' ),
				'tagid' => 0,
			)
		);
	 ?>
</div>


<div class="tm_tag_indicator_update_body">

</div>
