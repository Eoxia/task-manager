<?php
/**
 * Le contenu de la popup contenant les derniers commentaires des clients WPShop.
 *
 * @author Jimmy Latour <jimmy@eoxia.com>
 * @since 1.0.1.0
 * @version 1.0.1.0
 * @copyright 2017 Eoxia
 * @package Task Manager Ticket
 * @subpackage template
 */

if ( ! defined( 'ABSPATH' ) ) { exit; } ?>

<table>
	<tr>
		<th style="width: 10%">ID</th>
		<th style="width: 20%">Date</th>
		<th style="width: 20%">Email</th>
		<th>Point contenu</th>
		<th>Contenu</th>
	</tr>

	<?php if ( ! empty( $comments ) ) :
		foreach ( $comments as $comment ) :
			?>
			<tr>
				<td><?php echo esc_html( $comment->comment_ID ); ?></td>
				<td><?php echo esc_html( $comment->comment_date ); ?></td>
				<td>
					<a target="_blank" href="<?php echo esc_attr( admin_url( 'post.php?post=' . $comment->post_parent . '&action=edit' ) ); ?>">
						<?php echo esc_html( $comment->user_email ); ?>
					</a>
				</td>
				<td><?php echo nl2br( esc_html( $comment->point_content ) ); ?></td>
				<td><?php echo nl2br( esc_html( $comment->comment_content ) ); ?></td>
		<?php endforeach;
	endif; ?>
</ul>
