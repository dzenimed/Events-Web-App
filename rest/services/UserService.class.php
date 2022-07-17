<?php

require_once dirname(__FILE__).'/BaseService.class.php';
require_once dirname(__FILE__).'/../dao/UserDao.class.php';
require_once dirname(__FILE__).'/../../vendor/autoload.php';
require_once dirname(__FILE__).'/../clients/SMTPclients.class.php';

use Firebase\JWT\JWT;

class UserService extends BaseService
{
    private $smtpClient;

    public function __construct()
    {
        $this->dao = new UserDao();
        $this->smtpClient = new SMTPClient();
    }

    public function get_user_by_id($id)
    {
        return $this->dao->get_user_by_id($id);
    }

    public function get_user($search, $offset, $limit, $order)
    {
        if ($search) {
            return $this->dao->get_user($search, $offset, $limit, $order);
        } else {
            return $this->dao->get_all($offset, $limit, $order);
        }
    }

    public function get_user_by_token($token)
    {
        return $this->dao->get_user_by_token($token);
    }

    public function register($user)
    {
        try {
            $this->dao->beginTransaction();
            $username = trim($user['username']); // cut spaces around

            if (!preg_match('/^[A-Za-z0-9]+$/', $username)) { // check for valid characters
                Flight::json("Please only use alphanumeric characters and no spaces.");
                die();
            } elseif (strlen($username) < 3 || strlen($username) > 20) {
                Flight::json("Username must contain at least 3 characters.");
                die();
            } else {
                $user = parent::add([
        "username" => $user["username"],
        "name" => $user["name"],
        "surname" => $user["surname"],
        "email" => $user["email"],
        "password" => password_hash($user["password"], PASSWORD_DEFAULT),
        "role" => "user",
        "registered_at" => date(Config::DATE_FORMAT),
        "status" => "PENDING",
        "token" => md5(random_bytes(16))
      ]);
                $this->dao->commit();
            }
        } catch (\Exception $e) {
            $this->dao->rollBack();
            if (str_contains($e->getMessage(), 'user.username_UNIQUE')) {
                throw new Exception("Please choose another username.", 400, $e);
            } else {
                throw $e;
            }
        }
        $this->smtpClient->send_register_user_token($user);
        return $user;
    }

    public function login($user)
    {
        $db_user = $this->dao->getUser_by_username($user['username']);

        if (!isset($db_user['id'])) {
            throw new Exception("User doesn't exists", 400);
        }

        if (strlen($db_user['username']) < 3) {
            throw new Exception("User doesn't exists", 400);
        }

        if ($db_user['status'] != 'ACTIVE') {
            throw new Exception("User not active", 400);
        }

        if (password_verify($user["password"], $db_user['password'])) {
            echo '';
        } else {
            throw new Exception("Invalid password", 400);
        }
        return $db_user;
    }

    public function forgot($user)
    {
        $db_user = $this->dao->getUser_by_email($user['email']);

        if (!isset($db_user['id'])) {
            throw new Exception("User doesn't exists", 400);
        }

        // generate token - and save it to db
        $db_user = $this->update($db_user['id'], ['token' => md5(random_bytes(16))]);

        // send email
        $this->smtpClient->send_user_recovery_token($db_user);
    }

    public function reset($user)
    {
        $db_user = $this->dao->get_user_by_token($user['token']);

        if (!isset($db_user['id'])) {
            throw new Exception("Invalid token", 400);
        }

        $this->dao->update($db_user['id'], ['password' => password_hash($user["password"], PASSWORD_DEFAULT), 'token' => null]);

        return $db_user;
    }

    public function confirm($token)
    {
        $user = $this->dao->get_user_by_token($token);
        if (!isset($user['id'])) {
            throw new Exception("Invalid token", 400);
        }
        $this->dao->update($user['id'], ["status" => "ACTIVE", "token" => null]);

        return $user;
    }
}
