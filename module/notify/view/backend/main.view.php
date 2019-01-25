<?php
/**
 * Affichage de la popup pour gérer les notifications.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.5.0
 * @version 1.6.0
 * @copyright 2015-2018 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div>
	<h2>
		<?php esc_html_e( 'Teams', 'task-manager' ); ?>
		(<span class="selected-number"><?php echo esc_html( count( $affected_id ) ); ?></span>/<span class="total-number"><?php echo esc_html( count( $followers ) ); ?></span>)
	</h2>

	<ul class="list-follower wpeo-ul-users">
		<?php
		if ( ! empty( $followers ) ) :
			foreach ( $followers as $follower ) :
				?>
				<li class="follower <?php echo ( in_array( $follower->data['id'], $task->data['user_info']['affected_id'], true ) || $follower->data['id'] === $task->data['user_info']['owner_id'] ) ? 'active' : ''; ?>" data-id="<?php echo esc_attr( $follower->data['id'] ); ?>" style="width: 50px; height: 50px;">
					<?php echo do_shortcode( '[task_avatar ids=' . $follower->data['id'] . ']' ); ?>
				</li>
				<?php
			endforeach;
		endif;
		?>
		<input type="hidden" name="users_id" value="<?php echo esc_attr( implode( ',', $affected_id ) ); ?>" />
	</ul>
</div>

<?php
echo wp_kses(
	apply_filters( 'task_manager_popup_notify_after', '', $task ),
	array(
		'h2'    => array(),
		'h3'    => array(
			'style' => array(),
		),
		'div'   => array(
			'style'      => array(),
			'class'      => array(),
			'aria-label' => array(),
		),
		'p'     => array(
			'style' => array(),
		),
		'span'  => array(
			'style' => array(),
			'class' => array(),
		),
		'br'    => array(
			'style' => array(),
		),
		'ul'    => array(
			'class' => array(),
		),
		'li'    => array(
			'class'   => array(),
			'data-id' => array(),
			'style'   => array(),
		),
		'img'   => array(
			'class' => array(),
			'src'   => array(),
		),
		'input' => array(
			'type'  => array(),
			'name'  => array(),
			'value' => array(),
		),
	)
);
