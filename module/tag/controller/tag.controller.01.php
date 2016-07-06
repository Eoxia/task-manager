<?php

if ( !defined( 'ABSPATH' ) ) exit;

class tag_controller_01 extends term_ctr_01 {
	public $list_tag = array();

	/**
	* Nom du modèle a utiliser / Name of model to use
	* @var string
	*/
	protected $model_name = 'tag_model_01';

	/**
	 * Nom de la meta stockant les données / Meta name for data storage
	 * @var string
	 */
	protected $meta_key = 'wpeo_tag';

	/**
	 * Nom de la taxinomie par défaut / Name of default taxonomie
	 * @var string
	 */
	protected $taxonomy = 'wpeo_tag';

	/**
	 * Base de l'url pour la récupération au travers de l'API / Base slug for retriving through API
	 * @var string
	 */
	protected $base = 'tag';

	/**
	 * Numéro de la version courante pour l'API / Current version number for API
	 * @var string
	 */
	protected $version = '0.1';

	public function __construct() {
		parent::__construct();

		add_action( 'init', array( &$this, 'callback_admin_init' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'callback_admin_enqueue_scripts' ) );

		add_filter( 'task_manager_dashboard_filter', array( $this, 'callback_dashboard_filter' ), 13, 1 );
		add_filter( 'task_manager_dashboard_search', array( $this, 'callback_task_manager_dashboard_search' ), 11, 2 );

		// add_filter( 'wpeo-project-dashboard', array( $this, 'display_filter' ), 1 );
		add_filter( 'task_footer', array( $this, 'callback_task_footer' ), 5, 2 );
		add_filter( 'task_window_footer_task_controller', array( $this, 'callback_task_footer' ), 11, 2 );
	}

	public function callback_admin_init( ) {
		$labels = array(
			'name'                       => _x( 'Tags', 'taxonomy general name' ),
			'singular_name'              => _x( 'Tag', 'taxonomy singular name' ),
			'search_items'               => __( 'Search Tags' ),
			'popular_items'              => __( 'Popular Tags' ),
			'all_items'                  => __( 'All Tags' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => __( 'Edit Tag' ),
			'update_item'                => __( 'Update Tag' ),
			'add_new_item'               => __( 'Add New Tag' ),
			'new_item_name'              => __( 'New Tag Name' ),
			'separate_items_with_commas' => __( 'Separate Tags with commas' ),
			'add_or_remove_items'        => __( 'Add or remove Tags' ),
			'choose_from_most_used'      => __( 'Choose from the most used Tags' ),
			'not_found'                  => __( 'No Tags found.' ),
			'menu_name'                  => __( 'Tags' ),
		);

		$args = array(
			'hierarchical'          => false,
			'labels'                => $labels,
			'show_ui'               => true,
			'show_admin_column'     => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => true,
			'rewrite'               => array( 'slug' => 'Tag' ),
		);

		register_taxonomy( $this->taxonomy, 'wpeo-task', $args );

		/** Default tag */
		$option = get_option( 'wpeo_wp_project_tag_declared' );

		if ( empty( $option ) ) {
			wp_insert_term( __( 'Project', 'task-manager'), $this->taxonomy );
			update_option( 'wpeo_wp_project_tag_declared', true );
		}

		$this->list_tag = $this->index( array( 'empty_hidden' => false ) );
	}

	public function callback_admin_enqueue_scripts() {
		if( WPEO_TASKMANAGER_DEBUG ) {
			wp_enqueue_script( 'wpeo-task-tag-backend-js', WPEOMTM_TAG_ASSETS_URL . '/js/backend.js', array( "jquery", "jquery-form", "jquery-ui-datepicker", "jquery-ui-sortable", 'jquery-ui-autocomplete', 'suggest' ), WPEO_TASKMANAGER_VERSION );
		}
	}

	public function callback_dashboard_filter( $string ) {
		ob_start();
		require( wpeo_template_01::get_template_part( WPEOMTM_TAG_DIR, WPEOMTM_TAG_TEMPLATES_MAIN_DIR, 'backend', 'filter' ) );
		$string .= ob_get_clean();
		return $string;
	}

	public function callback_task_manager_dashboard_search( $string ) {
		ob_start();
		require( wpeo_template_01::get_template_part( WPEOMTM_TAG_DIR, WPEOMTM_TAG_TEMPLATES_MAIN_DIR, 'backend', 'choosen' ) );
		$string .= ob_get_clean();

		return $string;
	}

	public function callback_task_footer( $string, $element ) {
		ob_start();
		$this->render_list_tag( $element );
		$string .= ob_get_clean();

		return $string;
	}

	public function render_list_tag( $object ) {
		$list_tag_in_object = array();
		$list_tag_id		= array();

		if ( !empty( $object->taxonomy ) && !empty( $object->taxonomy[$this->taxonomy] ) ) {
			foreach( $object->taxonomy[$this->taxonomy] as $tag_id ) {
				$list_tag_in_object[] 	= $this->show( $tag_id );
				$list_tag_id[] 			= $tag_id;
			}
		}

		require( wpeo_template_01::get_template_part( WPEOMTM_TAG_DIR, WPEOMTM_TAG_TEMPLATES_MAIN_DIR, 'backend', 'display', 'tag-selected' ) );
	}

	public static function display_choosen() {
 		global $tag_controller;

		require( wpeo_template_01::get_template_part( WPEOMTM_TAG_DIR, WPEOMTM_TAG_TEMPLATES_MAIN_DIR, 'backend', 'choosen' ) );
	}

	public function save_tag( $tag_name ) {
		$tag_name = !empty( $tag_name ) ? sanitize_text_field( $tag_name ) : '';

		if ( empty( $tag_name ) ) {
			return __( 'Error for create tag', 'task-manager' );
		}

		$term = wp_create_term( $tag_name, $this->get_taxonomy() );

		return $term;
	}

	public function save_tag_on_element( $data ) {
		global $task_controller;

		$data['id'] = (int) $data['id'];
		$data['tag_id'] = (int) $data['tag_id'];
		$data['selected'] = filter_var( $data['selected'], FILTER_VALIDATE_BOOLEAN );
		$task = $task_controller->show( $data['id'] );
		$archive_tag = get_term_by( 'slug', 'archive', 'wpeo_tag' );

		if ( empty( $archive_tag ) ) {
			$artchive_tag = $this->save_tag( 'Archive' );
		}

		$log_message = '';

		if( $task != null ) {
			if( $data['selected'] ) {
				$task->taxonomy['wpeo_tag'][] = $data['tag_id'];
				$log_message = sprintf( __( 'The tag %d has selected for the task #%d by the user #%d', 'task-manager'), $data['tag_id'], $data['id'], get_current_user_id() );

				if( $data['tag_id'] == $archive_tag->term_id ) {
					$task->status = 'archive';
				}
			}
			else {
				$key = array_search( ( int ) $data['tag_id'], $task->taxonomy['wpeo_tag'] );
				$log_message = sprintf( __( 'The tag %d has deselected for the task #%d by the user #%d', 'task-manager'), $data['tag_id'], $data['id'], get_current_user_id() );

				if( $key > -1 )
					unset( $task->taxonomy['wpeo_tag'][$key] );
			}


			$task_controller->update( $task );

			taskmanager\log\eo_log( 'wpeo_project',
			array(
				'object_id' => $data['id'],
				'message' => $log_message,
			), 0 );
		}

		return $task;
	}

	public function get_tag_name_on_element( $element_id ) {
		global $task_controller;
		$task = $task_controller->show( $element_id );

		$list_tag_name = array();

		if ( !empty( $task->taxonomy['wpeo_tag'] ) ) {
		  foreach ( $task->taxonomy['wpeo_tag'] as $element_id ) {
				$term = get_term( $element_id, 'wpeo_tag' );
				$list_tag_name[] = $term->name;
		  }
		}

		return $list_tag_name;
	}

}

global $tag_controller;
$tag_controller = new tag_controller_01();
