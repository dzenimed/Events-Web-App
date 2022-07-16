<?php

require_once dirname(__FILE__).'/../config.php';
require_once dirname(__FILE__).'/../../vendor/autoload.php';

class SMTPClient
{
    private $mailer;

    public function __construct()
    {
        $transport = (new Swift_SmtpTransport(Config::SMTP_HOST, Config::SMTP_PORT, 'tls'))
      ->setUsername(Config::SMTP_USER)
      ->setPassword(Config::SMTP_PASSWORD);

        $this->mailer = new Swift_Mailer($transport);
    }


    public function send_register_user_token($user)
    {
        $message = (new Swift_Message('Confirm your account'))
      ->setFrom(['dzeniweb@gmail.com' => 'Vertex Events'])
      ->setTo([$user['email']])
      ->setBody('Here is the confirmation link: ' . Config::PROTOCOL() . Config::ENVIRONMENT_SERVER() . 'rest/confirm/' .$user['token']);

        $this->mailer->send($message);
    }

    public function send_user_recovery_token($user)
    {
        $message = (new Swift_Message('Reset Your Password'))
     ->setFrom(['dzeniweb@gmail.com' => 'IndigoEvents'])
     ->setTo([$user['email']])
     ->setBody('Here is the recovery token: '.$user['token']);

        $this->mailer->send($message);
    }

    public function send_user_confirmed_notice($user)
    {
        $message = (new Swift_Message('Account confirmation succesfull!'))
            ->setFrom(['dzeniweb@gmail.com' => 'IndigoEvents'])
            ->setTo($user['email'])
            ->setBody('Your account has been confirmed. Enjoy the full access to the application!');
        $this->mailer->send($message);
    }
}
