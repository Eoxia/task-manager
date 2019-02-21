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

if ( ! defined( 'ABSPATH' ) ) {
	exit; }

/**
 * Classe gérant les utilisateurs
 */
class Owner_Class extends \eoxia\User_Class {

	/**
	 * Le nom du modèle
	 *
	 * @var string
	 */
	protected $model_name = '\task_manager\Owner_Model';

	/**
	 * La route pour accéder à l'objet dans la rest API
	 *
	 * @var string
	 */
	protected $base = 'owner';

}

Owner_Class::g();
