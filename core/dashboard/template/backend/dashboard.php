<?php if ( !defined( 'ABSPATH' ) ) exit; ?>

<div class="wrap wpeo-project-wrap">
	<input type="hidden" id="wpeo_user_id" value="<?php echo get_current_user_id(); ?>" />
	<!-- Le titre de la page, et également un filtre pour ajouter autant d'actions souhaitées. -->
	<div class="wpeo-project-dashboard">
		<h2><?php
			_e( 'Tasks Manager', 'task-manager' );
			echo apply_filters( 'task_manager_dashboard_title', '' );
		?></h2>


		<!-- Barre blanche des filtres -->
		<header class="wpeo-header-bar <?php echo is_page() ? 'wpeo-no-display' : ''; ?>">
			<ul>
				<?php echo apply_filters( 'task_manager_dashboard_filter', '' ); ?>
				<li class="wpeo-general-search">
					<label for="general-search">
						<i class="dashicons dashicons-search"></i>
						<input id="general-search" type="text" name="general-search" placeholder="<?php _e( 'Enter a keyword', 'task-manager' ); ?>" />
						<span class="open-search-filter dashicons dashicons-arrow-down"></span>
					</label>
				</li>
			</ul>
		</header>

		<!-- Barre noire recherche -->
		<?php
		$string_search_filter = apply_filters( 'task_manager_dashboard_search', '' );
		echo !empty( $string_search_filter ) ? '<div class="wpeo-header-search"><ul>' . $string_search_filter . '</ul></div>' : '';
		?>
	</div>

	<!-- Le contenu du dashboard -->
	<?php echo apply_filters( 'task_manager_dashboard_content', '', 0 ); ?>
</div>
