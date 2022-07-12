<?php

require_once dirname(__FILE__).'/BaseService.class.php';
require_once dirname(__FILE__).'/../dao/EventDao.class.php';
require_once dirname(__FILE__).'/../dao/EventTypeDao.class.php';
require_once dirname(__FILE__).'/../../vendor/autoload.php';

class EventService extends BaseService
{
    private $eventTypeDao;

    public function __construct()
    {
        $this->dao = new EventDao();
        $this->eventTypeDao = new EventTypeDao();
    }

    public function get_events($search, $offset, $limit, $order)
    {
        if ($search) {
            return $this->dao->get_event($search, $offset, $limit, $order);
        } else {
            return $this->dao->get_all($offset, $limit, $order);
        }
    }

    public function get_event_by_city($city)
    {
        return $this->dao->get_event_by_city($city);
    }

    public function get_event_by_id($id)
    {
        return $this->dao->get_event_by_id($id);
    }


    public function add_event($event)
    {
        try {
            $this->dao->beginTransaction();
            $event = parent::add([
          "name" => $event["name"],
          "status" => $event["status"],
          "city" => $event["city"],
          "address" => $event["address"],
          "date_held" => $event["date_held"],
          "num_of_tickets" => $event["num_of_tickets"],
          "description" => $event["description"],
          "type_name" => $event["type_name"],
          "company_name" => $event["company_name"]
        ]);
            $this->dao->commit();
        } catch (\Exception $e) {
            $this->dao->rollBack();
            if (str_contains($e->getMessage(), 'event.type_name_UNIQUE')) {
                throw new Exception("Please choose another event type.", 400, $e);
            } else {
                throw $e;
            }
        }
        return $event;
    }
}
