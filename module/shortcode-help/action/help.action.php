<?php
/**
 * Fichier permettant de gérer l'affichage de l'aide pour les shortcodes d'affichage des tâches
 *
 * @package Task Manager
 * @subpackage Module/Shortcode-Help
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Classe de gestion de l'affichage de l'aide pour les shortcodes d'affichage des tâches
 */
class Task_Help_Action {

	/**
	 * Instanciation de l'afficahge de l'aide d'utilisation des shortcodes
	 */
	public function __construct() {
		/** Instanciation des éléments dans le WYSIWYG */
		add_action( 'admin_init', array( Task_Help_Filter::g(), 'callback_wysiwyg_init' ) );

		/** Fonction de callback pour la récupération de la liste des tâches pour insertion dans le WYSIWYG */
		add_action( 'wp_ajax_wysiwyg_get_list_task', array( $this, 'ajax_wysiwyg_get_list_task' ) );

		/** Ajout d'un onglet d'aide sur le tableau de bord pour l'utilisation d'un shortcode pour l'affichage  */
		add_action( 'load-toplevel_page_wpeomtm-dashboard', array( $this, 'taskmanager_contextual_hekp' ) );

		/**
		 * On ajoute les traductions des différents texte pour les boutons du WYSIWYG
		 *
		 * @see https://codex.wordpress.org/Plugin_API/Filter_Reference/mce_external_plugins
		 */
		foreach ( array( 'post.php', 'post-new.php' ) as $hook ) {
			add_action( "admin_head-$hook", array( $this, 'callback_task_button_i18n' ) );
		}
	}

	/**
	 * Affichage de l'aide dans le tableau de bord des tâches
	 */
	public function taskmanager_contextual_hekp() {
		$screen = get_current_screen();

		/** Add my_help_tab if current screen is My Admin Page */
		ob_start();
		View_Util::exec( 'shortcode-help', 'backend/contextual-help' );
		$shortcode_help_tab_content = ob_get_clean();
		$screen->add_help_tab( array(
			'id'	=> 'taskmanager-help-tab-shortcode',
			'title'	=> __( 'Shortcode', 'task-manager' ),
			'content'	=> $shortcode_help_tab_content,
		) );
	}

	/**
	 * Fonction de callback pour l'affichage de la liste des tâches pour insertion d'un shortcode
	 */
	public function ajax_wysiwyg_get_list_task() {
		$list_task = Task_Class::g()->get( array( 'post_parent' => 0 ) );

		$list_task_json = array(
	  	'type' => 'listbox',
	  	'name' => 'task_id',
	  	'label' => __( 'Task', 'task-manager' ),
	  	'values' => array(),
		);

		if ( ! empty( $list_task ) ) {
			foreach ( $list_task as $element ) {
				$list_task_json['values'][] = array(
		  		'text' => '#' . $element->id . ' - ' . $element->title,
			  	'value' => $element->id,
				);
			}
		}

		wp_send_json_success( array( 'list_task' => $list_task_json ) );
	}

	/**
	 * Fonction de callback permettant de gérer les traductions des boutons et actions dans le WYSIWYG pour les tâches
	 */
	public function callback_task_button_i18n() {
?>
<!-- TinyMCE Task Shortcode Plugin language -->
<script type='text/javascript'>
	var taskManagerWysiwygButton = {
	  'button_title': '<?php esc_html_e( 'Task shortcode', 'task-manager' ); ?>',
	  'window_title': '<?php esc_html_e( 'Sélectionnez une tâche', 'task-manager' ); ?>',
	};
</script>
<!--  / TinyMCE Task Shortcode Plugin language -->
<?php
	}

}

new Task_Help_Action();
