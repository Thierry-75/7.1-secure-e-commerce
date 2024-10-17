<?php 

namespace App\MessageHandler;

use App\Message\SendConfirmationReset;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Mime\Email;

#[AsMessageHandler()]
class SendConfirmationResetHandler
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function __invoke(SendConfirmationReset $notification)
    {
        
        $email = (new Email())
                ->from('admin@e-commerce.com')
                ->to($notification->getEmail())
                ->subject('Confirmation')
                ->html('<h3>' .$notification->getMessage() . '</h3>');
        $this->mailer->send($email);
    }
}