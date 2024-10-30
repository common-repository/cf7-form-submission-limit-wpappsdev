<?php

namespace WPAppsDev\CF7SL;

/**
 * The frontend class.
 */
class Frontend {
	/**
	 * Initialize the class.
	 */
	public function __construct() {
		// Add counter CF7 custom tag.
		add_action( 'wpcf7_init', [ $this, 'add_custom_tag' ] );
		// Form submission limit validation.
		add_filter( 'wpcf7_validate', [ $this, 'submission_limit_validation' ], 10, 2 );
		// Submission counter process.
		add_action( 'wpcf7_mail_sent', [ $this, 'submission_counter_process' ] );
		// Process feedback response.
		add_filter( 'wpcf7_feedback_response', [ $this, 'process_feedback_response' ], 10, 2 );
	}

	/**
	 * Add counter CF7 custom tag.
	 *
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function add_custom_tag() {
		wpcf7_add_form_tag(
			'counter',
			[ $this, 'custom_counter_tag_handler' ],
			[ 'name-attr' => true ]
		);
	}

	/**
	 * Custom counter tag frontend handler.
	 *
	 * @since  1.0.0
	 *
	 * @param object $tag
	 *
	 * @return string
	 */
	public function custom_counter_tag_handler( $tag ) {
		$tmp_array = explode( ':', $tag->name );

		if ( 2 != count( $tmp_array ) ) {
			return '';
		}

		$form_id            = $tmp_array[1];
		$user_id            = get_current_user_id();
		$limit_type         = get_post_meta( $form_id, 'wpadcf7sl-limit-type', true );
		$total_submission   = get_post_meta( $form_id, 'wpadcf7sl-total-submission', true );
		$after_submission   = get_post_meta( $form_id, 'wpadcf7sl-after-submission', true );
		$reload_delay       = get_post_meta( $form_id, 'wpadcf7sl-page-reload-delay', true );
		$redirect_page      = get_post_meta( $form_id, 'wpadcf7sl-redirect-page', true );
		$is_message_disable = get_post_meta( $form_id, 'wpadcf7sl-disable-display-message', true );

		// Backward compatibility. Set default limit type if limit type not set.
		if ( '' === $limit_type ) {
			$limit_type = 'formsubmit';
		}

		if ( 'userformsubmit' == $limit_type ) {
			if ( is_user_logged_in() ) {
				$total_count = get_user_meta( $user_id, "wpadcf7sl-total-submission-{$form_id}", true );
				$remaining   = $total_submission - (int) $total_count;
				$args        = [
					'tag'                => $tag,
					'is_logged_in'       => true,
					'user_id'            => $user_id,
					'remaining'          => $remaining,
					'after_submission'   => $after_submission,
					'reload_delay'       => $reload_delay,
					'redirect_page'      => get_the_permalink( $redirect_page ),
					'is_message_disable' => $is_message_disable,
				];
				set_transient( "wpadcf7sl-userformsubmit-{$form_id}-{$user_id}", $user_id, 60 * 20 );
			} else {
				$args = [
					'tag'          => $tag,
					'is_logged_in' => false,
				];
			}

			return wpadcf7sl_get_template_html( 'templates/counter-tag/userformsubmit.php', $args );
		}

		if ( 'formsubmit' == $limit_type ) {
			$total_count = get_post_meta( $form_id, 'submission-total-count', true );
			$remaining   = (int) $total_submission - (int) $total_count;

			$args = [
				'tag'                => $tag,
				'remaining'          => $remaining,
				'after_submission'   => $after_submission,
				'reload_delay'       => $reload_delay,
				'redirect_page'      => get_the_permalink( $redirect_page ),
				'is_message_disable' => $is_message_disable,
			];

			return wpadcf7sl_get_template_html( 'templates/counter-tag/formsubmit.php', $args );
		}

		do_action( 'wpadcf7sl-counter-tag-template', $form_id, $limit_type, $user_id );
	}

	/**
	 * Form submission limit validation.
	 *
	 * @since  1.0.0
	 *
	 * @param object $result
	 * @param object $tags
	 *
	 * @return object
	 */
	public function submission_limit_validation( $result, $tags ) {
		$postdata         = wp_unslash( $_POST );
		$form_id          = (int) $postdata['_wpcf7'];
		$is_enabled       = get_post_meta( $form_id, 'wpadcf7sl-limit-enabled', true );
		$limit_type       = get_post_meta( $form_id, 'wpadcf7sl-limit-type', true );
		$total_submission = get_post_meta( $form_id, 'wpadcf7sl-total-submission', true );

		// Backward compatibility. Set default limit type if limit type not set.
		if ( '' === $limit_type ) {
			$limit_type = 'formsubmit';
		}

		// Checked if the form enabled submission limit.
		if ( $is_enabled && 1 == $is_enabled ) {
			if ( 'userformsubmit' == $limit_type ) {
				// Checked if the user logged in.
				if ( isset( $postdata['wpadcf7sl_login'] ) && 1 == $postdata['wpadcf7sl_login'] ) {
					$user_id    = isset( $postdata['wpadcf7sl_userid'] ) ? $postdata['wpadcf7sl_userid'] : 0;
					$user_info  = get_userdata( $user_id );
					$valid_user = get_transient( "wpadcf7sl-userformsubmit-{$form_id}-{$user_id}" );

					// Checked if the user id is invalid.
					if ( false === $valid_user || $valid_user != $user_id ) {
						$result->invalidate( "formid:{$form_id}", __( 'Invalid user ID.', 'wpappsdev-submission-limit-cf7' ) );

						return $result;
					}

					// Checked if the user is a valid user.
					if ( $user_info ) {
						$total_count = (int) get_user_meta( $user_id, "wpadcf7sl-total-submission-{$form_id}", true );

						// Checked if the user can submit the form.
						if ( $total_count >= $total_submission ) {
							$result->invalidate( "formid:{$form_id}", __( 'Your form submission limit is over.', 'wpappsdev-submission-limit-cf7' ) );
						}
					} else {
						$result->invalidate( "formid:{$form_id}", __( 'Invalid user.', 'wpappsdev-submission-limit-cf7' ) );
					}

					return $result;
				}

				$result->invalidate( "formid:{$form_id}", __( 'You can not submit this form without login.', 'wpappsdev-submission-limit-cf7' ) );

				return $result;
			}

			if ( 'formsubmit' == $limit_type ) {
				$total_count = (int) get_post_meta( $form_id, 'submission-total-count', true );

				// Checked if the user can submit the form.
				if ( $total_count >= $total_submission ) {
					$result->invalidate( "formid:{$form_id}", __( 'Form submission is over.', 'wpappsdev-submission-limit-cf7' ) );
				}
			}

			do_action( 'wpadcf7sl-submission-limit-validation', $result, $form_id, $limit_type );
		}

		return $result;
	}

	/**
	 * Submission counter process.
	 *
	 * @since  1.0.0
	 *
	 * @param object $contact_form
	 *
	 * @return void
	 */
	public function submission_counter_process( $contact_form ) {
		$form_id    = $contact_form->id();
		$postdata   = wp_unslash( $_POST );
		$is_enabled = get_post_meta( $form_id, 'wpadcf7sl-limit-enabled', true );
		$limit_type = get_post_meta( $form_id, 'wpadcf7sl-limit-type', true );

		// Backward compatibility. Set default limit type if limit type not set.
		if ( '' === $limit_type ) {
			$limit_type = 'formsubmit';
		}

		if ( $is_enabled && 1 == $is_enabled ) {
			if ( 'userformsubmit' == $limit_type ) {
				$user_id     = isset( $postdata['wpadcf7sl_userid'] ) ? $postdata['wpadcf7sl_userid'] : 0;
				$total_count = (int) get_user_meta( $user_id, "wpadcf7sl-total-submission-{$form_id}", true );
				update_user_meta( $user_id, "wpadcf7sl-total-submission-{$form_id}", ( $total_count + 1 ) );
				delete_transient( "wpadcf7sl-userformsubmit-{$form_id}-{$user_id}" );
			}

			if ( 'formsubmit' == $limit_type ) {
				$total_count = (int) get_post_meta( $form_id, 'submission-total-count', true );
				update_post_meta( $form_id, 'submission-total-count', ( $total_count + 1 ) );
			}

			do_action( 'wpadcf7sl-submission-counter-process', $contact_form, $limit_type );
		}
	}

	/**
	 * Process feedback response.
	 *
	 * @since  2.0.0
	 *
	 * @param array $response
	 * @param array $result
	 *
	 * @return array
	 */
	public function process_feedback_response( $response, $result ) {
		if ( 'validation_failed' != $result['status'] ) {
			return $response;
		}

		$form_id        = $result['contact_form_id'];
		$invalid_fields = (array) $result['invalid_fields'];

		if ( isset( $invalid_fields["formid:{$form_id}"] ) ) {
			$errormsg            = $invalid_fields["formid:{$form_id}"]['reason'];
			$response['message'] = $errormsg;
		}

		$res_invalid_fields   = $response['invalid_fields'];
		$final_invalid_fields = [];

		foreach ( $res_invalid_fields as $field ) {
			if ( $field['into'] == "span.wpcf7-form-control-wrap.formid{$form_id}" ) {
				continue;
			}

			$final_invalid_fields[] = $field;
		}

		$response['invalid_fields'] = $final_invalid_fields;

		return $response;
	}
}
