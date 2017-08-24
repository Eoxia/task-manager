<?php
/**
 * Les actions relatives aux tâches.
 *
 * @author Jimmy Latour <jimmy.eoxia@gmail.com>
 * @since 1.0.0.0
 * @version 1.3.6.0
 * @copyright 2015-2017 Eoxia
 * @package task
 * @subpackage action
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Les actions relatives aux tâches.
 */
class Task_Action {

	/**
	 * Initialise les actions liées au tâche.
	 *
	 * @since 1.0.0.0
	 * @version 1.3.6.0
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'callback_init' ) );
		add_action( 'wp_print_script', array( $this, 'callback_wp_print_script' ) );
		add_action( 'admin_print_scripts', array( $this, 'callback_wp_print_script' ) );

		add_action( 'wp_ajax_create_task', array( $this, 'callback_create_task' ) );
		add_action( 'wp_ajax_delete_task', array( $this, 'callback_delete_task' ) );

		add_action( 'wp_ajax_edit_title', array( $this, 'callback_edit_title' ) );

		add_action( 'wp_ajax_change_color', array( $this, 'callback_change_color' ) );
		add_action( 'wp_ajax_notify_by_mail', array( $this, 'callback_notify_by_mail' ) );
		add_action( 'wp_ajax_load_task_properties', array( $this, 'callback_load_task_properties' ) );

		add_action( 'wp_ajax_search_parent', array( $this, 'callback_search_parent' ) );
		add_action( 'wp_ajax_move_task_to', array( $this, 'callback_move_task_to' ) );

		add_action( 'wp_ajax_load_more_task', array( $this, 'callback_load_more_task' ) );

		add_action( 'wp_ajax_export_task', array( $this, 'callback_export_task' ) );
	}

	/**
	 * Initialise le post status "archive".
	 *
	 * @return void
	 *
	 * @since 1.0.0.0
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
	 * @since 1.0.0.0
	 * @version 1.3.6.0
	 */
	public function callback_wp_print_script() {
		?>
		<script>
			window.task_manager_posts_per_page = "<?php echo esc_attr( \eoxia\Config_Util::$init['task-manager']->task->posts_per_page ); ?>";
		</script>
		<?php
	}

	/**
	 * Créer une tâche en utilisant le modèle Task_Model.
	 * Renvoie la vue dans la réponse de la requête XHR.
	 *
	 * @return void
	 *
	 * @since 1.0.0.0
	 * @version 1.3.6.0
	 */
	public function callback_create_task() {
		check_ajax_referer( 'create_task' );

		$parent_id = ! empty( $_POST['parent_id'] ) ? (int) $_POST['parent_id'] : 0;
		$tag_slug_selected = ! empty( $_POST['tag'] ) ? sanitize_text_field( $_POST['tag'] ) : 0;

		$task = Task_Class::g()->create( array(
			'title' 		=> __( 'New task', 'task-manager' ),
			'parent_id' => $parent_id,
		) );

		if ( ! empty( $tag_slug_selected ) ) {
			$tag = get_term_by( 'slug', $tag_slug_selected, 'wpeo_tag', 'ARRAY_A' );

			if ( empty( $tag ) ) {
				$tag = wp_create_term( $tag_slug_selected, 'wpeo_tag' );
			}

			$task->taxonomy['wpeo_tag'][] = (int) $tag['term_id'];
			Task_Class::g()->update( $task );
		}

		ob_start();
		\eoxia\View_Util::exec( 'task-manager', 'task', 'backend/task', array(
			'task' => $task,
		) );

		wp_send_json_success( array(
			'namespace' => 'taskManager',
			'module' => 'task',
			'callback_success' => 'createdTaskSuccess',
			'view' => ob_get_clean(),
		) );
	}

	/**
	 * Met le status de la tâche en "trash".
	 *
	 * @return void
	 *
	 * @since 1.0.0.0
	 * @version 1.3.6.0
	 */
	public function callback_delete_task() {
		check_ajax_referer( 'delete_task' );

		$task_id = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;

		if ( empty( $task_id ) ) {
			wp_send_json_error();
		}

		$task = Task_Class::g()->get( array(
			'post__in' => array(
				$task_id
			),
		), true );

		$task->status = 'trash';

		Task_Class::g()->update( $task );
		wp_send_json_success( array(
			'namespace' => 'taskManager',
			'module' => 'task',
			'callback_success' => 'deletedTaskSuccess',
			'view' => ob_get_clean(),
		) );
	}

	/**
	 * Changes le titre de la tâche
	 *
	 * @return void
	 *
	 * @since 1.0.0.0
	 * @version 1.3.6.0
	 */
	public function callback_edit_title() {
		check_ajax_referer( 'edit_title' );

		$task_id = ! empty( $_POST['task_id'] ) ? (int) $_POST['task_id'] : 0;
		$title = ! empty( $_POST['title'] ) ? sanitize_text_field( $_POST['title'] ) : '';

		$task = Task_Class::g()->get( array(
			'post__in' => array( $task_id ),
		), true );

		$task->title = $title;
		$task->slug = sanitize_title( $title );

		Task_Class::g()->update( $task );
		wp_send_json_success();
	}

	/**
	 * Changes la couleur de la tâche.
	 *
	 * @return void
	 *
	 * @since 1.0.0.0
	 * @version 1.3.6.0
	 */
	public function callback_change_color() {
		check_ajax_referer( 'change_color' );

		$id = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;
		$color = ! empty( $_POST['color'] ) ? sanitize_text_field( $_POST['color'] ) : '';

		$task = Task_Class::g()->get( array(
			'post__in' => array( $id ),
		), true );

		$task->front_info['display_color'] = $color;

		Task_Class::g()->update( $task );

		wp_send_json_success();
	}

	/**
	 * Envoie une notification par email au responsable et followers de la tâche avec en contenant du mail:
	 * Le nom de la tâche, les points, et des liens rapides pour y accéder.
	 *
	 * @return void
	 *
	 * @since 1.0.0.0
	 * @version 1.3.6.0
	 */
	public function callback_notify_by_mail() {
		check_ajax_referer( 'notify_by_mail' );

		$id = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;

		if ( empty( $id ) ) {
			wp_send_json_error();
		}

		$task = Task_Class::g()->get( array(
			'post__in' => array( $id ),
		), true );

		$sender_data = wp_get_current_user();
		$admin_email = get_bloginfo( 'admin_email' );
		$blog_name = get_bloginfo( 'name' );
		$owner_info = get_userdata( $task->user_info['owner_id'] );

		$recipients = array();
		$recipients[] = $owner_info->user_email;

		if ( ! empty( $task->user_info['affected_id'] ) ) {
			foreach ( $task->user_info['affected_id'] as $user_id ) {
				$user_info = get_userdata( $user_id );
				$recipients[] = $user_info->user_email;
			}
		}

		$subject = 'Task Manager: ';
		$subject .= __( 'The task #' . $task->id . ' ' . $task->title, 'task-manager' );

		$body = __( '<p>This mail has been send automaticly</p>', 'task-manager' );
		$body .= '<h2>#' . $task->id . ' ' . $task->title . ' send by ' . $sender_data->user_login . ' (' . $sender_data->user_email . ')</h2>';
		$body = apply_filters( 'task_points_mail', $body, $task );
		$body .= '<ul>';
		if ( ! empty( $task->parent_id ) ) {
			$body .= '<li><a href="' . admin_url( 'post.php?action=edit&post=' . $task->parent_id ) . '">Customer link</a></li>';
		}
		$body .= '<li><a href="' . admin_url( 'admin.php?page=wpeomtm-dashboard&term=' . $task->id ) . '">Task link</a></li>';
		$body .= '</ul>';

		$headers = array( 'Content-Type: text/html; charset=UTF-8' );
		$headers[] = 'From: ' . $blog_name . ' <' . $admin_email . '>';

		wp_mail( $recipients, $subject, $body, $headers );

		wp_send_json_success();
	}

	/**
	 * Charges les propriétés de la tâche et renvoie la vue à la réponse AJAX.
	 *
	 * @return void
	 *
	 * @since 1.0.0.0
	 * @version 1.3.6.0
	 */
	public function callback_load_task_properties() {
		check_ajax_referer( 'load_task_properties' );

		$task_id = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;

		$task = Task_Class::g()->get( array(
			'post__in' => array( $task_id ),
			'post_status' => array( 'publish', 'archive' ),
		), true );

		$task->author = Follower_Class::g()->get( array(
			'include' => array( $task->author_id ),
		), true );

		ob_start();
		\eoxia\View_Util::exec( 'task-manager', 'task', 'backend/properties', array(
			'task' => $task,
		) );

		wp_send_json_success( array(
			'view' => ob_get_clean(),
			'namespace' => 'taskManager',
			'module' => 'task',
			'callback_success' => 'loadedTaskProperties',
		) );
	}

	/**
	 * Recherche dans les posts types selon le term.
	 *
	 * @return void
	 *
	 * @since 1.0.0.0
	 * @version 1.4.0-ford
	 */
	public function callback_search_parent() {
		global $wpdb;
		$term = sanitize_text_field( $_GET['term'] );

		$posts_type = get_post_types();
		unset( $posts_type[ Task_Class::g()->get_post_type() ] );

		$posts_founded = array();
		$ids_founded = array();

		$query = "SELECT ID, post_title FROM {$wpdb->posts} WHERE ID LIKE '%" . $term . "%' AND post_type IN('" . implode( $posts_type, '\',\'' ) . "')";
		$results = $wpdb->get_results( $query );

		if ( ! empty( $results ) ) {
			foreach ( $results as $post ) {
				$posts_founded[] = array(
					'label' => '#' . $post->ID . ' - ' . $post->post_title,
					'value' => '#' . $post->ID . ' - ' . $post->post_title,
					'id' => $post->ID,
				);

				$ids_founded[] = $post->ID;
			}
		}

		$query = new \WP_Query( array(
			'post_type' => $posts_type,
			's' => $term,
			'post_status' => array( 'publish', 'draft' ),
		) );

		if ( ! empty( $query->posts ) ) {
			foreach ( $query->posts as $post ) {
				if ( ! in_array( $post->ID, $ids_founded, true ) ) {
					$posts_founded[] = array(
						'label' => '#' . $post->ID . ' - ' . $post->post_title,
						'value' => '#' . $post->ID . ' - ' . $post->post_title,
						'id' => $post->ID,
					);
				}
			}
		}

		wp_die( wp_json_encode( $posts_founded ) );
	}

	/**
	 * Déplaces la tâche vers la parent_id "to_element_id".
	 *
	 * @return void
	 *
	 * @since 1.0.0.0
	 * @version 1.3.6.0
	 */
	public function callback_move_task_to() {
		check_ajax_referer( 'move_task_to' );

		$task_id = ! empty( $_POST['task_id'] ) ? (int) $_POST['task_id'] : 0;
		$to_element_id = ! empty( $_POST['to_element_id'] ) ? (int) $_POST['to_element_id'] : 0;

		if ( empty( $task_id ) || empty( $to_element_id ) ) {
			wp_send_json_error();
		}

		$task = Task_Class::g()->get( array(
			'post__in' => array( $task_id ),
			'post_status' => array( 'publish', 'archive' ),
		), true );

		$task->parent_id = $to_element_id;

		Task_Class::g()->update( $task );

		wp_send_json_success( array(
			'namespace' => 'taskManager',
			'module' => 'task',
			'callback_success' => 'movedTaskTo',
		) );
	}

	/**
	 * Charges plus de tâche en appelant le shortcode task
	 *
	 * @return void
	 *
	 * @since 1.3.6.0
	 * @version 1.3.6.0
	 *
	 * @todo: nonce
	 */
	public function callback_load_more_task() {
		$offset = ! empty( $_POST['offset'] ) ? (int) $_POST['offset'] : 0;
		$posts_per_page = ! empty( $_POST['posts_per_page'] ) ? (int) $_POST['posts_per_page'] : 0;
		$post_parent = ! empty( $_POST['post_parent'] ) ? (int) $_POST['post_parent'] : 0;
		$term = ! empty( $_POST['term'] ) ? sanitize_text_field( $_POST['term'] ) : '';
		$users_id = ! empty( $_POST['users_id'] ) ? sanitize_text_field( $_POST['users_id'] ) : array();
		$status = ! empty( $_POST['status'] ) ? sanitize_text_field( $_POST['status'] ) : array();

		if ( ! empty( $users_id ) ) {
			$users_id = explode( ',', $users_id );
		}

		$categories_id = ! empty( $_POST['categories_id'] ) ? sanitize_text_field( $_POST['categories_id'] ) : array();

		if ( ! empty( $categories_id ) ) {
			$categories_id = explode( ',', $categories_id );
		}

		$param = array(
			'offset' => $offset,
			'posts_per_page' => $posts_per_page,
			'term' => $term,
			'users_id' => $users_id,
			'categories_id' => $categories_id,
			'status' => $status,
			'post_parent' => $post_parent,
		);

		ob_start();
		$tasks = Task_Class::g()->get_tasks( $param );

		Task_Class::g()->display_tasks( $tasks );

		wp_send_json_success( array(
			'view' => ob_get_clean(),
			'namespace' => 'taskManager',
			'module' => 'task',
			'callback_success' => 'loadedMoreTask',
			'can_load_more' => ! empty( $tasks ) ? true : false,
		) );
	}

	/**
	 * Exportes les points de la tâche dans un fichier .txt
	 *
	 * @return void
	 *
	 * @since 1.3.6.0
	 * @version 1.3.6.0
	 */
	public function callback_export_task() {
		check_ajax_referer( 'export_task' );

		$upload = wp_upload_dir();

		$task_id = ! empty( $_POST['id'] ) ? (int) $_POST['id'] : 0;

		if ( empty( $task_id ) ) {
			wp_send_json_error();
		}

		$task = Task_Class::g()->get( array(
			'post__in' => array( $task_id ),
			'post_status' => array( 'publish', 'archive' ),
		), true );

		$points_completed = array();
		$points_uncompleted = array();
		if ( ! empty( $task->task_info['order_point_id'] ) ) {
			$points = Point_Class::g()->get( array(
				'post_id' => $task->id,
				'orderby' => 'comment__in',
				'comment__in' => $task->task_info['order_point_id'],
				'status' => -34070,
			) );

			$points_completed = array_filter( $points, function( $point ) {
				return true === $point->point_info['completed'];
			} );

			$points_uncompleted = array_filter( $points, function( $point ) {
				return false === $point->point_info['completed'];
			} );
		}

		$file_name = $task->slug . current_time( 'timestamp' ) . '.txt';
		$file_info = array(
			'name' => $task->slug . current_time( 'timestamp' ) . '.txt',
			'path' => $upload['path'] . '/' . $file_name,
			'url' => $upload['url'] . '/' . $file_name,
			'content' => $task->id . ' - ' . $task->title . "\r\n\r\n",
		);

		$file_info['content'] .= __( 'Uncompleted', 'task-manager' ) . "\r\n";

		if ( ! empty( $points_uncompleted ) ) {
			foreach ( $points_uncompleted as $point ) {
				$file_info['content'] .= '	' . $point->id . ' - ' . trim( $point->content ) . "\r\n";
			}
		}

		$file_info['content'] .= __( 'Completed', 'task-manager' ) . "\r\n";

		if ( ! empty( $points_completed ) ) {
			foreach ( $points_completed as $point ) {
				$file_info['content'] .= '	' . $point->id . ' - ' . trim( $point->content ) . "\r\n";
			}
		}

		$fp = fopen( $file_info['path'], 'w' );
		fputs( $fp, $file_info['content'] );
		fclose( $fp );

		wp_send_json_success( array(
			'namespace' => 'taskManager',
			'module' => 'task',
			'callback_success' => 'exportedTask',
			'url' => $file_info['url'],
			'filename' => $file_info['name'],
		) );
	}
}

new Task_Action();
