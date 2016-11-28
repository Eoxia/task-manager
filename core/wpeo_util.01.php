<?php
namespace taskmanager\util;

if ( !defined( 'ABSPATH' ) ) exit;

class wpeo_util {
	public static $array_exclude_module = array();

	/**
	 * CORE - Install all extra-modules in "Core/Module" folder
	 */
	public static function install_in( $folder ) {
		/** Define the directory containing all exrta-modules for current plugin    */
		$module_folder = WPEO_TASKMANAGER_PATH . $folder . '/';

		/**  Check if the defined directory exists for reading and including the different modules   */
		if ( is_dir( $module_folder ) ) {
			$parent_folder_content = scandir( $module_folder );
			foreach ( $parent_folder_content as $folder ) {
				if ( $folder && substr( $folder, 0, 1) != '.' && $folder != 'index.php' && !in_array( $folder, self::$array_exclude_module ) ) {
					// if ( is_dir( $module_folder . $folder ) ) {
					// 	$child_folder_content = scandir( $module_folder . $folder );
					// }

					if ( file_exists( $module_folder . $folder . '/' . $folder . '.php' ) ) {
						$f = $module_folder . $folder . '/' . $folder . '.php';
						include( $f );
					}
				}
			}
		}
	}

	public static function install_module( $path_to_module ) {
		$module_name = $path_to_module . '.php';
		$path_to_module = WPEO_TASKMANAGER_PATH . 'core/' . $path_to_module;


		if( file_exists( $path_to_module . '/' . $module_name ) ) {
			include(  $path_to_module . '/' . $module_name );
		}
	}

	/**
	 * Check whether a plugin is active.
	 *
	 * Only plugins installed in the plugins/ folder can be active.
	 *
	 * Plugins in the mu-plugins/ folder can't be "activated," so this function will
	 * return false for those plugins.
	 *
	 * @since 2.5.0
	 *
	 * @param string $plugin Base plugin path from plugins directory.
	 * @return bool True, if in the active plugins list. False, not in the list.
	 */
	public static function is_plugin_active( $plugin ) {
		return in_array( $plugin, (array) get_option( 'active_plugins', array() ) );
	}

	public static function convert_to_hours_minut( $time, $format = '%02d:%02d' ) {
		if ( $time < 1 ) {
			return '00:00';
		}
		$hours = floor( $time / 60 );
		$minutes = ( $time % 60 );
		return sprintf( $format, $hours, $minutes );
	}

	public static function convert_to_minut( $time ) {
		$time = explode( ':', $time );

		if( count( $time ) != 2 )
			return 0;

		$final_time = $time[0] * 60;
		$final_time+= $time[1];

		return $final_time;
	}
}
