<?php
/**
 * Affichage des charts des utilisateurs selon un lapse de temps préfédini
 *
 * @author Corentin-Eoxia <dev@eoxia.com>
 * @since 1.5.0
 * @version 1.6.0
 * @copyright 2015-2018 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<div>
	<br>
</div>

<?php do_meta_boxes( 'indicator-page', 'normal', '' ); ?>
