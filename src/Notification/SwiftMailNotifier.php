<?php
/**
 * @licence GNU GPL v2+
 * @author Christoph Fischer
 */

namespace BannerMonitor\Notification;


use Swift_Message;
use Swift_Mailer;

class SwiftMailNotifier implements Notifier {

	private $mailer;
	private $receiver;
	private $senderMail;

	/**
	 * @param string|array $receiver
	 * @param string $senderMail
	 */
	public function __construct( Swift_Mailer $mailer, $receiver, $senderMail ) {
		$this->mailer = $mailer;
		$this->receiver = $receiver;
		$this->senderMail = $senderMail;
	}

	public function notify( $subject, $body ) {
		$message = $this->composeMessage( $subject, $body, $this->senderMail, $this->receiver );

		$result = $this->sendMail( $message );

		if( $result == 0 ) {
			return false;
		}

		return true;
	}

	private function composeMessage( $subject, $body, $sender, $receiver ) {
		$message = Swift_Message::newInstance();

		$message->setSubject( $subject );

		$message->setFrom( $sender );
		$message->setTo( $receiver );

		$message->setBody( $body );

		return $message;
	}

	private function sendMail( Swift_Message $message ) {
		return $this->mailer->send( $message );
	}
}