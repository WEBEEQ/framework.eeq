<?php

declare(strict_types=1);

namespace App\Repository;

class UserRepository
{
    protected object $database;
    protected object $manager;

    public function __construct(object $database, object $manager)
    {
        $this->database = $database;
        $this->manager = $manager;
    }

    public function isUserId(int $user): bool
    {
        $query = $this->manager->createQuery(
            'SELECT u.`user_id` FROM `users` u
            WHERE u.`user_active` = 1 AND u.`user_id` = :user'
        )
            ->setParameter('user', $user)
            ->getStrQuery();
        $result = $this->database->dbQuery($query);

        return (bool) $this->database->dbFetchArray($result);
    }

    public function isUserUserId(int $user, int $user2): bool
    {
        $query = $this->manager->createQuery(
            'SELECT u.`user_id` FROM `users` u
            WHERE u.`user_active` = 1 AND u.`user_id` = :user2
                AND u.`user_id` = :user'
        )
            ->setParameter('user2', $user2)
            ->setParameter('user', $user)
            ->getStrQuery();
        $result = $this->database->dbQuery($query);

        return (bool) $this->database->dbFetchArray($result);
    }

    public function setUserActive(int $user, string $key): bool
    {
        $query = $this->manager->createQuery(
            "UPDATE `users` u
            SET u.`user_active` = 1, u.`user_key` = ':key'
            WHERE u.`user_id` = :user"
        )
            ->setParameter('key', $key)
            ->setParameter('user', $user)
            ->getStrQuery();

        return $this->database->dbQuery($query);
    }

    public function isUserLogin(string $login): bool
    {
        $query = $this->manager->createQuery(
            "SELECT u.`user_id` FROM `users` u WHERE u.`user_login` = ':login'"
        )
            ->setParameter('login', $login)
            ->getStrQuery();
        $result = $this->database->dbQuery($query);

        return (bool) $this->database->dbFetchArray($result);
    }

    public function getUserPassword(int $user): ?string
    {
        $query = $this->manager->createQuery(
            'SELECT u.`user_password` FROM `users` u WHERE u.`user_id` = :user'
        )
            ->setParameter('user', $user)
            ->getStrQuery();
        $result = $this->database->dbQuery($query);
        if ($row = $this->database->dbFetchArray($result)) {
            return $row['user_password'];
        }

        return null;
    }

    public function setUserLoged(int $user, string $ip, string $date): bool
    {
        $query = $this->manager->createQuery(
            "UPDATE `users` u SET u.`user_ip_loged` = ':ip',
                u.`user_date_loged` = ':date'
            WHERE u.`user_id` = :user"
        )
            ->setParameter('ip', $ip)
            ->setParameter('date', $date)
            ->setParameter('user', $user)
            ->getStrQuery();

        return $this->database->dbQuery($query);
    }

    public function addRegistrationUserData(
        string $name,
        string $surname,
        string $login,
        string $password,
        string $key,
        string $email,
        string $ip,
        string $date
    ): bool {
        $query = $this->manager->createQuery(
            "INSERT INTO `users` (
                `user_admin`,
                `user_active`,
                `user_name`,
                `user_surname`,
                `user_login`,
                `user_password`,
                `user_key`,
                `user_email`,
                `user_description`,
                `user_show`,
                `user_ip_added`,
                `user_date_added`
            )
            VALUES (
                0,
                0,
                ':name',
                ':surname',
                ':login',
                ':password',
                ':key',
                ':email',
                '',
                0,
                ':ip',
                ':date'
            )"
        )
            ->setParameter('name', $name)
            ->setParameter('surname', $surname)
            ->setParameter('login', $login)
            ->setParameter(
                'password',
                password_hash($password, PASSWORD_DEFAULT)
            )
            ->setParameter('key', $key)
            ->setParameter('email', $email)
            ->setParameter('ip', $ip)
            ->setParameter('date', $date)
            ->getStrQuery();

        return $this->database->dbQuery($query);
    }

    public function getActivationUserData(string $login): array
    {
        $arrayResult = array();

        $query = $this->manager->createQuery(
            "SELECT u.`user_id`, u.`user_active`, u.`user_key` FROM `users` u
            WHERE u.`user_login` = ':login'"
        )
            ->setParameter('login', $login)
            ->getStrQuery();
        $result = $this->database->dbQuery($query);
        if ($row = $this->database->dbFetchArray($result)) {
            $arrayResult['user_id'] = (int) $row['user_id'];
            $arrayResult['user_active'] = (int) $row['user_active'];
            $arrayResult['user_key'] = $row['user_key'];
        }

        return $arrayResult;
    }

    public function getLoginUserData(string $login): array
    {
        $arrayResult = array();

        $query = $this->manager->createQuery(
            "SELECT u.`user_id`, u.`user_admin`, u.`user_active`,
                u.`user_password`, u.`user_key`, u.`user_email` FROM `users` u
            WHERE u.`user_login` = ':login'"
        )
            ->setParameter('login', $login)
            ->getStrQuery();
        $result = $this->database->dbQuery($query);
        if ($row = $this->database->dbFetchArray($result)) {
            $arrayResult['user_id'] = (int) $row['user_id'];
            $arrayResult['user_admin'] = (int) $row['user_admin'];
            $arrayResult['user_active'] = (int) $row['user_active'];
            $arrayResult['user_password'] = $row['user_password'];
            $arrayResult['user_key'] = $row['user_key'];
            $arrayResult['user_email'] = $row['user_email'];
        }

        return $arrayResult;
    }

    public function getResetUserData(string $login): array
    {
        $arrayResult = array();

        $query = $this->manager->createQuery(
            "SELECT u.`user_active`, u.`user_key`,
                u.`user_email` FROM `users` u
            WHERE u.`user_login` = ':login'"
        )
            ->setParameter('login', $login)
            ->getStrQuery();
        $result = $this->database->dbQuery($query);
        if ($row = $this->database->dbFetchArray($result)) {
            $arrayResult['user_active'] = (int) $row['user_active'];
            $arrayResult['user_key'] = $row['user_key'];
            $arrayResult['user_email'] = $row['user_email'];
        }

        return $arrayResult;
    }

    public function setPasswordUserData(
        int $user,
        string $password,
        string $key,
        string $ip,
        string $date
    ): bool {
        $query = $this->manager->createQuery(
            "UPDATE `users` u
            SET u.`user_password` = ':password', u.`user_key` = ':key',
                u.`user_ip_updated` = ':ip', u.`user_date_updated` = ':date'
            WHERE u.`user_id` = :user"
        )
            ->setParameter(
                'password',
                password_hash($password, PASSWORD_DEFAULT)
            )
            ->setParameter('key', $key)
            ->setParameter('ip', $ip)
            ->setParameter('date', $date)
            ->setParameter('user', $user)
            ->getStrQuery();

        return $this->database->dbQuery($query);
    }

    public function getPasswordUserData(string $login): array
    {
        $arrayResult = array();

        $query = $this->manager->createQuery(
            "SELECT u.`user_id`, u.`user_active`,
                u.`user_key`, u.`user_email` FROM `users` u
            WHERE u.`user_login` = ':login'"
        )
            ->setParameter('login', $login)
            ->getStrQuery();
        $result = $this->database->dbQuery($query);
        if ($row = $this->database->dbFetchArray($result)) {
            $arrayResult['user_id'] = (int) $row['user_id'];
            $arrayResult['user_active'] = (int) $row['user_active'];
            $arrayResult['user_key'] = $row['user_key'];
            $arrayResult['user_email'] = $row['user_email'];
        }

        return $arrayResult;
    }

    public function getAccountUserData(int $user): array
    {
        $arrayResult = array();

        $query = $this->manager->createQuery(
            'SELECT u.`user_id`, u.`user_name`, u.`user_surname`,
                u.`user_show` FROM `users` u
            WHERE u.`user_active` = 1 AND u.`user_id` = :user'
        )
            ->setParameter('user', $user)
            ->getStrQuery();
        $result = $this->database->dbQuery($query);
        if ($row = $this->database->dbFetchArray($result)) {
            $arrayResult['user_id'] = (int) $row['user_id'];
            $arrayResult['user_name'] = $row['user_name'];
            $arrayResult['user_surname'] = $row['user_surname'];
            $arrayResult['user_show'] = (int) $row['user_show'];
        }

        return $arrayResult;
    }

    public function setEditingUserData(
        int $user,
        int $province,
        int $city,
        string $name,
        string $surname,
        string $password,
        string $key,
        string $email,
        string $url,
        string $phone,
        string $street,
        string $postcode,
        string $description,
        string $ip,
        string $date
    ): bool {
        $province = ($province >= 1) ? $province : 'NULL';
        $city = ($city >= 1) ? $city : 'NULL';

        if ($password !== '') {
            $setPassword = "u.`user_password` = ':password', ";
        } else {
            $setPassword = '';
        }
        if ($email !== '') {
            $setActive = 'u.`user_active` = 0, ';
            $setKey = "u.`user_key` = ':key', ";
            $setEmail = "u.`user_email` = ':email', ";
        } else {
            $setActive = '';
            $setKey = '';
            $setEmail = '';
        }

        $query = $this->manager->createQuery(
            'UPDATE `users` u
            SET u.`province_id` = :province, u.`city_id` = :city,
                ' . $setActive . "u.`user_name` = ':name',
                u.`user_surname` = ':surname',
                " . $setPassword . $setKey . $setEmail
                . "u.`user_url` = ':url', u.`user_phone` = ':phone',
                u.`user_street` = ':street', u.`user_postcode` = ':postcode',
                u.`user_description` = ':description',
                u.`user_ip_updated` = ':ip', u.`user_date_updated` = ':date'
            WHERE u.`user_id` = :user"
        )
            ->setParameter('province', $province)
            ->setParameter('city', $city)
            ->setParameter('name', $name)
            ->setParameter('surname', $surname)
            ->setParameter(
                'password',
                password_hash($password, PASSWORD_DEFAULT)
            )
            ->setParameter('key', $key)
            ->setParameter('email', $email)
            ->setParameter('url', $url)
            ->setParameter('phone', $phone)
            ->setParameter('street', $street)
            ->setParameter('postcode', $postcode)
            ->setParameter('description', $description)
            ->setParameter('ip', $ip)
            ->setParameter('date', $date)
            ->setParameter('user', $user)
            ->getStrQuery();

        return $this->database->dbQuery($query);
    }

    public function getEditingUserData(int $user): array
    {
        $arrayResult = array();

        $query = $this->manager->createQuery(
            'SELECT * FROM `users` u WHERE u.`user_id` = :user'
        )
            ->setParameter('user', $user)
            ->getStrQuery();
        $result = $this->database->dbQuery($query);
        if ($row = $this->database->dbFetchArray($result)) {
            $arrayResult['province_id'] = (int) $row['province_id'];
            $arrayResult['city_id'] = (int) $row['city_id'];
            $arrayResult['user_name'] = $row['user_name'];
            $arrayResult['user_surname'] = $row['user_surname'];
            $arrayResult['user_email'] = $row['user_email'];
            $arrayResult['user_url'] = $row['user_url'];
            $arrayResult['user_phone'] = $row['user_phone'];
            $arrayResult['user_street'] = $row['user_street'];
            $arrayResult['user_postcode'] = $row['user_postcode'];
            $arrayResult['user_description'] = $row['user_description'];
        }

        return $arrayResult;
    }

    public function getAcceptationUserData(int $site): array
    {
        $arrayResult = array();

        $query = $this->manager->createQuery(
            'SELECT u.`user_login`, u.`user_email` FROM `sites` s
            INNER JOIN `users` u ON s.`user_id` = u.`user_id`
            WHERE s.`site_id` = :site'
        )
            ->setParameter('site', $site)
            ->getStrQuery();
        $result = $this->database->dbQuery($query);
        if ($row = $this->database->dbFetchArray($result)) {
            $arrayResult['user_login'] = $row['user_login'];
            $arrayResult['user_email'] = $row['user_email'];
        }

        return $arrayResult;
    }

    public function getApiUserData(string $login): array
    {
        $arrayResult = array();

        $query = $this->manager->createQuery(
            "SELECT u.`user_id`, u.`user_password` FROM `users` u
            WHERE u.`user_active` = 1 AND u.`user_login` = ':login'"
        )
            ->setParameter('login', $login)
            ->getStrQuery();
        $result = $this->database->dbQuery($query);
        if ($row = $this->database->dbFetchArray($result)) {
            $arrayResult['user_id'] = (int) $row['user_id'];
            $arrayResult['user_password'] = $row['user_password'];
        }

        return $arrayResult;
    }

    public function isUserMaxShow(int $user, int $show = 0): bool
    {
        $query = $this->manager->createQuery(
            'SELECT u.`user_id` FROM `users` u
            WHERE u.`user_show` >= (
                SELECT COUNT(*) FROM `sites` s
                INNER JOIN `users` u2 ON s.`user_id` = u2.`user_id`
                WHERE s.`site_active` = 1 AND s.`site_visible` = 1
                    AND u2.`user_active` = 1 AND u2.`user_show` >= :show
                    AND u2.`user_id` != :user
            ) AND u.`user_id` = :user'
        )
            ->setParameter('show', $show)
            ->setParameter('user', $user)
            ->getStrQuery();
        $result = $this->database->dbQuery($query);

        return (bool) $this->database->dbFetchArray($result);
    }

    public function setUserShow(
        int $user,
        int $user2,
        int $userShow,
        int $show = 1
    ): bool {
        $this->database->dbStartTransaction();
        $query = $this->manager->createQuery(
            'UPDATE `users` u
            SET u.`user_show` = u.`user_show` + 1
            WHERE u.`user_id` = :user'
        )
            ->setParameter('user', $user)
            ->getStrQuery();
        $result = $this->database->dbQuery($query);
        if ($result && ($userShow < 1 || $show < 1)) {
            $this->database->dbCommit();

            return true;
        }
        $query2 = $this->manager->createQuery(
            'UPDATE `users` u
            SET u.`user_show` = u.`user_show` - :show
            WHERE u.`user_id` = :user2'
        )
            ->setParameter('show', $show)
            ->setParameter('user2', $user2)
            ->getStrQuery();
        $result2 = $this->database->dbQuery($query2);
        if ($result && $result2) {
            $this->database->dbCommit();

            return true;
        } else {
            $this->database->dbRollback();
        }

        return false;
    }

    public function getShowData(int $user, int $show = 1): array
    {
        $arrayResult = array();

        $query = $this->manager->createQuery(
            'SELECT u.`user_id`, u.`user_show`, s.`site_url` FROM `sites` s
            INNER JOIN `users` u ON s.`user_id` = u.`user_id`
            WHERE s.`site_active` = 1 AND s.`site_visible` = 1
                AND u.`user_active` = 1 AND u.`user_show` >= :show
                AND u.`user_id` != :user
            ORDER BY RAND() LIMIT 1'
        )
            ->setParameter('show', $show)
            ->setParameter('user', $user)
            ->getStrQuery();
        $result = $this->database->dbQuery($query);
        if ($row = $this->database->dbFetchArray($result)) {
            $arrayResult['user_id'] = (int) $row['user_id'];
            $arrayResult['user_show'] = (int) $row['user_show'];
            $arrayResult['site_url'] = $row['site_url'];
        }

        return $arrayResult;
    }
}
