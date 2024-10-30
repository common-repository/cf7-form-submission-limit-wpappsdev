<?php
/**
 * Helper functions for this plugin.
 *
 * @since 1.0.0
 */

// File Security Check
defined( 'ABSPATH' ) || exit;

/**
 * [wpadcf7sl_print description]
 *
 * @since  	1.0.0
 *
 * @author 	Saiful Islam Ananda
 *
 * @param array/string $data
 *
 * @return void
 */
function wpadcf7sl_print( $data ) {
	if ( ! WP_DEBUG ) {
		return;
	}
	echo '<pre>';

	if ( is_array( $data ) || is_object( $data ) ) {
		print_r( $data );
	} else {
		// @codingStandardsIgnoreStart
		echo $data;
		// @codingStandardsIgnoreEnd
	}
	echo '</pre>';
}

/**
 * Get settings option value.
 *
 * @since  	1.0.0
 *
 * @author 	Saiful Islam Ananda
 *
 * @param string $option
 * @param string $section
 * @param string $default
 *
 * @return array/string
 */
function wpadcf7sl_get_option( $option, $section, $default = '' ) {
	$options = get_option( $section );

	if ( isset( $options[$option] ) ) {
		return $options[$option];
	}

	return $default;
}

/**
 * Get other templates passing attributes and including the file.
 *
 * Search for the template and include the file.
 *
 * @since 1.0.0
 *
 * @author Saiful Islam Ananda
 *
 * @see wpadcf7sl_locate_template()
 *
 * @param string $template_name Template to load.
 * @param array  $args          Args 		(optional) Passed arguments for the template file.
 * @param string $template_path (optional) Path to templates.
 * @param string $default_path  (optional) Default path to template files.
 */
function wpadcf7sl_get_template( $template_name, $args = [], $template_path = '', $default_path = '' ) {
	$cache_key = sanitize_key( implode( '-', [ 'template', $template_name, $template_path, $default_path, WPADCF7SL_VERSION ] ) );
	$template  = (string) wp_cache_get( $cache_key, WPADCF7SL_NAME );

	if ( ! $template ) {
		$template = wpadcf7sl_locate_template( $template_name, $template_path, $default_path );
		wp_cache_set( $cache_key, $template, WPADCF7SL_NAME );
	}

	// Allow 3rd party plugin filter template file from their plugin.
	$filter_template = apply_filters( 'wpadcf7sl_get_template', $template, $template_name, $args, $template_path, $default_path );

	if ( $filter_template !== $template ) {
		if ( ! file_exists( $filter_template ) ) {
			/* translators: %s template */
			wpadcf7sl_doing_it_wrong( __FUNCTION__, sprintf( __( '%s does not exist.', 'dokan-rgbswap' ), '<code>' . $template . '</code>' ), '1.0.0' );

			return;
		}
		$template = $filter_template;
	}

	$action_args = [
		'template_name' => $template_name,
		'template_path' => $template_path,
		'located'       => $template,
		'args'          => $args,
	];

	if ( ! empty( $args ) && is_array( $args ) ) {
		if ( isset( $args['action_args'] ) ) {
			wpadcf7sl_doing_it_wrong(
				__FUNCTION__,
				__( 'action_args should not be overwritten when calling wpadcf7sl_get_template.', 'dokan-rgbswap' ),
				'1.0.0'
			);
			unset( $args['action_args'] );
		}
		extract( $args ); // @codingStandardsIgnoreLine
	}

	do_action( 'wpadcf7sl_before_template_part', $action_args['template_name'], $action_args['template_path'], $action_args['located'], $action_args['args'] );

	include $action_args['located'];

	do_action( 'wpadcf7sl_after_template_part', $action_args['template_name'], $action_args['template_path'], $action_args['located'], $action_args['args'] );
}

/**
 * Like wpadcf7sl_get_template, but returns the HTML instead of outputting.
 *
 * @since 1.0.0
 *
 * @author Saiful Islam Ananda
 *
 * @see wpadcf7sl_get_template
 *
 * @param string $template_name Template name.
 * @param array  $args          Arguments. (default: array).
 * @param string $template_path Template path. (default: '').
 * @param string $default_path  Default path. (default: '').
 *
 * @return string
 */
function wpadcf7sl_get_template_html( $template_name, $args = [], $template_path = '', $default_path = '' ) {
	ob_start();
	wpadcf7sl_get_template( $template_name, $args, $template_path, $default_path );

	return ob_get_clean();
}

/**
 * Locate a template and return the path for inclusion.
 *
 * Locate the called template.
 * Search Order:
 * 1. /themes/theme-name/plugins-name/templates/$template_name
 * 2. /plugins/plugins-name/partials/templates/$template_name.
 *
 * @since 1.0.0
 *
 * @author Saiful Islam Ananda
 *
 * @param string $template_name Template to load.
 * @param string $template_path (optional) Path to templates.
 * @param string $default_path  (optional) Default path to template files.
 *
 * @return string $template 		Path to the template file.
 */
function wpadcf7sl_locate_template( $template_name, $template_path = '', $default_path = '' ) {
	// Set variable to search in templates folder of theme.
	if ( ! $template_path ) {
		$template_path = get_template_directory() . '/' . WPADCF7SL_NAME . '/templates/';
	}

	// Set default plugin templates path.
	if ( ! $default_path ) {
		$default_path = WPADCF7SL_DIR;
	}
	// Search template file in theme folder.
	$template = locate_template( [ $template_path . $template_name, $template_name ] );

	// Get plugins template file.
	if ( ! $template ) {
		$template = $default_path . $template_name;
	}

	return apply_filters( 'wpadcf7sl_locate_template', $template, $template_name, $template_path, $default_path );
}

/**
 * Wrapper for wpadcf7sl_doing_it_wrong.
 *
 * @since 1.0.0
 *
 * @author Saiful Islam Ananda
 *
 * @param string $function Function used.
 * @param string $message  Message to log.
 * @param string $version  Version the message was added in.
 */
function wpadcf7sl_doing_it_wrong( $function, $message, $version ) {
	// @codingStandardsIgnoreStart
	$message .= ' Backtrace: ' . wp_debug_backtrace_summary();

	if ( is_ajax() ) {
		do_action( 'wpadcf7sl_doing_it_wrong_run', $function, $message, $version );
		error_log( "{$function} was called incorrectly. {$message}. This message was added in version {$version}." );
	} else {
		_doing_it_wrong( $function, $message, $version );
	}
	// @codingStandardsIgnoreEnd
}

function reset_submission_limit( $form_id, $limit_type ) {
	// Backward compatibility. Set default limit type if limit type not set.
	if ( '' === $limit_type ) {
		$limit_type = 'formsubmit';
	}

	if ( 'formsubmit' == $limit_type && apply_filters( 'wpadcf7sl_reset_formsubmit_submission_limit', true, $limit_type ) ) {
		update_post_meta( $form_id, 'submission-total-count', 0 );
	}

	if ( 'userformsubmit' == $limit_type && apply_filters( 'wpadcf7sl_reset_userformsubmit_submission_limit', true, $limit_type ) ) {
		$user_ids = get_form_all_users( $form_id );

		foreach ( $user_ids as $user_id ) {
			update_user_meta( $user_id, "wpadcf7sl-total-submission-{$form_id}", 0 );
		}
	}

	do_action( 'wpadcf7sl_reset_submission_limit', $form_id, $limit_type );
}

/**
 * Get all users id for a cf7 form.
 *
 * @param int $fromId
 *
 * @return array
 */
function get_form_all_users( $fromId ) {
	global $wpdb;

	$result = $wpdb->get_results(
		$wpdb->prepare(
			"SELECT user_id
			from {$wpdb->prefix}usermeta
			WHERE meta_key = 'wpadcf7sl-total-submission-%d' AND meta_value > 0",
			$fromId
		),
		ARRAY_A
	);

	$userIds = wp_list_pluck( $result, 'user_id' );

	return $userIds;
}
