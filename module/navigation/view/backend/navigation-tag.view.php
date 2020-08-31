<?php
/**
 * Gestion du header pour les multisites.
 *
 * @author Evarisk <dev@evarisk.com>
 * @since 0.3.0
 * @copyright 2015-2019 Evarisk
 * @package DigiRiskDashboard
 */

namespace task_manager;

defined( 'ABSPATH' ) || exit; ?>

<div class="wpeo-dropdown dropdown-right">

	<input type="hidden" name="categories_id" value="<?php echo esc_attr( $data['categories_id'] ); ?>" />

	<div class="form-field" style="height: 100%; display: flex;">
		<?php

		if ( ! empty( $data['categories_searched'] ) ):
			foreach ( $data['categories_searched'] as $category ):
				?>
				<div data-id="<?php echo esc_attr( $category['id'] ); ?>" class="wpeo-button button-grey button-radius-2" style="display: flex;">
					<span><?php echo $category['name']; ?></span>
					<i class="fas fa-times"></i>
				</div>
			<?php
			endforeach;
		endif;
		?>
		<div class="content tm-filter field-elements" contenteditable=true data-text="Categories" style="width: 100%"></div>
	</div>
	<ul class="dropdown-content dropdown-categories">
		<?php
		if ( ! empty( $categories ) ) :
			foreach ( $categories as $category ) :
				\eoxia\View_Util::exec(
					'task-manager',
					'navigation',
					'backend/tag',
					array(
						'category' => $category,
					)
				);
			endforeach;
		else :
			?>
			<li><a target="_blank" href="<?php echo esc_attr( admin_url( 'edit-tags.php?taxonomy=wpeo_tag' ) ); ?>"><?php esc_html_e( 'Create categories', 'task-manager' ); ?></li>
		<?php
		endif;
		?>
	</ul>
</div>
