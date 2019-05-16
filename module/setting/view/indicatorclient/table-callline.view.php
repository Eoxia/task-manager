<?php
/**
 * Affichage de la table dans la section configuration de task manager
 *
 * @author Eoxia <dev@eoxia.com>
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

<tr>

<?php

$data = Setting_Class::g()->get_user_settings_indicator_client();
$i = 0;

if ( ! empty( $data ) ) :
	foreach ( $data as $key => $line ) :
		if( $line != '' ):
			\eoxia\View_Util::exec(
				'task-manager',
				'setting',
				'indicatorclient/table-line',
				array(
					'numberfrom'  => $line[ 'from_number' ],
					'numberto'    => $line[ 'to_number' ],
					'color'       => $line[ 'value_color' ],
					'key'         => $key
				)
			);
			$i++;
		endif;
	endforeach;
endif;
?>
