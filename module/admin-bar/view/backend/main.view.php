<?php
/**
 * Ajout du champ de recherche.
 *
 * @author Eoxia <dev@eoxia.com>
 * @version 1.3.6.0
 *
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; } ?>

<form action="<?php echo esc_attr( admin_url( 'admin-post.php' ) ); ?>" method="get">
	<?php wp_nonce_field( 'search_task' ); ?>
	<input type="hidden" name="action" value="search_task" />
	<input type="text" name="s" placeholder="Rechercher dans les tâches" style="height:24px;padding-left:5px;color:#333">
</form>
