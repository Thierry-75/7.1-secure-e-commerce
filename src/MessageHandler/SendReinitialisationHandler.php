<?php 

namespace App\MessageHandler;


use App\Message\SendReinitialisation;
use App\Service\MailService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;



#[AsMessageHandler()]
class SendReinitialisationHandler
{
    private $mailer;

    public function __construct(MailService $mailer)
    {
        $this->mailer = $mailer;
    }

    public function __invoke(SendReinitialisation $notification)
    {
        $this->mailer->sendMail(
            $notification->getFrom(),
            $notification->getTo(),
            $notification->getSubject(),
            $notification->getTemplate(),
            $notification->getContext());
    }
}