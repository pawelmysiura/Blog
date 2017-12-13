<?php
namespace AppBundle\Service\Mailer;


use AppBundle\Entity\User;

class UserMailer
{


    /**
     * @var \Swift_Mailer
     */
    private $swiftMailer;

    private $fromMail;

    private $fromName;


    function __construct(\Swift_Mailer $swiftMailer, $fromMail, $fromName)
    {
        $this->swiftMailer = $swiftMailer;
        $this->fromMail = $fromMail;
        $this->fromName = $fromName;
    }


    public function sendContactMailer($toMail, $subjectMail, $bodyMail){

        $message = \Swift_Message::newInstance()
            ->setFrom($this->fromMail, $this->fromName)
            ->setTo($this->fromMail, $toMail)
            ->setSubject('Blog contact | '.$subjectMail)
            ->setBody($bodyMail, 'text/html');

        $this->swiftMailer->send($message);

        }

        public function userMailer(User $user, $subject, $body){
        $message = \Swift_Message::newInstance()
            ->setSubject('Blog | '.$subject)
            ->setFrom($this->fromMail, $this->fromName)
            ->setTo($user->getEmail(), $user->getUsername())
            ->setBody($body, 'text/html');

        $this->swiftMailer->send($message);

        }
}