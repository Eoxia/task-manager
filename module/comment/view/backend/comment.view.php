<?php
/**
 * Un commentaire dans le backend.
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
	<ul>
		<li><?php echo esc_html( $comment->date ); ?></li>
		<li>Contetn</li>
		<li>Temps</li>
		<li>Actions</li>
	</ul>
</li>
