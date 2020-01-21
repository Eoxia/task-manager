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

	<input type="hidden" name="categories_id" />

	<input type="text" autocomplete="nope" class="form-field filter-tags" style="height: 100%;" placeholder="<?php echo esc_html_e( 'Categories', 'task-manager' ); ?>"/>
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
