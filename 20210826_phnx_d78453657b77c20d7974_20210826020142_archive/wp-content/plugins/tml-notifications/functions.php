<?php

/**
 * Theme My Login Notifications Functions
 *
 * @package Theme_My_Login_Notifications
 * @subpackage Functions
 */

/**
 * Get the Notifications plugin instance.
 *
 * @since 1.0
 *
 * @return Theme_My_Login_Notifications The Notifications plugin instance.
 */
function tml_notifications() {
	return theme_my_login()->get_extension( 'tml-notifications' );
}

/**
 * Register the default notification triggers.
 *
 * @since 1.0
 */
function tml_notifications_register_default_triggers() {
	tml_notifications_register_trigger( 'new_user_registered', array(
		'label' => __( 'New User Registered', 'tml-notifications' ),
		'hook' => 'register_new_user',
		'group' => __( 'Core', 'tml-notifications' ),
	) );

	tml_notifications_register_trigger( 'new_user_created', array(
		'label' => __( 'New User Created', 'tml-notficiations' ),
		'hook' => 'edit_user_created_user',
		'group' => __( 'Core', 'tml-notifications' ),
	) );

	tml_notifications_register_trigger( 'lost_password_request', array(
		'label' => __( 'Lost Password Request', 'tml-notifications' ),
		'hook' => 'retrieved_password_key',
		'args' => array( 'user', 'key' ),
		'group' => __( 'Core', 'tml-notifications' ),
	) );

	tml_notifications_register_trigger( 'password_changed', array(
		'label' => __( 'Password Changed', 'tml-notifications' ),
		'hook' => 'after_password_reset',
		'group' => __( 'Core', 'tml-notifications' ),
	) );
}

/**
 * Register a notification trigger.
 *
 * @since 1.0
 *
 * @param string $trigger The trigger name.
 * @param array  $args {
 *     Optional. An array of arguments for registering a notification trigger.
 * }
 * @return array The notification trigger data.
 */
function tml_notifications_register_trigger( $trigger, $args = array() ) {
	return tml_notifications()->register_trigger( $trigger, $args );
}

/**
 * Get a notification trigger.
 *
 * @since 1.0
 *
 * @param string $trigger The trigger name.
 * @return array|bool The trigger data if it exists or false otherwise.
 */
function tml_notifications_get_trigger( $trigger ) {
	return tml_notifications()->get_trigger( $trigger );
}

/**
 * Get the registered notification triggers.
 *
 * @since 1.0
 *
 * @return array The registered notification triggers.
 */
function tml_notifications_get_triggers() {
	return tml_notifications()->get_triggers();
}

/**
 * Get the registered notification trigger hooks.
 *
 * @since 1.0
 *
 * @return array The registered notification trigger hooks.
 */
function tml_notifications_get_trigger_hooks() {
	$hooks = array();
	foreach ( tml_notifications_get_triggers() as $trigger ) {
		$hooks[ $trigger['hook'] ] = $trigger['name'];
	}
	return $hooks;
}

/**
 * Get the registered notification trigger groups.
 *
 * @since 1.0
 *
 * @return array The registered notification trigger groups.
 */
function tml_notifications_get_trigger_groups() {
	$groups = array();
	foreach ( tml_notifications_get_triggers() as $trigger ) {
		$groups[ $trigger['group'] ][] = $trigger;
	}
	return $groups;
}

/**
 * Get the default notifications.
 *
 * @since 1.1
 *
 * @return array The default notifications.
 */
function tml_notifications_get_default_notifications() {
	$saved_notifications = (array) get_site_option( 'tml_notifications_default_notifications', array() );

	$default_notifications = array(
		'wp_new_user_notification_email' => array(
			'title' => __( 'New User Notification', 'tml-notifications' ),
			'hidden_fields' => array( 'recipient' ),
		),
		'wp_new_user_notification_email_admin' => array(
			'title' => __( 'New User Admin Notification', 'tml-notifications' ),
			'hidden_fields' => array(),
		),
		'tml_retrieve_password_email' => array(
			'title' => __( 'Retrieve Password Notification', 'tml-notifications' ),
			'hidden_fields' => array( 'recipient', 'disable' ),
		),
		'password_change_email' => array(
			'title' => __( 'Password Change Notification', 'tml-notifications' ),
			'hidden_fields' => array( 'recipient' ),
		),
		'wp_password_change_notification_email' => array(
			'title' => __( 'Password Change Admin Notification', 'tml-notifications' ),
			'hidden_fields' => array(),
		),
		'email_change_confirmation_email' => array(
			'title' => __( 'Email Change Confirmation Notification', 'tml-notifications' ),
			'hidden_fields' => array( 'recipient', 'from_name', 'from_address', 'format', 'subject', 'disable' ),
		),
		'email_change_email' => array(
			'title' => __( 'Email Change Notification', 'tml-notifications' ),
			'hidden_fields' => array( 'recipient' ),
		),
	);

	if ( tml_extension_exists( 'tml-moderation' ) ) {
		$default_notifications['tml_moderation_user_activation_email'] = array(
			'title' => __( 'User Activation Notification', 'tml-notifications' ),
			'hidden_fields' => array( 'recipient', 'disable' ),
		);
		$default_notifications['tml_moderation_user_approval_email'] = array(
			'title' => __( 'User Approval Admin Notification', 'tml-notifications' ),
			'hidden_fields' => array(),
		);
	}


	$notifications = array_merge_recursive( $saved_notifications, $default_notifications );

	/**
	 * Filter the default notifications.
	 *
	 * @since 1.0
	 *
	 * @param array $notifications The default notifications.
	 */
	return (array) apply_filters( 'tml_notifications_get_default_notifications', $notifications );
}

/**
 * Get a default notification.
 *
 * @since 1.1
 *
 * @return array The notfication or false if it doesn't exist.
 */
function tml_notifications_get_default_notification( $notification ) {
	$notifications = tml_notifications_get_default_notifications();
	if ( isset( $notifications[ $notification ] ) ) {
		return $notifications[ $notification ];
	}
	return false;
}

/**
 * Determine if a default notification is disabled.
 *
 * @since 1.1
 *
 * @param string $notification The notification name.
 * @return bool Whether the notification is disabled or not.
 */
function tml_notifications_is_default_notification_disabled( $notification ) {
	if ( ! $notification = tml_notifications_get_default_notification( $notification ) ) {
		return false;
	}
	return ! empty( $notification['disable'] );
}

/**
 * Get the custom notifications.
 *
 * @since 1.0
 *
 * @return array The custom notifications.
 */
function tml_notifications_get_custom_notifications() {
	$notifications = get_site_option( 'tml_notifications_custom_notifications', array() );

	/**
	 * Filter the custom notifications.
	 *
	 * @since 1.0
	 *
	 * @param array $notifications The custom notifications.
	 */
	return (array) apply_filters( 'tml_notifications_get_custom_notifications', $notifications );
}

/**
 * Handle user notifications.
 *
 * @since 1.0
 */
function tml_notifications_user_notification_handler() {
	$hook = current_action();
	$trigger_hooks = tml_notifications_get_trigger_hooks();

	if ( ! isset( $trigger_hooks[ $hook ] ) ) {
		return;
	}

	$trigger = tml_notifications_get_trigger( $trigger_hooks[ $hook ] );

	$args = array_combine( $trigger['args'], array_slice( func_get_args(), 0, count( $trigger['args'] ) ) );
	if ( ! isset( $args['user'] ) ) {
		return;
	}

	if ( is_array( $args['user'] ) ) {
		$args['user'] = (object) $args['user'];
	}
	$args['user'] = new WP_User( $args['user'] );

	$variables = array();
	switch ( $hook ) {
		case 'retrieved_password_key' :
			$variables['%reset_url%'] = network_site_url(
				sprintf( 'wp-login.php?action=rp&key=%s&login=%s', $args['key'], rawurlencode( $args['user']->user_login ) ),
				'login'
			);
			break;
	}

	/**
	 * Filters the variables available to custom notifications.
	 *
	 * @since 1.1.2
	 *
	 * @param array  $variables The custom variables.
	 * @param string $trigger   The trigger which caused this notification.
	 * @param array  $args      The trigger arguments.
	 */
	$variables = apply_filters( 'tml_notifications_user_notification_variables', $variables, $trigger['name'], $args );

	foreach ( tml_notifications_get_custom_notifications() as $notification ) {
		if ( ! isset( $notification['triggers'] ) ) {
			continue;
		}

		if ( ! in_array( $trigger['name'], $notification['triggers'] ) ) {
			continue;
		}

		$recipient = $notification['recipient'];
		if ( $args['user'] instanceof WP_User ) {
			if ( empty( $recipient ) ) {
				$recipient = $args['user']->user_email;
			}
		}

		if ( empty( $recipient ) ) {
			continue;
		}

		$subject = tml_notifications_replace_variables( $notification['subject'], $args['user'], $variables );
		$message = tml_notifications_replace_variables( $notification['message'], $args['user'], $variables );

		$headers = array();
		if ( ! empty( $notification['from_name'] ) && ! empty( $notification['from_address'] ) ) {
			$headers[] = 'From: "' . $notification['from_name'] . '" <' . $notification['from_address'] . '>';
		} elseif ( ! empty( $notification['from_address'] ) ) {
			$headers[] = 'From: ' . $notification['from_address'];
		}
		if ( 'html' == $notification['format'] ) {
			$headers[] = 'Content-Type: text/html';

			$message = wpautop( $message );
		} else {
			$message = preg_replace( "/(\r\n|\r|\n)/", "\r\n", $message );
		}

		wp_mail( $recipient, $subject, $message, $headers );
	}
}

/**
 * Apply filters to most of the standard WP email filters.
 *
 * @since 1.1
 *
 * @param array $email             The email arguments.
 * @param array|int|string|WP_User The user data, ID, login, or object.
 * @return array The email arguments.
 */
function tml_notifications_filter_default_notification( $email, $user ) {
	$current_filter = current_filter();
	$replacements = array();

	if ( ! $user instanceof WP_User ) {
		if ( is_array( $user ) ) {
			$user = new WP_User( (object) $user );
		} elseif ( is_int( $user ) ) {
			$user = get_user_by( 'id', $user );
		} else {
			$user = get_user_by( 'login', $user );
		}
	}

	switch ( $current_filter ) {
		case 'wp_new_user_notification_email' :
			$replacements['%reset_url%'] = add_query_arg( array(
				'key' => get_password_reset_key( $user ),
				'login' => rawurlencode( $user->user_login ),
			), network_site_url( 'wp-login.php?action=rp', 'login') );
			break;

		case 'email_change_email' :
			$user_data = func_get_arg( 2 );
			$replacements['%new_email%'] = $user_data['user_email'];
			break;

		case 'tml_moderation_user_activation_email' :
			$replacements['%activation_url%'] = add_query_arg( array(
				'key' => func_get_arg( 3 ),
				'login' => rawurlencode( $user->user_login ),
			), tml_get_action_url( 'activate' ) );
			break;
	}

	if ( ! $notification = tml_notifications_get_default_notification( $current_filter ) ) {
		return $email;
	}

	if ( ! empty( $notification['recipient'] ) ) {
		$email['to'] = $notification['recipient'];
	}

	if ( ! empty( $notification['subject'] ) ) {
		$email['subject'] = tml_notifications_replace_variables( $notification['subject'], $user, $replacements );
	}

	if ( ! empty( $notification['message'] ) ) {
		if ( 'html' == $notification['format'] ) {
			$message = wpautop( $notification['message'] );
		} else {
			$message = preg_replace( "/(\r\n|\r|\n)/", "\r\n", $notification['message'] );
		}
		$email['message'] = tml_notifications_replace_variables( $message, $user, $replacements );
	}

	if ( ! is_array( $email['headers'] ) ) {
		$email['headers'] = array();
	}

	if ( ! empty( $notification['from_name'] ) && ! empty( $notification['from_address'] ) ) {
		$email['headers'][] = "From: {$notification['from_name']} <{$notification['from_address']}>";
	} elseif ( ! empty( $notification['from_address'] ) ) {
		$email['headers'][] = "From: {$notification['from_address']}";
	}

	if ( ! empty( $notification['format'] ) ) {
		$email['headers'][] = "Content-Type: text/{$notification['format']}";
	}

	return $email;
}

/**
 * Filter the new user email content.
 *
 * @since 1.1
 *
 * @param string $content The new user email content.
 * @param array  $args    The new user email arguments.
 * @return string The new user email content.
 */
function tml_notifications_filter_new_user_email_content( $content, $args ) {
	if ( ! $notification = tml_notifications_get_default_notification( 'email_change_confirmation_email' ) ) {
		return $content;
	}

	if ( empty( $notification['message'] ) ) {
		return $content;
	}

	return tml_notifications_replace_variables( $notification['message'], wp_get_current_user(), array(
		'%confirm_url%' => esc_url( admin_url( 'profile.php?newuseremail=' . $args['hash'] ) ),
		'%new_email%' => $args['newemail'],
	) );
}

/**
 * Replace variables matching a pattern in a string.
 *
 * @since 1.0
 *
 * @param string      $input The input string.
 * @param int|WP_User $user  The user ID or object.
 * @param array       $replacements The additional replacement variables.
 * @return string The input string with known variables replaced.
 */
function tml_notifications_replace_variables( $input, $user = null, $replacements = array() ) {
	$defaults = array(
		'%site_name%' => wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES ),
		'%site_description%' => wp_specialchars_decode( get_option( 'blogdescription' ), ENT_QUOTES ),
		'%site_url%' => site_url(),
		'%home_url%' => home_url(),
		'%login_url%' => wp_login_url(),
		'%user_ip%' => $_SERVER['REMOTE_ADDR'] ,
	);

	if ( is_multisite() ) {
		$defaults = array_merge( $defaults, array(
			'%site_name%' => get_network()->site_name,
			'%site_url%' => network_site_url(),
			'%home_url%' => network_home_url(),
		) );
	}

	$replacements = wp_parse_args( $replacements, $defaults );

	if ( ! empty( $user ) && ! $user instanceof WP_User ) {
		$user = new WP_User( $user );
	}

	if ( $user instanceof WP_User ) {
		preg_match_all( '/%([a-zA-Z0-9-_]*)%/', $input, $matches );

		foreach ( $matches[0] as $key => $match ) {
			if ( ! isset( $replacements[ $match ] ) && isset( $user->{ $matches[1][ $key ] } ) ) {
				$replacements[ $match ] = $user->{ $matches[1][ $key ] };
			}
		}
	}

	/**
	 * Filters the notification replacement variables.
	 *
	 * @since 1.0
	 *
	 * @param array   $replacements The replacement variables.
	 * @param WP_User $user         The user object.
	 */
	$replacements = apply_filters( 'tml_notifications_replace_variables', $replacements, $user );

	if ( empty( $replacements ) ) {
		return $input;
	}

	return str_replace( array_keys( $replacements ), array_values( $replacements ), $input );
}

/**
 * Set mail from data for password retrievals.
 *
 * @since 1.1.2
 */
function tml_notifications_lostpassword_post() {
	if ( $notification = tml_notifications_get_default_notification( 'tml_retrieve_password_email' ) ) {
		if ( ! empty( $notification['from_address'] ) ) {
			tml_set_data( 'mail_from_address', $notification['from_address'] );
		}
		if ( ! empty( $notification['from_name'] ) ) {
			tml_set_data( 'mail_from_name', $notification['from_name'] );
		}
		if ( ! empty( $notification['format'] ) ) {
			tml_set_data( 'mail_content_type', 'text/' . $notification['format'] );
		}
	}
}

/**
 * Filter all parameters to wp_mail().
 *
 * @since 1.1.4
 *
 * @param array $args The wp_mail() arguments.
 * @return array The wp_mail() arguments.
 */
function tml_notifications_filter_wp_mail( $args ) {
	if ( $content_type = tml_get_data( 'mail_content_type' ) ) {
		tml_set_data( 'mail_content_type', null );
		if ( is_array( $args['headers'] ) ) {
			$args['headers'][] = "Content-Type: {$content_type}";
		} else {
			$args['headers'] .= "Content-Type: {$content_type};";
		}
	}
	return $args;
}

/**
 * Filter the mail from address.
 *
 * @since 1.1.2
 *
 * @param string $from_email The mail from address.
 * @return string The mail from address.
 */
function tml_notifications_filter_wp_mail_from( $from_address ) {
	if ( $tml_from_address = tml_get_data( 'mail_from_address' ) ) {
		tml_set_data( 'mail_from_address', null );
		return $tml_from_address;
	}
	return $from_address;
}

/**
 * Filter the mail from name.
 *
 * @since 1.1.2
 *
 * @param string $from_name The mail from name.
 * @return string The mail from name.
 */
function tml_notifications_filter_wp_mail_from_name( $from_name ) {
	if ( $tml_from_name = tml_get_data( 'mail_from_name' ) ) {
		tml_set_data( 'mail_from_name', null );
		return $tml_from_name;
	}
	return $from_name;
}

/**
 * Filter the retrieve password notification title.
 *
 * @since 1.1.2
 *
 * @param string $title The retrieve password notification title.
 * @return string The retrieve password notification title.
 */
function tml_notifications_filter_retrieve_password_title( $title ) {
	if ( ! $notification = tml_notifications_get_default_notification( 'tml_retrieve_password_email' ) ) {
		return $title;
	}

	if ( empty( $notification['subject'] ) ) {
		return $title;
	}

	return $notification['subject'];
}

/**
 * Filter the retrieve password notification message.
 *
 * @since 1.1.2
 *
 * @param string  $message    The retrieve password notification message.
 * @param string  $key        The password reset key.
 * @param string  $user_login The username for the user.
 * @param WP_User $user_data  The user object.
 * @return string The retrieve password notification message.
 */
function tml_notifications_filter_retrieve_password_message( $message, $key, $user_login, $user_data ) {
	if ( ! $notification = tml_notifications_get_default_notification( 'tml_retrieve_password_email' ) ) {
		return $message;
	}

	if ( empty( $notification['message'] ) ) {
		return $message;
	}

	if ( 'html' == $notification['format'] ) {
		$message = wpautop( $notification['message'] );
	} else {
		$message = preg_replace( "/(\r\n|\r|\n)/", "\r\n", $notification['message'] );
	}

	return tml_notifications_replace_variables( $message, $user_data, array(
		'%reset_url%' => network_site_url(
			sprintf( 'wp-login.php?action=rp&key=%s&login=%s', $key, rawurlencode( $user_login ) ),
			'login'
		),
	) );
}
