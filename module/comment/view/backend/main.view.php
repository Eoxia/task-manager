<?php
/**
 * La vue principale des commentaires dans le backend.
 *
 * @author Jimmy Latour <jimmy.eoxia@gmail.com>
 * @since 1.0.0.0
 * @version 1.3.6.0
 * @copyright 2015-2017 Eoxia
 * @package comment
 * @subpackage view
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) { exit; } ?>

<li>
	<form action="<?php echo esc_attr( admin_url( 'admin-ajax.php' ) ); ?>" method="POST">
		<ul>
			<?php wp_nonce_field( 'add_comment' ); ?>
			<input type="hidden" name="post_id" value="<?php echo esc_attr( $task_id ); ?>" />
			<input type="hidden" name="parent_id" value="<?php echo esc_attr( $point_id ); ?>" />
			<input type="hidden" name="action" value="add_comment" />

			<li><input type="text" name="date" value="<?php echo esc_attr( current_time( 'mysql' ) ); ?>" /></li>
			<li><input type="text" name="content" /></li>
			<li><input type="text" name="time" /></li>
			<li class="submit-form">add</li>
		</ul>
	</form>
</li>

<?php
View_Util::exec( 'comment', 'backend/list-comment', array(
	'comments' => $comments,
) );
