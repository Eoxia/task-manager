<?php
/**
 * Gestion de la navigation
 *
 * @since 1.0.0
 * @version 1.6.0
 *
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Gestion de la navigation.
 */
class Shortcut_Class extends \eoxia\Singleton_Util {

	/**
	 * Le constructeur
	 *
	 * @return void
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	protected function construct() {}

	public function get_data_shortcut( $shortcut ){
		$shortcut['link'] = parse_url( $shortcut['link'] );
		parse_str( $shortcut['link']['query'], $query );

		$data                   = array();
		$query['term']          = ! empty( $query['term'] ) ? sanitize_text_field( $query['term'] ) : '';
		$query['task_id']       = ! empty( $query['task_id'] ) ? (int) $query['task_id'] : '';
		$query['point_id']      = ! empty( $query['point_id'] ) ? (int) $query['point_id'] : '';
		$query['post_parent']   = ! empty( $query['post_parent'] ) ? (int) $query['post_parent'] : 0;
		$query['categories_id'] = ! empty( $query['categories_id'] ) ? sanitize_text_field( $query['categories_id'] ) : '';
		$query['user_id']       = ! empty( $query['user_id'] ) ? (int) $query['user_id'] : '';

		$shortcut['info'] = Navigation_Class::g()->get_search_result( $query['term'], 'any', $query['task_id'], $query['point_id'], $query['post_parent'], $query['categories_id'], $query['user_id'] );

		return $shortcut;
	}

	public function get_shortcut_by_id( $id, $shortcuts ) {
		$found_def = null;

		foreach ( $shortcuts as $parent_id => $def ) {
			if ($def['id'] == $id) {
				$found_def = $def;
			}

			if (!empty($def['child']) && null == $found_def ) {
				$found_def = $this->get_shortcut_by_id($id, $def['child']);
			}
		}

		return $found_def;
	}

	public function first_level( $shortcuts, $current_shortcuts = array() ) {
		if ( ! empty( $shortcuts ) ) {
			foreach ( $shortcuts as $parent_id => $def ) {


				if ( ! empty( $def['child'] ) ) {
					$current_shortcuts = $this->first_level( $def['child'], $current_shortcuts );
				}

				unset( $def['child'] );
				$def['child'] = array();

				$current_shortcuts[] = $def;
			}
		}

		return $current_shortcuts;
	}

	public function get_last_id( $shortcuts ) {
		$shortcuts = $this->first_level( $shortcuts );
		$maxID = 0;

		if ( ! empty( $shortcuts ) ) {
			foreach ( $shortcuts as $key => $def ) {
				if ( $def['id'] > $maxID ) {
					$maxID = $def['id'];
				}
			}
		}

		return $maxID + 1;
	}

	public function update_name( $shortcuts, $id, $name ) {
		foreach ( $shortcuts as $parent_id => &$def ) {
			if ( $def['id'] == $id ) {
				$def['label'] = $name;
			}

			if ( ! empty( $def['child'] ) ) {
				$def['child'] = $this->update_name( $def['child'], $id, $name );
			}
		}

		return $shortcuts;
	}

	public function delete_id( $shortcuts, $id ) {
		foreach ( $shortcuts as $key => &$def ) {
			if ( $def['id'] == $id ) {
				unset( $shortcuts[ $key ] );
				continue;
			}

			if ( ! empty( $def['child'] ) ) {
				$def['child'] = $this->delete_id( $def['child'], $id );
			}
		}

		return $shortcuts;
	}

	public function get_key_by_id( $id, $shortcuts ) {
		$found_key = null;

		foreach ( $shortcuts as $key => $def ) {
			if ($def['id'] == $id) {
				$found_key = $key;
			}

			if (!empty($def['child']) && null == $found_key ) {
				$found_key = $this->get_shortcut_by_id($id, $def['child']);
			}
		}

		return $found_key;
	}
}

Shortcut_Class::g();
