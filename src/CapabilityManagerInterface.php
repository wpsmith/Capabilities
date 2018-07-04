<?php
/**
 * Capabilities Manager Interface
 *
 * The contents of this class is largely borrowed from WordPress SEO (WPSEO\Admin\Capabilities).
 *
 * You may copy, distribute and modify the software as long as you track changes/dates in source files.
 * Any modifications to or software including (via compiler) GPL-licensed code must also be made
 * available under the GPL along with build & install instructions.
 *
 * @package    WPS\Capabilities
 * @author     Travis Smith <t@wpsmith.net>
 * @copyright  2015-2018 Travis Smith
 * @license    http://opensource.org/licenses/gpl-2.0.php GNU Public License v2
 * @link       https://github.com/wpsmith/WPS
 * @version    1.0.0
 * @since      0.1.0
 */

namespace WPS\Capabilities;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WPS\Capabilities\CapabilityManagerInterface' ) ) {
	/**
	 * Capability Manager interface.
	 */
	interface CapabilityManagerInterface {
		/**
		 * Registers a capability.
		 *
		 * @param string $capability Capability to register.
		 * @param array  $roles      Roles to add the capability to.
		 * @param bool   $overwrite  Optional. Use add or overwrite as registration method.
		 */
		public function register( $capability, array $roles, $overwrite = false );

		/**
		 * Adds the registerd capabilities to the system.
		 */
		public function add();

		/**
		 * Removes the registered capabilities from the system.
		 */
		public function remove();

		/**
		 * Returns the list of registered capabilities.
		 *
		 * @return string[] List of registered capabilities.
		 */
		public function get_capabilities();
	}
}
