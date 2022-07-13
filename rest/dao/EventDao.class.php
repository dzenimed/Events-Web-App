<?php

require_once dirname(__FILE__)."/BaseDao.class.php";

class EventDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct("event");
    }

    public function get_events_by_name($search, $offset, $limit, $order= '-id'){
      list($order_column, $order_direction) = self::parse_order($order);
      $params = [];
      $query = "SELECT * FROM event
                WHERE 1 = 1 ";
      if (isset($search)){
        $query .= "AND ( LOWER(name) LIKE CONCAT('%', :search, '%') OR LOWER(description) LIKE CONCAT('%', :search, '%'))";
        $params['search'] = strtolower($search);
      }

        $query .="ORDER BY ${order_column} ${order_direction} ";
        $query .="LIMIT ${limit} OFFSET ${offset}";
        return $this->query($query, $params);
    }
    public function get_event($search, $offset, $limit, $order= '-id')
    {
        list($order_column, $order_direction) = self::parse_order($order);

        return $this->query(
            "SELECT * FROM event
                        WHERE LOWER(name) LIKE CONCAT('%', :name, '%')
                        ORDER BY ${order_column} ${order_direction}
                        LIMIT ${limit} OFFSET ${offset}",
            ["name"=>strtolower($search)]
        );
    }
    //doesnt work
    public function get_event_by_city($city)
    {
        return $this->query("SELECT * FROM event WHERE city LIKE '%':city'%'", ['city' => $city]);
    }

    public function get_event_by_id($id)
    {
        return $this->query("SELECT * FROM event WHERE id = :id", ['id' => $id]);
    }
}
