<?php
/**
 * Classe gérant les utilisateurs
 *
 * @author Jimmy Latour <jimmy@evarisk.com>
 * @since 1.3.6
 * @version 1.5.0
 * @copyright 2015-2018 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Classe gérant les utilisateurs
 */
class Follower_Class extends \eoxia\User_Class {
	/**
	 * Le nom du modèle
	 *
	 * @var string
	 */
	protected $model_name = '\task_manager\Follower_Model';

	/**
	 * La route pour accéder à l'objet dans la rest API
	 *
	 * @var string
	 */
	protected $base = 'follower';
	
	public function init_default_data() {
		$users = get_users( array(
			'roles' => 'administrator',
		) );
		
		if ( ! empty( $users ) ) {
			foreach ( $users as $user ) {
				$shortcuts = get_user_meta( $user->ID, '_tm_shortcuts', true );
				
				if ( empty( $shortcuts ) ) {
					$shortcuts = array(
						'wpeomtm-dashboard' => array(
							array(
								'label' => __( 'My tasks', 'task-manager' ),
								'page'  => 'admin.php',
								'link'  => '?page=wpeomtm-dashboard&user_id=' . get_current_user_id(),
							),
						),
					);
					
					update_user_meta( $user->ID, '_tm_shortcuts', $shortcuts );
				}
			}
		}
	}
}

Follower_Class::g();
