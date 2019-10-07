<?php

namespace App\Mail;

class Mailer
{
    protected $apiKey;

    public function __construct ($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function send ($subject, $toEmail, $toName, $content) {
        $email = new \SendGrid\Mail\Mail(); 
        $email->setFrom("noreply@verwimp.nl", "Louis Verwimp BV");
        $email->setSubject($subject);
        $email->addTo($toEmail, $toName);
        $email->addContent("text/html", $content);
        $sendgrid = new \SendGrid($this->apiKey);

        try {
            $response = $sendgrid->send($email);
            // print $response->statusCode() . "\n";
            // print_r($response->headers());
            // print $response->body() . "\n";
        } catch (Exception $e) {
            echo 'Caught exception: '. $e->getMessage() ."\n";
        }
    }
}