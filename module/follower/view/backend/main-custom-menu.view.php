<?php
/**
 * Vue Main dans le menu Utilisateur.
 * Gestion des onglets dans la page "users-page".
 *
 * @package   TaskManager
 * @author    Nicolas Domenech <nicolas@eoxia.com>
 * @copyright 2015-2020 Eoxia
 * @since     3.0.1
 * @version   3.0.1
 */

namespace task_manager;

namespace task_manager;

use eoxia\View_Util;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Documentation des variables utilisées dans la vue.
 *
 * @var string $default_tab             La page par défaut affichée.
 * @var string $user                    Les données d'un utilisateur.
 */

?>

<div class="wrap wpeo-wrap tm-wrap">
	<div class="wpeo-tab">
		<ul class="tab-list">
			<li class="tab-element <?php echo ( 'tm-profile' === $default_tab ) ? 'tab-active' : ''; ?>" href="#" data-target="tm-profile" ><?php esc_html_e( 'Profile', 'task-manager' ); ?></li>
		</ul>
		<div class="tab-container">
			<div id="tm-profile" class="tab-content <?php echo ( 'tm-profile' === $default_tab ) ? 'tab-active' : ''; ?>">
				<?php
				View_Util::exec(
					'task-manager',
					'follower',
					'backend/user-profile-custom-menu',
					array(
						'user' => $user,
					)
				);
				?>
			</div>
		</div>
	</div>
</div>
