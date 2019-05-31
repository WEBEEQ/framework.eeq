<?php declare(strict_types=1);

// src/Service/RestService.php
namespace App\Service;

class RestService
{
    protected $config;
    protected $restModel;
    protected $message;

    public function __construct(
        object $config,
        object $restModel,
        object $message
    ) {
        $this->config = $config;
        $this->restModel = $restModel;
        $this->message = $message;
    }

    public function addSiteMessage(
        string $user,
        string $password,
        string $name,
        string $www
    ): object {
        $userPassword = $this->restModel->getUserPassword($user, $id) ?? '';
        if (password_verify($password, $userPassword)) {
            if (strlen($name) < 1) {
                $this->message->addMessage(
                    'Nazwa strony www musi zostać podana.'
                );
            } elseif (strlen($name) > 100) {
                $this->message->addMessage(
                    'Nazwa strony www może zawierać maksymalnie 100 znaków.'
                );
            }
            $http = substr($www, 0, 7) != 'http://';
            $https = substr($www, 0, 8) != 'https://';
            if ($http && $https) {
                $this->message->addMessage(
                    'Url musi rozpoczynać się od znaków: http://'
                );
            }
            if (strlen($www) > 100) {
                $this->message->addMessage(
                    'Url może zawierać maksymalnie 100 znaków.'
                );
            }
            if (!$this->message->isMessage()) {
                $siteData = $this->restModel->addSiteData(
                    (int) $id,
                    $name,
                    $www,
                    $this->config->getRemoteAddress(),
                    $this->config->getDateTimeNow()
                );
                if ($siteData) {
                    $this->message->addMessage(
                        'Strona www została dodana i oczekuje na akceptację.'
                    );
                    $this->message->setOk(true);
                } else {
                    $this->message->addMessage(
                        'Dodanie strony www nie powiodło się.'
                    );
                }
            }
        } else {
            $this->message->addMessage(
                'Błędna autoryzacja przesyłanych danych.'
            );
        }

        return $this->message;
    }

    public function updateSiteMessage(
        string $user,
        string $password,
        int $site,
        string $name,
        int $visible
    ): object {
        $userPassword = $this->restModel->getUserPassword($user, $id) ?? '';
        if (password_verify($password, $userPassword)) {
            if (!$this->restModel->isUserSite((int) $id, $site)) {
                $this->message->addMessage(
                    'Baza nie zawiera podanej strony dla autoryzacji.'
                );
            }
            if (strlen($name) < 1) {
                $this->message->addMessage(
                    'Nazwa strony www musi zostać podana.'
                );
            } elseif (strlen($name) > 100) {
                $this->message->addMessage(
                    'Nazwa strony www może zawierać maksymalnie 100 znaków.'
                );
            }
            if (!$this->message->isMessage()) {
                $siteData = $this->restModel->setSiteData(
                    $site,
                    $visible,
                    $name,
                    $this->config->getRemoteAddress(),
                    $this->config->getDateTimeNow()
                );
                if ($siteData) {
                    $this->message->addMessage(
                        'Dane strony www zostały zapisane.'
                    );
                    $this->message->setOk(true);
                } else {
                    $this->message->addMessage(
                        'Zapisanie danych strony www nie powiodło się.'
                    );
                }
            }
        } else {
            $this->message->addMessage(
                'Błędna autoryzacja przesyłanych danych.'
            );
        }

        return $this->message;
    }

    public function deleteSiteMessage(
        string $user,
        string $password,
        int $site
    ): object {
        $userPassword = $this->restModel->getUserPassword($user, $id) ?? '';
        if (password_verify($password, $userPassword)) {
            if (!$this->restModel->isUserSite((int) $id, $site)) {
                $this->message->addMessage(
                    'Baza nie zawiera podanej strony dla autoryzacji.'
                );
            }
            if (!$this->message->isMessage()) {
                if ($this->restModel->deleteSiteData($site)) {
                    $this->message->addMessage(
                        'Dane strony www zostały usunięte.'
                    );
                    $this->message->setOk(true);
                } else {
                    $this->message->addMessage(
                        'Usunięcie danych strony www nie powiodło się.'
                    );
                }
            }
        } else {
            $this->message->addMessage(
                'Błędna autoryzacja przesyłanych danych.'
            );
        }

        return $this->message;
    }
}
