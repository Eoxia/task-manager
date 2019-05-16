<?php
/**
 * Affichage des stastiques des utilisateurs selon un lapse de temps préfédini
 *
 * @author Corentin-Eoxia <dev@eoxia.com>
 * @since 1.9.0
 * @version 1.10.0 - BETA
 * @copyright 2019 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
	<div class="wpeo-form" style="height: 20px">
		<form style="float:left">
			<span class="action-attribute button" data-action="update_indicator_stats" data-parent="span" data-month="<?php echo esc_attr( date( 'Y-m-01', $date[ 'start_str' ] - 2 ) ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'update_indicator_stats' ) ); ?>">
				<span class="button-icon fa fa-minus" aria-hidden="true"></span>
			</span>

			<span class="action-attribute button" data-action="update_indicator_stats" data-parent="span" data-month="<?php echo esc_attr( date( 'Y-m-01', $date[ 'start_str' ] ) ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'update_indicator_stats' ) ); ?>">
				<span id="tm_client_indicator_header_display"><?php echo esc_attr( $date[ 'value' ] ); ?></span>
			</span>

			<span class="action-attribute button" data-action="update_indicator_stats" data-parent="span" data-month="<?php echo esc_attr( date( 'Y-m-01', $date[ 'end_str' ] + 2 ) ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'update_indicator_stats' ) ); ?>">
				<span class="button-icon fa fa-plus" aria-hidden="true"></span>
			</span>
		</form>
		<span style="float:left">
			<h1>
				<?php echo esc_attr( $element ); ?>
			</h1>
		</span>
		<?php
		\eoxia\View_Util::exec(
			'task-manager',
			'indicator',
			'backend-stats/indicator-button',
			array(
				'date'  => $date,
				'element' => $element
			)
		);
		?>
	</div>

<br />


<div class="wpeo-tab">
	<ul class="tab-list">
		<?php foreach( $customers as $key => $customer ): ?>
			<li class="tab-element wpeo-tooltip-event" data-target="tab<?= $key ?>"
				aria-label="<?php echo esc_attr( $customer[ 'time_elapsed_readable' ] . '/ ' . $customer[ 'time_estimated_readable' ] . ' (' . $customer[ 'time_percent' ] . '%)' ); ?>"
				<?php if( $customer[ 'time_percent' ] > 100 ): ?>
					style="border: 2px solid red; border-top: none;"
				<?php endif; ?>>
				<span class="tab-icon">
						<i class="fas fa-<?php echo esc_attr( $customer[ 'icon' ] ); ?>"></i>
				</span>
				<span style="display:block">
					<?php echo esc_attr( $customer[ 'name' ] ); ?>
				</span>
				<span style="display:block; text-align: center">
					<?php echo esc_attr( '(' . $customer[ 'time_percent' ] . '%)' ); ?>
				</span>
			</li>
		<?php endforeach; ?>
	</ul>
	<div class="tab-container">
		<?php foreach( $customers as $key => $customer ):?>
			<div id="tab<?= $key ?>" class="tab-content" style="display : block">
				<div class="wpeo-grid grid-4">

					<div class="grid-1">
						<a href="<?php echo admin_url( 'post.php?post=' . $customer[ 'id'] . '&action=edit' ); ?>">
						<h2><?php echo esc_attr( '#' . $customer[ 'id' ] ) ?>
						<?php echo esc_attr( $customer[ 'name' ] . ' ('. $customer[ 'time_percent' ] .'%)') ?></h2></a>
						<p><?php esc_html_e( 'Time elapsed :', 'task-manager' ); ?>
						<?php echo esc_attr( $customer[ 'elapsed' ] . ' (' . $customer[ 'time_elapsed_readable' ] .')' ); ?></p>
						<p><?php esc_html_e( 'Time estimated :', 'task-manager' ); ?>
						<?php echo esc_attr( $customer[ 'estimated' ] . ' (' . $customer[ 'time_estimated_readable' ] .')' ); ?></p>
					</div>

					<div class="grid-2">
						<div class="wpeo-tab tab-vertical">
							<ul class="tab-list">
								<?php foreach( $customer[ 'categorie' ] as $key_categorie => $categorie ): ?>
									<li class="tab-element wpeo-tooltip-event" data-target="tab_<?php echo esc_attr( $key_categorie ); ?>"
										aria-label="<?php echo esc_attr( $categorie[ 'time_elapsed_readable' ] . '/ ' . $categorie[ 'time_estimated_readable' ] . ' (' . $categorie[ 'time_percent' ] . '%)' ); ?>">
										<span class="tab-icon">
											<i class="fas fa-<?php if( isset( $categorie[ 'icon' ] ) ): echo esc_attr( $categorie[ 'icon' ] ); endif; ?>"></i>
										</span>
										<?php echo esc_attr( $categorie[ 'info'] . '('. $categorie[ 'time_percent' ] .'%)' ); ?>
									</li>
								<?php endforeach; ?>
							</ul>
							<div class="tab-container">
								<?php foreach( $customer[ 'categorie' ] as $key_categorie => $categorie ): ?>
										<div id="tab_<?php echo esc_attr( $key_categorie ); ?>" class="tab-content">
											<h2><?php echo esc_attr( $categorie[ 'info' ] . ' ('. $categorie[ 'time_percent' ] .'%)') ?></h2>
											<p><?php esc_html_e( 'Time elapsed :', 'task-manager' ); ?>
											<?php echo esc_attr( $categorie[ 'elapsed' ] . ' (' . $categorie[ 'time_elapsed_readable' ] .')' ); ?></p>
											<p><?php esc_html_e( 'Time estimated :', 'task-manager' ); ?>
											<?php echo esc_attr( $categorie[ 'estimated' ] . ' (' . $categorie[ 'time_elapsed_readable' ] .')' ); ?></p>
										</div>
								<?php endforeach; ?>
							</div>
						</div>
					</div>
					<div class="grid-1">
						<div class="wpeo-button button-blue action-input"
						data-id="<?php echo esc_attr( $customer[ 'id' ] ); ?>"
						data-action="load_stats_indicator"
						data-nonce="<?php echo esc_attr( wp_create_nonce( 'load_stats_indicator' ) ); ?>">
							<span><?php esc_html_e( 'Generate Stats', 'task-manager' ); ?></span>
						</div>
					</div>

				</div>

				<div class="tm_indicator_stats">

				</div>
			</div>
		<?php endforeach; ?>
	</div>
</div>
