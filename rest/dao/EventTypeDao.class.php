<?php

require_once dirname(__FILE__)."/BaseDao.class.php";

class EventTypeDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct("eventType");
    }

    public function getEventType_by_name($type_name)
    {
        return $this->query_unique("SELECT * FROM eventtype WHERE type_name = :type_name", ["type_name"=>$type_name]);
    }
}
