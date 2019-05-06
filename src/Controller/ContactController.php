<?php
declare(strict_types = 1);

// src/Controller/ContactController.php
namespace AppBundle\Controller;

use AppBundle\Bundle\Html;
use AppBundle\Core\{Config, Email};

class ContactController
{
    protected $serverName;
    protected $serverDomain;
    protected $administratorEmail;

    public function __construct()
    {
        $this->serverName = Config::getServerName();
        $this->serverDomain = Config::getServerDomain();
        $this->administratorEmail = Config::getAdministratorEmail();
    }

    public function contactAction(string $email, string $subject, string $text, bool $submit): array
    {
        $message = '';
        $ok = false;

        if ($submit) {
            if (!preg_match('/^([0-9A-Za-z._-]+)@([0-9A-Za-z-]+\.)+([A-Za-z]{2,4})$/', $email)) {
                $message .= 'E-mail musi mieć format zapisu: nazwisko@domena.pl' . "\r\n";
            }
            if ($subject == '') {
                $message .= 'Temat wiadomości musi zostać podany.' . "\r\n";
            }
            if ($text == '') {
                $message .= 'Treść wiadomości musi zostać podana.' . "\r\n";
            }
            if ($message == '') {
                if ($this->sendContactEmail($email, $subject, $text)) {
                    $message .= 'Wiadomość została wysłana.' . "\r\n";
                    $ok = true;
                    $email = '';
                    $subject = '';
                    $text = '';
                } else {
                    $message .= 'Wysłanie wiadomości nie powiodło się.' . "\r\n";
                }
            }
        }

        $message = Html::prepareMessage($message, $ok);

        return array(
            'message' => $message,
            'email' => $email,
            'subject' => $subject,
            'text' => $text
        );
    }

    private function sendContactEmail(string $email, string $subject, string $text): bool
    {
        return Email::sendEmail(
            $this->serverName,
            $email,
            $this->administratorEmail,
            $subject . ' [' . $this->serverDomain . ']',
            $text
        );
    }
}
