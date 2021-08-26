<?php

namespace TVE\Dashboard\Automator;

use function Thrive\Automator\tap_logger;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden!
}

/**
 * Class Tag_User
 */
class Add_User extends \Thrive\Automator\Items\Action {
	private $autoresponder;

	private $additional = array();

	/**
	 * Get the action identifier
	 *
	 * @return string
	 */
	public static function get_id() {
		return 'thrive/adduser';
	}

	/**
	 * Get the action name/label
	 *
	 * @return string
	 */
	public static function get_name() {
		return 'Add user in autoresponder';
	}

	/**
	 * Get the action description
	 *
	 * @return string
	 */
	public static function get_description() {
		return 'Add user to autoresponder';
	}

	/**
	 * Get the action logo
	 *
	 * @return string
	 */
	public static function get_image() {
		return 'tap-add-user';
	}

	/**
	 * Get the name of app to which action belongs
	 *
	 * @return string
	 */
	public static function get_app_name() {
		return 'Thrive Dashboard';
	}

	public static function get_required_data_objects() {
		return array( 'user_data', 'form_data' );
	}

	/**
	 * Array of action-field keys, required for the action to be setup
	 *
	 * @return array
	 */
	public static function get_required_action_fields() {
		return array( 'autoresponder' => true);
	}

	public function prepare_data( $data = array() ) {
		if ( ! empty( $data['extra_data'] ) ) {
			$data = $data['extra_data'];
		}

		$this->autoresponder = $data['autoresponder']['value'];

		foreach ( $data['autoresponder']['subfield'] as $key => $subfield ) {
			$this->additional[ $key ] = $subfield['value'];
		}
	}

	public function do_action( $data ) {
		$api_load = array();

		if ( ! empty( $data['form_data'] ) ) {
			$api_load ['email'] = $data['form_data']->get_value( 'email' );
		} else if ( ! empty( $data['user_data'] ) ) {
			$api_load ['email'] = $data['user_data']->get_value( 'user_email' );
		}
		if ( empty( $api_load ) ) {
			return false;
		}

		$apis = \Thrive_Dash_List_Manager::getAvailableAPIs( true );

		$api = $apis[ $this->autoresponder ];
		if ( empty( $api ) ) {
			return false;
		}

		if ( ! empty( $this->additional['tag_input'] ) && $api->hasTags() ) {
			$tags = $this->additional['tag_input'];
			if ( is_array( $tags ) ) {
				$tags = implode( ', ', $tags );
			}
			$api_load[ $api->getTagsKey() ] = $tags;
		}

		if ( ! empty( $this->additional['optin'] ) && $api->hasTags() ) {
			$api_load[ $api->getOptinKey() ] = $this->additional['optin'];
		}

		$list_identifier = ! empty( $this->additional['mailing_list'] ) ? $this->additional['mailing_list'] : null;

		return $api->addSubscriber( $list_identifier, $api_load );
	}


	public static function get_subfields( $field, $selected_value, $action_data ) {
		$api_instance = \Thrive_Dash_List_Manager::connectionInstance( $selected_value );
		$fields       = array();
		if ( $api_instance && $api_instance->isConnected() ) {
			$field_keys = $api_instance->get_automator_autoresponder_fields();

			$multiple_option_types = array( 'autocomplete', 'checkbox', 'select' );

			if ( ! empty( $field_keys ) ) {
				$available_fields = \Thrive\Automator\Items\Action_Field::get();
				foreach ( $field_keys as $subfield ) {
					$subfield_class = $available_fields[ $subfield ];
					$state_data     = $subfield_class::localize();

					if ( in_array( $subfield_class::get_type(), $multiple_option_types ) ) {
						$state_data['values'] = $subfield_class::get_options_callback( $selected_value );
					}
					$fields[ $state_data['id'] ] = $state_data;
				}
			}
		}

		return $fields;
	}

	/**
	 * Match all trigger that provice user/form data
	 *
	 * @param $trigger
	 *
	 * @return bool
	 */
	public static function is_compatible_with_trigger( $trigger ) {
		$action_data_keys = static::get_required_data_objects() ?: array();

		return count( array_intersect( $action_data_keys, $trigger::get_provided_data_objects() ) ) > 0;
	}

	public function can_run( $data ) {
		$valid          = true;
		$available_data = array();

		foreach ( static::get_required_data_objects() as $key ) {
			if ( ! empty( $data[ $key ] ) ) {
				$available_data[] = $key;
			}
		}

		if ( empty( $available_data ) ) {
			$valid = false;
			tap_logger()->register( [
				'key'         => static::get_id(),
				'id'          => 'data-not-provided-to-action',
				'message'     => 'Data object required by ' . static::class . ' action is not provided by trigger',
				'class-label' => tap_logger()->get_nice_class_name( static::class ),
			] );
		}

		return $valid;
	}

}
