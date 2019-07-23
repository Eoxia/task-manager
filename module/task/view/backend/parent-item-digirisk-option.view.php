<?php
/**
 * Vue des informations complémentaires lors qu'une tache possède un parent Digirisk Risque.
 *
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2006-2018 Eoxia <dev@eoxia.com>.
 *
 * @license   AGPLv3 <https://spdx.org/licenses/AGPL-3.0-or-later.html>
 *
 * @package   TaskManager\Templates
 *
 * @since     1.8.0
 */

namespace task_manager;

?>
<input type="hidden" name="evaluation_method_id" value="5" />
<textarea style="display: none;" name="evaluation_variables"><?php echo ! empty( $risk->data['evaluation']->data ) ? wp_json_encode( $risk->data['evaluation']->data['variables'], JSON_FORCE_OBJECT ) : '{}'; ?></textarea>
<div style="display : inline-flex">

	<div class="wpeo-dropdown dropdown-grid dropdown-padding-0 cotation-container wpeo-tooltip-event tm-use-class-from-digirisk"
		aria-label="<?php echo esc_attr( $risk->data['current_equivalence'] ); ?>"
		data-color="red"
		data-tooltip-persist="true">
		<span data-scale="<?php echo ! empty( $risk->data['evaluation'] ) ? esc_attr( $risk->data['evaluation']->data['scale'] ) : 0; ?>" class="dropdown-toggle cotation" style="padding-top: 15px;">
			<?php
			if ( 0 !== $risk->data['id'] && -1 !== $risk->data['current_equivalence'] ) :
				?>
				<span><?php echo esc_html( $risk->data['current_equivalence'] ); ?></span>
				<?php
			else :
				?>
				<span><i class="fas fa-chart-line"></i></span>
				<?php
			endif;
			?>
		</span>
	</div>

	<?php $risk_category = $risk->data[ 'risk_category' ]; ?>
	<div class="categorie-container toggle grid padding">
		<div class="action">
		<?php if ( isset( $risk_category ) && null !== $risk_category->data['id'] ) : ?>
			<div class="wpeo-tooltip-event hover" aria-label="<?php echo esc_attr( $risk_category->data['name'] ); ?>">
				<?php echo wp_get_attachment_image( $risk_category->data['thumbnail_id'], 'thumbnail', false ); ?>
			</div>
			<input class="input-hidden-danger" type="hidden" name="risk_category_id" value='<?php echo esc_attr( $risk_category->data['id'] ); ?>' />
		<?php else : ?>
			<div class="wpeo-button button-square-40 wpeo-tooltip-event button-disable button-event" data-direction="top" data-color="red" aria-label="<?php echo esc_attr_e( 'Corrompu', 'digirisk' ); ?>" >
				<i class="button-icon fas fa-times" aria-hidden="true"></i>
			</div>
		<?php endif; ?>
		</div>
	</div>

</div>
