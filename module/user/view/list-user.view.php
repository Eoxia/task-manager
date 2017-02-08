<?php
/**
 * For each user call the view user-gravatar
 *
 * @package module/user
 * @since 0.1
 * @version 1.3.6.0
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) { exit; } ?>

<ul>
<?php if ( ! empty( $users ) ) :
	foreach ( $users as $user ) :
		View_Util::exec( 'user', 'user-gravatar' );
	endforeach;
endif; ?>
</ul>
