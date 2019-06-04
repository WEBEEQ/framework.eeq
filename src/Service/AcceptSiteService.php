<?php declare(strict_types=1);

// src/Service/AcceptSiteService.php
namespace App\Service;

class AcceptSiteService
{
    protected $config;
    protected $mail;
    protected $html;
    protected $csrfToken;
    protected $acceptSiteModel;
    protected $acceptSiteValidator;

    public function __construct(
        object $config,
        object $mail,
        object $html,
        object $csrfToken,
        object $acceptSiteModel,
        object $acceptSiteValidator
    ) {
        $this->config = $config;
        $this->mail = $mail;
        $this->html = $html;
        $this->csrfToken = $csrfToken;
        $this->acceptSiteModel = $acceptSiteModel;
        $this->acceptSiteValidator = $acceptSiteValidator;
    }

    public function variableAction(
        string $name,
        string $www,
        int $active,
        int $visible,
        bool $delete,
        bool $submit,
        string $token,
        int $site
    ): array {
        if ($submit) {
            if ($delete) {
                $this->acceptSiteModel->getUserData($site, $login, $email);
                $siteData = $this->acceptSiteModel->deleteSiteData($site);
                if ($siteData) {
                    $acceptationEmail = $this->sendAcceptationEmail(
                        $active,
                        $delete,
                        $email,
                        $login,
                        $www
                    );

                    return array(
                        'layout' => 'src/Layout/main/main.php',
                        'content' =>
                            'src/View/accept-site/site-rejected-info.php',
                        'activeMenu' => 'accept-site',
                        'title' => 'Informacja',
                        'acceptationEmail' => $acceptationEmail
                    );
                } else {
                    return array(
                        'layout' => 'src/Layout/main/main.php',
                        'content' =>
                            'src/View/accept-site/data-not-deleted-info.php',
                        'activeMenu' => 'accept-site',
                        'title' => 'Informacja'
                    );
                }
            } else {
                $this->acceptSiteValidator->validate($name, $www, $token);
                if ($this->acceptSiteValidator->isValid()) {
                    $siteData = $this->acceptSiteModel->setSiteData(
                        $site,
                        $active,
                        $visible,
                        $name,
                        $www,
                        $this->config->getRemoteAddress(),
                        $this->config->getDateTimeNow()
                    );
                    if ($siteData) {
                        $this->acceptSiteModel->getSiteData(
                            $site,
                            $active,
                            $visible,
                            $name,
                            $www,
                            $login,
                            $email
                        );
                        $acceptationEmail = $this->sendAcceptationEmail(
                            (int) $active,
                            $delete,
                            $email,
                            $login,
                            $www
                        );

                        return array(
                            'layout' => 'src/Layout/main/main.php',
                            'content' =>
                                'src/View/accept-site/site-accepted-info.php',
                            'activeMenu' => 'accept-site',
                            'title' => 'Informacja',
                            'active' => $active,
                            'acceptationEmail' => $acceptationEmail
                        );
                    } else {
                        return array(
                            'layout' => 'src/Layout/main/main.php',
                            'content' =>
                                'src/View/accept-site/data-not-saved-info.php',
                            'activeMenu' => 'accept-site',
                            'title' => 'Informacja'
                        );
                    }
                }
            }
        } else {
            $this->acceptSiteModel->getSiteData(
                $site,
                $active,
                $visible,
                $name,
                $www,
                $login,
                $email
            );
        }

        return array(
            'layout' => 'src/Layout/main/main.php',
            'content' => 'src/View/accept-site/accept-site.php',
            'activeMenu' => 'accept-site',
            'title' => 'Akceptacja strony',
            'error' => $this->html->prepareError(
                $this->acceptSiteValidator->getError()
            ),
            'name' => $name,
            'www' => $www,
            'active' => $active,
            'visible' => $visible,
            'delete' => $delete,
            'token' => $this->csrfToken->generateToken()
        );
    }

    private function sendAcceptationEmail(
        int $active,
        bool $delete,
        string $email,
        string $login,
        string $www
    ): ?bool {
        if ($delete) {
            $accept = 'Strona www podana poniżej została odrzucona.';
        } elseif ($active == 1) {
            $accept = 'Strona www podana poniżej została zaakceptowana.';
        } else {
            return null;
        }

        return $this->mail->sendEmail(
            $this->config->getServerName(),
            $this->config->getAdminEmail(),
            $email,
            'Akceptacja strony www konta ' . $login . ' w serwisie '
                . $this->config->getServerDomain(),
            $accept . "\r\n\r\n" . $www . "\r\n\r\n" . '--' . "\r\n"
                . $this->config->getAdminEmail()
        );
    }
}
