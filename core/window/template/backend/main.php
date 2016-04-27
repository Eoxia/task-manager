<?php if ( !defined( 'ABSPATH' ) ) exit; ?>

<div class="wpeo-window-dashboard">
	<header>
		<div class="wpeo-window-background-avatar"></div>

		<h2><?php echo !empty( $element ) ? (!empty( $element->title ) ? $element->title : substr($element->content, 0, 100)) : ''; ?></h2>
		<i class="dashicons dashicons-no-alt"></i>
	</header>

	<div id="wpeo-task-sub-header" data-id="<?php echo !empty( $element->id ) ? $element->id : ''; ?>">
	<!-- Section sub header  -->
		<?php echo !empty( $element ) ? apply_filters( 'task_window_sub_header_' . $global, '', $element ) : ''; ?>
	</div>

	<!-- Section information -->
	<?php echo !empty( $element ) ? apply_filters( 'task_window_information_' . $global, '', $element ) : ''; ?>

	<!-- Section actions -->
	<?php echo !empty( $element ) ? apply_filters( 'task_window_action_' . $global, '', $element ) : ''; ?>

	<!-- Section ajouter -->
	<?php echo !empty( $element ) ? apply_filters( 'task_window_add_' . $global, '', $element ) : ''; ?>

	<!-- Footer -->
	<footer>
		<?php echo !empty( $element ) ? apply_filters( 'task_window_footer_' . $global, '', $element ) : ''; ?>
	</footer>
</div>
