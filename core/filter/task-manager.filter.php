<?php
/**
 * Les filtres principales de l'application.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.7.0
 * @version 1.8.0
 * @copyright 2018 Eoxia.
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Les filtres principales de l'application.
 */
class Task_Manager_Filter {

	/**
	 * Le constructeur ajoutes les filtres WordPress suivantes:
	 */
	public function __construct() {
		add_filter( 'heartbeat_send', array( $this, 'callback_heartbeat_send' ), 10, 2 );
		//
		// add_filter( 'eo_model_wpeo_point_after_put', array( $this, 'add_to_index' ), 10, 2 );
		// add_filter( 'eo_model_wpeo_point_after_post', array( $this, 'add_to_index' ), 10, 2 );
		// add_filter( 'eo_model_wpeo_time_after_put', array( $this, 'add_to_index' ), 10, 2 );
		// add_filter( 'eo_model_wpeo_time_after_post', array( $this, 'add_to_index' ), 10, 2 );
		// add_filter( 'eo_model_wpeo-task_after_put', array( $this, 'add_to_index' ), 10, 2 );
		// add_filter( 'eo_model_wpeo-task_after_post', array( $this, 'add_to_index' ), 10, 2 );.
	}

	/**
	 * Mise à jour des données en cache "Tâche", "Point", "Comment" de Task Manager.
	 * Cette méthode est accroché au "heartbeat" de WordPress.
	 *
	 * @since 1.8.0
	 * @version 1.8.0
	 *
	 * @param  Array   $response  Les données du Heartbeat.
	 * @param  integer $screen_id L'ID du screen.
	 *
	 * @return Array              Les données du Heartbeat ainsi que les données en cache de Task Manager.
	 */
	public function callback_heartbeat_send( $response, $screen_id ) {
		$notifications = get_posts( array(
			'post_type'    => 'wpeo-notification',
			'numberposts'  => 6,
			'post_status'  => 'publish',
			'author'       => get_current_user_id(),
			'meta_key'   => 'read',
			'meta_compare' => '!=',
			'meta_value'   => 1,
		) );

		ob_start();
		if ( ! empty( $notifications ) ) {
			foreach ( $notifications as &$notification ) {
				$notification = Notify_Class::g()->get_notification_data( $notification );
				\eoxia\View_Util::exec( 'task-manager', 'notify', 'backend/page/item', array(
					'entry' => $notification,
				) );
			}
		}
		?>
		<div class="notification-content wpeo-grid grid-2">
			<a href="<?php echo esc_attr( admin_url( 'admin.php?page=tm-notification' ) ); ?>">

				<div class="content">
					<div class="main-content">
						<p>
							<?php esc_html_e( 'See all notifications', 'task-manager' ); ?>
						</p>
					</div>
				</div>
			</a>

			<a href="#" class="action-attribute" data-action="tm_notification_all_read">
				Mark all as read
			</a>
		</div>
		<?php
		$notification_view = ob_get_clean();

		$response['task_manager_data']    = \eoxia\JSON_Util::g()->open_and_decode( PLUGIN_TASK_MANAGER_PATH . 'core/assets/json/data.json' );
		$response['number_notifications'] = count( $notifications ) > 5 ? '5+' : count( $notifications );
		$response['notification_view']    = $notification_view;

		return $response;
	}

	/**
	 * Ajoutes l'entrée d'une tâche, d'un point ou d'un commentaire lors de l'ajout ou de la modification de celui-ci.
	 *
	 * @since 1.8.0
	 * @version 1.8.0
	 *
	 * @param Task_Model|Point_Model|Task_Comment_Model $object Les données de l'objet.
	 * @param array                                     $args   Les données reçu d'un formulaire non traitée.
	 *
	 * @return Task_Model|Point_Model|Task_Comment_Model $object Les données de l'objet modifiée.
	 */
	public function add_to_index( $object, $args ) {
		$data = \eoxia\JSON_Util::g()->open_and_decode( PLUGIN_TASK_MANAGER_PATH . 'core/assets/json/data.json' );
		$id   = $object->data['id'];

		$prefix = '';

		$content = $object->data['content'];
		$type    = '';

		switch ( $object->data['type'] ) {
			case Task_Class::g()->get_type():
				$content = $object->data['title'];
				$prefix  = 'T';
				$type    = 'task';
				break;
			case Point_Class::g()->get_type():
				$prefix = 'P';
				$type   = 'point';
				break;
			case Task_Comment_Class::g()->get_type():
				$prefix = 'C';
				$type   = 'comment';
				break;
			default:
				break;
		}

		$id_index = $prefix . $object->data['id'];

		$data->list->$id_index = array(
			'id'      => $id,
			'type'    => $type,
			'content' => $content,
		);

		$data->last[] = array(
			'id_index' => $id_index,
			'id'       => $id,
			'type'     => $type,
			'content'  => $content,
		);

		if ( count( $data->last ) > 5 ) {
			array_shift( $data->last );
		}

		$data = json_encode( $data );
		$data = preg_replace_callback(
			'/\\\\u([0-9a-f]{4})/i',
			function ( $matches ) {
				$sym = mb_convert_encoding( pack( 'H*', $matches[1] ), 'UTF-8', 'UTF-16' );
				return $sym;
			},
			$data
		);

		$file = fopen( PLUGIN_TASK_MANAGER_PATH . 'core/assets/json/data.json', 'w+' );
		fwrite( $file, $data );
		fclose( $file );

		return $object;
	}
}

new Task_Manager_Filter();
