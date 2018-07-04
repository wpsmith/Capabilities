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

if ( ! class_exists( 'WPS\Capabilities\Base' ) ) {
	/**
	 * Capability Utils collection.
	 */
	class Base extends \WPS\Core\Singleton {

		/**
		 * Prefix.
		 *
		 * @var string
		 */
		protected static $prefix = 'wps';

		/**
		 * Sets the prefix.
		 *
		 * @param string $prefix Prefix string.
		 */
		public static function set_prefix( $prefix ) {
			self::$prefix = str_replace( '-', '_', sanitize_title_with_dashes( $prefix ) );
		}

		/**
		 * Gets the prefix.
		 *
		 * @return string
		 */
		public static function get_prefix() {
			return rtrim( self::$prefix, '_' );
		}

		/**
		 * Set the object properties.
		 *
		 * @since 0.2.1
		 *
		 * @param string $property Property in object.  Must be set in object.
		 * @param mixed  $value    Value of property.
		 *
		 * @return self  Returns Self object, allows for chaining.
		 */
		public function set( $property, $value ) {

			if ( ! property_exists( $this, $property ) ) {
				return $this;
			}

			$this->$property = $value;

			return $this;
		}

		/**
		 * Magic getter for our object.
		 *
		 * @param  string $property Property in object to retrieve.
		 *
		 * @throws \Exception Throws an exception if the field is invalid.
		 *
		 * @return mixed     Property requested.
		 */
		public function __get( $property ) {

			if ( property_exists( $this, $property ) ) {
				return $this->{$property};
			}

			throw new \Exception( 'Invalid ' . __CLASS__ . ' property: ' . $property );
		}
	}
}
