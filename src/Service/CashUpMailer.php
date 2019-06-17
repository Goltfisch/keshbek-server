<?php

namespace App\Service;

use Symfony\Component\Templating\EngineInterface;

class CashUpMailer
{
    const TEMPLATE_NEW = 'emails/new.html.twig';
    const TEMPLATE_NEW_SUBJECT = 'Neuer Kassensturz';

    const TEMPLATE_DUE = 'emails/due.html.twig';
    const TEMPLATE_DUE_SUBJECT = 'Zahlung fällig';

    /**
     * @var \Swift_Mailer $mailer
     */
    private $mailer;

    /**
     * @var EngineInterface $template
     */
    private $template;

    public function __construct(
        \Swift_Mailer $mailer,
        EngineInterface $template
    ) {
        $this->mailer = $mailer;
        $this->template = $template;
    }

    public function sendMail($data, $template = self::TEMPLATE_NEW, $subject = self::TEMPLATE_NEW_SUBJECT)
    {
        $name = $data['debitorData']['firstname'] . ' ' . $data['debitorData']['lastname'];
        $email = $data['debitorData']['email'];
        $creditors = $data['creditors'];

        if (!$name || !$email || !$creditors) {
            return;
        }
        
        $message = (new \Swift_Message($subject))

            ->setFrom('support@8mylez.com')
            ->setTo($email)
            ->setBody(
                $this->template->render(
                    $template,
                    [
                        'name' => $name,
                        'creditors' => $creditors
                    ]
                ),
                'text/html'
           );

        $this->mailer->send($message);

        return true;
    }
}
