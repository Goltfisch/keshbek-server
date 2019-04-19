<?php

namespace App\Service;

use Symfony\Component\Templating\EngineInterface;

class CashUpMailer
{
    const TEMPLATE_NEW = 'emails/new.html.twig';
    const TEMPLATE_DUE = 'emails/due.html.twig';

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

    public function sendMail($data, $template = self::TEMPLATE_NEW)
    {
        $message = (new \Swift_Message('Hello Email'))
        ->setFrom('send@example.com')
        ->setTo('daniel.wolf.web@gmail.com')
        ->setBody(
            $this->template->render(
                $template,
                ['name' => 'demoName']
            ),
            'text/html'
        );

        $this->mailer->send($message);
        //insert data into $template

        //send Email
        //Kassensturz-Status wird auf “Aussenstehend” gestellt
    }
}
