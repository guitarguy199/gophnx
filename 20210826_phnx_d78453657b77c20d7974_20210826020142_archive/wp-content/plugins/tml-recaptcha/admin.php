<?php

/**
 * Theme My Login reCAPTCHA Admin Functions
 *
 * @package Theme_My_Login_Recaptcha
 * @subpackage Administration
 */

/**
 * Get the recaptcha settings sections.
 *
 * @since 1.0
 *
 * @return array The recaptcha settings sections.
 */
function tml_recaptcha_admin_get_settings_sections() {
	return array(
		// General
		'tml_recaptcha_settings_general' => array(
			'title' => '',
			'callback' => '__return_null',
			'page' => 'tml-recaptcha',
		),
	);
}

/**
 * Get the recaptcha settings fields.
 *
 * @since 1.0
 *
 * @return array The recaptcha settings fields.
 */
function tml_recaptcha_admin_get_settings_fields() {
	return array(
		// General
		'tml_recaptcha_settings_general' => array(
			// Public key
			'tml_recaptcha_public_key' => array(
				'title' => __( 'Site Key', 'tml-recaptcha' ),
				'callback' => 'tml_admin_setting_callback_input_field',
				'sanitize_callback' => 'sanitize_text_field',
				'args' => array(
					'label_for' => 'tml_recaptcha_public_key',
					'value' => get_site_option( 'tml_recaptcha_public_key' ),
					'input_class' => 'regular-text',
				),
			),
			// Private key
			'tml_recaptcha_private_key' => array(
				'title' => __( 'Secret Key', 'tml-recaptcha' ),
				'callback' => 'tml_admin_setting_callback_input_field',
				'sanitize_callback' => 'sanitize_text_field',
				'args' => array(
					'label_for' => 'tml_recaptcha_private_key',
					'value' => get_site_option( 'tml_recaptcha_private_key' ),
					'input_class' => 'regular-text',
				),
			),
			// Theme
			'tml_recaptcha_theme' => array(
				'title' => __( 'Theme', 'tml-recaptcha' ),
				'callback' => 'tml_admin_setting_callback_dropdown_field',
				'sanitize_callback' => 'sanitize_text_field',
				'args' => array(
					'label_for' => 'tml_recaptcha_theme',
					'options' => array(
						'light' => _x( 'Light', 'recaptcha theme', 'tml-recaptcha' ),
						'dark' => _x( 'Dark', 'recaptcha theme', 'tml-recaptcha' ),
					),
					'selected' => get_site_option( 'tml_recaptcha_theme' ),
				),
			),
			// Show on login
			'tml_recaptcha_show_on_login' => array(
				'title' => __( 'Show On Forms', 'tml-recaptcha' ),
				'callback' => 'tml_admin_setting_callback_checkbox_group_field',
				'sanitize_callback' => 'intval',
				'args' => array(
					'legend' => __( 'Show on forms', 'tml-recaptcha' ),
					'options' => array(
						'tml_recaptcha_show_on_login' => array(
							'label' => __( 'Log In' ),
							'value' => '1',
							'checked' => get_site_option( 'tml_recaptcha_show_on_login' ),
						),
						'tml_recaptcha_show_on_register' => array(
							'label' => __( 'Register' ),
							'value' => '1',
							'checked' => get_site_option( 'tml_recaptcha_show_on_register', true ),
						),
						'tml_recaptcha_show_on_lostpassword' => array(
							'label' => __( 'Lost Password' ),
							'value' => '1',
							'checked' => get_site_option( 'tml_recaptcha_show_on_lostpassword' ),
						),
						'tml_recaptcha_show_on_comments' => array(
							'label' => __( 'Comment' ),
							'value' => '1',
							'checked' => get_site_option( 'tml_recaptcha_show_on_comments' ),
						),
					),
				),
			),
			// Show on register
			'tml_recaptcha_show_on_register' => array(
				'sanitize_callback' => 'intval',
			),
			// Show on lost password
			'tml_recaptcha_show_on_lostpassword' => array(
				'sanitize_callback' => 'intval',
			),
			// Show on comments
			'tml_recaptcha_show_on_comments' => array(
				'sanitize_callback' => 'intval',
			),
		),
	);
}
