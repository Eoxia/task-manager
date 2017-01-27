<?php
/**
 * Template pour l'affichage d'onglets dans le tableau de bord des tâches
 *
 * @package Task Manager
 * @subpackage Module/Tag
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<ul class="wpeo-tag-wrap action-attribute"
	data-action="load_tags" data-nonce="<?php echo esc_attr( wp_create_nonce( 'load_tags' ) ); ?>" data-id="<?php echo esc_attr( $object->id ); ?>"
	data-module="tag" data-before-method="before_load_tags" >
<?php
	ob_start();
	View_Util::exec( 'tag', 'backend/tag', array( 'list_tag' => $list_tag, 'object' => $object ) );
	$tags_display = ob_get_clean();
	echo $tags_display; // WPCS: XSS ok.
?>
<?php if ( empty( $tags_display ) ) : ?>
	<li class="wpeo-tag-add-tag" ><?php esc_html_e( 'Click here to add a tag', 'task-manager' ); ?></li>
<?php endif; ?>

</ul>
