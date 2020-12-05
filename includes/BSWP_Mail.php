<?php


namespace BetterSharingWP;

/**
 * Class BSWP_Mail
 *
 * @package BetterSharingWP
 */
class BSWP_Mail {


	/**
	 * Set From
	 *
	 * @param string $from email from.
	 *
	 * @return mixed
	 */
	public function setFrom( $from ) {
		$this->from = $from;
		return $from;
	}

	/**
	 * Set To
	 *
	 * @param mixed $to email to (array or string).
	 *
	 * @return mixed
	 */
	public function setTo( $to ) {
		$this->to = $to;
		return $to;
	}

	/**
	 * Set Subject
	 *
	 * @param string $subject email subject.
	 *
	 * @return mixed
	 */
	public function setSubject( $subject ) {
		$this->subject = $subject;
		return $subject;
	}

	/**
	 * Set Message
	 *
	 * @param string $message message for email.
	 *
	 * @return mixed
	 */
	public function setMessage( $message ) {
		$this->message = $message;
		return $message;
	}


	/**
	 * Send Email
	 *
	 * @return boolean
	 */
	public function send() {
		if ( ! $this->from
			|| ! $this->to
			|| ! $this->subject
			|| ! $this->message
		) {
			return new \WP_Error( 'BSWP Mail Fail', _( 'Missing email data' ) );
		}

		if ( ! is_array( $this->from ) || empty( $this->from ) ) {
			return new \WP_Error( 'BSWP Mail Fail', _( 'Missing proper from data, expecting ["name", "email"]' ), $this->from );
		}

		// Set from.
		$headers = array(
			'From: ' . $this->from['name'] . ' <' . $this->from['email'] . '>',
		);

		if ( ! is_array( $this->to ) ) {
			return new \WP_Error( 'BSWP Mail Fail', _( 'To emails not array' ), $this->to );
		}

		// Set main "To".
		$main_to = $this->to[0];
		unset( $this->to[0] );

		// CC the rest of the emails.
		foreach ( $this->to as $email ) {
			$headers[] = 'Cc: ' . $email;
		}

		return wp_mail( $main_to, $this->subject, $this->message, $headers );

	}


}
