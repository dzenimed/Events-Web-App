<?php

require_once dirname(__FILE__)."/BaseDao.class.php";

class UserDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct("user");
    }

    public function getUser_by_username($username)
    {
        return $this->query_unique("SELECT * FROM user WHERE username = :username", ["username"=>$username]);
    }

    public function getUser_by_email($email)
    {
        return $this->query_unique("SELECT * FROM user WHERE email = :email", ["email"=>$email]);
    }

    public function get_user($search, $offset, $limit, $order= '-id')
    {
        list($order_column, $order_direction) = self::parse_order($order);

        return $this->query(
            "SELECT * FROM user
                        WHERE LOWER(username) LIKE CONCAT('%', :username, '%')
                        ORDER BY ${order_column} ${order_direction}
                        LIMIT ${limit} OFFSET ${offset}",
            ["username"=>strtolower($search)]
        );
    }

    public function get_user_by_token($token)
    {
        return $this->query_unique("SELECT id FROM user WHERE token = :token", ["token" => $token]);
    }

    public function get_user_by_id($id)
    {
        return $this->query("SELECT * FROM user WHERE id = :id", ['id' => $id]);
    }
}
