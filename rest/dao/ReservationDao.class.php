<?php

require_once dirname(__FILE__)."/BaseDao.class.php";

class ReservationDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct("reservationdetails");
    }

    public function get_reservation_by_id($id)
    {
        return $this->query_unique("SELECT * FROM reservationdetails WHERE id = :id", ["id"=>$id]);
    }

    public function get_reservation_by_user_id($user_id)
    {
        return $this->query_unique("SELECT * FROM reservationdetails WHERE user_id = :user_id", ["user_id"=>$user_id]);
    }

    public function get_user_reservations($user_id)
    {
      $query = "SELECT rd.status AS status, rd.date_reserved, rd.event_id AS event_id, u.name AS user_name,
      u.surname, e.name AS event_name, e.city, e.address, e.date_held, e.image_link FROM
      reservationdetails rd JOIN user u ON rd.user_id = u.id
      JOIN event e ON rd.event_id = e.id WHERE user_id = :user_id";
      return $this->query($query, ['user_id' => $user_id]);
    }
}
