<?php
/**
 * Capabilities Manager Utilities Class
 *
 * Assists in the creation and management of Capabilities.
 * The contents of this class is largely borrowed from WordPress SEO (WPSEO\Admin\Capabilities).
 *
 * You may copy, distribute and modify the software as long as you track changes/dates in source files.
 * Any modifications to or software including (via compiler) GPL-licensed code must also be made
 * available under the GPL along with build & install instructions.
 *
 * @package    WPS\WP\Capabilities
 * @author     Travis Smith <t@wpsmith.net>
 * @copyright  2015-2019 Travis Smith
 * @license    http://opensource.org/licenses/gpl-2.0.php GNU Public License v2
 * @link       https://github.com/wpsmith/WPS
 * @version    1.0.0
 * @since      0.1.0
 */

namespace WPS\WP\Capabilities;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( __NAMESPACE__ . '\Utils' ) ) {
	/**
	 * Capability Utils collection.
	 */
	class Utils extends Base {

		/**
		 * Checks if the user has the proper capabilities.
		 *
		 * @param string $capability Capability to check.
		 *
		 * @return bool True if the user has the proper rights.
		 */
		public static function current_user_can( $capability ) {
			if ( $capability === self::get_prefix() . '_manage_options' ) {
				return self::has( $capability );
			}

			return self::has_any( array( self::get_prefix() . '_manage_options', $capability ) );
		}

		/**
		 * Checks if the current user has at least one of the supplied capabilities.
		 *
		 * @param array $capabilities Capabilities to check against.
		 *
		 * @return bool True if the user has at least one capability.
		 */
		protected static function has_any( array $capabilities ) {
			foreach ( $capabilities as $capability ) {
				if ( self::has( $capability ) ) {
					return true;
				}
			}

			return false;
		}

		/**
		 * Checks if the user has a certain capability.
		 *
		 * @param string $capability Capability to check against.
		 *
		 * @return bool True if the user has the capability.
		 */
		protected static function has( $capability ) {
			return current_user_can( $capability );
		}
	}
}
