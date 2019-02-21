<?php
/**
 * Capabilities Manager Factory Class
 *
 * Assists in the creation and management of Capabilities. This is the main class
 * one would use for the creation and management of custom capabilities.
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

if ( ! class_exists( __NAMESPACE__ . '\CapabilityManager' ) ) {
	/**
	 * Capability Manager Factory.
	 */
	class CapabilityManager extends Base {

		/**
		 * CapabilityManagerInterface constructor.
		 */
		protected function __construct() {
			self::get();
		}

		/**
		 * Returns the Manager to use.
		 *
		 * @return CapabilityManager Manager to use.
		 */
		public static function get() {
			static $manager = null;

			if ( null === $manager ) {
				if ( function_exists( 'wpcom_vip_add_role_caps' ) ) {
					$manager = CapabilityManagerVIP::get_instance();
				} else {
					$manager = CapabilityManagerWP::get_instance();
				}
			}

			return $manager;
		}
	}
}
