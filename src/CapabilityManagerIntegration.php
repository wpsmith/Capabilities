<?php
/**
 * Capabilities Manager Integration Class
 *
 * Assists in the integration of custom Capabilities into Members and
 * User Role Editor plugins.
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

if ( ! class_exists( __NAMESPACE__ . '\CapabilityManagerIntegration' ) ) {
	/**
	 * Integrates Yoast SEO capabilities with third party role manager plugins.
	 *
	 * Integrates with: Members
	 * Integrates with: User Role Editor
	 */
	class CapabilityManagerIntegration extends Base {

		/**
		 * @var CapabilityManagerInterface Capability manager to use.
		 */
		public $manager;

		/**
		 * Label for Group
		 *
		 * @var string
		 */
		protected $label;

		/**
		 * Slug for Group
		 *
		 * @var string
		 */
		protected $slug;

		/**
		 * Icon for Group.
		 *
		 * @var string
		 */
		protected $icon_class;

		/**
		 * CapabilityManagerIntegration constructor.
		 *
		 * @param CapabilityManagerInterface $manager    The capability manager to use.
		 * @param string                     $label      Label of Group.
		 * @param string                     $slug       Slug of Group.
		 * @param string                     $icon_class Icon for Group.
		 */
		protected function __construct( CapabilityManagerInterface $manager, $label = 'Plugin', $slug = 'plugin', $icon_class = 'dashicons-admin-plugins' ) {
			$this->manager    = $manager;
			$this->label      = $label;
			$this->slug       = sanitize_title_with_dashes( $slug );
			$this->icon_class = $icon_class;
		}

		/**
		 * Registers the hooks.
		 *
		 * @return void
		 */
		public function register_hooks() {
			add_filter( 'members_get_capabilities', array( $this, 'get_capabilities' ) );
			add_action( 'members_register_cap_groups', array( $this, 'action_members_register_cap_group' ) );

			add_filter( 'ure_capabilities_groups_tree', array( $this, 'filter_ure_capabilities_groups_tree' ) );
			add_filter( 'ure_custom_capability_groups', array( $this, 'filter_ure_custom_capability_groups' ), 10, 2 );
		}

		/**
		 * Get the Yoast SEO capabilities.
		 * Optionally append them to an existing array.
		 *
		 * @param  array $caps Optional existing capability list.
		 *
		 * @return array
		 */
		public function get_capabilities( array $caps = array() ) {
			if ( ! did_action( self::get_prefix() . '_register_capabilities' ) ) {
				do_action( self::get_prefix() . '_register_capabilities' );
			}

			return array_merge( $caps, $this->manager->get_capabilities() );
		}

		/**
		 * Add capabilities to its own group in the Members plugin.
		 *
		 * @see  members_register_cap_group()
		 */
		public function action_members_register_cap_group() {
			if ( ! function_exists( 'members_register_cap_group' ) ) {
				return;
			}
			// Register the yoast group.
			members_register_cap_group( $this->slug,
				array(
					'label'      => esc_html( $this->label ),
					'caps'       => $this->get_capabilities(),
					'icon'       => sanitize_html_class( $this->icon_class ),
					'diff_added' => true,
				)
			);
		}

		/**
		 * Adds Yoast SEO capability group in the User Role Editor plugin.
		 *
		 * @see    URE_Capabilities_Groups_Manager::get_groups_tree()
		 *
		 * @param  array $groups Current groups.
		 *
		 * @return array Filtered list of capabilty groups.
		 */
		public function filter_ure_capabilities_groups_tree( $groups = array() ) {
			$groups = (array) $groups;

			$groups[ $this->slug ] = array(
				'caption' => esc_html( $this->label ),
				'parent'  => 'custom',
				'level'   => 3,
			);

			return $groups;
		}

		/**
		 * Adds capabilities to the Yoast SEO group in the User Role Editor plugin.
		 *
		 * @see    URE_Capabilities_Groups_Manager::get_cap_groups()
		 *
		 * @param  array  $groups Current capability groups.
		 * @param  string $cap_id Capability identifier.
		 *
		 * @return array List of filtered groups.
		 */
		public function filter_ure_custom_capability_groups( $groups = array(), $cap_id = '' ) {
			if ( in_array( $cap_id, $this->get_capabilities(), true ) ) {
				$groups   = (array) $groups;
				$groups[] = $this->slug;
			}

			return $groups;
		}
	}
}
