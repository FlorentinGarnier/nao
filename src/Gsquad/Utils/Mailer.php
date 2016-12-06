<?php
/**
 * Created by PhpStorm.
 * User: aigie
 * Date: 04/12/2016
 * Time: 17:36
 */

namespace Gsquad\Utils;

use Symfony\Component\Templating\EngineInterface;

class Mailer
{
    protected $mailer;
    protected $templating;

    public function __construct(\Swift_Mailer $mailer, EngineInterface $templating)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
    }

    public function sendMail($from, $to, $subject, $body)
    {
        $mail = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($from)
            ->setTo($to)
            ->setBody($body)
            ->setContentType('text/html');

        $this->mailer->send($mail);
    }

    public function sendContactMail($data)
    {
        $template = 'mails/contact_mail.html.twig';

        $from = $data['email'];

        $to = 'admin@example.com';

        $subject = '[NAO] Formulaire de contact';

        $body = $this->templating->render($template, array('data' => $data));

        $this->sendMail($from, $to, $subject, $body);
    }
}
