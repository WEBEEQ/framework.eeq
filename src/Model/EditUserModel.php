<?php declare(strict_types=1);

// src/Model/EditUserModel.php
namespace App\Model;

use App\Core\DataBase;

class EditUserModel extends DataBase
{
    public function generateKey(): string
    {
        $key = '';

        for ($i = 0; $i < 100; $i++) {
            if (rand(0, 2) != 0) {
                $j = rand(0, 51);
                $key .= substr(
                    'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz',
                    $j,
                    1
                );
            } else {
                $j = rand(0, 9);
                $key .= substr(
                    '1234567890',
                    $j,
                    1
                );
            }
        }

        return $key;
    }

    public function isUserId(int $id, int $user): bool
    {
        $result = $this->dbQuery(
            'SELECT `users`.`user_id` FROM `users`
            WHERE `users`.`user_active` = 1 AND `users`.`user_id` = ' . $id . '
                AND `users`.`user_id` = ' . $user
        );

        return ($this->dbFetchArray($result)) ? true : false;
    }

    public function getUserPassword(int $user): ?string
    {
        $result = $this->dbQuery(
            'SELECT `users`.`user_password` FROM `users`
            WHERE `users`.`user_id` = ' . $user
        );
        if ($row = $this->dbFetchArray($result)) {
            extract($row);

            return $user_password;
        }

        return null;
    }

    public function setUserData(
        int $user,
        int $province,
        int $city,
        string $name,
        string $surname,
        string $password,
        string $key,
        string $email,
        string $www,
        string $phone,
        string $street,
        string $postcode,
        string $description,
        string $ip,
        string $date
    ): bool {
        $province = ($province >= 1) ? (string) $province : 'NULL';
        $city = ($city >= 1) ? (string) $city : 'NULL';

        if ($password != '') {
            $setPassword = "`users`.`user_password` = '"
                . password_hash($password, PASSWORD_DEFAULT) . "', ";
        } else {
            $setPassword = '';
        }
        if ($email != '') {
            $setActive = '`users`.`user_active` = 0, ';
            $setKey = "`users`.`user_key` = '" . $key . "', ";
            $setEmail = "`users`.`user_email` = '" . $email . "', ";
        } else {
            $setActive = '';
            $setKey = '';
            $setEmail = '';
        }

        return $this->dbQuery(
            'UPDATE `users`
            SET `users`.`province_id` = ' . $province . ',
                `users`.`city_id` = ' . $city . ',
                ' . $setActive . "`users`.`user_name` = '" . $name . "',
                `users`.`user_surname` = '" . $surname . "',
                " . $setPassword . $setKey . $setEmail
                . "`users`.`user_url` = '" . $www . "',
                `users`.`user_phone` = '" . $phone . "',
                `users`.`user_street` = '" . $street . "',
                `users`.`user_postcode` = '" . $postcode . "',
                `users`.`user_description` = '" . $description . "',
                `users`.`user_ip_updated` = '" . $ip . "',
                `users`.`user_date_updated` = '" . $date . "'
            WHERE `users`.`user_id` = " . $user
        );
    }

    public function getUserData(
        int $user,
        ?int &$province_id,
        ?int &$city_id,
        ?string &$user_name,
        ?string &$user_surname,
        ?string &$user_email,
        ?string &$user_url,
        ?string &$user_phone,
        ?string &$user_street,
        ?string &$user_postcode,
        ?string &$user_description
    ): bool {
        $result = $this->dbQuery(
            'SELECT * FROM `users`
            WHERE `users`.`user_id` = ' . $user
        );
        if ($row = $this->dbFetchArray($result)) {
            extract($row);

            return true;
        }

        return false;
    }

    public function getProvinceList(): array
    {
        $arrayResult = array();

        $result = $this->dbQuery(
            'SELECT `provinces`.`province_id`,
                `provinces`.`province_name` FROM `provinces`
            WHERE `provinces`.`province_active` = 1
            ORDER BY `provinces`.`province_name` ASC'
        );
        while ($row = $this->dbFetchArray($result)) {
            $arrayResult[$row['province_id']]['province_name'] =
                $row['province_name'];
        }

        return $arrayResult;
    }

    public function getCityList(int $province): array
    {
        $arrayResult = array();

        if ($province >= 1) {
            $result = $this->dbQuery(
                'SELECT `cities`.`city_id`, `cities`.`city_name` FROM `cities`
                INNER JOIN `provinces`
                    ON `cities`.`province_id` = `provinces`.`province_id`
                WHERE `cities`.`city_active` = 1
                    AND `provinces`.`province_active` = 1
                    AND `cities`.`province_id` = ' . $province . '
                ORDER BY `cities`.`city_name` ASC'
            );
            while ($row = $this->dbFetchArray($result)) {
                $arrayResult[$row['city_id']]['city_name'] = $row['city_name'];
            }
        }

        return $arrayResult;
    }
}
