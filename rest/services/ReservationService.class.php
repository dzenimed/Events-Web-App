<?php

require_once dirname(__FILE__).'/BaseService.class.php';
require_once dirname(__FILE__).'/../dao/CompanyDao.class.php';
require_once dirname(__FILE__).'/../dao/UserDao.class.php';
require_once dirname(__FILE__).'/../dao/EventDao.class.php';
require_once dirname(__FILE__).'/../dao/ReservationDao.class.php';
require_once dirname(__FILE__).'/../../vendor/autoload.php';

class ReservationService extends BaseService
{
    private EventDao $eventDao;

    public function __construct()
    {
        $this->dao = new ReservationDao();
        $this->eventDao = new EventDao();
    }

    public function get_reservation_by_id($id)
    {
        return $this->dao->get_reservation_by_id($id);
    }

    public function get_user_reservations($user)
    {
        return $this->dao->get_user_reservations($user);
    }

    public function add_reservation($reservation)
    {
      try {      
          $this->dao->beginTransaction();
          $reservation = parent::add([
          "status" => "ACTIVE",
          "date_reserved" => date(Config::DATE_FORMAT),
          "user_id" => $reservation["user_id"],
          "event_id" => $reservation["event_id"],
      ]);
          $this->dao->commit();
      } catch (\Exception $e) {
          $this->dao->rollBack();
          throw $e;
      }
      $this->eventDao->decrease_tickets($reservation["event_id"]);
      return $reservation;
    }

    // public function update_reservation($user, $id, $reservationdetails)
    // {
    //     $reservation = $this->dao->get_by_id($id);
    //     if ($reservation['user_id'] != $user['id']) {
    //         throw new Exception("This account cannot make any modifications.");
    //     }
    //     // unset($entity['user_id']);
    //     // unset($entity['status']);
    //     return $this->update($id, $reservationdetails);
    // }
}
