<?php
/**
 * La vue lors que le boutton import TAG est cliqué (on affiche la liste des catégories)
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.10.0
 * @version 1.10.0
 * @copyright 2019 Eoxia
 * @package Task_Manager\Import
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>
<?php if( ! empty( $tags ) ): ?>
<select>
	<?php foreach( $tags as $key => $tag ): ?>
		<option value="<?php echo esc_attr( $tag->data[ 'name' ] ); ?>">
			<?php echo esc_attr( $tag->data[ 'name' ] ); ?>
		</option>
	<?php endforeach; ?>
</select>

<?php endif; ?>
