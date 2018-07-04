<?php
/**
 * Capabilities Manager WP Class
 *
 * Assists in the creation and management of Capabilities in WordPress.
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

if ( ! class_exists( 'WPS\Capabilities\CapabilityManagerWP' ) ) {
	/**
	 * Default WordPress capability manager implementation.
	 */
	final class CapabilityManagerWP extends AbstractCapabilityManager {
		/**
		 * Adds the capabilities to the roles.
		 *
		 * @return void
		 */
		public function add() {
			foreach ( $this->capabilities as $capability => $roles ) {
				if ( ! in_array( 'administrator', $roles, true ) ) {
					$roles[] = 'administrator';
				}
				$filtered_roles = $this->filter_roles( $capability, $roles );

				$wp_roles = $this->get_wp_roles( $filtered_roles );
				foreach ( $wp_roles as $wp_role ) {
					$wp_role->add_cap( $capability );
				}
			}
		}

		/**
		 * Unregisters the capabilities from the system.
		 *
		 * @return void
		 */
		public function remove() {
			// Remove from any roles it has been added to.
			$roles = wp_roles()->get_names();
			$roles = array_keys( $roles );

			foreach ( $this->capabilities as $capability => $_roles ) {
				$registered_roles = array_unique( array_merge( $roles, $this->capabilities[ $capability ] ) );

				// Allow filtering of roles.
				$filtered_roles = $this->filter_roles( $capability, $registered_roles );

				$wp_roles = $this->get_wp_roles( $filtered_roles );
				foreach ( $wp_roles as $wp_role ) {
					$wp_role->remove_cap( $capability );
				}
			}
		}
	}
}
