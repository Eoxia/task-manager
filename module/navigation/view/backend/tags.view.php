<?php
/**
 * Vue pour afficher les catégories dans la barre de recherche.
 *
 * @author Jimmy Latour <jimmy@eoxia.com>
 * @since 0.1.0
 * @version 1.5.0
 * @copyright 2015-2017 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<ul class="tags">
	<li class="wpeo-tag-title"><i class="fas fa-tag"></i> <?php esc_html_e( 'Categories', 'task-manager' ); ?></li>
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
