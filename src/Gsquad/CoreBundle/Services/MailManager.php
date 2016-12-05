<?php
/**
 * Created by PhpStorm.
 * User: aigie
 * Date: 05/12/2016
 * Time: 09:24
 */

namespace Gsquad\CoreBundle\Services;


use Gsquad\Utils\Mailer;

class MailManager extends Mailer
{
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
