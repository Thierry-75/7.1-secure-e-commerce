<?php 

namespace App\MessageHandler;

use App\Message\SendInscription;
use App\Service\MailService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;



#[AsMessageHandler()]
class SendInscriptionHandler
{
    private $mailer;

    public function __construct(MailService $mailer)
    {
        $this->mailer = $mailer;
    }

    public function __invoke(SendInscription $notification)
    {
        $this->mailer->sendMail(
            $notification->getFrom(),
            $notification->getTo(),
            $notification->getSubject(),
            $notification->getTemplate(),
            $notification->getContext());
    }
}