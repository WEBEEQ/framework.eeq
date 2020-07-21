<?php

declare(strict_types=1);

namespace App\Service;

use App\Repository\{SiteRepository, UserRepository};

class AcceptSiteService
{
    protected object $acceptSiteController;
    protected object $config;
    protected object $mail;
    protected object $html;
    protected object $csrfToken;
    protected object $acceptSiteValidator;

    public function __construct(
        object $acceptSiteController,
        object $config,
        object $mail,
        object $html,
        object $csrfToken,
        object $acceptSiteValidator
    ) {
        $this->acceptSiteController = $acceptSiteController;
        $this->config = $config;
        $this->mail = $mail;
        $this->html = $html;
        $this->csrfToken = $csrfToken;
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
        $rm = $this->acceptSiteController->getManager();

        if ($submit) {
            if ($delete) {
                $acceptationUserData = $rm
                    ->getRepository(UserRepository::class)
                    ->getAcceptationUserData($site);
                $acceptationSiteData = $rm
                    ->getRepository(SiteRepository::class)
                    ->deleteAcceptationSiteData($site);
                if ($acceptationSiteData) {
                    $acceptationEmail = $this->sendAcceptationEmail(
                        $active,
                        $delete,
                        $acceptationUserData['user_email'],
                        $acceptationUserData['user_login'],
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
                $acceptationSiteData = $rm
                    ->getRepository(SiteRepository::class)
                    ->setAcceptationSiteData(
                        $site,
                        $active,
                        $visible,
                        $name,
                        $www,
                        $this->config->getRemoteAddress(),
                        $this->config->getDateTimeNow()
                    );
                if ($acceptationSiteData) {
                    $acceptationUserData = $rm
                        ->getRepository(UserRepository::class)
                        ->getAcceptationUserData($site);
                    $acceptationEmail = $this->sendAcceptationEmail(
                        $active,
                        $delete,
                        $acceptationUserData['user_email'],
                        $acceptationUserData['user_login'],
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
            $acceptationSiteData = $rm->getRepository(SiteRepository::class)
                ->getAcceptationSiteData($site);
        }

        return array(
            'content' => 'src/View/accept-site/accept-site.php',
            'activeMenu' => 'accept-site',
            'title' => 'Akceptacja strony',
            'error' => $this->html->prepareError(
                $this->acceptSiteValidator->getError()
            ),
            'name' => $acceptationSiteData['site_name'] ?? $name,
            'www' => $acceptationSiteData['site_url'] ?? $www,
            'active' => $acceptationSiteData['site_active'] ?? $active,
            'visible' => $acceptationSiteData['site_visible'] ?? $visible,
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
