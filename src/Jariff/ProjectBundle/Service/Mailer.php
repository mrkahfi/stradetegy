<?php

namespace Jariff\ProjectBundle\Service;

class Mailer {

	protected $mailer;

    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

	public function sendEmail($from, $to, $replyTo, $body, $subject = '') {

		$mail = \Swift_Message::newInstance();
		$mail->setSubject($subject)
        ->setContentType('text/html')
		->setFrom($from)
		->setTo($to)
		->setReplyTo($replyTo)
		->setBody($body);

		// $mailer = $this->container->get('mailer');
		$result = $this->mailer->send($mail);

		return $result;
	}
}