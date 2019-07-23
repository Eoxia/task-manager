<?php
/**
 * La vue principale des tâches dans le backend.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.0.0.0
 * @version 1.3.6.0
 * @copyright 2015-2017 Eoxia
 * @package task
 * @subpackage view
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; }
	?>

		<div class="wpeo-tag" style="display : none">
			<ul style="background-color: #ececec; cursor: pointer; position: absolute; opacity: 1; z-index: 5; max-height: 300px; overflow-y: scroll; min-width: 300px; margin-top: 5px;">
					<?php foreach( $followers as $key => $follower ): ?>
						<li class="tm_list_administrator" data-id="<?php echo esc_html( $follower->data['id'] ); ?>" data-select="false"
							style="font-size: 15px; padding: 0.6em 2.6em; display: flex; flex-direction: row; justify-content: space-around;">
							<div>
								<?php echo do_shortcode( '[task_avatar ids="' . $follower->data['id'] . '" size="40"]' ); ?>
							</div>
							<div class="content-text">
								<?php echo esc_html( $follower->data['displayname'] ); ?>
							</div>
							<div class="tm-user-data">
								<input type="hidden" value="<?php echo esc_html( $follower->data['displayname'] . "#" . $follower->data['id'] ); ?>"/>
							</div>
						</li>
					<?php endforeach; ?>
					<!-- EVERYONE -->
					<li class="tm_list_administrator" data-select="false"
						style="font-size: 15px; padding: 0.6em 2.6em; display: flex; flex-direction: row; justify-content: space-around;">
						<div class="content-text">
							<?php echo 'everyone' ?>
						</div>
						<div class="tm-user-data">
							<input type="hidden" value="everyone"/>
						</div>
					</li>
					<!-- EVERYONE -->
			</ul>
		</div>
