<?php
/**
 * Affichage d'un temps rapide.
 *
 * @author Jimmy Latour <jimmy.eoxia@gmail.com>
 * @since 1.6.0
 * @version 1.6.0
 * @copyright 2015-2017 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<ul class="item">
	<li class="task">
		<input type="hidden" name="comments[<?php echo esc_attr( $i ); ?>][task_id]" value="<?php echo esc_attr( $quicktime['displayed']['task']->id ); ?>" />
		<?php echo esc_html( '#' . $quicktime['displayed']['task']->id . ' ' . $quicktime['displayed']['task']->title ); ?>
	</li>
	<li class="point wpeo-tooltip-event"
		aria-label="<?php echo esc_attr( '#' . $quicktime['displayed']['point']->id . ' ' . $quicktime['displayed']['point']->content ); ?>">
		<input type="hidden" name="comments[<?php echo esc_attr( $i ); ?>][point_id]" value="<?php echo esc_attr( $quicktime['displayed']['point']->id ); ?>" />
		<?php echo esc_html( $quicktime['displayed']['point_fake_content'] ); ?>
	</li>
	<li class="content">
		<textarea name="comments[<?php echo esc_attr( $i ); ?>][content]"><?php echo $quicktime['content']; ?></textarea>
	</li>
	<li class="min">
		<i class="fa fa-clock-o" aria-hidden="true"></i>
		<input type="hidden" class="time" name="comments[<?php echo esc_attr( $i ); ?>][time]" />
		<input type="text" class="displayed" />
	<li>
	<li class="actions"><input type="checkbox" class="set_time" /></li>
</ul>
