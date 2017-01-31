<?php
/**
 * Définitoin du template permettant l'affichage des tâches associées aux comandes dans les clients WPShop
 *
 * @package taskmanager
 * @subpackage wpshop
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?><h3 style="border-bottom:1px solid #000" ><?php esc_html_e( 'Tâches associées aux commandes du client', 'task-manager' ); ?></h3>
<?php require( wpeo_template_01::get_template_part( WPEO_TASK_DIR, WPEO_TASK_TEMPLATES_MAIN_DIR, 'backend', 'main' ) ); ?>
