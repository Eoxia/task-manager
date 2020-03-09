<?php
/**
 * Le contenu la page "mon-compte" de WPShop.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.0.0
 * @version 1.2.0
 * @copyright 2015-2017 Eoxia
 * @package Task_Manager_WPShop
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div class="project-header">
	<span class="header-title"><i class="fas fa-hashtag"></i><?php echo esc_attr( $project->data['id'] ); ?> <?php echo esc_attr( $project->data['title'] ); ?></span>
	<span class="header-time"><i class="far fa-clock"></i><?php echo $project->data['time_info']['elapsed'] . '/' . $project->data['last_history_time']->data['estimated_time']; ?></span>
	<span class="header-tag"><i class="fas fa-tags"></i> <?php echo $project->readable_tag; ?></span>
</div>
