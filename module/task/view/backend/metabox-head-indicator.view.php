<?php
/**
 * La vue principale de la page des clients WPShop.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.0.0
 * @version 1.5.0
 * @copyright 2015-2017 Eoxia
 * @package core
 * @subpackage view
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<span class="tm_client_indicator_update">
	<span class="action-attribute button"
	id="tm_client_indicator_header_minus"
	data-action="update_indicator_client"
	data-parent="span"
	data-postid="<?= $post_id ?>"
	data-postauthor="<?= $post_author ?>"
	data-year="<?= $year - 1?>"
	data-parent-id="<?php echo esc_attr( $parent_id ); ?>"
	data-nonce="<?php echo esc_attr( wp_create_nonce( 'update_indicator_client' ) ); ?>">
		<span class="button-icon fa fa-minus" aria-hidden="true"></span>
	</span>

	<span class="action-attribute button"
	id="tm_client_indicator_header_actual"
	data-action="update_indicator_client"
	data-parent="span"
	data-postid="<?= $post_id ?>"
	data-postauthor="<?= $post_author ?>"
	data-year="<?= $year ?>"
	data-parent-id="<?php echo esc_attr( $parent_id ); ?>"
	data-nonce="<?php echo esc_attr( wp_create_nonce( 'update_indicator_client' ) ); ?>">
		<span id="tm_client_indicator_header_display"><?= $year ?></span>
	</span>

	<!-- <input type="hidden" > -->

	<span class="action-attribute button"
	id="tm_client_indicator_header_plus"
	data-action="update_indicator_client"
	data-parent="span"
	data-postid="<?= $post_id ?>"
	data-postauthor="<?= $post_author ?>"
	data-year="<?= $year + 1?>"
	data-parent-id="<?php echo esc_attr( $parent_id ); ?>"
	data-nonce="<?php echo esc_attr( wp_create_nonce( 'update_indicator_client' ) ); ?>">
		<span class="button-icon fa fa-plus" aria-hidden="true"></span>
	</span>
</span>
