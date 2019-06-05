<?php
/**
 * Affichage des charts des utilisateurs selon un lapse de temps préfédini
 *
 * @author Corentin-Eoxia <dev@eoxia.com>
 * @since 1.9.0
 * @version 1.9.0
 * @copyright 2019 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<?php
	//do_meta_boxes( 'audit-page', 'normal', '' );

	\eoxia\View_Util::exec( 'task-manager', 'audit', '/audit-page/metabox-button-create', array() );

	Audit_Class::g()->callback_audit_list_metabox();

 ?>
