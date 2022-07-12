<?php

require_once dirname(__FILE__).'/BaseService.class.php';
require_once dirname(__FILE__).'/../dao/CompanyDao.class.php';
require_once dirname(__FILE__).'/../dao/UserDao.class.php';
require_once dirname(__FILE__).'/../dao/EventDao.class.php';
require_once dirname(__FILE__).'/../../vendor/autoload.php';

class ReservationService extends BaseService
{
    private $eventDao;

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
        return $this->dao->get_user_reservations($user['id']);
    }

    public function add_reservation($reservation, $event_id, $user_id)
    {
        $event = $this->eventDao->get_event_by_id($event_id);

        $reservation["status"] = "ACTIVE";
        $reservation["date_reserved"] = date(Config::DATE_FORMAT);
        $reservation["user_id"] = $user_id;
        $reservation["event_id"] = $event_id;

        return parent::add($reservation);
    }

    public function update_reservation($user, $id, $reservationdetails)
    {
        $reservation = $this->dao->get_by_id($id);
        if ($reservation['user_id'] != $user['id']) {
            throw new Exception("This account cannot make any modifications.");
        }
        // unset($entity['user_id']);
        // unset($entity['status']);
        return $this->update($id, $reservationdetails);
    }
}
