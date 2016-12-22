<?php if ( !defined( 'ABSPATH' ) ) exit; ?>
<p>
	<?php esc_html_e( 'Vous pouvez utiliser des shortcodes pour afficher vos tâches dans vos articles/pages/etc', 'task-manager' ); ?><br/>
	<?php esc_html_e( 'Le shortcode nécessite le paramètre "id" qui permet de définir la tâche à afficher.', 'task-manager' ); ?><br/>
	<?php esc_html_e( 'Pour afficher la tâche 4 par exemple, il vous faudra insérer le code suivant', 'task-manager' ); ?> : [task id="4"]
</p>
