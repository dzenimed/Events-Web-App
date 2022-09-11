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
      }else{
        $search = '';
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

    public function increase_tickets($id){
      return $this->query("UPDATE event SET num_of_tickets = num_of_tickets + 1 WHERE id = :id", ['id'=>$id]);
    }

    public function decrease_tickets($id){
      return $this->query("UPDATE event SET num_of_tickets = num_of_tickets - 1 WHERE id = :id", ['id'=>$id]);
    }

    public function get_events_number($status)
    {
        return $this->query("SELECT COUNT(*) AS number_of_events FROM event WHERE status=:status", ['status' => $status]);
    }

    // public function update_event($id, $event){
    //   return $this->update($id, $event);
    // }
      //return $this->update("UPDATE event SET num_of_tickets = num_of_tickets-:num_of_tickets WHERE id = :id", ["id" => $id, "num_of_tickets" => $num_of_tickets]);
      // $query = "UPDATE event SET num_of_tickets = num_of_tickets-:num_of_tickets WHERE id = :id";
      // $stmt = $this->connection->prepare($query);
      // $params=["id" => $id, "quantity" => $quantity];
      // $stmt -> execute($params);


    // public function update_event($id, $num_of_tickets){
    //   $query = "UPDATE event SET num_of_tickets = num_of_tickets-1 WHERE id = :id";
    //   $stmt = $this->connection->prepare($query);
    //   $params=["id" => $id];
    //   $stmt -> execute($params);
    //  return $this->query_unique("UPDATE event SET num_of_tickets = num_of_tickets-:num_of_tickets WHERE id = :id", ["id" => $id, "num_of_tickets" => $num_of_tickets]);
    //}


        // public function get_event_by_only_name($id)
        // {
        //     return $this->query("SELECT id FROM event WHERE name=""", ['id' => $id]);
        // }
}
