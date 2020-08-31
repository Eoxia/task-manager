<?php
/**
 * Les actions relatives aux tâches.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.0.0
 * @version 1.6.0
 * @copyright 2015-2018 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

use eoxia\Config_Util;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Les actions relatives aux tâches.
 */
class Task_Action {

	/**
	 * Initialise les actions liées au tâche.
	 *
	 * @since 1.0.0
	 * @version 1.6.0
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'callback_init' ) );
		add_action( 'wp_print_script', array( $this, 'callback_wp_print_script' ) );
		add_action( 'admin_print_scripts', array( $this, 'callback_wp_print_script' ) );

		add_action( 'wp_ajax_create_task', array( $this, 'callback_create_task' ) );
		add_action( 'wp_ajax_delete_task', array( $this, 'callback_delete_task' ) );

		add_action( 'wp_ajax_edit_title', array( $this, 'callback_edit_title' ) );
		add_action( 'wp_ajax_edit_created_date', array( $this, 'callback_edit_created_date' ) );

		add_action( 'wp_ajax_change_color', array( $this, 'callback_change_color' ) );

		add_action( 'wp_ajax_search_parent', array( $this, 'callback_search_parent' ) );
		add_action( 'wp_ajax_move_task_to', array( $this, 'callback_move_task_to' ) );

		add_action( 'wp_ajax_load_more_task', array( $this, 'callback_load_more_task' ) );

		add_action( 'wp_ajax_recompile_task', array( $this, 'callback_recompile_task' ) );

		add_action( 'add_meta_boxes', array( $this, 'callback_add_meta_boxes' ), 10, 2 );

		add_action( 'wp_ajax_update_indicator_client', array( $this, 'callback_update_indicator_client' ) );

		add_action( 'tm_filter_daily_activity_after', array( $this, 'add_filter_customer_client' ), 10, 3 );

		add_action( 'wp_ajax_pagination_update_tasks', array( $this, 'callback_pagination_update_tasks' ) );

		add_action( 'wp_ajax_search_parent_for_task', array( $this, 'callback_search_parent_for_task' ) );

		add_action( 'wp_ajax_load_all_task_parent_data', array( $this, 'callback_load_all_task_parent_data' ) );

		add_action( 'wp_ajax_link_parent_to_task', array( $this, 'callback_link_parent_to_task' ) ); // TASK GET A PARENT !
		add_action( 'wp_ajax_delink_parent_to_task', array( $this, 'callback_delink_parent_to_task' ) ); // TASK LEAVE HER PARENT !

		add_action( 'wp_ajax_update_task_per_page_user', array( $this, 'callback_update_task_per_page_user' ) ); // TASK LEAVE HER PARENT !

		add_action( 'wp_ajax_hide_points', array( $this, 'task_hide_points' ) );

		add_action( 'wp_ajax_task_state', array( $this, 'callback_task_state' ) );

		add_action( 'wp_ajax_task_update', array( $this, 'callback_task_update' ) );

		add_action( 'wp_ajax_tm_edit_columns', array( $this, 'callback_tm_edit_columns' ) );
		add_action( 'wp_ajax_tm_save_columns', array( $this, 'callback_tm_save_columns' ) );
	}

	/**
	 * Initialise le post status "archive".
	 *
	 * @return void
	 *
	 * @since 1.0.0
	 * @version 1.3.6.0
	 */
	public function callback_init() {
		register_post_status( 'archive' );
	}

	/**
	 * Ajoutes le nombre de posts_per_page dans une variable JS
	 *
	 * @return void
	 *
	 * @since 1.0.0
	 * @version 1.3.6.0
	 */
	public function callback_wp_print_script() {
		$element_per_page = get_user_meta( get_current_user_id(), '_tm_task_per_page', true );
		$element_per_page = empty( $element_per_page ) ? 10 : $element_per_page;

		?>
		<script>
			window.task_manager_posts_per_page = "<?php echo esc_attr( $element_per_page ); ?>";
		</script>
		<?php
	}

	/**
	 * La fonction de callback de l'action admin_menu de WordPress
	 *
	 * @since 6.0.0
	 */
	public function admin_menu() {
		//CMH::register_menu( __( 'My Tasks', 'task-manager' ), __( 'My Tasks', 'task-manager' ), 'read', array( ), 'fas fa-check-square', 'bottom' );
	}

	/**
	 * Créer une tâche en utilisant le modèle Task_Model.
	 * Renvoie la vue dans la réponse de la requête XHR.
	 *
	 * @return void
	 *
	 * @since 1.0.0
	 * @version 1.6.0
	 */
	public function callback_create_task() {
		check_ajax_referer( 'create_task' );

		$parent_id         = ! empty( $_POST['parent_id'] ) ? (int) $_POST['parent_id'] : 0;
		$tag_slug_selected = ! empty( $_POST['tag'] ) ? sanitize_text_field( $_POST['tag'] ) : 0;

		$task_args = array(
			'title'     => __( 'New Project', 'task-manager' ),
			'parent_id' => $parent_id,
			'status'    => 'publish',
		);

		if ( ! empty( $tag_slug_selected ) ) {
			$tag = get_term_by( 'slug', $tag_slug_selected, 'wpeo_tag', 'ARRAY_A' );

			if ( empty( $tag ) ) {
				$tag = wp_create_term( $tag_slug_selected, 'wpeo_tag' );
			}

			$task_args['taxonomy'] = array(
				Tag_Class::g()->get_type() => array(
					$tag['term_id'],
				),
			);
		}

		$task = Task_Class::g()->create( $task_args, true );

		ob_start();
		Task_Class::g()->display_bodies( array( $task ) );

		wp_send_json_success(
			array(
				'namespace'        => 'taskManager',
				'module'           => 'task',
				'callback_success' => 'createdTaskSuccess',
				'view'             => ob_get_clean(),
			)
		);
	}

	/**
	 * Met le status de la tâche en "trash".
	 *
	 * @return void
	 *
	 * @since 1.0.0
	 * @version 1.6.0
	 */
	public function callback_delete_task() {
		check_ajax_referer( 'delete_task' );

		$task_id = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;

		if ( empty( $task_id ) ) {
			wp_send_json_error();
		}

		$task = Task_Class::g()->update(
			array(
				'id'     => $task_id,
				'status' => 'trash',
			)
		);

		do_action( 'tm_delete_task', $task );

		wp_send_json_success(
			array(
				'namespace'        => 'taskManager',
				'module'           => 'task',
				'callback_success' => 'deletedTaskSuccess',
				'view'             => ob_get_clean(),
			)
		);
	}

	/**
	 * Changes le titre de la tâche
	 *
	 * @return void
	 *
	 * @since 1.0.0
	 * @version 1.6.0
	 */
	public function callback_edit_title() {
//		check_ajax_referer( 'edit_title' );

		$task_id = ! empty( $_POST['task_id'] ) ? (int) $_POST['task_id'] : 0;
		$title   = ! empty( $_POST['title'] ) ?  $_POST['title'] : '';

		if ( empty( $task_id ) ) {
			wp_send_json_error();
		}

		$task = Task_Class::g()->get(
			array(
				'id' => $task_id,
			),
			true
		);
		$task->data['title'] = $title;
		$task->data['slug']  = sanitize_title( $title );

		Task_Class::g()->update( $task->data );
		wp_send_json_success();
	}

	/**
	 * Change la date de création d'un projet.
	 *
	 * @return void
	 *
	 * @since 3.0.2
	 * @version 3.0.2
	 */
	public function callback_edit_created_date() {
//		check_ajax_referer( 'edit_created_date' );

		$task_id = ! empty( $_POST['task_id'] ) ? (int) $_POST['task_id'] : 0;
		$created_date   = ! empty( $_POST['created_date'] ) ?  sanitize_text_field( $_POST['created_date'] ) : current_time( 'mysql' );

		if ( empty( $task_id ) ) {
			wp_send_json_error();
		}

		$task = Task_Class::g()->get(
			array(
				'id' => $task_id,
			),
			true
		);

		$task->data['date'] = $created_date;

		Task_Class::g()->update( $task->data );
		wp_send_json_success();
	}

	/**
	 * Changes la couleur de la tâche.
	 *
	 * @return void
	 *
	 * @since 1.0.0
	 * @version 1.6.0
	 */
	public function callback_change_color() {
		check_ajax_referer( 'change_color' );

		$id    = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;
		$color = ! empty( $_POST['color'] ) ? sanitize_text_field( $_POST['color'] ) : '';

		$task = Task_Class::g()->get(
			array(
				'id' => $id,
			),
			true
		);

		$task->data['front_info']['display_color'] = $color;

		Task_Class::g()->update( $task->data );

		wp_send_json_success();
	}

	/**
	 * Recherche dans les posts types selon le term.
	 *
	 * @since 1.0.0
	 * @version 1.6.0
	 *
	 * @return void
	 */
	public function callback_search_parent() {
		global $wpdb;
		$term = sanitize_text_field( $_GET['term'] );

		$posts_type = get_post_types();
		unset( $posts_type[ Task_Class::g()->get_type() ] );

		$posts_founded = array();
		$ids_founded   = array();

		/** [comment dev test pour adapter au phpcs - 07/02/2019]
			* $implode_posts_type = implode( $posts_type, '\',\'' );
			*
			* $query_string = apply_filters( 'task_manager_search_parent_query', "SELECT ID, post_title FROM {$wpdb->posts} WHERE ID LIKE '% " . '%1$s' . " %' AND post_type IN('" . '%2$s' . "')" );
			*
			* $query = $wpdb->query( $wpdb->prepare( $query_string, $term, $implode_posts_type ) ); // WPCS: unprepared SQL OK.
			*/

		$query = apply_filters( 'task_manager_search_parent_query', "SELECT ID, post_title FROM {$wpdb->posts} WHERE ID LIKE '%" . $term . "%' AND post_type IN('" . implode( $posts_type, '\',\'' ) . "')", $term );

		// WPCS: PreparedSQLPlaceholders replacement count ok.
		$results = $wpdb->get_results( $query ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

		if ( ! empty( $results ) ) {
			foreach ( $results as $post ) {
				$posts_founded[] = array(
					'label' => '' . $term . ' - ' . $post->post_title,
					'value' => '' . $term . ' - ' . $post->post_title,
					'id'    => $post->ID,
				);

				$ids_founded[] = $post->ID;
			}
		}

		$query = new \WP_Query(
			array(
				'post_type'   => $posts_type,
				's'           => $term,
				'post_status' => array( 'publish', 'draft' ),
			)
		);

		if ( ! empty( $query->posts ) ) {
			foreach ( $query->posts as $post ) {
				if ( ! in_array( $post->ID, $ids_founded, true ) ) {
					$posts_founded[] = array(
						'label' => '#' . $post->ID . ' - ' . $post->post_title,
						'value' => '#' . $post->ID . ' - ' . $post->post_title,
						'id'    => $post->ID,
					);
				}
			}
		}

		if ( empty( $posts_founded ) ) {
			$posts_founded[] = array(
				'label' => __( 'No post found', 'task-manager' ),
				'value' => __( 'No post found', 'task-manager' ),
				'id'    => 0,
			);
		}

		wp_die( wp_json_encode( $posts_founded ) );
	}

	/**
	 * Déplaces la tâche vers la parent_id "to_element_id".
	 *
	 * @return void
	 *
	 * @since 1.0.0
	 * @version 1.6.0
	 */
	public function callback_move_task_to() {
		check_ajax_referer( 'move_task_to' );

		$task_id       = ! empty( $_POST['task_id'] ) ? (int) $_POST['task_id'] : 0;
		$to_element_id = ! empty( $_POST['to_element_id'] ) ? (int) $_POST['to_element_id'] : 0;

		if ( empty( $task_id ) || empty( $to_element_id ) ) {
			wp_send_json_error();
		}

		$task = Task_Class::g()->get(
			array(
				'id' => $task_id,
			),
			true
		);

		$task->data['parent_id'] = $to_element_id;

		Task_Class::g()->update( $task->data );

		wp_send_json_success(
			array(
				'namespace'        => 'taskManager',
				'module'           => 'task',
				'callback_success' => 'movedTaskTo',
				'task_id'          => $task_id,
			)
		);
	}

	/**
	 * Charges plus de tâche en appelant le shortcode task
	 *
	 * @return void
	 *
	 * @since 1.3.6
	 * @version 1.5.0
	 *
	 * @todo: nonce
	 */
	public function callback_load_more_task() {
		$offset         = ! empty( $_POST['offset'] ) ? (int) $_POST['offset'] : 0;
		$posts_per_page = ! empty( $_POST['posts_per_page'] ) ? (int) $_POST['posts_per_page'] : 0;
		$post_parent    = ! empty( $_POST['post_parent'] ) ? (int) $_POST['post_parent'] : 0;
		$term           = ! empty( $_POST['term'] ) ? sanitize_text_field( $_POST['term'] ) : '';
		$user_id        = ! empty( $_POST['user_id'] ) ? sanitize_text_field( $_POST['user_id'] ) : '';
		$status         = ! empty( $_POST['status'] ) ? sanitize_text_field( $_POST['status'] ) : array();
		$tab            = ! empty( $_POST['tab'] ) ? sanitize_text_field( $_POST['tab'] ) : array();

		if ( ! isset( $users_id ) || ! empty( $users_id ) ) {
			$users_id = explode( ',', $user_id );
		}

		$categories_id = ! empty( $_POST['categories_id'] ) ? sanitize_text_field( $_POST['categories_id'] ) : array();

		if ( ! empty( $categories_id ) ) {
			$categories_id = explode( ',', $categories_id );
		}

		$param = apply_filters(
			'task_manager_load_more_query_args',
			array(
				'offset'         => $offset,
				'posts_per_page' => $posts_per_page,
				'term'           => $term,
				'users_id'       => $users_id,
				'categories_id'  => $categories_id,
				'status'         => $status,
				'post_parent'    => $post_parent,
			),
			$tab
		);

		$tasks = Task_Class::g()->get_tasks( $param );
		ob_start();
		Task_Class::g()->display_bodies( $tasks );

		wp_send_json_success(
			array(
				'view'             => ob_get_clean(),
				'namespace'        => 'taskManager',
				'module'           => 'task',
				'callback_success' => 'loadedMoreTask',
				'can_load_more'    => ! empty( $tasks ) ? true : false,
			)
		);
	}

	/**
	 * Recompiles toutes les données de la tâche.
	 *
	 * @since 1.6.0
	 * @version 1.6.0
	 *
	 * @return void
	 */
	public function callback_recompile_task() {
		check_ajax_referer( 'recompile_task' );

		$id = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;

		$recompiled_elements = Task_Class::g()->recompile_task( $id );


		wp_send_json_success(
			array(
				'namespace'           => 'taskManager',
				'module'              => 'task',
				'callback_success'    => 'recompiledTask',
				'recompiled_elements' => $recompiled_elements,
			)
		);
	}

	/**
	 * Fait le contenu de la metabox
	 *
	 * @param string  $post_type Le type du post.
	 * @param WP_Post $post      Les données du post.
	 *
	 * @since 1.0.0
	 * @version 1.6.2
	 */
	public function callback_add_meta_boxes( $post_type, $post ) {
		if ( in_array( $post_type, Config_Util::$init['task-manager']->associate_post_type, true ) ) {

			ob_start();
			\eoxia\View_Util::exec( 'task-manager', 'task', 'backend/metabox-create-buttons', array( 'parent_id' => $post->ID ) );
			$buttons = ob_get_clean();

			$currentyear = date( 'Y', strtotime( 'now' ) );

			ob_start();
			\eoxia\View_Util::exec( 'task-manager', 'task', 'backend/metabox-head-indicator', array( 'parent_id' => $post->ID, 'year' => $currentyear, 'post_id' => $post->ID, 'post_author' => $post->post_author ) );
			$button_indicator = ob_get_clean();


			add_meta_box( 'wpeo-task-metabox', __( 'Task', 'task-manager' ) . apply_filters( 'tm_posts_metabox_buttons', $buttons ), array( Task_Class::g(), 'callback_render_metabox' ), $post_type, 'normal', 'default' );
			add_meta_box( 'wpeo-task-metabox-indicator', __( 'Indicator', 'task-manager' ) . apply_filters( 'tm_posts_metabox_buttons', $button_indicator ), array( Task_Class::g(), 'callback_render_indicator' ), $post_type, 'normal', 'default' );
			add_meta_box( 'wpeo-task-history-metabox', __( 'History task', 'task-manager' ), array( Indicator_Class::g(), 'callback_my_daily_activity' ), $post_type, 'side', 'default' );
		}
	}

	public function callback_update_indicator_client(){
		check_ajax_referer( 'update_indicator_client' );

		$year = ! empty( $_POST['year'] ) ? (int) $_POST['year'] : date("Y");;
		$postid    = ! empty( $_POST['postid'] ) ? (int) $_POST['postid'] : 0;
		$postauthor    = ! empty( $_POST['postauthor'] ) ? (int) $_POST['postauthor'] : 0;

		if( ! $postid || ! $year ){
			wp_send_json_error();
		}

		$alldata = Task_Class::g()->update_client_indicator( $postid, $postauthor, $year );

		$year       = $alldata[ 'year' ];
		$type       = $alldata[ 'type' ];
		$info       = $alldata[ 'info' ];
		$everymonth = $alldata[ 'everymonth' ];
		$view = ob_start();

		\eoxia\View_Util::exec(
			'task-manager',
			'task',
			'backend/metabox-indicators',
			array(
				'type' => $type,
				'info' => $info,
				'everymonth' => $everymonth
			)
		);

		wp_send_json_success(
			array(
				'namespace'        => 'taskManager',
				'module'           => 'task',
				'callback_success' => 'updateIndicatorClientSuccess',
				'view'             => ob_get_clean(),
				'year'             => $year
			)
		);

	}

	public function add_filter_customer_client( $user_id, $customer_id = 0, $page = '' ){
		$screen = get_current_screen();

		if( $page == '' ){
			$page = $screen->id;
		}

		if( $page == 'toplevel_page_wpeomtm-dashboard' ){

			$customer_ctr = new \wps_customer_ctr();

			\eoxia\View_Util::exec(
				'task-manager',
				'indicator',
				'backend/filter-customer',
				array(
					'customer_ctr'         => $customer_ctr,
					'selected_customer_id' => $customer_id,
					'page'                 => $page
				)
			);

		}else{
			$customer_ctr = new \wps_customer_ctr();

			\eoxia\View_Util::exec(
				'task-manager',
				'indicator',
				'backend/filter-customerid',
				array(
					'client_id' => $customer_id,
					'page'      => $page

				)
			);

		}

	}

	public function callback_pagination_update_tasks(){

		$page_actual = isset( $_POST[ 'page' ] ) ? (int) $_POST[ 'page' ] : 0;
		$post_id = isset( $_POST[ 'post_id' ] ) ? (int) $_POST[ 'post_id' ] : 0;
		$next = isset( $_POST[ 'next' ] ) ? (int) $_POST[ 'next' ] : 0;
		$show_archive = isset( $_POST[ 'show' ] ) && $_POST[ 'show' ] == 'true' ? (bool) true : false;

		if( ! $post_id ){
			wp_send_json_error();
		}

		$post_page_per_client = Task_Class::g()->get_task_per_page_for_this_user( get_current_user_id() );
		$next = ( $next - 1 ) > 0 ? ( $next - 1 ) * $post_page_per_client : 0;

		if( $show_archive ){
			$args_parameter = array(
				'offset'      => $next,
				'post_status' => 'publish,pending,draft,future,private,inherit,archive'
			);
		}else{
			$args_parameter = array(
				'offset'      => $next,
				'post_status' => 'publish,pending,draft,future,private,inherit'
			);
		}

		ob_start();
		Task_Class::g()->callback_render_metabox( array(), array(), $args_parameter, $post_id );

		wp_send_json_success(
			array(
				'view'             => ob_get_clean(),
				'namespace'        => 'taskManager',
				'module'           => 'task',
				'callback_success' => 'loadedTasksSuccess',
				'show_archive'     => $show_archive
			)
		);
	}

	public function callback_load_all_task_parent_data(){
		check_ajax_referer( 'load_all_task_parent_data' );
		$posttype_found = array();
		$commands_founded = array();
		global $eo_search;

		$query = new \WP_Query(
			array(
				'post_type'      => Config_Util::$init['task-manager']->associate_post_type,
				'posts_per_page' => -1,
				'post_status'    => 'any'
			)
		);



		// echo '<pre>'; print_r( $query ); echo '</pre>'; exit;

		if ( ! empty( $query->posts ) ) {
			foreach( $query->query[ 'post_type' ] as $post_type_title){
				$posttype_found[ $post_type_title ] = array();
			}

			foreach ( $query->posts as &$post ) {
				if( $post->post_type == "wpshop_shop_order" ){ // 19/04/2019 -> Exception car WP SHOP v1 oblige une nouvelle requete
					$order_meta = get_post_meta( $post->ID, '_order_postmeta', true ); // A supprimer A la sortie de WPSHOP V2
					$posttype_found[ $post->post_type ][] = array( //
						'label' => $order_meta[ 'order_temporary_key'] ? $order_meta[ 'order_temporary_key'] : $order_meta[ 'order_key'], //
						'value' => $order_meta[ 'order_temporary_key'] ? $order_meta[ 'order_temporary_key'] : $order_meta[ 'order_key'], //
						'id'    => $post->ID //
					); //
					continue; //
				}else if( $post->post_type == "digi-risk" ){ // 05/06/2019 pour digirisk
					if( class_exists( '\digi\Risk_Class' ) ){
						$postrisk = \digi\Risk_Class::g()->get( array( 'id' => $post->ID ), true );
						$posttype_found[ $post->post_type ][] = array( //
							'label' => $postrisk->data[ 'title' ], //
							'value' => $postrisk->data[ 'title' ], //
							'id'    => $postrisk->data[ 'id' ] //
						);
					}
				}else{
					$posttype_found[ $post->post_type ][] = array(
						'label' => $post->post_title,
						'value' => $post->post_title,
						'id'    => $post->ID
					);

				}

			}
		}

		if ( empty( $posttype_found ) ) { // Aucun post type trouvé
			$posttype_found[] = array(
				'label' => __( 'No post type found', 'task-manager' ),
				'value' => __( 'No post type found', 'task-manager' ),
				'id'    => 0,
			);
		}

			ob_start();
			\eoxia\View_Util::exec(
				'task-manager',
				'task',
				'backend/list_parent_element',
				array(
					'data' => $posttype_found,
				)
			);

			wp_send_json_success(
				array(
					'view'             => ob_get_clean(),
					'namespace'        => 'taskManager',
					'module'           => 'task',
					'callback_success' => 'loadedAllClientsCommands',
				)
			);
	}

	public function callback_link_parent_to_task(){
		$task_id = isset( $_POST[ 'id' ] ) ? (int) $_POST[ 'id' ] : 0;
		$parent_id = isset( $_POST[ 'parent_id' ] ) ? (int) $_POST[ 'parent_id' ] : 0;
		if( ! $task_id || ! $parent_id ){
			wp_send_json_error();
		}

		$task = Task_Class::g()->get(
			array(
				'id' => $task_id,
			),
			true
		);

		$task->data['parent_id'] = $parent_id;
		$task = Task_Class::g()->update( $task->data );

		$this->json_success_display_task_parent_view( $task );
	}

	public function callback_delink_parent_to_task(){
		$task_id = isset( $_POST[ 'id' ] ) ? (int) $_POST[ 'id' ] : 0;
		if( ! $task_id ){
			wp_send_json_error();
		}

		$task = Task_Class::g()->get(
			array(
				'id' => $task_id,
			),
			true
		);

		$task->data['parent_id'] = 0;
		$task = Task_Class::g()->update( $task->data );

		$this->json_success_display_task_parent_view( $task );
	}

	public function json_success_display_task_parent_view( $task = array() ){
		if( empty( $task ) ){
			wp_send_json_error();
		}

		ob_start();

		\eoxia\View_Util::exec(
			'task-manager',
			'task',
			'backend/linked-post-type',
			array(
				'task' => $task,
			)
		);

		wp_send_json_success(
			array(
				'view'             => ob_get_clean(),
				'namespace'        => 'taskManager',
				'module'           => 'task',
				'callback_success' => 'reloadTaskParentElement',
			)
		);

	}

	public function callback_update_task_per_page_user(){
		$task_per_page = isset( $_POST[ 'task_page' ] ) ? (int) $_POST[ 'task_page' ] : 0;
		if( ! $task_per_page ){
			wp_send_json_error( __( 'Error in number task per page', 'task-manager' ) );
		}

		$data = array(
			'value'         => $task_per_page,
			'option_name'   => 'Number of task per page in client',
			'modified_date' => strtotime( 'now' ),
			'modified_date' => date( 'd/m/y', strtotime( 'now' ) )
		);

		$user_id = get_current_user_id();

		update_user_meta( $user_id, '_tm_task_per_page', $data );

		wp_send_json_success(
			array(
				'namespace'        => 'taskManager',
				'module'           => 'task',
				'callback_success' => 'returnSuccessUpdateTaskPerPage',
				'text_success'      => sprintf( __( 'Successfully update to %1$s tasks per page ', 'task-manager' ), $task_per_page )
			)
		);
	}

	public function task_hide_points() {
		$task_id = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;
		$hide    = ( isset( $_POST['hide'] ) && 'true' == $_POST['hide'] ) ? true : false;

		$hide_tasks = get_user_meta( get_current_user_id(), '_tm_hide_task_hide', true );

		if ( empty( $hide_tasks ) ) {
			$hide_tasks = array();
		}

		$hide_tasks[ $task_id ] = $hide;

		update_user_meta( get_current_user_id(), '_tm_hide_task_hide', $hide_tasks );

		$task = Task_Class::g()->get( array( 'id' => $task_id ), true );

		ob_start();
		\eoxia\View_Util::exec(
			'task-manager',
			'task',
			'backend/task-only-content',
			array(
				'task'       => $task,
				'hide_tasks' => $hide_tasks,
			)
		);

		wp_send_json_success( array(
			'namespace'        =>  'taskManager',
			'module'           =>  'task',
			'view'             =>  ob_get_clean(),
			'callback_success' => 'taskHidedPoints',
		) );
	}

	public function callback_task_state() {
		$task_id = isset( $_POST[ 'task_id' ] ) ? (int) $_POST[ 'task_id' ] : 0;
		$state = ! empty( $_POST['state'] ) ? sanitize_text_field( $_POST['state'] ) : '';

		if ( ! $task_id ) {
			wp_send_json_error();
		}

		$task = Task_Class::g()->get(
			array(
				'id' => $task_id,
			),
			true
		);

		$task->data['task_info']['state'] = $state;
		$task = Task_Class::g()->update( $task->data );

		do_action( 'wp_ajax_task_update' , $task_id );
		ob_start();
		\eoxia\View_Util::exec(
			'task-manager',
			'task',
			'New/task',
			array(
				'task'       => $task,
			)
		);
		wp_send_json_success( array(
			'namespace'        =>  'taskManager',
			'module'           =>  'newTask',
			'view'             =>  ob_get_clean(),
			'callback_success' => 'taskStateSuccess',
		) );

	}

	public function callback_task_update( $task_id ) {

		$last_update = Task_Class::g()->get_task_last_update( $task_id );

		update_post_meta( $task_id, 'wpeo_task', 'last_update' );
	}

	public function callback_tm_edit_columns() {
		$user_columns_def = Follower_Class::g()->user_columns_def;

		wp_send_json_success( array(
			'namespace'        => 'taskManager',
			'module'           => 'newTask',
			'callback_success' => 'editedColumnSuccess',
			'user_columns_def' => $user_columns_def,
		) );
	}

	public function callback_tm_save_columns() {
		$columns = ! empty( $_POST['columns'] ) ? array( $_POST['columns'] ) : array();

		if ( empty( $columns ) ) {
			wp_send_json_error();
		}

		if ( ! empty( $columns[0] ) ) {
			foreach ( $columns[0] as $key => &$column ) {
				$column['displayed'] = ( isset( $column['displayed'] ) && 'true' == $column['displayed'] ) ? true : false;
				$column['order']     = ! empty( $column['order'] ) ? (int) $column['order'] : 0;
			}
		}

		unset ( $column );

		update_user_meta( get_current_user_id(), \eoxia\Config_Util::$init['task-manager']->follower->user_columns_key, $columns[0] );

		wp_send_json_success( array(
			'namespace'        => 'taskManager',
			'module'           => 'newTask',
			'callback_success' => 'savedColumnSuccess',
		) );
	}
}

new Task_Action();
