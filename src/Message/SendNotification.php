<?php 

namespace App\Message;


class SendNotification 
{
    private $message;
    private $email;

    public function __construct(string $message, string $email)
    {
        $this->message = $message;
        $this->email = $email;
        
    }

    /**
     * Get the value of message
     */ 
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Get the value of email
     */ 
    public function getEmail()
    {
        return $this->email;
    }
}