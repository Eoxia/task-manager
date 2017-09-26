<?php
/**
 * Classe gérant les utilisateurs
 *
 * @author Jimmy Latour <jimmy@evarisk.com>
 * @since 1.3.6.0
 * @version 1.3.6.0
 * @copyright 2015-2017 Eoxia
 * @package user
 * @subpackage class
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Classe gérant les utilisateurs
 */
class Follower_Class extends \eoxia\User_Class {

	/**
	 * Le nom du modèle
	 *
	 * @var string
	 */
	protected $model_name 	= '\task_manager\Follower_Model';

	/**
	 * La route pour accéder à l'objet dans la rest API
	 *
	 * @var string
	 */
	protected $base = 'follower';

	/**
	 * Le constructeur
	 *
	 * @return void
	 *
	 * @since 1.3.6.0
	 * @version 1.3.6.0
	 */
	protected function construct() {
		parent::construct();
	}

}

Follower_Class::g();
