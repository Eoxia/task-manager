<?php
/**
 * Parcours toutes les audits et appel la vue "audit"
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.9.0
 * @version 1.9.0
 * @copyright 2019 Eoxia
 * @package task-manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<?php
if ( ! empty( $audits ) ) :
	foreach ( $audits as $audit ) :
		\eoxia\View_Util::exec(
			'task-manager',
			'audit',
			'audit-page/metabox-audit',
			array(
				'audit' => $audit,
			)
		);
	endforeach;
endif;
