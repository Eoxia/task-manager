<?php
/**
 * Gestion des tâches.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.8.0
 * @copyright 2015-2018 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Gestion des tâches.
 */
class Sticky_Note_Class extends \eoxia\Post_Class {

	/**
	 * Le nom du modèle
	 *
	 * @var string
	 */
	protected $model_name = '\task_manager\Sticky_Note_Model';

	/**
	 * Le post type
	 *
	 * @var string
	 */
	protected $type = 'wpeo-sticky-note';

	/**
	 * La clé principale du modèle
	 *
	 * @var string
	 */
	protected $meta_key = 'wpeo_sticky_note';

	/**
	 * La route pour accéder à l'objet dans la rest API
	 *
	 * @var string
	 */
	protected $base = 'sticky-note';

	/**
	 * La version de l'objet
	 *
	 * @var string
	 */
	protected $version = '0.1';

	/**
	 * Affichage
	 *
	 * @param  [type] $post    [description].
	 * @param  [type] $metabox [description].
	 * @return void
	 */
	public function display( $post, $metabox ) {
		\eoxia\View_Util::exec(
			'task-manager',
			'sticky-note',
			'backend/main',
			array(
				'note' => $metabox['args']['note'],
			)
		);
	}

	/**
	 * Ajout d'un nouveau
	 *
	 * @return void
	 */
	public function display_add_new() {
		\eoxia\View_Util::exec( 'task-manager', 'sticky-note', 'backend/add' );
	}
}

Sticky_Note_Class::g();
