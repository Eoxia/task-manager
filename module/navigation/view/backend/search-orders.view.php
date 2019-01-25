<?php
/**
 * Affichage du résultat des commandes trouvés lors de la recherche.
 *
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2015-2018 Eoxia <dev@eoxia.com>.
 *
 * @license   GPLv3 <https://spdx.org/licenses/GPL-3.0-or-later.html>
 *
 * @package   EO_Framework\EO_Search\Template
 *
 * @since     1.8.0
 */

namespace task_manager;

defined( 'ABSPATH' ) || exit;

if ( ! empty( $posts ) ) :
	foreach ( $posts as $post ) :
		?>
		<li data-id="<?php echo esc_attr( $post->ID ); ?>" data-result="<?php echo esc_html( $post->meta['tm_key'] ); ?>" class="autocomplete-result">
			<div class="autocomplete-result-container">
				<span class="autocomplete-result-title"><?php echo esc_html( $post->meta['tm_key'] ); ?></span>
			</div>
		</li>
		<?php
	endforeach;
else :
	?>
	<li class="autocomplete-result">
		<div class="autocomplete-result-container">
			<span class="autocomplete-result-title">Aucun résultat</span>
			<span class="autocomplete-result-subtitle">Avec le terme de recherche: <?php echo esc_html( $term ); ?></span>
	</li>
	<?php
endif;
