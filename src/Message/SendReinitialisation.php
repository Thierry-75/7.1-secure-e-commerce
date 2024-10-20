<?php


namespace App\Message;

class SendReinitialisation
{
    private $from;
    private $to;
    private $subject;
    private $template;
    private array $context;

    public function __construct(string $from,string $to,string $subject,string $template, array $context)
    {
        $this->from = $from;
        $this->to = $to;
        $this->subject = $subject;
        $this->template = $template;
        $this->context = $context;
    }

    

    /**
     * Get the value of from
     */ 
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * Get the value of to
     */ 
    public function getTo()
    {
        return $this->to;
    }

    /**
     * Get the value of subject
     */ 
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Get the value of template
     */ 
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * Get the value of context
     */ 
    public function getContext()
    {
        return $this->context;
    }
}
