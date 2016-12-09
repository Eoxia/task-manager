<?php

if( !defined( 'ABSPATH' ) ) exit;

if( !class_exists( 'taskmanager_controller_01' ) ) {
	class taskmanager_controller_01 {

		public function __construct() {
			add_action( 'wp_head', array( $this, 'callback_wp_head' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'callback_admin_enqueue_scripts' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'callback_enqueue_scripts' ) );
			add_action( 'admin_print_scripts', array( $this, 'callback_admin_print_scripts' ) );
	 		add_action( 'wp_print_scripts', array( $this, 'callback_admin_print_scripts' ) );

			taskmanager\util\wpeo_util::install_in( 'module' );
		}

		public function callback_wp_head() {
			?>
			<script>var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>'</script>
			<?php
		}

		/**
		 * Inclus les javascripts et css nécessaire pour le fonctionnement de ce plugin.
		 *
		 * @return void
		 */
		public function callback_admin_enqueue_scripts() {
			if( WPEO_TASKMANAGER_DEBUG ) {
				wp_enqueue_script( 'wpeo-chosen-js', WPEO_TASKMANAGER_ASSET_URL . '/js/chosen.jquery.min.js', array( "jquery" ), WPEO_TASKMANAGER_VERSION );
				wp_enqueue_script( 'eoajax', WPEO_TASKMANAGER_ASSET_URL . '/js/eoajax.js', array( "jquery" ), WPEO_TASKMANAGER_VERSION );
				wp_enqueue_script( 'wpeo-task-backend-js', WPEO_TASKMANAGER_ASSET_URL . '/js/backend.js', array( "jquery", "jquery-form", "jquery-ui-datepicker", "jquery-ui-sortable", 'jquery-ui-autocomplete', 'suggest' ), WPEO_TASKMANAGER_VERSION );
			}
			else {
				wp_enqueue_script( 'wpeo-task-backend-js', WPEO_TASKMANAGER_ASSET_URL . '/js/backend.min.js', array( "jquery", "jquery-form", "jquery-ui-datepicker", "jquery-ui-sortable", 'jquery-ui-autocomplete', 'suggest' ), WPEO_TASKMANAGER_VERSION );
			}

			wp_register_style( 'wpeo-task-css', WPEO_TASKMANAGER_ASSET_URL . '/css/style.css', '', WPEO_TASKMANAGER_VERSION );
			wp_register_style( 'jquery-ui', '//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css', '', WPEO_TASKMANAGER_VERSION );

			wp_register_style( 'wpeo-chosen-css', WPEO_TASKMANAGER_ASSET_URL . '/css/chosen.min.css', '', WPEO_TASKMANAGER_VERSION );
			wp_enqueue_style( 'wpeo-task-css' );
			wp_enqueue_style( 'wpeo-chosen-css' );
			wp_enqueue_style( 'dashicons' );
			wp_enqueue_style( 'jquery-ui' );
		}

		/**
		 * Inclus le fichier frontend.css
		 *
		 * @return void
		 */
		public function callback_enqueue_scripts() {
			wp_enqueue_script( 'eoajax', WPEO_TASKMANAGER_ASSET_URL . '/js/eoajax.js', array( "jquery" ), WPEO_TASKMANAGER_VERSION );
			wp_enqueue_script( 'wpeo-task-frontend-js', WPEO_TASKMANAGER_ASSET_URL . '/js/frontend.js', array( "jquery", "jquery-form", "jquery-ui-datepicker" ), WPEO_TASKMANAGER_VERSION );

			wp_register_style( 'wpeo-task-frontend-css', WPEO_TASKMANAGER_ASSET_URL . '/css/frontend.css', '', WPEO_TASKMANAGER_VERSION );
			wp_enqueue_style( 'wpeo-task-frontend-css' );
		}

		/**
		 * Inclus le fichier language.js.php pour la traduction des texte en Javascript.
		 *
		 * @return void
		 */
		public function callback_admin_print_scripts() {
			require( wpeo_template_01::get_template_part( WPEO_TASKMANAGER_DIR, WPEO_TASKMANAGER_ASSETS_DIR, "js", "language.js") );
		}
  }

	global $taskmanager_controller;
	$taskmanager_controller = new taskmanager_controller_01();
}
?>
