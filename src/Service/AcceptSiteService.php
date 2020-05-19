<?php

declare(strict_types=1);

namespace App\Service;

class AcceptSiteService
{
    protected object $config;
    protected object $mail;
    protected object $html;
    protected object $csrfToken;
    protected object $acceptSiteModel;
    protected object $acceptSiteValidator;

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
                if ($this->acceptSiteModel->deleteSiteData($site)) {
                    $acceptationEmail = $this->sendAcceptationEmail(
                        $active,
                        $delete,
                        $email,
                        $login,
                        $www
                    );

                    return array(
                        'content' => 'src/View/accept-site/'
                            . 'site-rejected-info.php',
                        'activeMenu' => 'accept-site',
                        'title' => 'Informacja',
                        'acceptationEmail' => $acceptationEmail
                    );
                } else {
                    return array(
                        'content' => 'src/View/accept-site/'
                            . 'data-not-deleted-info.php',
                        'activeMenu' => 'accept-site',
                        'title' => 'Informacja'
                    );
                }
            }
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
                        'content' => 'src/View/accept-site/'
                            . 'site-accepted-info.php',
                        'activeMenu' => 'accept-site',
                        'title' => 'Informacja',
                        'active' => $active,
                        'acceptationEmail' => $acceptationEmail
                    );
                } else {
                    return array(
                        'content' => 'src/View/accept-site/'
                            . 'data-not-saved-info.php',
                        'activeMenu' => 'accept-site',
                        'title' => 'Informacja'
                    );
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
        } elseif ($active === 1) {
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
            $accept . "\n\n" . $www . "\n\n" . '--' . "\n"
                . $this->config->getAdminEmail()
        );
    }
}
