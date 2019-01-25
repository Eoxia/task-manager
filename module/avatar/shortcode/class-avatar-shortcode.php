<?php
/**
 * Gestion des shortcodes des avatars
 *
 * @since 1.3.4
 * @version 1.6.0
 * @package Task_Manager
 * @subpackage avatar
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Gestion des avatars
 */
class Avatar_Shortcode {

	/**
	 * Déclaration des shortcodes pour les avatars des utilisateurs
	 */
	public function __construct() {
		add_shortcode( 'task_avatar', array( $this, 'callback_task_avatar' ), 10, 1 );
	}

	/**
	 * Définition du callback pour l'affichage des avatars des utilisateurs
	 *
	 * @param  array $param Les paramètres passés au shortcode.
	 *
	 * @return string       L'affichage de l'avater correspondant aux paramètres demandés.
	 */
	public function callback_task_avatar( $param ) {
		$param = shortcode_atts(
			array(
				'size' => 50,
				'ids'  => '',
			),
			$param,
			'task_avatar'
		);

		$users = Avatar_Class::g()->get_avatars( $param );

		ob_start();
		\eoxia\View_Util::exec(
			'task-manager',
			'avatar',
			'avatar',
			array(
				'users' => $users,
				'size'  => $param['size'],
			)
		);

		return ob_get_clean();
	}
}

new Avatar_Shortcode();
