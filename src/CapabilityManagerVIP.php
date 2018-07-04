<?php
/**
 * Capabilities Manager VIP Class
 *
 * Assists in the creation and management of Capabilities in WP VIP.
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

if ( ! class_exists( 'WPS\Capabilities\CapabilityManagerVIP' ) ) {
	/**
	 * VIP implementation of the Capability Manager.
	 */
	final class CapabilityManagerVIP extends AbstractCapabilityManager {
		/**
		 * Adds the registered capabilities to the system.
		 *
		 * @return void
		 */
		public function add() {
			$role_capabilities = array();
			foreach ( $this->capabilities as $capability => $roles ) {
				$role_capabilities = $this->get_role_capabilities( $role_capabilities, $capability, $roles );
			}

			foreach ( $role_capabilities as $role => $capabilities ) {
				wpcom_vip_add_role_caps( $role, $capabilities );
			}
		}

		/**
		 * Removes the registered capabilities from the system
		 *
		 * @return void
		 */
		public function remove() {
			// Remove from any role it has been added to.
			$roles = wp_roles()->get_names();
			$roles = array_keys( $roles );

			$role_capabilities = array();
			foreach ( array_keys( $this->capabilities ) as $capability ) {
				// Allow filtering of roles.
				$role_capabilities = $this->get_role_capabilities( $role_capabilities, $capability, $roles );
			}

			foreach ( $role_capabilities as $role => $capabilities ) {
				wpcom_vip_remove_role_caps( $role, $capabilities );
			}
		}

		/**
		 * Returns the roles which the capability is registered on.
		 *
		 * @param array  $role_capabilities List of all roles with their capabilities.
		 * @param string $capability        Capability to filter roles for.
		 * @param array  $roles             List of default roles.
		 *
		 * @return array List of capabilities.
		 */
		protected function get_role_capabilities( $role_capabilities, $capability, $roles ) {
			// Allow filtering of roles.
			$filtered_roles = $this->filter_roles( $capability, $roles );

			foreach ( $filtered_roles as $role ) {
				if ( ! isset( $add_role_caps[ $role ] ) ) {
					$role_capabilities[ $role ] = array();
				}

				$role_capabilities[ $role ][] = $capability;
			}

			return $role_capabilities;
		}
	}
}
