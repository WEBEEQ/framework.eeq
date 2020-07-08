<?php

declare(strict_types=1);

namespace App\Repository;

class SiteRepository
{
    protected object $database;
    protected object $manager;

    public function __construct(object $database, object $manager)
    {
        $this->database = $database;
        $this->manager = $manager;
    }

    public function isSiteId(int $site): bool
    {
        $query = $this->manager->createQuery(
            'SELECT s.`site_id` FROM `sites` s
            INNER JOIN `users` u ON s.`user_id` = u.`user_id`
            WHERE u.`user_active` = 1 AND s.`site_active` = 0
                AND s.`site_id` = :site'
        )
            ->setParameter('site', $site)
            ->getStrQuery();
        $result = $this->database->dbQuery($query);

        return (bool) $this->database->dbFetchArray($result);
    }

    public function isUserSiteId(int $user, int $site): bool
    {
        $query = $this->manager->createQuery(
            'SELECT s.`site_id` FROM `sites` s
            INNER JOIN `users` u ON s.`user_id` = u.`user_id`
            WHERE u.`user_active` = 1 AND s.`site_active` = 1
                AND s.`user_id` = :user AND s.`site_id` = :site'
        )
            ->setParameter('user', $user)
            ->setParameter('site', $site)
            ->getStrQuery();
        $result = $this->database->dbQuery($query);

        return (bool) $this->database->dbFetchArray($result);
    }

    public function addAccountSiteData(
        int $user,
        string $name,
        string $url,
        string $ip,
        string $date
    ): bool {
        $query = $this->manager->createQuery(
            "INSERT INTO `sites` (
                `user_id`,
                `site_active`,
                `site_visible`,
                `site_name`,
                `site_url`,
                `site_ip_added`,
                `site_date_added`
            )
            VALUES (:user, 0, 1, ':name', ':url', ':ip', ':date')"
        )
            ->setParameter('user', $user)
            ->setParameter('name', $name)
            ->setParameter('url', $url)
            ->setParameter('ip', $ip)
            ->setParameter('date', $date)
            ->getStrQuery();

        return $this->database->dbQuery($query);
    }

    public function getAccountSiteList(
        int $user,
        int $level,
        int $listLimit
    ): array {
        $arrayResult = array();

        $query = $this->manager->createQuery(
            'SELECT s.`site_id`, s.`site_name` FROM `sites` s
            INNER JOIN `users` u ON s.`user_id` = u.`user_id`
            WHERE s.`site_active` = 1 AND u.`user_active` = 1
                AND s.`user_id` = :user
            ORDER BY s.`site_date_added` DESC LIMIT :start, :limit'
        )
            ->setParameter('user', $user)
            ->setParameter('start', ($level - 1) * $listLimit)
            ->setParameter('limit', $listLimit)
            ->getStrQuery();
        $result = $this->database->dbQuery($query);
        while ($row = $this->database->dbFetchArray($result)) {
            $arrayResult[$row['site_id']]['site_name'] = $row['site_name'];
        }

        return $arrayResult;
    }

    public function getAccountSiteCount(int $user): int
    {
        $count = 0;

        $query = $this->manager->createQuery(
            'SELECT COUNT(*) AS `count` FROM `sites` s
            INNER JOIN `users` u ON s.`user_id` = u.`user_id`
            WHERE s.`site_active` = 1 AND u.`user_active` = 1
                AND s.`user_id` = :user'
        )
            ->setParameter('user', $user)
            ->getStrQuery();
        $result = $this->database->dbQuery($query);
        if ($row = $this->database->dbFetchArray($result)) {
            $count = (int) $row['count'];
        }

        return $count;
    }

    public function setEditingSiteData(
        int $site,
        int $visible,
        string $name,
        string $ip,
        string $date
    ): bool {
        $query = $this->manager->createQuery(
            "UPDATE `sites` s
            SET s.`site_visible` = :visible, s.`site_name` = ':name',
                s.`site_ip_updated` = ':ip', s.`site_date_updated` = ':date'
            WHERE s.`site_id` = :site"
        )
            ->setParameter('visible', $visible)
            ->setParameter('name', $name)
            ->setParameter('ip', $ip)
            ->setParameter('date', $date)
            ->setParameter('site', $site)
            ->getStrQuery();

        return $this->database->dbQuery($query);
    }

    public function getEditingSiteData(int $site): array
    {
        $arrayResult = array();

        $query = $this->manager->createQuery(
            'SELECT s.`site_visible`, s.`site_name`,
                s.`site_url` FROM `sites` s
            WHERE s.`site_id` = :site'
        )
            ->setParameter('site', $site)
            ->getStrQuery();
        $result = $this->database->dbQuery($query);
        if ($row = $this->database->dbFetchArray($result)) {
            $arrayResult['site_visible'] = (int) $row['site_visible'];
            $arrayResult['site_name'] = $row['site_name'];
            $arrayResult['site_url'] = $row['site_url'];
        }

        return $arrayResult;
    }

    public function deleteEditingSiteData(int $site): bool
    {
        $query = $this->manager->createQuery(
            'DELETE FROM `sites` WHERE `site_id` = :site'
        )
            ->setParameter('site', $site)
            ->getStrQuery();

        return $this->database->dbQuery($query);
    }

    public function getAdminSiteList(int $level, int $listLimit): array
    {
        $arrayResult = array();

        $query = $this->manager->createQuery(
            'SELECT s.`site_id`, s.`site_name` FROM `sites` s
            INNER JOIN `users` u ON s.`user_id` = u.`user_id`
            WHERE s.`site_active` = 0 AND u.`user_active` = 1
            ORDER BY s.`site_date_added` DESC LIMIT :start, :limit'
        )
            ->setParameter('start', ($level - 1) * $listLimit)
            ->setParameter('limit', $listLimit)
            ->getStrQuery();
        $result = $this->database->dbQuery($query);
        while ($row = $this->database->dbFetchArray($result)) {
            $arrayResult[$row['site_id']]['site_name'] = $row['site_name'];
        }

        return $arrayResult;
    }

    public function getAdminSiteCount(): int
    {
        $count = 0;

        $query = $this->manager->createQuery(
            'SELECT COUNT(*) AS `count` FROM `sites` s
            INNER JOIN `users` u ON s.`user_id` = u.`user_id`
            WHERE s.`site_active` = 0 AND u.`user_active` = 1'
        )->getStrQuery();
        $result = $this->database->dbQuery($query);
        if ($row = $this->database->dbFetchArray($result)) {
            $count = (int) $row['count'];
        }

        return $count;
    }

    public function setAcceptationSiteData(
        int $site,
        int $active,
        int $visible,
        string $name,
        string $url,
        string $ip,
        string $date
    ): bool {
        $query = $this->manager->createQuery(
            "UPDATE `sites` s
            SET s.`site_active` = :active, s.`site_visible` = :visible,
                s.`site_name` = ':name', s.`site_url` = ':url',
                s.`site_ip_updated` = ':ip', s.`site_date_updated` = ':date'
            WHERE s.`site_id` = :site"
        )
            ->setParameter('active', $active)
            ->setParameter('visible', $visible)
            ->setParameter('name', $name)
            ->setParameter('url', $url)
            ->setParameter('ip', $ip)
            ->setParameter('date', $date)
            ->setParameter('site', $site)
            ->getStrQuery();

        return $this->database->dbQuery($query);
    }

    public function getAcceptationSiteData(int $site): array
    {
        $arrayResult = array();

        $query = $this->manager->createQuery(
            'SELECT s.`site_active`, s.`site_visible`, s.`site_name`,
                s.`site_url` FROM `sites` s
            WHERE s.`site_id` = :site'
        )
            ->setParameter('site', $site)
            ->getStrQuery();
        $result = $this->database->dbQuery($query);
        if ($row = $this->database->dbFetchArray($result)) {
            $arrayResult['site_active'] = (int) $row['site_active'];
            $arrayResult['site_visible'] = (int) $row['site_visible'];
            $arrayResult['site_name'] = $row['site_name'];
            $arrayResult['site_url'] = $row['site_url'];
        }

        return $arrayResult;
    }

    public function deleteAcceptationSiteData(int $site): bool
    {
        $query = $this->manager->createQuery(
            'DELETE FROM `sites` WHERE `site_id` = :site'
        )
            ->setParameter('site', $site)
            ->getStrQuery();

        return $this->database->dbQuery($query);
    }

    public function isApiUserSite(int $user, int $site): bool
    {
        $query = $this->manager->createQuery(
            'SELECT s.`site_id` FROM `sites` s
            INNER JOIN `users` u ON s.`user_id` = u.`user_id`
            WHERE u.`user_active` = 1 AND s.`user_id` = :user
                AND s.`site_id` = :site'
        )
            ->setParameter('user', $user)
            ->setParameter('site', $site)
            ->getStrQuery();
        $result = $this->database->dbQuery($query);

        return (bool) $this->database->dbFetchArray($result);
    }

    public function addApiSiteData(
        int $user,
        string $name,
        string $url,
        string $ip,
        string $date
    ): bool {
        $query = $this->manager->createQuery(
            "INSERT INTO `sites` (
                `user_id`,
                `site_active`,
                `site_visible`,
                `site_name`,
                `site_url`,
                `site_ip_added`,
                `site_date_added`
            )
            VALUES (:user, 0, 1, ':name', ':url', ':ip', ':date')"
        )
            ->setParameter('user', $user)
            ->setParameter('name', $name)
            ->setParameter('url', $url)
            ->setParameter('ip', $ip)
            ->setParameter('date', $date)
            ->getStrQuery();

        return $this->database->dbQuery($query);
    }

    public function setApiSiteData(
        int $site,
        int $visible,
        string $name,
        string $ip,
        string $date
    ): bool {
        $query = $this->manager->createQuery(
            "UPDATE `sites` s
            SET s.`site_visible` = :visible, s.`site_name` = ':name',
                s.`site_ip_updated` = ':ip', s.`site_date_updated` = ':date'
            WHERE s.`site_id` = :site"
        )
            ->setParameter('visible', $visible)
            ->setParameter('name', $name)
            ->setParameter('ip', $ip)
            ->setParameter('date', $date)
            ->setParameter('site', $site)
            ->getStrQuery();

        return $this->database->dbQuery($query);
    }

    public function deleteApiSiteData(int $site): bool
    {
        $query = $this->manager->createQuery(
            'DELETE FROM `sites` WHERE `site_id` = :site'
        )
            ->setParameter('site', $site)
            ->getStrQuery();

        return $this->database->dbQuery($query);
    }
}
