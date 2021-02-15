<?php

namespace BetterSharingWP\API;

/**
 * Email API
 *
 * @package Email
 **/
class Email {

	/**
	 * Rest Init
	 *
	 * @return void
	 */
	public function rest_init() {
		register_rest_route(
			'bswp/v1',
			'/bswp_email',
			array(
				'methods'  => array( 'POST', 'GET' ),
				'callback' => array( $this, 'bswp_email_before_send' ),
			)
		);
	}

	/**
	 * Before Email Send
	 *
	 * @param \WP_REST_Request $request ajax request.
	 * @return boolean
	 */
	public function bswp_email_before_send( \WP_REST_Request $request ) {
		$body = json_decode( $request->get_body() );

		$emails = isset( $body->emails ) ? (array) $body->emails : array();
		$emails = array_map( 'sanitize_email', $emails );

		// return error if no email.
		if ( empty( $emails ) ) {
			return new \WP_Error( 'bswp_email', __( 'No Email Found' ), $body );
		}

		$subject = isset( $body->subject ) ? (string) $body->subject : '';
		$subject = sanitize_text_field( $subject );

		$message = isset( $body->message ) ? (string) $body->message : '';
		$message = sanitize_text_field( $message );

		$mail = wp_mail( $emails, $subject, $message );

		return new \WP_REST_Response(
			array(
				'mail' => $mail,
			)
		);
	}
}
