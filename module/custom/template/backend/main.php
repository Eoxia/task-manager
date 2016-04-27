<?php if ( !defined( 'ABSPATH' ) ) exit; ?>

<div class="wrap wpeo-project-wrap">
  <form action="<?php echo admin_url( 'admin-ajax.php' ); ?>" method="POST"></form>
	<input type="hidden" id="wpeo_user_id" value="<?php echo get_current_user_id(); ?>" />
  <input type="hidden" class="wpeo-task-post-parent" value="<?php echo $post->ID; ?>" />
	<!-- Le titre de la page, et également un filtre pour ajouter autant d'actions souhaitées. -->
	<div class="wpeo-project-dashboard">
		<h2><?php
			_e( 'Tasks Manager', 'task-manager' );
			echo apply_filters( 'task_manager_dashboard_title', '' );
		?></h2>
	</div>

	<!-- Le contenu du dashboard -->
	<?php echo apply_filters( 'task_manager_dashboard_content', '', $post->ID ); ?>
</div>
