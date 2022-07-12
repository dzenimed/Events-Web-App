<?php

require_once dirname(__FILE__).'/BaseService.class.php';
require_once dirname(__FILE__).'/../dao/EventTypeDao.class.php';
require_once dirname(__FILE__).'/../../vendor/autoload.php';

class EventTypeService extends BaseService
{
    private $eventTypeDao;

    public function __construct()
    {
        $this->dao = new EventTypeDao();
    }

    public function get_eventType_by_name($type_name)
    {
        return $this->dao->getEventType_by_name($type_name);
    }

    public function add_event_type($event_type)
    {
        try {
            $this->dao->beginTransaction();
            $event_type = parent::add([
        "type_name" => $event_type["type_name"],
        "description" => $event_type["description"]
      ]);
            $this->dao->commit();
        } catch (\Exception $e) {
            $this->dao->rollBack();
            if (str_contains($e->getMessage(), 'event_type.type_name_UNIQUE')) {
                throw new Exception("Please choose another event type name.", 400, $e);
            } else {
                throw $e;
            }
        }
        return $event_type;
    }
}
